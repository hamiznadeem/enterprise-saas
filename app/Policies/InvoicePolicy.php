<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization, BaseTenantPolicy;

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'invoice.view');
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $this->hasPermission($user, 'invoice.view')
            && $this->belongsToTenant($user, $invoice)
            && $this->belongsToBranch($user, $invoice);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'invoice.create');
    }

    public function markPaid(User $user, Invoice $invoice): bool
    {
        return $this->hasPermission($user, 'invoice.pay')
            && $this->belongsToTenant($user, $invoice)
            && $this->belongsToBranch($user, $invoice);
    }
}