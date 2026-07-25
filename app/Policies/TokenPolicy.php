<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Token;
use Illuminate\Auth\Access\HandlesAuthorization;

class TokenPolicy
{
    use HandlesAuthorization, BaseTenantPolicy;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'token.view');
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'token.create');
    }

    public function view(User $user, Token $token): bool
    {
        return $this->belongsToTenant($user, $token)
            && $this->belongsToBranch($user, $token);
    }

    public function callNext(User $user, Token $token): bool
    {
        return $this->hasPermission($user, 'token.call')
            && $this->belongsToTenant($user, $token);
    }

    public function complete(User $user, Token $token): bool
    {
        return $this->hasPermission($user, 'token.complete')
            && $this->belongsToTenant($user, $token);
    }

    public function cancel(User $user, Token $token): bool
    {
        return $this->hasPermission($user, 'token.cancel')
            && $this->belongsToTenant($user, $token);
    }
}