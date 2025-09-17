<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EduardoUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'eduardo@chinmex.mx';
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Eduardo',
                'username' => 'eduardo',
                'email' => $email,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('admin');
        }
    }
}
