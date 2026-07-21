<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()['cache']->forget('spatie.permission.cache');

        // ── Platform Permissions ──
        $platformModules = [
            'dashboard'  => ['view'],
            'plans'      => ['view', 'create', 'edit', 'delete'],
            'tenants'    => ['view', 'create', 'edit', 'suspend', 'delete', 'renew'],
            'invoices'   => ['view'],
            'settings'   => ['view', 'update'],
            'audit-logs' => ['view'],
            'roles'      => ['view', 'create', 'edit', 'delete'],
            'sessions'   => ['view', 'delete'],
        ];

        $platformPerms = [];
        foreach ($platformModules as $module => $actions) {
            foreach ($actions as $action) {
                $name = "{$module}.{$action}";
                $platformPerms[] = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'platform']);
            }
        }

        // ── Platform Roles ──
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'platform']);
        $superAdmin->syncPermissions($platformPerms);

        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'platform']);
        $viewerPerms = Permission::where('guard_name', 'platform')
            ->whereIn('name', ['dashboard.view', 'tenants.view', 'invoices.view', 'audit-logs.view', 'plans.view'])
            ->get();
        $viewer->syncPermissions($viewerPerms);

        $finance = Role::firstOrCreate(['name' => 'finance', 'guard_name' => 'platform']);
        $financePerms = Permission::where('guard_name', 'platform')
            ->whereIn('name', ['dashboard.view', 'tenants.view', 'tenants.renew', 'invoices.view', 'plans.view'])
            ->get();
        $finance->syncPermissions($financePerms);

        // ── Tenant Permissions ──
        $tenantModules = [
            'patients'      => ['view', 'create', 'edit', 'delete'],
            'tokens'        => ['view', 'create', 'manage'],
            'doctors'       => ['view', 'create', 'edit', 'delete'],
            'prescriptions' => ['view', 'create'],
            'invoices'      => ['view', 'create', 'manage'],
            'pos'           => ['view', 'manage'],
            'pharmacy'      => ['view', 'manage'],
            'staff'         => ['view', 'create', 'edit', 'delete'],
            'reports'       => ['view'],
            'settings'      => ['view', 'update'],
        ];

        $tenantPerms = [];
        foreach ($tenantModules as $module => $actions) {
            foreach ($actions as $action) {
                $name = "{$module}.{$action}";
                $tenantPerms[] = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            }
        }

        // ── Tenant Roles ──
        $owner = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $owner->syncPermissions($tenantPerms);

        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $managerPerms = Permission::where('guard_name', 'web')
            ->whereNotIn('name', ['settings.view', 'settings.update'])
            ->get();
        $manager->syncPermissions($managerPerms);

        $receptionist = Role::firstOrCreate(['name' => 'receptionist', 'guard_name' => 'web']);
        $receptionistPerms = Permission::where('guard_name', 'web')
            ->whereIn('name', [
                'patients.view', 'patients.create', 'patients.edit',
                'tokens.view', 'tokens.create', 'tokens.manage',
                'doctors.view',
                'invoices.view', 'invoices.create', 'invoices.manage',
            ])
            ->get();
        $receptionist->syncPermissions($receptionistPerms);

        $doctor = Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);
        $doctorPerms = Permission::where('guard_name', 'web')
            ->whereIn('name', [
                'patients.view',
                'tokens.view', 'tokens.manage',
                'prescriptions.view', 'prescriptions.create',
                'pharmacy.view',
            ])
            ->get();
        $doctor->syncPermissions($doctorPerms);

        $cashier = Role::firstOrCreate(['name' => 'cashier', 'guard_name' => 'web']);
        $cashierPerms = Permission::where('guard_name', 'web')
            ->whereIn('name', [
                'pos.view', 'pos.manage',
                'invoices.view', 'invoices.manage',
                'patients.view',
            ])
            ->get();
        $cashier->syncPermissions($cashierPerms);

        $this->command->info('Permissions & Roles seeded successfully!');
        $this->command->info('Platform: super-admin, viewer, finance');
        $this->command->info('Tenant: owner, manager, receptionist, doctor, cashier');
    }
}