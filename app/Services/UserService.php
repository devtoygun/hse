<?php

namespace App\Services;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserService
{
    public function getAllUsers(): Collection
    {
        return User::query()
            ->orderByDesc('id')
            ->get();
    }

    public function getUserById(int $id): User
    {
        return User::query()->findOrFail($id);
    }

    public function updateProfile(int $id, array $data): bool
    {
        $user = User::query()->findOrFail($id);

        return $user->update($data);
    }

    public function changePassword(int $id, string $currentSessionPassword): array
    {
        $admin = Auth::user();

        if (! $admin) {
            return ['status' => false, 'message' => 'Oturum bulunamadi. Lutfen tekrar giris yapin.'];
        }

        if (! Hash::check($currentSessionPassword, $admin->password)) {
            return ['status' => false, 'message' => 'Yonetici sifresi hatali!'];
        }

        $user = User::query()->findOrFail($id);
        $newPassword = $this->generateTemporaryPassword();
        $now = Carbon::now();

        try {
            DB::beginTransaction();

            $user->forceFill([
                'password' => $newPassword,
                'first_login' => 1,
                'remember_token' => Str::random(60),
            ])->saveOrFail();

            $user->refresh();

            if (! Hash::check($newPassword, $user->password)) {
                throw new \RuntimeException('Yeni sifre veritabanina dogru kaydedilemedi.');
            }

            DB::table('active_sessions')
                ->where('user_id', $user->id)
                ->where('is_active', 1)
                ->update([
                    'is_active' => 0,
                    'logged_out_at' => $now,
                    'updated_at' => $now,
                ]);

            DB::table('system_logs')->insert([
                'admin_id' => $admin->id,
                'target_user_id' => $user->id,
                'action' => 'password_reset_and_logout',
                'description' => 'Yonetici tarafindan sifre sifirlandi. Kullanici ilk girise zorlandi ve tum aktif oturumlari sonlandirildi.',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::commit();

            Mail::to($user->email)->send(new ResetPasswordMail($user, $newPassword));

            return [
                'status' => true,
                'message' => 'Islem basarili. Sifre degistirildi, first_login guncellendi ve oturumlar kapatildi.',
                'plain_password' => $newPassword,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return ['status' => false, 'message' => 'Hata olustu: '.$e->getMessage()];
        }
    }

    private function generateTemporaryPassword(): string
    {
        return Str::password(10, letters: true, numbers: true, symbols: true, spaces: false);
    }
}

