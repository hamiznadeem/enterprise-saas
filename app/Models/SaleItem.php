<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard

class SaleItem extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Sale items are strictly isolated by clinic

    protected $fillable = [
        'tenant_id',
        'sale_id',
        'itemable_type',
        'itemable_id',
        'item_name',
        'unit_price',
        'unit_name',
        'quantity',
        'total_price',
    ];

    // Ensure proper data types
    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Relationship: This item belongs to a sale
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Polymorphic Relationship: Get the actual item (Medicine, Product, etc.)
    public function itemable()
    {
        return $this->morphTo();
    }
}