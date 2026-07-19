<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Security Guard import kiya

class Patient extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Data is now isolated by clinic

    protected $fillable = [
        'tenant_id', // (Guard ke liye zaroori hai fillable mein)
        'name',
        'phone',
        'cnic',
        'age',
        'gender',
        'address',
        'emergency_contact',
        'blood_group',
        'allergies',
        'medical_history',
    ];


        public function tokens()
    {
        return $this->hasMany(Token::class);
    }
}