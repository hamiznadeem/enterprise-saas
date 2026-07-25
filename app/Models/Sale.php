<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard



class Sale extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Sales are strictly isolated by clinic

    protected $fillable = [
        'tenant_id',
        'patient_id',
        'user_id',
        'sale_number',
        'subtotal',
        'tax_percentage',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'payment_method',
        'status',
        'branch_id',
    ];

    // Ensure proper data types
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A sale can have multiple items (Cart items)
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    

public function branch()
{
    return $this->belongsTo(Branch::class);
}

// ──  Scopes ──

public function scopeToday($query)
{
    return $query->whereDate('created_at', today());
}

public function scopeForBranch($query, int $branchId)
{
    return $query->where('branch_id', $branchId);
}

public function scopeByPaymentMethod($query, string $method)
{
    return $query->where('payment_method', $method);
}

// ──  Accessors ──

public function getFormattedTotalAttribute(): string
{
    return 'PKR ' . number_format($this->total_amount, 2);
}

public function getItemCountAttribute(): int
{
    return $this->items->sum('quantity');
}
}