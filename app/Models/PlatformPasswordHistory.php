<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformPasswordHistory extends Model
{
    protected $table = 'platform_password_history';

    const UPDATED_AT = null;

    protected $fillable = [
        'platform_admin_id',
        'password',
    ];

    public function admin()
    {
        return $this->belongsTo(PlatformAdmin::class);
    }
}