<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Auth\Access\HandlesAuthorization;

class DoctorPolicy
{
    use HandlesAuthorization, BaseTenantPolicy;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'doctor.view');
    }

    public function view(User $user, Doctor $doctor): bool
    {
        return $this->hasPermission($user, 'doctor.view')
            && $this->belongsToTenant($user, $doctor)
            && $this->belongsToBranch($user, $doctor);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'doctor.create');
    }

    public function update(User $user, Doctor $doctor): bool
    {
        return $this->hasPermission($user, 'doctor.edit')
            && $this->belongsToTenant($user, $doctor);
    }

    public function delete(User $user, Doctor $doctor): bool
    {
        return $this->hasPermission($user, 'doctor.delete')
            && $this->belongsToTenant($user, $doctor);
    }

    public function toggleStatus(User $user, Doctor $doctor): bool
    {
        return $this->hasPermission($user, 'doctor.edit')
            && $this->belongsToTenant($user, $doctor);
    }
}