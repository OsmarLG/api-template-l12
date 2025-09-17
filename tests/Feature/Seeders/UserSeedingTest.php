<?php

namespace Tests\Feature\Seeders;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\EduardoUserSeeder;
use Database\Seeders\MasterUserSeeder;
use Database\Seeders\OsmarLGUserSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SprinfilUserSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSeedingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_seeds_core_users_and_roles_and_generates_total_of_ten_users(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MasterUserSeeder::class);
        $this->seed(AdminUserSeeder::class);
        $this->seed(OsmarLGUserSeeder::class);
        $this->seed(SprinfilUserSeeder::class);
        $this->seed(EduardoUserSeeder::class);
        $this->seed(UserSeeder::class);

        // Core users exist
        $this->assertNotNull(User::where('email', 'master@chinmex.mx')->first());
        $this->assertNotNull(User::where('email', 'admin@chinmex.mx')->first());
        $this->assertNotNull(User::where('email', 'osmarlg@chinmex.mx')->first());
        $this->assertNotNull(User::where('email', 'sprinfil@chinmex.mx')->first());
        $this->assertNotNull(User::where('email', 'eduardo@chinmex.mx')->first());
        $this->assertNotNull(User::where('email', 'test@example.com')->first());

        // Roles assigned
        $this->assertTrue(User::where('email', 'master@chinmex.mx')->first()->hasRole('master'));
        $this->assertTrue(User::where('email', 'admin@chinmex.mx')->first()->hasRole('admin'));
        $this->assertTrue(User::where('email', 'osmarlg@chinmex.mx')->first()->hasRole('master'));
        $this->assertTrue(User::where('email', 'sprinfil@chinmex.mx')->first()->hasRole('master'));
        $this->assertTrue(User::where('email', 'test@example.com')->first()->hasRole('user'));

        // Total users should be at least 10 (UserSeeder completes up to 10)
        $this->assertGreaterThanOrEqual(10, User::count());
    }
}
