<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['admin_id', 'action', 'subject_type', 'subject_id', 'description', 'ip_address'];
    
    public function admin() { return $this->belongsTo(\App\Models\PlatformAdmin::class, 'admin_id'); }
    
    // Helper: Asani se log likho
    public static function log($action, $description, $subject = null) {
        return static::create([
            'admin_id' => auth()->guard('platform')->id(),
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}