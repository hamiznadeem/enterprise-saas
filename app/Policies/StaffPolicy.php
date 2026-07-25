<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StaffPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('staff.view');
    }

    public function create(User $user): bool
    {
        return $user->can('staff.create');
    }

    public function update(User $user, User $staff): bool
    {
        return $user->can('staff.edit')
            && $staff->tenant_id === $user->tenant_id;
    }

    public function delete(User $user, User $staff): bool
    {
        return $user->can('staff.delete')
            && $staff->tenant_id === $user->tenant_id
            && $staff->id !== $user->id; // Apna khud delete nahi kar sakta
    }

    public function toggleStatus(User $user, User $staff): bool
    {
        return $user->can('staff.edit')
            && $staff->tenant_id === $user->tenant_id
            && $staff->id !== $user->id;
    }

    public function assignRole(User $user): bool
    {
        return $user->can('staff.assign-role');
    }

    public function assignBranch(User $user): bool
    {
        return $user->can('staff.assign-branch');
    }

    public function restore(User $user, User $staff): bool
    {
        return $user->can('staff.restore')
            && $staff->tenant_id === $user->tenant_id;
    }
}