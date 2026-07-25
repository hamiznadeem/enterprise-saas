<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPasswordHistory extends Model
{
    protected $table = 'user_password_history';

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}