<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Security Guard import kiya

class Doctor extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Data is now isolated by clinic

    protected $fillable = [
        'tenant_id',
        'name',
        'specialization',
        'consultation_fee',
        'phone',
        'is_active',
        'daily_patient_limit',
    ];

    // Ye batata hai ke fee ko hamesha number (decimal) mein treat kare
    protected $casts = [
        'consultation_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}