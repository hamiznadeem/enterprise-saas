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
        'branch_id',
    ];

    // Ensure proper data types
    protected $casts = [
        'is_walk_in' => 'boolean',
        'called_at' => 'datetime',
        'completed_at' => 'datetime',
    ];




    // Relationships: A token belongs to a patient, a doctor, and a service

    public function branch()
{
    return $this->belongsTo(Branch::class);
}

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


    // ── Scopes ──

public function scopeWaiting($query)
{
    return $query->where('status', 'waiting');
}

public function scopeInProgress($query)
{
    return $query->where('status', 'in-progress');
}

public function scopeCompleted($query)
{
    return $query->where('status', 'completed');
}

public function scopeCancelled($query)
{
    return $query->where('status', 'cancelled');
}

public function scopeForDoctor($query, int $doctorId)
{
    return $query->where('doctor_id', $doctorId);
}

public function scopeForBranch($query, int $branchId)
{
    return $query->where('branch_id', $branchId);
}

public function scopeToday($query)
{
    return $query->whereDate('created_at', today());
}

public function scopeWalkIn($query)
{
    return $query->where('is_walk_in', true);
}



// ── Accessors ──

public function getStatusColorAttribute(): string
{
    return match ($this->status) {
        'waiting'     => 'yellow',
        'in-progress' => 'blue',
        'completed'   => 'green',
        'cancelled'   => 'red',
        default       => 'gray',
    };
}

public function getWaitingDurationAttribute(): ?string
{
    if ($this->called_at) {
        return $this->created_at->diffInSeconds($this->called_at) . 's';
    }
    return $this->created_at->diffForHumans();
}

public function getConsultationDurationAttribute(): ?string
{
    if ($this->called_at && $this->completed_at) {
        $minutes = $this->called_at->diffInMinutes($this->completed_at);
        return $minutes . ' min';
    }
    return null;
}


}