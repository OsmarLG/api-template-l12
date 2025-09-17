<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OsmarLGUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'osmarlg@chinmex.mx';
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Osmar LG',
                'username' => 'osmarlg',
                'email' => $email,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('master');
        }
    }
}
