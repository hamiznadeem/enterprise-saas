<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // pluck sirf 'value' lega, 'key' lega. Simple array ban jayega
        $settings = PlatformSetting::pluck('value', 'key')->toArray();
        
        // Timezone list (Sirf isliye chahiye kyunki select mein options chahiye)
        $timezones = \DateTimeZone::listIdentifiers(); 
        
        return view('platform.settings.index', compact('settings', 'timezones'));
    }

    
    public function update(Request $request)
    {
        $validated = $request->validate([
            // General
            'app_name' => 'required|string|max:255',
            'currency' => 'required|string|max:10',
            'default_language' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            
            // Branding (File Uploads)
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico|max:1024',
            
            // SMTP
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|in:tls,ssl,',
            'smtp_from_address' => 'nullable|email',
            
            // SMS
            'sms_provider' => 'nullable|string|max:50',
            'sms_api_key' => 'nullable|string|max:255',
            'sms_sender' => 'nullable|string|max:20',
            
            // System
            'maintenance_mode' => 'nullable|boolean',
            'maintenance_message' => 'nullable|string|max:500',
        ]);

        // 1. Simple Text/Select Fields Save
        $textFields = ['app_name', 'currency', 'default_language', 'timezone', 'smtp_host', 'smtp_port', 'smtp_username', 'smtp_encryption', 'smtp_from_address', 'sms_provider', 'sms_api_key', 'sms_sender', 'maintenance_message'];
        foreach ($textFields as $field) {
            if ($request->has($field)) {
                PlatformSetting::set($field, $request->$field, $this->getGroup($field));
            }
        }

        // 2. Boolean Fields Save (Maintenance Mode)
        if ($request->has('maintenance_mode')) {
            $value = $request->maintenance_mode === '1' ? '1' : '0';
            PlatformSetting::set('maintenance_mode', $value, 'system');
        }

        // 3. File Uploads (Logo & Favicon)
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $name = 'platform-logo.' . $file->getClientOriginalExtension();
            $file->storeAs('settings/' . $name, 'public');
            PlatformSetting::set('logo', $name, 'branding');
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $name = 'favicon.' . $file->getClientOriginalExtension();
            $publicPath = $file->storeAs('settings/' . $name, 'public');
            PlatformSetting::set('favicon', $name, 'branding');
        }

        return back()->with('success', 'All settings saved successfully!');
    }

    // Helper: Field ka group determine karna
    private function getGroup($field)
    {
        if (in_array($field, ['logo', 'favicon'])) return 'branding';
        if (in_array($field, ['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'smtp_from_address'])) return 'smtp';
        if (in_array($field, ['sms_provider', 'sms_api_key', 'sms_sender'])) return 'sms';
        if (in_array($field, ['maintenance_mode', 'maintenance_message'])) return 'system';
        return 'general';
    }
}