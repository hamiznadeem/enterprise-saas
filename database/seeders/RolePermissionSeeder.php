<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Permissions Reset kar do (Taake agar naye add kiye toh conflict na ho)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Permissions Define Karo (Module ke hisaab se)
        $permissions = [
            'dashboard view',
            'patient view', 'patient create', 'patient edit', 'patient delete',
            'token view', 'token create', 'token manage',
            'doctor view', 'doctor create', 'doctor edit', 'doctor delete',
            'prescription view', 'prescription create',
            'invoice view', 'invoice manage',
            'pos view', 'pos manage',
            'pharmacy view', 'pharmacy manage',
            'staff view', 'staff create', 'staff edit',
            'role view', 'role manage',
        ];

        // Loop karke DB mein save karo
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Roles Define Karo
        $roles = [
            'super-admin',
            'owner',
            'manager',
            'receptionist',
            'doctor',
            'cashier',
            'pharmacist',
        ];

        $rolePermissionMap = [
            'super-admin' => $permissions, // Sab kuch kar sakta hai
            'owner' => $permissions,       // Sab kuch kar sakta hai
            'manager' => $permissions,    // Sab kuch kar sakta hai
            'receptionist' => [
                'dashboard view', 'patient view', 'patient create', 'patient edit',
                'token view', 'token create', 'token manage',
                'prescription view', 'invoice view',
            ],
            'doctor' => [
                'dashboard view', 'patient view',
                'token view', 'token manage',
                'prescription view', 'prescription create',
            ],
            'cashier' => [
                'dashboard view', 'invoice view', 'invoice manage',
                'pos view', 'pos manage',
            ],
            'pharmacist' => [
                'dashboard view', 'pos view', 'pos manage',
                'pharmacy view', 'pharmacy manage',
                'prescription view',
            ],
        ];

        // Roles banao aur unhe permissions assign karo
        foreach ($roles as $role) {
            $roleModel = Role::firstOrCreate(['name' => $role]);
            
            if (isset($rolePermissionMap[$role])) {
                $roleModel->syncPermissions($rolePermissionMap[$role]);
            }
        }

        $this->command->info('Roles & Permissions seeded successfully!');
    }
}