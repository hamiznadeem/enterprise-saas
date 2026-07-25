<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityLogPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('activity-log.view');
    }

    public function export(User $user): bool
    {
        return $user->can('activity-log.export');
    }
}