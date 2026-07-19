<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard

class Token extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Tokens are strictly isolated by clinic

    protected $fillable = [
        'tenant_id',
        'patient_id',
        'doctor_id',
        'service_id',
        'token_number',
        'status',
        'is_walk_in',
        'called_at',
        'completed_at',
    ];

    // Ensure proper data types
    protected $casts = [
        'is_walk_in' => 'boolean',
        'called_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships: A token belongs to a patient, a doctor, and a service
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // A token can have one invoice
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

        // A token can have one prescription
    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }
}