<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PlatformPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Permissions
        $permissions = [
            // Dashboard
            'dashboard.view',

            // Tenants
            'tenants.view',
            'tenants.create',
            'tenants.edit',
            'tenants.delete',
            'tenants.renew',
            'tenants.suspend',

            // Plans
            'plans.view',
            'plans.create',
            'plans.edit',
            'plans.delete',

            // Invoices
            'invoices.view',

            // Audit Logs
            'audit-logs.view',

            // Settings
            'settings.view',
            'settings.update',

            // Roles & Permissions (Super Admin only)
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Sessions
            'sessions.view',
            'sessions.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'platform'
            ]);
        }

        // 2. Create Roles & Assign Permissions

        // Super Admin — Gets EVERYTHING
        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'platform'
        ]);
        $superAdmin->syncPermissions(Permission::where('guard_name', 'platform')->get());

        // Support Staff — Read only + basic tenant actions
        $support = Role::firstOrCreate([
            'name' => 'support',
            'guard_name' => 'platform'
        ]);
        $support->syncPermissions([
            'dashboard.view',
            'tenants.view',
            'tenants.renew',
            'invoices.view',
            'audit-logs.view',
        ]);

        // Finance Manager — Invoices + Plans
        $finance = Role::firstOrCreate([
            'name' => 'finance',
            'guard_name' => 'platform'
        ]);
        $finance->syncPermissions([
            'dashboard.view',
            'tenants.view',
            'invoices.view',
            'plans.view',
            'plans.edit',
        ]);

        $this->command->info('✅ Platform Permissions & Roles seeded successfully!');
        $this->command->info('   - Roles: super-admin, support, finance');
        $this->command->info('   - Permissions: ' . count($permissions) . ' created.');
    }
}