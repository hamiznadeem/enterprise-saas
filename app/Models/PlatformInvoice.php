<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformInvoice extends Model
{
    protected $fillable = ['tenant_id', 'subscription_id', 'invoice_number', 'amount', 'tax', 'total', 'status', 'due_date', 'paid_at'];
    protected $casts = ['amount' => 'decimal:2', 'tax' => 'decimal:2', 'total' => 'decimal:2', 'paid_at' => 'datetime', 'due_date' => 'datetime'];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function subscription() { return $this->belongsTo(TenantSubscription::class); }
}