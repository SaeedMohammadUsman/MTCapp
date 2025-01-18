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
            // Admin Permissions
            'user_create',
            'user_edit',
            'user_delete',
            'user_activate',
            'user_deactivate',
            'role_assign',

            // Manager Permissions
            'purchase_order_create',
            'package_create',
            'stock_in_finalize',
            'stock_out_finalize',
            'package_edit',

            // Employee Permissions
            'process_tasks',
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
        $adminRole->syncPermissions([
            'user_create',
            'user_edit',
            'user_delete',
            'user_activate',
            'user_deactivate',
            'role_assign',
            'purchase_order_create',
            'package_create',
            'stock_in_finalize',
            'stock_out_finalize',
            'package_edit',
            'process_tasks',
        ]);

        $managerRole->syncPermissions([
            'purchase_order_create',
            'package_create',
            'stock_in_finalize',
            'stock_out_finalize',
            'package_edit',
        ]);

        $employeeRole->syncPermissions([
            'process_tasks',
        ]);

        // Assign Admin Role to Default User
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin',
            'password' => bcrypt('mtcapp'), // Default password
        ]);

        $admin->assignRole($adminRole);
    }
}
