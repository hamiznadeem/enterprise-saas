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
}