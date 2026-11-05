<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RuntimeException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');
        if (!$email || !$password) {
            throw new RuntimeException(
                'ADMIN_EMAIL and ADMIN_PASSWORD must be configured.'
            );
        }
        $admin = User::firstOrNew([
            'email' => $email,
        ]);
        $admin->forceFill([
            'name' => env('ADMIN_NAME', 'CodeArena Administrator'),
            'password' => Hash::make($password),
            'is_admin' => true,
        ])->save();

    }
}
