<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard

class Medicine extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Medicines are strictly isolated by clinic

    protected $fillable = [
        'tenant_id',
        'name',
        'brand_name',      
        'generic_name',
        'category',
        'stock_quantity',
        'sale_price',
        'purchase_price',
        'expiry_date',
        'batch_number',
        'barcode',      
        'is_active',
        'unit_name',
    ];

    // Ensure proper data types
    protected $casts = [
        'stock_quantity' => 'integer',
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];
}