<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Permissions
        $permissions = [
            // General permissions
            'view_dashboard',
            'view_reports',

            // Admin-specific permissions
            'user_manage',
            'role_manage',

            // Manager-specific permissions
            'stock_manage',
            'purchase_order_manage',
            'package_manage',

            // Employee-specific permissions
            'task_process',
        ];

        // Create Permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee']);

        // Assign Permissions to Roles
        $adminRole->givePermissionTo(Permission::all()); // Admin gets all permissions
        $managerRole->givePermissionTo([
            'view_dashboard',
            'view_reports',
            'stock_manage',
            'purchase_order_manage',
            'package_manage',
        ]);
        $employeeRole->givePermissionTo([
            'view_dashboard',
            'view_reports',
            'task_process',
        ]);
        
        # Default Admin Credentials

        // Assign Admin Role to Default User
        $admin = User::firstOrCreate([
            'email' => env('DEFAULT_ADMIN_EMAIL', 'admin@mtcapp.com'),
        ], [
            'name' => env('DEFAULT_ADMIN_NAME', 'Usman-Saeed'),
            'password' => bcrypt(env('DEFAULT_ADMIN_PASSWORD', 'mtcapp')),
        ]);

        $admin->assignRole($adminRole);

    }
}
