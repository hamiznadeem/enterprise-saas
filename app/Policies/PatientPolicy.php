<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientPolicy
{
    use HandlesAuthorization, BaseTenantPolicy;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'patient.view');
    }

    public function view(User $user, Patient $patient): bool
    {
        return $this->hasPermission($user, 'patient.view')
            && $this->belongsToTenant($user, $patient)
            && $this->belongsToBranch($user, $patient);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'patient.create');
    }

    public function update(User $user, Patient $patient): bool
    {
        return $this->hasPermission($user, 'patient.edit')
            && $this->belongsToTenant($user, $patient)
            && $this->belongsToBranch($user, $patient);
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $this->hasPermission($user, 'patient.delete')
            && $this->belongsToTenant($user, $patient)
            && $this->belongsToBranch($user, $patient);
    }

    public function viewHistory(User $user, Patient $patient): bool
    {
        return $this->hasPermission($user, 'patient.view-history')
            && $this->belongsToTenant($user, $patient)
            && $this->belongsToBranch($user, $patient);
    }
}