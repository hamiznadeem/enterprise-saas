<?php

namespace App\Traits;

use App\Scopes\TenantScope;

trait BelongsToTenant
{
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            // Sirf Container check karega. Auth nahi chhedega!
            if (app()->bound('currentTenant') && is_null($model->tenant_id)) {
                $model->tenant_id = app('currentTenant')->id;
            }
        });
    }
}