<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Auth\Access\HandlesAuthorization;

class BranchPolicy
{
    use HandlesAuthorization, BaseTenantPolicy;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'branch.view');
    }

    public function view(User $user, Branch $branch): bool
    {
        return $this->belongsToTenant($user, $branch);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'branch.create');
    }

    public function update(User $user, Branch $branch): bool
    {
        return $this->hasPermission($user, 'branch.edit')
            && $this->belongsToTenant($user, $branch);
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $this->hasPermission($user, 'branch.delete')
            && $this->belongsToTenant($user, $branch);
    }

    public function switchTo(User $user, Branch $branch): bool
    {
        // User ko is branch ka access hai ya nahi
        return $user->branches()
            ->where('branches.id', $branch->id)
            ->where('user_branches.is_active', true)
            ->exists();
    }
}