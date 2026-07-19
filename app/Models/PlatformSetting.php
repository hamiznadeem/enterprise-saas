<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $fillable = ['key', 'value', 'group'];
    
    // Helper: Kisi bhi setting ko easily get karo
    public static function get($key, $default = null) {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    // Helper: Easily set karo
    public static function set($key, $value, $group = 'general') {
        return static::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
    }
}