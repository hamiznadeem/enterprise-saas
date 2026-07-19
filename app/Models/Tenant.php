<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'slug',
        'domain', 
        'database', 
        'plan_id', 
        'status', 
        'trial_ends_at', 
        'business_type', 
        'outlets', 
        'is_active', 
        'will_expire_at',
        'owner_name',
        'owner_email',
        'phone',     
        'city',         
        'location',  
        'web_access_url',
        'enabled_modules',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'will_expire_at' => 'datetime',
        'is_active' => 'boolean',
        'enabled_modules' => 'array', 
    ];

    // Check karna ke kya tenant expired ho chuka hai
    public function isExpired()
    {
        if ($this->will_expire_at && $this->will_expire_at->isPast() && in_array($this->status, ['trial', 'active'])) {
            return true;
        }
        return false;
    }

    // Tenant ko expired mark karo
    public function markAsExpired()
    {
        if ($this->isExpired()) {
            $this->update(['status' => 'expired']);
            return true;
        }
        return false;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function userBranches()
    {
        return $this->hasMany(UserBranch::class);
    }

    public function subscriptions() 
    { 
        return $this->hasMany(TenantSubscription::class); 
    }
}