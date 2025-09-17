<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles, permissions and users
        $this->call([
            RolesAndPermissionsSeeder::class,
            MasterUserSeeder::class,
            AdminUserSeeder::class,
            OsmarLGUserSeeder::class,
            UserSeeder::class,
        ]);
    }
}
