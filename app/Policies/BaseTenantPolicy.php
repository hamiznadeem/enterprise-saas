<?php

namespace App\Policies;

trait BaseTenantPolicy
{
    /**
     * Yeh before check har policy method se pehle run hota hai.
     * Agar user tenant owner hai to sab allow — optional.
     */
    public function before($user, $ability)
    {
        // Agar koi specific bypass chahiye to yahan likho
        // return true; // Sab allow
        return null; // Normal flow continue
    }

    /**
     * Check kare ke model user ke tenant ka hai ya nahi
     */
    protected function belongsToTenant($user, $model): bool
    {
        if (!isset($model->tenant_id)) return false;
        return $model->tenant_id === $user->tenant_id;
    }

    /**
     * Check kare ke model user ki branch ka hai ya nahi
     */
    protected function belongsToBranch($user, $model): bool
    {
        if (!$user->branch_id || !isset($model->branch_id)) return true; // Agar branch_id nahi to skip
        return $model->branch_id === $user->branch_id;
    }

    /**
     * Permission check via Spatie
     */
    protected function hasPermission($user, string $permission): bool
    {
        return $user->can($permission);
    }
}