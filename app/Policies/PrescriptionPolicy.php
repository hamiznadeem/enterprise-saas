<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Prescription;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrescriptionPolicy
{
    use HandlesAuthorization, BaseTenantPolicy;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'prescription.view');
    }

    public function view(User $user, Prescription $prescription): bool
    {
        return $this->hasPermission($user, 'prescription.view')
            && $this->belongsToTenant($user, $prescription)
            && $this->belongsToBranch($user, $prescription);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'prescription.create');
    }
}