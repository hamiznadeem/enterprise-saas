<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Service;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization, BaseTenantPolicy;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'service.view');
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'service.create');
    }

    public function update(User $user, Service $service): bool
    {
        return $this->hasPermission($user, 'service.edit')
            && $this->belongsToTenant($user, $service);
    }

    public function delete(User $user, Service $service): bool
    {
        return $this->hasPermission($user, 'service.delete')
            && $this->belongsToTenant($user, $service);
    }
}