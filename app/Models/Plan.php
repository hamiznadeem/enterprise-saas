<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'billing_cycle', 
        'trial_days', 'limits', 'features', 'is_active'
    ];

    protected $casts = [
        'limits' => 'array',
        'features' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    // Yeh function plan ki duration days mein return karega
    public function getDurationInDaysAttribute()
    {
        return match($this->billing_cycle) {
            'monthly' => 30,
            'quarterly' => 90,
            'yearly' => 365,
            'lifetime' => 36500, // 100 years
            default => 30,
        };
    }

    // URL friendly slug generate karo
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = \Str::slug($value);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}