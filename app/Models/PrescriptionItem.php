<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard

class PrescriptionItem extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Items are strictly isolated by clinic

    protected $fillable = [
        'tenant_id',
        'prescription_id',
        'medicine_id',
        'dosage',
        'days',
        'instructions',
    ];

    // Ensure proper data types
    protected $casts = [
        'days' => 'integer',
    ];

    // Relationships
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}