<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;


class UserService
{
    /**
     * Tüm kullanıcıların listesini döndürür.
     */
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    /**
     * ID'ye göre kullanıcıyı tüm ilişkileriyle birlikte getirir.
     */
    public function getUserById(int $id): User
    {
        // 'with' içine modelinde tanımlı olan ilişkileri (örn: posts, profile, roles) ekleyebilirsin.
        return User::with(['profile', 'roles'])->findOrFail($id);
    }

    /**
     * Profil bilgilerini günceller.
     */
    public function updateProfile(int $id, array $data): bool
    {
        $user = User::findOrFail($id);
        return $user->update($data);
    }

    /**
     * Şifre değiştirme işlemini yapar.
     */
    public function changePassword(int $id, string $currentSessionPassword): array
    {
        $admin = Auth::user();

        // 1. Yönetici şifre doğrulaması
        if (!Hash::check($currentSessionPassword, $admin->password)) {
            return ['status' => false, 'message' => 'Yönetici şifresi hatalı!'];
        }

        $user = User::findOrFail($id);
        $newPassword = Str::random(6);
        $now = Carbon::now();

        try {
            DB::beginTransaction();

            // 2. Kullanıcı bilgilerini güncelle
            $user->update([
                'password' => Hash::make($newPassword),
                'first_login' => 1,
                'remember_token' => Str::random(60),
            ]);

            // 3. active_sessions tablosundaki oturumları kapat
            DB::table('active_sessions')
                ->where('user_id', $user->id)
                ->where('is_active', 1)
                ->update([
                    'is_active' => 0,
                    'logged_out_at' => $now
                ]);

            // 4. Veritabanına Log Kaydı At
            DB::table('system_logs')->insert([
                'admin_id' => $admin->id,
                'target_user_id' => $user->id,
                'action' => 'password_reset_and_logout',
                'description' => "Yönetici tarafından şifre sıfırlandı. Kullanıcı ilk girişe zorlandı ve tüm aktif oturumları sonlandırıldı.",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::commit();

            // 5. Mail gönderimi (Opsiyonel - Kuyruğa atılması önerilir)
             Mail::to($user->email)->send(new ResetPasswordMail($user, $newPassword));

            return [
                'status' => true, 
                'message' => "İşlem başarılı. Oturumlar kapatıldı ve log kaydedildi.",
                'plain_password' => $newPassword
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => 'Hata oluştu: ' . $e->getMessage()];
        }
    }
}