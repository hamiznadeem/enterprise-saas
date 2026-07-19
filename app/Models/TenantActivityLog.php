<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class TenantActivityLog extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'ip_address',
        'user_agent',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    // Jo user ne action kiya
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Kis cheez par action hua (Patient, Token, Sale, etc.)
    public function subject()
    {
        if ($this->subject_type && $this->subject_id) {
            return $this->morphTo();
        }
        return null;
    }

    // Easily log karne ka static method
    public static function log(string $action, string $description = null, $subject = null)
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}