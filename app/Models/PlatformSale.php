<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSale extends Model
{
    protected $table = 'platform_sales'; // Table ka naam explicitly batana zaroori hai

    protected $fillable = [
        'tenant_id',
        'platform_invoice_id',
        'total',
        'status',
        'payment_method',
    ];

    // Relations (Future reports ke liye)
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invoice()
    {
        return $this->belongsTo(PlatformInvoice::class, 'platform_invoice_id');
    }
}