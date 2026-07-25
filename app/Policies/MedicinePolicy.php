<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Medicine;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicinePolicy
{
    use HandlesAuthorization, BaseTenantPolicy;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'medicine.view');
    }

    public function view(User $user, Medicine $medicine): bool
    {
        return $this->hasPermission($user, 'medicine.view')
            && $this->belongsToTenant($user, $medicine);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'medicine.create');
    }

    public function update(User $user, Medicine $medicine): bool
    {
        return $this->hasPermission($user, 'medicine.edit')
            && $this->belongsToTenant($user, $medicine);
    }

    public function delete(User $user, Medicine $medicine): bool
    {
        return $this->hasPermission($user, 'medicine.delete')
            && $this->belongsToTenant($user, $medicine);
    }
}