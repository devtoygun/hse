<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            [
                'email' => 'testuser@codex.local',
            ],
            [
                'firstname' => 'Test',
                'lastname' => 'User',
                'phone' => '5551112233',
                'is_admin' => false,
                'status' => 'active',
                'password' => 'Test12345!',
            ]
        );
    }
}
