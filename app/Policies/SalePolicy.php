<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Sale;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
{
    use HandlesAuthorization, BaseTenantPolicy;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'sale.view');
    }

    public function view(User $user, Sale $sale): bool
    {
        return $this->hasPermission($user, 'sale.view')
            && $this->belongsToTenant($user, $sale)
            && $this->belongsToBranch($user, $sale);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'sale.create');
    }

    public function checkout(User $user): bool
    {
        return $this->hasPermission($user, 'sale.checkout');
    }
}