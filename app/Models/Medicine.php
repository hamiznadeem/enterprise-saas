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
        'branch_id',
    ];

    // Ensure proper data types
    protected $casts = [
        'stock_quantity' => 'integer',
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    
// ── Relationships ──

public function branch()
{
    return $this->belongsTo(Branch::class);
}

public function saleItems()
{
    return $this->morphMany(SaleItem::class, 'itemable');
}

// ── Scopes ──

public function scopeActive($query)
{
    return $query->where('is_active', true);
}

public function scopeLowStock($query, int $threshold = 10)
{
    return $query->where('stock_quantity', '<=', $threshold);
}

public function scopeExpiringSoon($query, int $days = 30)
{
    return $query->where('expiry_date', '<=', now()->addDays($days))
        ->where('expiry_date', '>=', now());
}

public function scopeExpired($query)
{
    return $query->where('expiry_date', '<', now());
}

public function scopeForBranch($query, int $branchId)
{
    return $query->where('branch_id', $branchId);
}

public function scopeSearch($query, string $term)
{
    return $query->where('name', 'LIKE', "%{$term}%")
        ->orWhere('generic_name', 'LIKE', "%{$term}%")
        ->orWhere('brand_name', 'LIKE', "%{$term}%")
        ->orWhere('barcode', 'LIKE', "%{$term}%");
}

// ── Accessors ──

public function getProfitMarginAttribute(): float
{
    if ($this->purchase_price <= 0) return 0;
    return (($this->sale_price - $this->purchase_price) / $this->purchase_price) * 100;
}

public function getIsExpiredAttribute(): bool
{
    return $this->expiry_date && $this->expiry_date->isPast();
}

public function getIsLowStockAttribute(): bool
{
    return $this->stock_quantity <= 10;
}

public function getStockStatusAttribute(): string
{
    if ($this->is_expired) return 'expired';
    if ($this->is_low_stock) return 'low';
    return 'ok';
}

public function getStockStatusColorAttribute(): string
{
    return match ($this->stock_status) {
        'expired' => 'red',
        'low'     => 'yellow',
        default    => 'green',
    };
}
}