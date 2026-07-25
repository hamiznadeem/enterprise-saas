<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard

class Invoice extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Invoices are strictly isolated by clinic

    protected $fillable = [
        'tenant_id',
        'patient_id',
        'token_id',
        'doctor_fee',
        'service_fee',
        'total_amount',
        'discount',
        'status',
        'branch_id',
    ];

    // Ensure proper data types
    protected $casts = [
        'doctor_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function token()
    {
        return $this->belongsTo(Token::class);
    }

    
public function branch()
{
    return $this->belongsTo(Branch::class);
}

public function doctor()
{
    return $this->belongsTo(Doctor::class);
}


// ── Scopes ──

public function scopePaid($query)
{
    return $query->where('status', 'paid');
}

public function scopeUnpaid($query)
{
    return $query->where('status', 'unpaid');
}

public function scopeForBranch($query, int $branchId)
{
    return $query->where('branch_id', $branchId);
}

public function scopeToday($query)
{
    return $query->whereDate('created_at', today());
}

// ── Accessors ──

public function getStatusColorAttribute(): string
{
    return match ($this->status) {
        'paid'    => 'green',
        'unpaid'  => 'red',
        'partial' => 'yellow',
        default   => 'gray',
    };
}

public function getDueAmountAttribute(): string
{
    return max(0, $this->total_amount - $this->discount);
}
}