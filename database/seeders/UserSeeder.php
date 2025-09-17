<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create the specific demo user if it does not exist
        $email = 'test@example.com';
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'username' => 'testuser',
                'email' => $email,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('user');
            }
        }

        // Create additional users to reach a total of 10 demo users including the test user
        $existingCount = User::count();
        $toCreate = max(0, 10 - $existingCount);
        if ($toCreate > 0) {
            User::factory($toCreate)->create()->each(function (User $u) {
                if (method_exists($u, 'assignRole')) {
                    $u->assignRole('user');
                }
            });
        }
    }
}
