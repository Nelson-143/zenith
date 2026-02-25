<?php

namespace Database\Seeders; // ✅ Correct namespace

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = ['Super Admin', 'Admin', 'User'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $permissions = ['create-branch', 'manage-users', 'view-reports'];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        Role::where('name', 'Super Admin')->first()->syncPermissions(Permission::all());
        Role::where('name', 'Admin')->first()->syncPermissions(['view-reports']);
        Role::where('name', 'User')->first()->syncPermissions([]);

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
