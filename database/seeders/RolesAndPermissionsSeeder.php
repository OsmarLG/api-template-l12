<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Define permissions
        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm);
        }

        // Roles
        $master = Role::findOrCreate('master');
        $admin = Role::findOrCreate('admin');
        $user = Role::findOrCreate('user');

        // Assign permissions
        $master->syncPermissions(Permission::all());
        $admin->syncPermissions(['users.view', 'users.create', 'users.update']);
        $user->syncPermissions(['users.view']);
    }
}
