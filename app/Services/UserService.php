<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

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
    public function changePassword(int $id, string $newPassword): bool
    {
        $user = User::findOrFail($id);
        
        return $user->update([
            'password' => Hash::make($newPassword)
        ]);
    }
}