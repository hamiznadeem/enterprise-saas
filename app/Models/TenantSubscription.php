<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantSubscription extends Model
{
    protected $fillable = ['tenant_id', 'plan_id', 'type', 'amount', 'notes', 'starts_at', 'ends_at'];
    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'amount' => 'decimal:2'];
    
    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function plan() { return $this->belongsTo(Plan::class); }
}