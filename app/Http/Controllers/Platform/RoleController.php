<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('guard_name', 'platform')->with('permissions')->get();
        $allPermissions = Permission::where('guard_name', 'platform')->get()->groupBy(function ($perm) {
            // Group by module (e.g., "tenants.create" -> "Tenants")
            return explode('.', $perm->name)[0];
        });

        return view('platform.roles.index', compact('roles', 'allPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,NULL,NULL,guard_name,platform',
            'permissions' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'platform'
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Role created successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error creating role.'], 500);
        }
    }

    public function update(Request $request, Role $role)
    {
        // Prevent editing super-admin role name
        if ($role->name === 'super-admin') {
            return response()->json(['success' => false, 'message' => 'Cannot modify Super Admin role.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id . ',id,guard_name,platform',
            'permissions' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $role->update(['name' => $request->name]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Role updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error updating role.'], 500);
        }
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super-admin') {
            return response()->json(['success' => false, 'message' => 'Cannot delete Super Admin role.'], 403);
        }

        $role->delete();
        return response()->json(['success' => true, 'message' => 'Role deleted.']);
    }
}