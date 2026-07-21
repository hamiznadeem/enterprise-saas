# Enterprise SaaS — Full Source Code Context

Auto-generated reference containing the complete source code of this Laravel 12 multi-tenant SaaS application (Controllers, Models, Middleware, Requests, Services, Repositories, Scopes, Traits, Notifications, Providers, DTOs, View Components, and Routes).

Purpose: give any AI assistant full, exact context of existing code so new code it writes matches existing conventions, namespaces, method signatures, and does not conflict or duplicate existing logic.

See also (uploaded separately in project): `folder_structure_context.md` (full directory tree) and `database_context.md` (full DB schema).

## Table of Contents

- [Routes](#routes)
- [Models](#models)
- [Controllers](#controllers)
- [Middleware](#middleware)
- [Form Requests](#form-requests)
- [Services](#services)
- [Repositories](#repositories)
- [Scopes](#scopes)
- [Traits](#traits)
- [Notifications](#notifications)
- [Providers](#providers)
- [View Components](#view-components)

## Routes


### `routes/web.php`

```php
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TrialController;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
// use App\Models\User;
use App\Http\Controllers\DashboardController as DashboardController;
use App\Http\Controllers\Tenant\AuthController as TenantAuthController;


// Landing Route
Route::get('/', [LandingController::class, 'index'])->name('landing');


// Registration Route (Points directly to the updated Breeze controller)
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::middleware(['web'])->group(base_path('routes/platform.php'));


// Free Trial Registration (public — no auth)
Route::get('/free-trial', [TrialController::class, 'showForm'])->name('trial.form');
Route::post('/free-trial', [TrialController::class, 'register'])->name('trial.register');


// ==========================================
// TENANT AUTH (Public — No Auth Required)
// ==========================================
Route::get('/login', [TenantAuthController::class, 'showLoginForm'])->name('tenantView.login');
Route::post('/tenant/auth/login', [TenantAuthController::class, 'login'])->name('tenant.auth.login');
Route::post('/tenant/auth/logout', [TenantAuthController::class, 'logout'])->name('tenant.auth.logout');


Route::get('/forgot-password', function () {
    return view('tenantView.auth.forgot-password');
})->name('tenant.password.request');

Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('tenant.password.email');

Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('tenant.password.reset');

Route::post('/reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('tenant.password.update');



Route::get('/tenant/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('tenantView.login');
    }
    
    $user = Auth::user();
    $tenant = \App\Models\Tenant::find($user->tenant_id);

        
    
    // Already expired
    if ($tenant->status === 'expired') {
        return redirect()->route('tenant.billing');
    }
    
    // Trial expired — auto mark
    if ($tenant->status === 'trial' && $tenant->trial_ends_at && $tenant->trial_ends_at->isPast()) {
        $tenant->update(['status' => 'expired']);
        return redirect()->route('tenant.billing');
    }
    
    // Active plan expired — auto mark
    if ($tenant->status === 'active' && $tenant->will_expire_at && $tenant->will_expire_at->isPast()) {
        $tenant->update(['status' => 'expired']);
        return redirect()->route('tenant.billing');
    }
    
    // Suspended
    if ($tenant->status === 'suspended') {
        Auth::logout();
        return redirect()->route('tenantView.login')
            ->withErrors(['email' => 'Your account has been suspended. Contact support.']);
    }
    
    // Inactive
    if (!$tenant->is_active) {
        Auth::logout();
        return redirect()->route('tenantView.login')
            ->withErrors(['email' => 'Your account is inactive. Contact support.']);
    }
    
    app()->instance('currentTenant', $tenant);
    
    $controller = app(\App\Http\Controllers\DashboardController::class);
    return $controller->__invoke();
})->name('tenant.dashboard');


// ── BILLING PAGE ROUTE ──
Route::get('/tenant/billing', function () {
    if (!Auth::check()) {
        return redirect()->route('tenantView.login');
    }
    
    $user = Auth::user();
    $tenant = \App\Models\Tenant::find($user->tenant_id);
    
    return view('tenantView.billing', compact('tenant'));
})->name('tenant.billing');


// ==========================================
// TENANT ROUTES (Auth Required)
// ==========================================

// Dashboard Route
// Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'tenant.identifier', 'trial'])->name('dashboard');


// Patients & Tokens Routes (Secure Group)
Route::middleware(['auth', 'tenant.identifier', 'trial'])->group(function () {
    
    // Patient Routes
    Route::get('/patients', [App\Http\Controllers\PatientController::class, 'index'])->name('patients.index');
    Route::post('/patients', [App\Http\Controllers\PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/search', [App\Http\Controllers\PatientController::class, 'search'])->name('patients.search');
    Route::get('/patients/{patient}/history', [App\Http\Controllers\PatientController::class, 'showHistory'])->name('patients.history');

    // Token Routes
    Route::get('/tokens/create', [App\Http\Controllers\TokenController::class, 'create'])->name('tokens.create');
    Route::get('/tokens', [App\Http\Controllers\TokenController::class, 'index'])->name('tokens.index');
    Route::post('/tokens', [App\Http\Controllers\TokenController::class, 'store'])->name('tokens.store');
    

    // Doctor Dashboard Routes
    Route::get('/doctor/dashboard', [App\Http\Controllers\TokenController::class, 'doctorDashboard'])->name('tokens.doctor.dashboard');
    Route::post('/doctor/call-next', [App\Http\Controllers\TokenController::class, 'callNextToken'])->name('tokens.doctor.call-next');
    Route::post('/doctor/complete/{id}', [App\Http\Controllers\TokenController::class, 'completeToken'])->name('tokens.doctor.complete');

    // Invoice Routes
    Route::post('/invoices/generate/{token_id}', [App\Http\Controllers\InvoiceController::class, 'store'])->name('invoices.generate');
    Route::get('/invoices/{invoice}', [App\Http\Controllers\InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices/{invoice}/pay', [App\Http\Controllers\InvoiceController::class, 'markAsPaid'])->name('invoices.pay');


    // Prescription Routes
    Route::get('/prescriptions/create/{token_id}', [App\Http\Controllers\PrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::get('/prescriptions/search-medicine', [App\Http\Controllers\PrescriptionController::class, 'searchMedicine'])->name('prescriptions.search-medicine');
    Route::post('/prescriptions/store/{token_id}', [App\Http\Controllers\PrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::get('/prescriptions/{prescription}', [App\Http\Controllers\PrescriptionController::class, 'show'])->name('prescriptions.show'); 
    Route::get('/prescriptions/search-medicine', [App\Http\Controllers\PrescriptionController::class, 'searchMedicine'])->name('prescriptions.search-medicine');

    // POS Routes
    Route::get('/pos', [App\Http\Controllers\PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/search', [App\Http\Controllers\PosController::class, 'searchItems'])->name('pos.search');
    Route::post('/pos/checkout', [App\Http\Controllers\PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/receipt/{sale}', [App\Http\Controllers\PosController::class, 'showReceipt'])->name('pos.receipt');

    // Pharmacy Inventory Alerts
    Route::get('/pharmacy/dashboard', [App\Http\Controllers\PharmacyController::class, 'index'])->name('pharmacy.dashboard');

    // Activity Logs
    Route::get('/activity-logs', [App\Http\Controllers\Tenant\ActivityLogController::class, 'index'])->name('tenant.activity-logs');
});

// Doctor Management
Route::middleware(['auth', 'tenant.identifier', 'trial'])->group(function () {
    Route::get('/doctors', [App\Http\Controllers\DoctorController::class, 'index'])->name('doctors.index');
    Route::post('/doctors', [App\Http\Controllers\DoctorController::class, 'store'])->name('doctors.store');
    Route::put('/doctors/{doctor}', [App\Http\Controllers\DoctorController::class, 'update'])->name('doctors.update');
    Route::delete('/doctors/{doctor}', [App\Http\Controllers\DoctorController::class, 'destroy'])->name('doctors.destroy');
    Route::post('/doctors/{doctor}/toggle-status', [App\Http\Controllers\DoctorController::class, 'toggleStatus'])->name('doctors.toggle-status');
});

// Staff Management 
Route::middleware(['auth', 'tenant.identifier', 'trial'])->group(function () {
    Route::get('/staff', [App\Http\Controllers\StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [App\Http\Controllers\StaffController::class, 'store'])->name('staff.store');
    Route::put('/staff/{staff}', [App\Http\Controllers\StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [App\Http\Controllers\StaffController::class, 'destroy'])->name('staff.destroy');
    Route::post('/staff/{staff}/toggle-status', [App\Http\Controllers\StaffController::class, 'toggleStatus'])->name('staff.toggle-status');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// require __DIR__.'/auth.php';
```

### `routes/platform.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Platform\AuthController;
use App\Http\Controllers\Platform\PasswordController;
use App\Http\Controllers\Platform\VerificationController;
use App\Http\Controllers\Platform\SessionController;
use App\Http\Controllers\Platform\DashboardController;
use App\Http\Controllers\Platform\PlanController;
use App\Http\Controllers\Platform\TenantController;
use App\Http\Controllers\Platform\InvoiceController;
use App\Http\Controllers\Platform\SettingController;
use App\Http\Controllers\Platform\AuditLogController;
use App\Http\Controllers\Platform\RoleController;

// ── Public Auth Routes ──
Route::prefix('super-admin')->middleware('web')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('platform.login');
    Route::post('/login', [AuthController::class, 'login'])->name('platform.login.post');
    Route::get('/forgot-password', [PasswordController::class, 'showForgotForm'])->name('platform.password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->name('platform.password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('platform.password.reset');
    Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('platform.password.update');
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('platform.verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('platform.verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->name('platform.verification.send');
});

// ── Protected Routes ──
Route::prefix('super-admin')->middleware(['web', 'platform.auth'])->group(function () {
    
    // Dashboard — Sab ke liye allowed
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('platform.dashboard');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('platform.logout');
    Route::post('/logout-all', [AuthController::class, 'logoutAllDevices'])->name('platform.logout.all');

    // Plans — View sab ko, Create/Edit/Delete restricted
    Route::get('/plans', [PlanController::class, 'index'])
        ->middleware('permission:plans.view')
        ->name('platform.plans.index');
    Route::post('/plans', [PlanController::class, 'store'])
        ->middleware('permission:plans.create')
        ->name('platform.plans.store');
    Route::put('/plans/{plan}', [PlanController::class, 'update'])
        ->middleware('permission:plans.edit')
        ->name('platform.plans.update');
    Route::delete('/plans/{plan}', [PlanController::class, 'destroy'])
        ->middleware('permission:plans.delete')
        ->name('platform.plans.destroy');

    // Tenants Management
    Route::get('/tenants', [TenantController::class, 'index'])
        ->middleware('permission:tenants.view')
        ->name('platform.tenants.index');
    Route::post('/tenants', [TenantController::class, 'store'])
        ->middleware('permission:tenants.create')
        ->name('platform.tenants.store');
    Route::put('/tenants/{tenant}', [TenantController::class, 'update'])
        ->middleware('permission:tenants.edit')
        ->name('platform.tenants.update');
    Route::post('/tenants/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])
        ->middleware('permission:tenants.suspend')
        ->name('platform.tenants.toggle-status');
    Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy'])
        ->middleware('permission:tenants.delete')
        ->name('platform.tenants.destroy');
    Route::get('/tenants/{tenant}', [TenantController::class, 'show'])
        ->middleware('permission:tenants.view')
        ->name('platform.tenants.show');
    Route::post('/tenants/{tenant}/renew', [TenantController::class, 'renew'])
        ->middleware('permission:tenants.renew')
        ->name('tenants.renew');
    Route::post('/tenants/{tenant}/toggle-module', [TenantController::class, 'toggleModule'])
        ->middleware('permission:tenants.edit')
        ->name('platform.tenants.toggle-module');
    Route::post('/tenants/{tenant}/subscription-log', [TenantController::class, 'addSubscriptionLog'])
        ->middleware('permission:tenants.edit')
        ->name('platform.tenants.subscription-log');

    // Invoices — Read only for most
    Route::get('/invoices', [InvoiceController::class, 'index'])
        ->middleware('permission:invoices.view')
        ->name('platform.invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])
        ->middleware('permission:invoices.view')
        ->name('platform.invoices.show');

    // Settings — Super admin only effectively
    Route::get('/settings', [SettingController::class, 'index'])
        ->middleware('permission:settings.view')
        ->name('platform.settings.index');
    Route::post('/settings', [SettingController::class, 'update'])
        ->middleware('permission:settings.update')
        ->name('platform.settings.update');

    // Audit Logs — Read only
    Route::get('/audit-logs', [AuditLogController::class, 'index'])
        ->middleware('permission:audit-logs.view')
        ->name('platform.audit-logs.index');

    // Roles & Permissions — Super admin only
    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:roles.view')
        ->name('platform.roles.index');
    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('permission:roles.create')
        ->name('platform.roles.store');
    Route::put('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('permission:roles.edit')
        ->name('platform.roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
        ->middleware('permission:roles.delete')
        ->name('platform.roles.destroy');

    // Password Change
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('platform.password.change');
    Route::post('/change-password', [PasswordController::class, 'changePassword'])->name('platform.password.update.post');

    // Sessions
    Route::get('/sessions', [SessionController::class, 'index'])
        ->middleware('permission:sessions.view')
        ->name('platform.sessions.index');
    Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])
        ->middleware('permission:sessions.delete')
        ->name('platform.sessions.destroy');
    Route::post('/sessions/kill-all', [SessionController::class, 'killAll'])
        ->middleware('permission:sessions.delete')
        ->name('platform.sessions.kill-all');
});
```

### `routes/auth.php`

```php
<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
// use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Route::get('register', [RegisteredUserController::class, 'create'])
    //     ->name('register');

    // Route::post('register', [RegisteredUserController::class, 'store']);

    // Route::get('login', [AuthenticatedSessionController::class, 'create'])
    //     ->name('login');

    // Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    //     ->name('logout');
});
```

### `routes/console.php`

```php
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
```

## Models


### `app/Models/AuditLog.php`

```php
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
```

### `app/Models/Doctor.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Security Guard import kiya

class Doctor extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Data is now isolated by clinic

    protected $fillable = [
        'tenant_id',
        'name',
        'specialization',
        'consultation_fee',
        'phone',
        'is_active',
        'daily_patient_limit',
    ];

    // Ye batata hai ke fee ko hamesha number (decimal) mein treat kare
    protected $casts = [
        'consultation_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
```

### `app/Models/Domain.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'domain',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
```

### `app/Models/Invoice.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard

class Invoice extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Invoices are strictly isolated by clinic

    protected $fillable = [
        'tenant_id',
        'patient_id',
        'token_id',
        'doctor_fee',
        'service_fee',
        'total_amount',
        'discount',
        'status',
    ];

    // Ensure proper data types
    protected $casts = [
        'doctor_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function token()
    {
        return $this->belongsTo(Token::class);
    }
}
```

### `app/Models/LoginLog.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    // Yeh model BelongsToTenant NAHI use karta — security logs hain,
    // tenant identify hone se pehle bhi log hona chahiye
    protected $table = 'login_logs';

    // updated_at nahi chahiye — sirf created_at
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'email',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'browser_version',
        'os',
        'os_version',
        'status',
        'reason',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ── Relationships ──

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // ── Scopes ──

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeLast24Hours($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    // ── Helpers ──

    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function getDeviceIcon(): string
    {
        return match ($this->device_type) {
            'mobile'  => 'fa-mobile-screen',
            'tablet'  => 'fa-tablet-screen-button',
            default    => 'fa-desktop',
        };
    }

    public function getStatusColor(): string
    {
        return $this->isSuccessful() ? 'emerald' : 'red';
    }
}
```

### `app/Models/Medicine.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard

class Medicine extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Medicines are strictly isolated by clinic

    protected $fillable = [
        'tenant_id',
        'name',
        'brand_name',      
        'generic_name',
        'category',
        'stock_quantity',
        'sale_price',
        'purchase_price',
        'expiry_date',
        'batch_number',
        'barcode',      
        'is_active',
        'unit_name',
    ];

    // Ensure proper data types
    protected $casts = [
        'stock_quantity' => 'integer',
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];
}
```

### `app/Models/Patient.php`

```php
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
```

### `app/Models/Plan.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'billing_cycle', 
        'trial_days', 'limits', 'features', 'is_active'
    ];

    protected $casts = [
        'limits' => 'array',
        'features' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    // Yeh function plan ki duration days mein return karega
    public function getDurationInDaysAttribute()
    {
        return match($this->billing_cycle) {
            'monthly' => 30,
            'quarterly' => 90,
            'yearly' => 365,
            'lifetime' => 36500, // 100 years
            default => 30,
        };
    }

    // URL friendly slug generate karo
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = \Str::slug($value);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
```

### `app/Models/PlatformAdmin.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class PlatformAdmin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'platform';
    protected $table = 'platform_admins';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'locked_until' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    // ── Relationships ──

    public function passwordHistory()
    {
        return $this->hasMany(PlatformPasswordHistory::class)->orderByDesc('created_at');
    }

    // ── Account Lock ──

    public function isLocked(): bool
    {
        if (!$this->locked_until) return false;
        if ($this->locked_until->isPast()) {
            $this->update(['login_attempts' => 0, 'locked_until' => null, 'is_active' => true]);
            return false;
        }
        return true;
    }

    public function getLockRemainingMinutes(): ?int
    {
        if (!$this->locked_until || $this->locked_until->isPast()) return null;
        return now()->diffInMinutes($this->locked_until);
    }

    public function recordFailedAttempt(): bool
    {
        $this->increment('login_attempts');
        $this->refresh();

        if ($this->login_attempts >= 5) {
            $this->update([
                'login_attempts' => 5,
                'locked_until' => now()->addMinutes(15),
                'is_active' => false,
            ]);
            return true;
        }
        return false;
    }

    public function resetLoginAttempts(): void
    {
        $this->update(['login_attempts' => 0, 'locked_until' => null]);
    }

    public function getAttemptsRemaining(): int
    {
        return max(0, 5 - $this->login_attempts);
    }

    // ── Email Verification ──

    public function isVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function markEmailVerified(): void
    {
        $this->update(['email_verified_at' => now()]);
    }
}
```

### `app/Models/PlatformInvoice.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformInvoice extends Model
{
    protected $fillable = ['tenant_id', 'subscription_id', 'invoice_number', 'amount', 'tax', 'total', 'status', 'due_date', 'paid_at'];
    protected $casts = ['amount' => 'decimal:2', 'tax' => 'decimal:2', 'total' => 'decimal:2', 'paid_at' => 'datetime', 'due_date' => 'datetime'];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function subscription() { return $this->belongsTo(TenantSubscription::class); }
}
```

### `app/Models/PlatformPasswordHistory.php`

```php
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
```

### `app/Models/PlatformSale.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSale extends Model
{
    protected $table = 'platform_sales'; // Table ka naam explicitly batana zaroori hai

    protected $fillable = [
        'tenant_id',
        'platform_invoice_id',
        'total',
        'status',
        'payment_method',
    ];

    // Relations (Future reports ke liye)
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invoice()
    {
        return $this->belongsTo(PlatformInvoice::class, 'platform_invoice_id');
    }
}
```

### `app/Models/PlatformSetting.php`

```php
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
```

### `app/Models/Prescription.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard

class Prescription extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Prescriptions are strictly isolated by clinic

    protected $fillable = [
        'tenant_id',
        'patient_id',
        'doctor_id',
        'token_id',
        'diagnosis',
        'notes',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function token()
    {
        return $this->belongsTo(Token::class);
    }

    // A prescription can have multiple medicines
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
```

### `app/Models/PrescriptionItem.php`

```php
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
```

### `app/Models/Sale.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard



class Sale extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Sales are strictly isolated by clinic

    protected $fillable = [
        'tenant_id',
        'patient_id',
        'user_id',
        'sale_number',
        'subtotal',
        'tax_percentage',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'payment_method',
        'status',
    ];

    // Ensure proper data types
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A sale can have multiple items (Cart items)
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
```

### `app/Models/SaleItem.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard

class SaleItem extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Sale items are strictly isolated by clinic

    protected $fillable = [
        'tenant_id',
        'sale_id',
        'itemable_type',
        'itemable_id',
        'item_name',
        'unit_price',
        'unit_name',
        'quantity',
        'total_price',
    ];

    // Ensure proper data types
    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Relationship: This item belongs to a sale
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Polymorphic Relationship: Get the actual item (Medicine, Product, etc.)
    public function itemable()
    {
        return $this->morphTo();
    }
}
```

### `app/Models/Service.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard

class Service extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Data is now isolated by clinic

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'fee',
        'is_active',
    ];

    // Ensure proper data types when accessing these fields
    protected $casts = [
        'fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
```

### `app/Models/Tenant.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'slug',
        'domain', 
        'database', 
        'plan_id', 
        'status', 
        'trial_ends_at', 
        'business_type', 
        'outlets', 
        'is_active', 
        'will_expire_at',
        'owner_name',
        'owner_email',
        'phone',     
        'city',         
        'location',  
        'web_access_url',
        'enabled_modules',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'will_expire_at' => 'datetime',
        'is_active' => 'boolean',
        'enabled_modules' => 'array', 
    ];

    // Check karna ke kya tenant expired ho chuka hai
    public function isExpired()
    {
        if ($this->will_expire_at && $this->will_expire_at->isPast() && in_array($this->status, ['trial', 'active'])) {
            return true;
        }
        return false;
    }

    // Tenant ko expired mark karo
    public function markAsExpired()
    {
        if ($this->isExpired()) {
            $this->update(['status' => 'expired']);
            return true;
        }
        return false;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function userBranches()
    {
        return $this->hasMany(UserBranch::class);
    }

    public function subscriptions() 
    { 
        return $this->hasMany(TenantSubscription::class); 
    }
}
```

### `app/Models/TenantActivityLog.php`

```php
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
```

### `app/Models/TenantSubscription.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantSubscription extends Model
{
    protected $fillable = ['tenant_id', 'plan_id', 'type', 'amount', 'notes', 'starts_at', 'ends_at'];
    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'amount' => 'decimal:2'];
    
    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function plan() { return $this->belongsTo(Plan::class); }
}
```

### `app/Models/Token.php`

```php
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
```

### `app/Models/User.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Traits\BelongsToTenant;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToTenant, HasRoles, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
        'role',
        'doctor_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'locked_until' => 'datetime',
    ];
    // ── Relationships ──

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branches()
    {
        return $this->hasMany(UserBranch::class);
    }

    public function defaultBranch()
    {
        return $this->hasOne(UserBranch::class)->where('is_default', true)->where('is_active', true);
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    // ── Branch Helpers ──

    public function hasBranchAccess(int $branchId): bool
    {
        return $this->branches()
            ->where('id', $branchId)
            ->where('is_active', true)
            ->exists();
    }

    public function getActiveBranches()
    {
        return $this->branches()->active()->get();
    }

    public function getDefaultBranch(): ?UserBranch
    {
        return $this->defaultBranch ?? $this->branches()->active()->first();
    }

    public function assignBranch(array $data): UserBranch
    {
        if (!empty($data['is_default']) && $data['is_default']) {
            $this->branches()->where('tenant_id', $data['tenant_id'] ?? $this->tenant_id)
                ->update(['is_default' => false]);
        }

        return UserBranch::create(array_merge([
            'user_id'   => $this->id,
            'tenant_id' => $this->tenant_id,
            'is_active' => true,
            'is_default' => false,
        ], $data));
    }


        // ── Account Lock Helpers ──

    public function isLocked(): bool
    {
        return \App\Services\AccountLockService::isLocked($this);
    }

    public function getLockRemainingMinutes(): ?int
    {
        return \App\Services\AccountLockService::getRemainingLockMinutes($this);
    }

    public function getAttemptsRemaining(): int
    {
        return \App\Services\AccountLockService::getAttemptsRemaining($this);
    }
        /**
     * Override to use custom notification
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\TenantPasswordResetNotification($token));
    }
}
```

### `app/Models/UserBranch.php`

```php
<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class UserBranch extends Model
{
    use BelongsToTenant;

    protected $table = 'user_branches';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'branch_name',
        'branch_code',
        'address',
        'phone',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
    ];

    // ── Relationships ──

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // ── Helpers ──

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    public function makeDefault(): void
    {
        // Pehle is tenant ki saari branches ka default hata do
        static::where('tenant_id', $this->tenant_id)
            ->where('user_id', $this->user_id)
            ->update(['is_default' => false]);

        // Phir isko default bana do
        $this->update(['is_default' => true]);
    }
}
```

## Controllers


### `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\AccountLockService;
use App\Services\LoginLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): never
    {
        abort(404);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        // ── Step 1: Check if account is locked ──
        if ($user && AccountLockService::isLocked($user)) {
            $minutes = AccountLockService::getRemainingLockMinutes($user);

            LoginLogService::logFailed($request, 'account_locked', $user);

            throw ValidationException::withMessages([
                'email' => "Account locked due to too many failed attempts. Try again in {$minutes} minute(s).",
            ]);
        }

        // ── Step 2: Check if account is inactive (manual deactivation, not lock) ──
        if ($user && !$user->is_active && !$user->locked_until) {
            LoginLogService::logFailed($request, 'account_inactive', $user);

            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated. Contact your administrator.',
            ]);
        }

        // ── Step 3: Attempt authentication ──
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            // FAILED LOGIN
            if ($user) {
                $gotLocked = AccountLockService::recordFailedAttempt($user);

                if ($gotLocked) {
                    $minutes = AccountLockService::LOCK_DURATION_MINUTES;
                    LoginLogService::logFailed($request, 'account_locked', $user);

                    throw ValidationException::withMessages([
                        'email' => "Too many failed attempts. Account locked for {$minutes} minutes.",
                    ]);
                }

                $remaining = AccountLockService::getAttemptsRemaining($user);
                LoginLogService::logFailed($request, 'invalid_credentials', $user);

                throw ValidationException::withMessages([
                    'email' => "Invalid credentials. {$remaining} attempt(s) remaining.",
                ]);
            }

            // User not found — still log
            LoginLogService::logFailed($request, 'invalid_credentials');
            RateLimiter::hit($request->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // ── Step 4: Login successful ──
        $user = Auth::user();

        // Reset failed attempts
        AccountLockService::resetAttempts($user);

        // Log success
        LoginLogService::logSuccess($request, $user);

        // Clear rate limiter
        RateLimiter::clear($request->throttleKey());

        // Regenerate session
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
```

### `app/Http/Controllers/Auth/ConfirmablePasswordController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
```

### `app/Http/Controllers/Auth/EmailVerificationNotificationController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
```

### `app/Http/Controllers/Auth/EmailVerificationPromptController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('auth.verify-email');
    }
}
```

### `app/Http/Controllers/Auth/NewPasswordController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        // Changed from 'auth.reset-password' to 'tenantView.auth.reset-password'
        return view('tenantView.auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('tenantView.login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
```

### `app/Http/Controllers/Auth/PasswordController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
```

### `app/Http/Controllers/Auth/PasswordResetLinkController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
```

### `app/Http/Controllers/Auth/RegisteredUserController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Tenant;
use App\Models\Domain;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
        public function store(Request $request): RedirectResponse | JsonResponse
    {
        // 1. Form ka data validate karein (sab fields zaroori hain)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['required', 'string', 'max:255', 'unique:tenants,name'],
            'business_type' => ['required', 'string', 'max:255'],
            'outlets' => ['required', 'integer', 'min:1'],
        ]);

        $tenant = Tenant::create([
            'name' => $request->company_name,
            'domain' => \Illuminate\Support\Str::slug($request->company_name),
            'database' => env('DB_DATABASE'),
            'status' => 'active',
            'trial_ends_at' => now()->addDays(14),
            'business_type' => $request->business_type,
            'outlets' => $request->outlets,
        ]);

        // Phir us Tenant ka Domain banayein (Future ke liye jab subdomain par chalayenge)
        Domain::create([
            'tenant_id' => $tenant->id,
            'domain' => Str::slug($request->company_name) . '.localhost.com',
        ]);

        // Ab User (Owner) banayein aur usko Tenant se attach karein
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $tenant->id, // Yahan user ko clinic se jod rahe hain
            'role' => 'owner', // Is user ka role owner hai
        ]);

        // ==========================================

        // 3. User ko automatically login kara dein
        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            // Agar email send nahi hua toh koi baat nahi, clinic toh ban gayi
        }
        Auth::login($user);

         // 3. User ko automatically login kara dein
        event(new Registered($user));
        Auth::login($user);

        // ==========================================
        // 4. SMART RETURN (Landing Page ke liye)
        // ==========================================
        
        // Agar request Landing Page (AJAX) se aayi hai
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'redirect_url' => route('dashboard')
            ]);
        }

        // Agar koi direct register page par aaya tha
        return redirect(route('dashboard'));
    }
}
```

### `app/Http/Controllers/Auth/VerifyEmailController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
```

### `app/Http/Controllers/Controller.php`

```php
<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
}
```

### `app/Http/Controllers/DashboardController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\Medicine;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $todayPatients = Patient::whereDate('created_at', today())->count();
        $waitingTokens = Token::where('status', 'waiting')->count();
        $inProgressTokens = Token::where('status', 'in-progress')->count();
        
        $todayRevenue = Invoice::whereDate('created_at', today())->sum('total_amount');
        
        $lowStockCount = Medicine::where('stock_quantity', '<=', 10)->count();
        $expiringSoonCount = Medicine::whereBetween('expiry_date', [now(), now()->addDays(30)])->count();

        $recentTokens = Token::with(['patient', 'doctor'])
            ->latest()
            ->take(8)
            ->get();

        $todayCompleted = Token::where('status', 'completed')
            ->whereDate('updated_at', today())
            ->count();

        return view('tenantView.dashboard', compact(
            'todayPatients',
            'waitingTokens',
            'inProgressTokens',
            'todayRevenue',
            'lowStockCount',
            'expiringSoonCount',
            'recentTokens',
            'todayCompleted'
        ));
    }
}
```

### `app/Http/Controllers/DoctorController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Services\DoctorService;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use Illuminate\Http\Request;
use App\Http\Requests\Doctor\UpdateDoctorRequest;

class DoctorController extends Controller
{
    protected $service;

    // Service automatically inject ho jayegi
    public function __construct(DoctorService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $doctors = $this->service->getDoctors();
        return view('doctors.index', compact('doctors'));
    }

    public function store(StoreDoctorRequest $request)
    {
        // Form Request ne validate kar liya, ab bas service ko bhej do
        $this->service->createDoctor($request->validated());
        
        return redirect()->back()->with('success', 'Doctor added successfully!');
    }

        public function update(UpdateDoctorRequest $request, $id)
    {
        $this->service->updateDoctor($id, $request->validated());
        return redirect()->back()->with('success', 'Doctor updated successfully!');
    }

    public function destroy($id)
    {
        $this->service->deleteDoctor($id);
        return redirect()->back()->with('success', 'Doctor deleted successfully!');
    }

        public function toggleStatus($id)
    {
        $this->service->toggleDoctorStatus($id);
        return back()->with('success', 'Doctor status updated!');
    }
}
```

### `app/Http/Controllers/InvoiceController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Token;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Generate bill for a completed token
    public function store(Request $request, $token_id)
    {
        // Find the token (Security scope ensures it belongs to the current clinic)
        $token = Token::findOrFail($token_id);

        // Check if token is actually completed
        if ($token->status !== 'completed') {
            return redirect()->back()->with('error', 'Bill can only be generated for completed checkups.');
        }

        // Prevent duplicate invoices for the same token
        if ($token->invoice) {
            return redirect()->back()->with('error', 'Bill for this token is already generated!');
        }

        // 1. Get Doctor Fee
        $doctorFee = $token->doctor->consultation_fee ?? 0;

        // 2. Get Service Fee (if any service was selected)
        $serviceFee = 0;
        if ($token->service) {
            $serviceFee = $token->service->fee ?? 0;
        }

        // 3. Calculate Total
        $totalAmount = $doctorFee + $serviceFee;

        // 4. Create the Invoice
        // Note: tenant_id is NOT added here because our BelongsToTenant trait adds it automatically!
        Invoice::create([
            'patient_id' => $token->patient_id,
            'token_id'   => $token->id,
            'doctor_fee' => $doctorFee,
            'service_fee'=> $serviceFee,
            'total_amount'=> $totalAmount,
            'status'     => 'unpaid',
        ]);

        return redirect()->back()->with('success', "Bill of Rs. {$totalAmount} generated successfully for Token {$token->token_number}!");
    }

        // Display the invoice receipt
    public function show(Invoice $invoice)
    {
        // Load relationships so we can display doctor and service names
        $invoice->load(['patient', 'token.doctor', 'token.service']);
        
        // Get current clinic details to show on top of the receipt
        $clinic = app('currentTenant');

        return view('invoices.show', compact('invoice', 'clinic'));
    }

        // Mark the invoice as paid
    public function markAsPaid(Invoice $invoice)
    {
        // Update status to paid
        $invoice->update([
            'status' => 'paid'
        ]);

        return redirect()->back()->with('success', 'Payment of Rs. ' . number_format($invoice->total_amount, 2) . ' received successfully!');
    }
}
```

### `app/Http/Controllers/LandingController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing');
    }
}
```

### `app/Http/Controllers/PatientController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Token;


class PatientController extends Controller
{
    // 1. Yeh function screen dikhayega (Table + Search bar + Modal Form)
    public function index()
    {
        // NEW: Database se patients fetch karke view ko bhej rahe hain
        $patients = Patient::latest()->paginate(10);
        return view('patients.index', compact('patients'));
    }

    // 2. Yeh function naya patient save karega
    public function store(Request $request)
    {
        // Form ka data validate karein (sab fields zaroori hain)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'cnic' => 'nullable|string|max:15',
            'age' => 'required|string|max:3',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'blood_group' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        // Data save karein (Yahan BelongsToTenant trait automatically tenant_id laga dega)
        Patient::create($validated);
        
        \App\Services\TenantActivityService::logPatientCreated(Patient::latest()->first());

        // Wapas usi page par bhej dein with success message
        return redirect()->route('patients.index')->with('success', 'Patient registered successfully!');
    }

    // 3. Yeh function search karega purane patient ko
    public function search(Request $request)
    {
        // NEW: Nayi UI 'q' parameter bhejti hai AJAX mein
        $search = $request->get('q', '');
        
        if (empty($search)) {
            return response()->json([]);
        }
        
        // Current tenant ke andar hi search karega (Security guard automatically lagega)
        $patients = Patient::where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('cnic', 'like', "%{$search}%")
                            ->latest()
                            ->take(20)
                            ->get();

        // Results ko JSON format mein bhejega (AJAX ke liye)
        return response()->json($patients);
    }

    // View complete patient history (Visits, Prescriptions, Invoices)
    public function showHistory(Patient $patient)
    {
        // NEW: Patient model par directly tokens load kar rahe hain (Nayi UI isko expect karti hai)
        $patient->load(['tokens.doctor', 'tokens.service', 'tokens.prescription.items.medicine', 'tokens.invoice']);
        
        return view('patients.history', compact('patient'));
    }

}
```

### `app/Http/Controllers/PharmacyController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    // Pharmacy Inventory Dashboard (Alerts)
    public function index()
    {
        // THRESHOLDS: You can change these limits later
        $lowStockThreshold = 5; // Alert if 5 or less units left
        $expiryDaysThreshold = 30; // Alert if expiring within 30 days

        // 1. Get Low Stock Medicines
        $lowStockMedicines = Medicine::where('is_active', true)
            ->where('stock_quantity', '<=', $lowStockThreshold)
            ->orderBy('stock_quantity', 'asc') // Lowest stock first
            ->get();

        // 2. Get Expiring Soon Medicines (Including already expired)
        $expiringMedicines = Medicine::where('is_active', true)
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays($expiryDaysThreshold))
            ->orderBy('expiry_date', 'asc') // Expiring soonest first
            ->get();

        return view('pharmacy.dashboard', compact('lowStockMedicines', 'expiringMedicines'));
    }
}
```

### `app/Http/Controllers/Platform/AuditLogController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('admin')->latest()->paginate(50);
        return view('platform.audit-logs.index', compact('logs'));
    }
}
```

### `app/Http/Controllers/Platform/AuthController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformAdmin;
use App\Services\PlatformSessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('platform')->check()) {
            return redirect()->route('platform.dashboard');
        }
        return view('platform.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        $admin = PlatformAdmin::where('email', $request->email)->first();

        // Check lock
        if ($admin && $admin->isLocked()) {
            $mins = $admin->getLockRemainingMinutes();
            return response()->json([
                'success' => false,
                'message' => "Account locked. Try again in {$mins} minute(s).",
            ], 423);
        }

        // Check active
        if ($admin && !$admin->is_active && !$admin->locked_until) {
            return response()->json([
                'success' => false,
                'message' => 'Account deactivated. Contact system admin.',
            ], 403);
        }

        // Attempt
        if (!Auth::guard('platform')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            if ($admin) {
                $locked = $admin->recordFailedAttempt();
                if ($locked) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Too many failed attempts. Account locked for 15 minutes.',
                    ], 423);
                }
                $remaining = $admin->getAttemptsRemaining();
                return response()->json([
                    'success' => false,
                    'message' => "Invalid credentials. {$remaining} attempt(s) remaining.",
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 422);
        }

        // Success
        $admin = Auth::guard('platform')->user();
        $admin->resetLoginAttempts();

        session(['platform_admin_id' => $admin->id]);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'redirect' => route('platform.dashboard'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('platform')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('platform.login');
    }

    public function logoutAllDevices(Request $request)
    {
        $admin = Auth::guard('platform')->user();
        $killed = PlatformSessionService::killAllOtherSessions($admin->id);

        return response()->json([
            'success' => true,
            'message' => "{$killed} other session(s) terminated.",
        ]);
    }
}
```

### `app/Http/Controllers/Platform/DashboardController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\PlatformInvoice;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\PlatformSale;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Pehle expired tenants ko update kar do
        Tenant::chunk(100, function ($tenants) {
            foreach ($tenants as $tenant) {
                $tenant->markAsExpired();
            }
        });

        // 2. Tenant Counts
        $totalTenants      = Tenant::count();
        $activeTenants     = Tenant::where('status', 'active')->count();
        $trialTenants      = Tenant::where('status', 'trial')->count();
        $expiredTenants    = Tenant::where('status', 'expired')->count();
        $suspendedTenants  = Tenant::where('status', 'suspended')->count();

        // 3. Platform Level Counts
        $totalPlans = Plan::count();

        // 4. Total Users
        $totalUsers = User::count();

        // 5. Monthly Revenue
        $monthlyRevenue = PlatformInvoice::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'paid')
            ->sum('total') ?? 0;

        // 6. New Registrations (is mahine ke naye tenants)
        $newRegistrations = Tenant::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // 7. Active Sessions (last 5 minute mein active)
        $activeSessions = DB::table('sessions')
            ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
            ->count();

            // 8. Total Platform Sales
            $totalSales = PlatformSale::where('status', 'completed')->sum('total') ?? 0;

        // 9. Total Storage (DB size in MB)
        $result = DB::select(
            "SELECT SUM(data_length + index_length) AS size 
            FROM information_schema.tables 
            WHERE table_schema = ?",
            [config('database.connections.' . config('database.default') . '.database')]
        );
        $totalStorage = isset($result[0]->size) 
            ? number_format(round($result[0]->size / 1024 / 1024, 2), 2) 
            : '0.00';

        // 10. Recent Activities (Audit Logs se last 5)
        $recentActivities = AuditLog::with('admin')->latest()->take(5)->get();

        return view('platform.dashboard', compact(
            'totalTenants', 'activeTenants', 'trialTenants', 'expiredTenants', 'suspendedTenants',
            'totalPlans', 'totalUsers', 'totalSales', 'totalStorage', 'monthlyRevenue',
            'newRegistrations', 'activeSessions', 'recentActivities'
        ));
    }
}
```

### `app/Http/Controllers/Platform/InvoiceController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformInvoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = PlatformInvoice::with('tenant')->latest()->get();
        return view('platform.invoices.index', compact('invoices'));
    }

    public function show(PlatformInvoice $invoice)
    {
        $invoice->load('tenant', 'subscription.plan');
        return view('platform.invoices.show', compact('invoice'));
    }
}
```

### `app/Http/Controllers/Platform/PasswordController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformAdmin;
use App\Models\PlatformPasswordReset;
use App\Services\PlatformPasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    // ── Forgot Password ──

    public function showForgotForm()
    {
        return view('platform.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $admin = PlatformAdmin::where('email', $request->email)->first();

        if ($admin) {
            $token = Str::random(60);

            DB::table('platform_password_resets')->updateOrInsert(
                ['email' => $admin->email],
                ['email' => $admin->email, 'token' => Hash::make($token), 'created_at' => now()]
            );

            // Dev mode — return token. Production mein email bhejo.
            return response()->json([
                'success' => true,
                'message' => 'Reset link generated.',
                'token' => $token,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'If the email exists, a reset link will be sent.',
        ]);
    }

    // ── Reset Password ──

    public function showResetForm(Request $request, $token)
    {
        return view('platform.auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $reset = DB::table('platform_password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired reset token.',
            ], 422);
        }

        $admin = PlatformAdmin::where('email', $request->email)->first();
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email.',
            ], 422);
        }

        $strength = PlatformPasswordService::strength($request->password);
        if ($strength['score'] < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Password too weak. Use uppercase, numbers, and special characters.',
                'strength' => $strength,
            ], 422);
        }

        if (PlatformPasswordService::isOldPassword($admin, $request->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot reuse a recent password.',
            ], 422);
        }

        $hashed = Hash::make($request->password);
        PlatformPasswordService::recordHistory($admin, $hashed);

        $admin->update([
            'password' => $hashed,
            'login_attempts' => 0,
            'locked_until' => null,
            'is_active' => true,
        ]);

        DB::table('platform_password_resets')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
            'redirect' => route('platform.login'),
        ]);
    }

    // ── Change Password ──

    public function showChangeForm()
    {
        return view('platform.settings.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Auth::guard('platform')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        $strength = PlatformPasswordService::strength($request->password);
        if ($strength['score'] < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Password too weak.',
                'strength' => $strength,
            ], 422);
        }

        if (PlatformPasswordService::isOldPassword($admin, $request->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot reuse a recent password.',
            ], 422);
        }

        $hashed = Hash::make($request->password);
        PlatformPasswordService::recordHistory($admin, $hashed);

        $admin->update(['password' => $hashed]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }
}
```

### `app/Http/Controllers/Platform/PlanController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('price')->get();
        return view('platform.plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly,lifetime',
            'trial_days' => 'nullable|integer|min:0',
            'limits.branches' => 'required|integer|min:1',
            'limits.users' => 'required|integer|min:1',
            'limits.products' => 'required|integer|min:1',
        ]);

        $validated['limits'] = [
            'branches' => $request->input('limits.branches'),
            'users' => $request->input('limits.users'),
            'products' => $request->input('limits.products'),
        ];

        Plan::create($validated);

        AuditLog::log('create', "Created Plan: {$validated['name']}");
    
        return response()->json(['success' => true, 'message' => 'Plan created successfully!']);
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly,lifetime',
            'trial_days' => 'nullable|integer|min:0',
            'limits.branches' => 'required|integer|min:1',
            'limits.users' => 'required|integer|min:1',
            'limits.products' => 'required|integer|min:1',
        ]);

        $validated['limits'] = [
            'branches' => $request->input('limits.branches'),
            'users' => $request->input('limits.users'),
            'products' => $request->input('limits.products'),
        ];

        $plan->update($validated);

        AuditLog::log('create', "Created Plan: {$validated['name']}");

        return response()->json(['success' => true, 'message' => 'Plan updated successfully!']);
    }

    public function destroy(Plan $plan)
    {
        if($plan->tenants()->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete plan assigned to tenants.'], 400);
        }
        $plan->delete();

        AuditLog::log('create', "Created Plan: {$validated['name']}");
        
        return response()->json(['success' => true, 'message' => 'Plan deleted.']);
    }
}
```

### `app/Http/Controllers/Platform/RoleController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('guard_name', 'platform')->with('permissions')->get();
        $allPermissions = Permission::where('guard_name', 'platform')->get()->groupBy(function ($perm) {
            // Group by module (e.g., "tenants.create" -> "Tenants")
            return explode('.', $perm->name)[0];
        });

        return view('platform.roles.index', compact('roles', 'allPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,NULL,NULL,guard_name,platform',
            'permissions' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'platform'
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Role created successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error creating role.'], 500);
        }
    }

    public function update(Request $request, Role $role)
    {
        // Prevent editing super-admin role name
        if ($role->name === 'super-admin') {
            return response()->json(['success' => false, 'message' => 'Cannot modify Super Admin role.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id . ',id,guard_name,platform',
            'permissions' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $role->update(['name' => $request->name]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Role updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error updating role.'], 500);
        }
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super-admin') {
            return response()->json(['success' => false, 'message' => 'Cannot delete Super Admin role.'], 403);
        }

        $role->delete();
        return response()->json(['success' => true, 'message' => 'Role deleted.']);
    }
}
```

### `app/Http/Controllers/Platform/SessionController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Services\PlatformSessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('platform')->user();
        $sessions = PlatformSessionService::getActiveSessions($admin->id);
        return view('platform.sessions.index', compact('sessions'));
    }

    public function destroy(Request $request, $sessionId)
    {
        $killed = PlatformSessionService::killSession($sessionId);
        if (!$killed) {
            return response()->json(['success' => false, 'message' => 'Cannot kill current session.'], 422);
        }
        return response()->json(['success' => true, 'message' => 'Session terminated.']);
    }

    public function killAll(Request $request)
    {
        $admin = Auth::guard('platform')->user();
        $killed = PlatformSessionService::killAllOtherSessions($admin->id);
        return response()->json(['success' => true, 'message' => "{$killed} other session(s) terminated."]);
    }
}
```

### `app/Http/Controllers/Platform/SettingController.php`

```php
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
```

### `app/Http/Controllers/Platform/TenantController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Domain;
use App\Models\User;
use App\Models\Plan;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TenantController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', 1)->get();
        
        $query = Tenant::with('plan');
        
        if ($search = request()->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%")
                  ->orWhere('owner_email', 'like', "%{$search}%");
            });
        }
        
        if ($status = request()->get('status')) {
            $query->where('status', $status);
        }
        
        if ($planId = request()->get('plan_id')) {
            $query->where('plan_id', $planId);
        }
        
        if (request()->ajax()) {
            return response()->json($query->latest()->get());
        }

        $tenants = $query->latest()->get();
        return view('platform.tenants.index', compact('tenants', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => [
                'required', 'string', 'max:255',
                Rule::unique('tenants', 'domain')->whereNull('deleted_at'),
                Rule::unique('domains', 'domain'),
            ],
            'plan_id' => 'required|exists:plans,id',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $plan = Plan::find($validated['plan_id']);

        $expiryDate = null;
        $status = 'active';

        if ($plan->trial_days > 0) {
            $expiryDate = now()->addDays($plan->trial_days);
            $status = 'trial';
        } else {
            $expiryDate = now()->addDays($plan->duration_in_days ?? 30);
        }

        DB::beginTransaction();

        try {
            $tenant = Tenant::create([
                'name' => $validated['name'],
                'domain' => $validated['domain'],
                'owner_name' => $validated['owner_name'],
                'owner_email' => $validated['owner_email'],
                'phone' => $validated['phone'] ?? null,
                'city' => $validated['city'] ?? null,
                'location' => $validated['location'] ?? null,
                'plan_id' => $validated['plan_id'],
                'status' => $status,
                'is_active' => 1,
                'trial_ends_at' => $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : null,
                'will_expire_at' => $expiryDate,
                'enabled_modules' => $plan->features ?? ['clinic' => true, 'pos' => true, 'pharmacy' => true],
            ]);

            Domain::create([
                'domain' => $validated['domain'] . '.yoursaas.com',
                'tenant_id' => $tenant->id,
            ]);

            $password = Str::random(12);

            $owner = User::create([
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'password' => Hash::make($password),
                'tenant_id' => $tenant->id,
                'role' => 'owner',
                'email_verified_at' => now(),
            ]);

            $owner->assignRole('owner');

            $tenant->subscriptions()->create([
                'plan_id' => $validated['plan_id'],
                'starts_at' => now(),
                'ends_at' => $expiryDate,
                'status' => $status,
                'trial_days' => $plan->trial_days,
            ]);

            AuditLog::create([
                'platform_admin_id' => auth('platform')->id(),
                'action' => 'tenant_created',
                'description' => "Created tenant: {$tenant->name}",
                'properties' => [
                    'tenant_id' => $tenant->id,
                    'tenant_name' => $tenant->name,
                    'domain' => $validated['domain'] . '.yoursaas.com',
                    'owner_email' => $validated['owner_email'],
                    'plan' => $plan->name,
                    'plan_id' => $plan->id,
                    'status' => $status,
                ],
            ]);

            DB::commit();

            try {
                $tenant->notify(new \App\Notifications\TenantCreatedNotification($tenant, [
                    'email' => $validated['owner_email'],
                    'password' => $password,
                ]));
            } catch (\Exception $e) {
                // Email fail hone pe tenant creation rokna nahi chahiye
            }

            return response()->json([
                'success' => true,
                'message' => 'Tenant created successfully!',
                'credentials' => [
                    'email' => $validated['owner_email'],
                    'password' => $password,
                ],
                'tenant' => [
                    'name' => $tenant->name,
                    'domain' => $validated['domain'] . '.yoursaas.com',
                    'plan' => $plan->name,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $errorMsg = 'Error creating tenant.';
            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'Unique')) {
                $errorMsg = 'Owner email or domain already exists.';
            }
            
            return response()->json([
                'success' => false,
                'message' => $errorMsg,
            ], 500);
        }
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => [
                'required', 'string', 'max:255',
                Rule::unique('tenants', 'domain')->ignore($tenant->id)->whereNull('deleted_at'),
                Rule::unique('domains', 'domain')->ignore($tenant->domains()->value('id')),
            ],
            'plan_id' => 'required|exists:plans,id',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $plan = Plan::find($validated['plan_id']);

        DB::beginTransaction();

        try {
            $oldData = $tenant->only(['name', 'domain', 'owner_name', 'owner_email', 'plan_id', 'status']);
            $planChanged = $tenant->plan_id != $plan->id;

            $updateData = [
                'name' => $validated['name'],
                'domain' => $validated['domain'],
                'owner_name' => $validated['owner_name'],
                'owner_email' => $validated['owner_email'],
                'plan_id' => $validated['plan_id'],
                'phone' => $validated['phone'] ?? null,
                'city' => $validated['city'] ?? null,
                'location' => $validated['location'] ?? null,
            ];

            if ($planChanged) {
                $updateData['will_expire_at'] = now()->addDays($plan->duration_in_days ?? 30);
                $updateData['status'] = 'active';
                $updateData['trial_ends_at'] = null;
            }

            $tenant->update($updateData);

            $tenant->domains()->update([
                'domain' => $validated['domain'] . '.yoursaas.com',
            ]);

            $owner = $tenant->users()->where('role', 'owner')->first();
            if ($owner) {
                $owner->update([
                    'name' => $validated['owner_name'],
                    'email' => $validated['owner_email'],
                ]);
            }

            AuditLog::create([
                'platform_admin_id' => auth('platform')->id(),
                'action' => 'tenant_updated',
                'description' => "Updated tenant: {$tenant->name}",
                'properties' => [
                    'tenant_id' => $tenant->id,
                    'old' => $oldData,
                    'new' => $tenant->only(['name', 'domain', 'owner_name', 'owner_email', 'plan_id', 'status']),
                    'plan_changed' => $planChanged,
                ],
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Tenant updated successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error updating tenant.'], 500);
        }
    }

    public function renew(Tenant $tenant)
    {
        $plan = $tenant->plan;
        
        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'No plan assigned to this tenant.'], 422);
        }
        
        $oldExpiry = $tenant->will_expire_at?->toDateTimeString();
        $baseDate = $tenant->will_expire_at && $tenant->will_expire_at->isFuture() ? $tenant->will_expire_at : Carbon::now();
        
        $tenant->update([
            'will_expire_at' => $baseDate->copy()->addDays($plan->duration_in_days ?? 30),
            'status' => 'active',
            'is_active' => 1,
        ]);

        AuditLog::create([
            'platform_admin_id' => auth('platform')->id(),
            'action' => 'tenant_renewed',
            'description' => "Renewed tenant: {$tenant->name} for " . ($plan->duration_in_days ?? 30) . " days",
            'properties' => [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'plan' => $plan->name,
                'days_added' => $plan->duration_in_days ?? 30,
                'old_expiry' => $oldExpiry,
                'new_expiry' => $tenant->will_expire_at->toDateTimeString(),
            ],
        ]);

        return response()->json(['success' => true, 'message' => 'Subscription renewed successfully!']);
    }

    public function toggleStatus(Tenant $tenant)
    {
        $oldStatus = $tenant->status;
        $newStatus = $tenant->is_active ? 0 : 1;
        $tenant->update([
            'is_active' => $newStatus,
            'status' => $newStatus ? 'active' : 'suspended'
        ]);

        $action = $newStatus ? 'activated' : 'suspended';

        AuditLog::create([
            'platform_admin_id' => auth('platform')->id(),
            'action' => 'tenant_status_toggled',
            'description' => "{$action} tenant: {$tenant->name}",
            'properties' => [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'old_status' => $oldStatus,
                'new_status' => $tenant->status,
            ],
        ]);

        return response()->json(['success' => true, 'message' => "Tenant {$action}!"]);
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('subscriptions.plan', 'plan');
        $modules = $tenant->enabled_modules ?? ($tenant->plan ? $tenant->plan->features : []);
        $plans = Plan::where('is_active', 1)->get();
        return view('platform.tenants.show', compact('tenant', 'modules', 'plans'));
    }

    public function toggleModule(Request $request, Tenant $tenant)
    {
        $request->validate(['module' => 'required|string']);
        
        $modules = $tenant->enabled_modules ?? [];
        $oldState = $modules[$request->module] ?? false;
        $modules[$request->module] = !$oldState;
        
        $tenant->update(['enabled_modules' => $modules]);

        AuditLog::create([
            'platform_admin_id' => auth('platform')->id(),
            'action' => 'module_toggled',
            'description' => ucfirst($request->module) . ' ' . ($oldState ? 'disabled' : 'enabled') . ' for: ' . $tenant->name,
            'properties' => [
                'tenant_id' => $tenant->id,
                'module' => $request->module,
                'old_state' => $oldState,
                'new_state' => !$oldState,
            ],
        ]);

        return response()->json(['success' => true, 'message' => 'Module updated!']);
    }

    public function addSubscriptionLog(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'type' => 'required|in:trial_extend,payment',
            'days' => 'nullable|integer|min:1|required_if:type,trial_extend',
            'amount' => 'nullable|numeric|min:0|required_if:type,payment',
            'notes' => 'nullable|string',
        ]);

        $endsAt = null;

        if ($validated['type'] === 'trial_extend') {
            $days = (int) $validated['days']; 
            $endsAt = $tenant->will_expire_at ? $tenant->will_expire_at->copy()->addDays($days) : now()->addDays($days);
            $tenant->update(['will_expire_at' => $endsAt, 'trial_ends_at' => $endsAt, 'status' => 'trial']);
        } elseif ($validated['type'] === 'payment') {
            $baseDate = $tenant->will_expire_at && $tenant->will_expire_at->isFuture() ? $tenant->will_expire_at : now();
            $endsAt = $baseDate->copy()->addDays($tenant->plan->duration_in_days ?? 30);
            $tenant->update(['will_expire_at' => $endsAt, 'status' => 'active']);
        }

        $sub = $tenant->subscriptions()->create([
            'plan_id' => $tenant->plan_id,
            'type' => $validated['type'],
            'amount' => $validated['amount'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'starts_at' => now(),
            'ends_at' => $endsAt,
        ]);

        $invoiceId = null;

        if ($validated['type'] === 'payment' && $sub->amount > 0) {
            $lastInvoice = \App\Models\PlatformInvoice::count();
            $invoiceNum = 'INV-' . str_pad($lastInvoice + 1, 5, '0', STR_PAD_LEFT);
            
            $invoice = \App\Models\PlatformInvoice::create([
                'tenant_id' => $tenant->id,
                'subscription_id' => $sub->id,
                'invoice_number' => $invoiceNum,
                'amount' => $sub->amount,
                'tax' => 0,
                'total' => $sub->amount,
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $invoiceId = $invoice->id;
        }

        AuditLog::create([
            'platform_admin_id' => auth('platform')->id(),
            'action' => 'subscription_log',
            'description' => "Added {$validated['type']} log for: {$tenant->name}",
            'properties' => [
                'tenant_id' => $tenant->id,
                'type' => $validated['type'],
                'amount' => $sub->amount,
                'new_expiry' => $endsAt?->toDateTimeString(),
            ],
        ]);

        return response()->json(['success' => true, 'message' => 'Subscription updated & Invoice generated!']);
    }
    
    public function destroy(Tenant $tenant)
    {
        DB::beginTransaction();
        try {
            $deletedData = [
                'tenant' => $tenant->toArray(),
                'users_count' => $tenant->users()->count(),
                'domains' => $tenant->domains()->pluck('domain')->toArray(),
            ];

            $tenant->users()->delete();
            $tenant->domains()->delete();
            $tenant->subscriptions()->delete();
            \App\Models\PlatformInvoice::where('tenant_id', $tenant->id)->delete();
            $tenant->delete();

            AuditLog::create([
                'platform_admin_id' => auth('platform')->id(),
                'action' => 'tenant_deleted',
                'description' => "Deleted tenant: {$tenant->name}",
                'properties' => $deletedData,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Tenant deleted.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error deleting tenant.'], 500);
        }
    }
}
```

### `app/Http/Controllers/Platform/VerificationController.php`

```php
<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    public function show(Request $request)
    {
        $admin = Auth::guard('platform')->user();

        if ($admin->isVerified()) {
            return redirect()->route('platform.dashboard');
        }

        return view('platform.auth.verify-email');
    }

    public function verify(Request $request, $id, $hash)
    {
        $admin = PlatformAdmin::findOrFail($id);

        if (!hash_equals(sha1($admin->getEmailForVerification()), $hash)) {
            return response()->json(['success' => false, 'message' => 'Invalid verification link.'], 422);
        }

        if ($admin->isVerified()) {
            return response()->json(['success' => true, 'message' => 'Email already verified.', 'redirect' => route('platform.dashboard')]);
        }

        $admin->markEmailVerified();

        return response()->json(['success' => true, 'message' => 'Email verified successfully.', 'redirect' => route('platform.dashboard')]);
    }

    public function resend(Request $request)
    {
        $admin = Auth::guard('platform')->user();

        if ($admin->isVerified()) {
            return response()->json(['success' => false, 'message' => 'Email already verified.'], 422);
        }

        $verificationUrl = URL::temporarySignedRoute(
            'platform.verification.verify',
            now()->addMinutes(60),
            ['id' => $admin->id, 'hash' => sha1($admin->getEmailForVerification())]
        );

        // Dev mode — return URL. Production mein email bhejo.
        return response()->json([
            'success' => true,
            'message' => 'Verification link generated.',
            'url' => $verificationUrl,
        ]);
    }
}
```

### `app/Http/Controllers/PosController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Medicine;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    // Display the POS Screen
    public function index()
    {
        // Get unique categories for the filter buttons (e.g., Painkiller, Antibiotic)
        $categories = Medicine::where('is_active', true)
                ->distinct()
                ->pluck('category')
                ->filter()
                ->values();

        return view('pos.index', compact('categories'));
    }

    public function searchItems(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        // Enterprise Search Logic: 
        $medicines = Medicine::where(function ($query) use ($q) {
                $query->where('name', 'like', "{$q}%")
                    ->orWhere('generic_name', 'like', "{$q}%")
                    ->orWhere('brand_name', 'like', "{$q}%")
                    ->orWhere('barcode', 'like', "{$q}%");
            })
            ->orderBy('name', 'asc') // Alphabetical order mein arrange karega
            ->limit(20)
            ->get();

        return response()->json($medicines);
    }


    // Process the Cart & Complete the Sale
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:medicines,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0',
            'patient_id' => 'nullable|exists:patients,id',
        ]);

        // START DATABASE TRANSACTION (If anything fails, everything rolls back safely)
        return DB::transaction(function () use ($validated, $request) {
            
            $subtotal = 0;
            $cartItemsData = [];

            // 1. Validate Stock & Prepare Cart Data
            foreach ($validated['cart'] as $item) {
                $medicine = Medicine::find($item['id']);

                if ($medicine->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$medicine->name}! Available: {$medicine->stock_quantity}");
                }

                $itemTotal = $medicine->sale_price * $item['quantity'];
                $subtotal += $itemTotal;

                // Prepare data for sale_items table using Polymorphic relationship
                $cartItemsData[] = [
                    'itemable_type' => Medicine::class, // Scalable: Tomorrow this could be Product::class
                    'itemable_id'   => $medicine->id,
                    'item_name'     => $medicine->name, // SNAPSHOT: Freeze the name
                    'unit_price'    => $medicine->sale_price, // SNAPSHOT: Freeze the price
                    'unit_name'     => $medicine->unit_name,
                    'quantity'      => $item['quantity'],
                    'total_price'   => $itemTotal,
                ];

                // DEDUCT STOCK: Subtract sold quantity from inventory
                $medicine->decrement('stock_quantity', $item['quantity']);
            }

            // 2. Calculate Tax & Final Total
            $taxPercentage = $validated['tax_percentage'] ?? 0;
            $discountValue = $validated['discount_amount'] ?? 0;
            
            $taxAmount = ($subtotal * $taxPercentage) / 100;
            
            // SMART DISCOUNT: Check if discount_type was sent (we will send it from JS)
            $discountType = $request->input('discount_type', 'amount');
            $discountAmount = ($discountType === 'percent') ? ($subtotal * $discountValue) / 100 : $discountValue;
            
            // Prevent negative total
            if ($discountAmount > $subtotal) {
                $discountAmount = $subtotal;
            }

            $totalAmount = ($subtotal + $taxAmount) - $discountAmount;

            // 3. Generate Unique Sale Number
            $lastSaleCount = Sale::where('tenant_id', app('currentTenant')->id)->count();
            $saleNumber = 'POS-' . str_pad($lastSaleCount + 1, 5, '0', STR_PAD_LEFT);

            // 4. Create the Main Sale Record
            $sale = Sale::create([
                'patient_id'     => $validated['patient_id'] ?? null,
                'user_id'        => auth()->id(),
                'sale_number'    => $saleNumber,
                'subtotal'       => $subtotal,
                'tax_percentage' => $taxPercentage,
                'tax_amount'     => $taxAmount,
                'discount_amount'=> $discountAmount,
                'total_amount'   => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'status'         => 'completed',
            ]);

            // 5. Attach Items to the Sale
            foreach ($cartItemsData as $itemData) {
                $sale->items()->create($itemData);
            }

            // Return success with Sale ID to redirect to receipt
            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'message' => 'Sale completed successfully!'
            ]);
        });
    }

        // Display the final receipt after successful payment
    public function showReceipt(Sale $sale)
    {
        // Load items to show on receipt
        $sale->load('items');
        $clinic = app('currentTenant');
        
        return view('pos.receipt', compact('sale', 'clinic'));
    }
}
```

### `app/Http/Controllers/PrescriptionController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Token;
use App\Models\Medicine;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    // Show the prescription form for the current patient
    public function create($token_id)
    {
        $token = Token::findOrFail($token_id);
        
        // Only allow prescription for patients who are currently with the doctor (in-progress)
        if ($token->status !== 'in-progress') {
            return redirect()->back()->with('error', 'Prescription can only be written for the current patient.');
        }

        // If prescription already exists, show it instead of creating a new one
        if ($token->prescription) {
            return redirect()->route('prescriptions.show', $token->prescription->id);
        }

        return view('prescriptions.create', compact('token'));
    }

       // SMART SEARCH: Called via AJAX when doctor types in the medicine box
    public function searchMedicine(Request $request)
    {
        $search = $request->get('q');

        // Kam az kam 2 characters type karne do
        if (empty($search) || strlen($search) < 2) {
            return response()->json([]);
        }

        // Search karo aur sirf 10 results lo (Dropdown ko clean rakhne ke liye)
        $medicines = Medicine::where('is_active', true)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "{$search}%") // Shuru se match
                     ->orWhere('generic_name', 'like', "{$search}%"); // Shuru se match
            })
            ->limit(10)
            ->get();

        // Data ko JS ke format mein map karo
        $data = $medicines->map(function ($med) {
            $alternatives = [];
            
            // Agar stock 0 hai toh alternatives dhundho
            if ($med->stock_quantity == 0 && !empty($med->generic_name)) {
                $alts = Medicine::where('generic_name', $med->generic_name)
                    ->where('id', '!=', $med->id)
                    ->where('stock_quantity', '>', 0)
                    ->limit(3)
                    ->get();
                    
                foreach ($alts as $alt) {
                    $alternatives[] = [
                        'id' => $alt->id,
                        'name' => $alt->name,
                        'stock' => $alt->stock_quantity
                    ];
                }
            }

            return [
                'id' => $med->id,
                'name' => $med->name,
                'generic_name' => $med->generic_name,
                'stock' => $med->stock_quantity, // JS ko 'stock' chahiye
                'alternatives' => $alternatives
            ];
        });

        return response()->json($data);
    }

    // Save the prescription and selected medicines
    public function store(Request $request, $token_id)
    {
        $token = Token::findOrFail($token_id);

        $validated = $request->validate([
            'diagnosis' => 'required|string',
            'notes' => 'nullable|string',
            'medicines' => 'required|array|min:1', // At least one medicine required
            'medicines.*.id' => 'required|exists:medicines,id',
            'medicines.*.dosage' => 'required|string',
            'medicines.*.days' => 'required|integer|min:1',
            'medicines.*.instructions' => 'nullable|string',
        ]);

        // Create the main prescription record
        $prescription = Prescription::create([
            'patient_id' => $token->patient_id,
            'doctor_id' => \App\Models\Doctor::find(auth()->user()->doctor_id)->id ?? null,
            'token_id'   => $token->id,
            'diagnosis'  => $validated['diagnosis'],
            'notes'      => $validated['notes'],
        ]);

        // Save each medicine as a prescription item
        foreach ($validated['medicines'] as $medData) {
            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'medicine_id'     => $medData['id'],
                'dosage'          => $medData['dosage'],
                'days'            => $medData['days'],
                'instructions'    => $medData['instructions'] ?? null,
            ]);
        }

        return redirect()->route('prescriptions.show', $prescription->id)->with('success', 'Prescription saved successfully!');
    }



        // Display the saved prescription slip
    public function show(Prescription $prescription)
    {
        // Load relationships so we can display medicine names
        $prescription->load(['items.medicine', 'patient', 'doctor']);
        
        // Get current clinic details to show on top of the slip
        $clinic = app('currentTenant');

        return view('prescriptions.show', compact('prescription', 'clinic'));
    }

    
}
```

### `app/Http/Controllers/ProfileController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
```

### `app/Http/Controllers/StaffController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Services\StaffService;
use App\Http\Requests\StoreStaffRequest;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    protected $service;

    // Service ka automatically object ban jayega (Dependency Injection)
    public function __construct(StaffService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('staff.index', [
            'staff' => $this->service->getStaffList()
        ]);
    }

    public function store(StoreStaffRequest $request)
    {
        $this->service->createStaff($request->validated());
        return redirect()->route('staff.index')->with('success', 'Staff member added successfully!');
    }

    public function update(StoreStaffRequest $request, $staff)
    {
        $this->service->updateStaff($staff, $request->validated());
        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully!');
    }

    public function destroy($staff)
    {
        $this->service->deleteStaff($staff);
        return redirect()->route('staff.index')->with('success', 'Staff member removed!');
    }

    public function toggleStatus($staff)
    {
        $this->service->toggleStaffStatus($staff);
        return back()->with('success', 'Staff status updated!');
    }
}
```

### `app/Http/Controllers/SuperAdminController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Sale;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    // Super Admin Dashboard (Global Stats)
  public function dashboard()
{
    $tenants = \App\Models\Tenant::withCount(['users', 'invoices'])->latest()->get();
    $stats = [
        'total_tenants' => $tenants->count(),
        'active_trials' => $tenants->where('trial_ends_at', '>', now())->count(),
        'expired_trials' => $tenants->where('trial_ends_at', '<=', now())->count(),
        'global_revenue' => \App\Models\Invoice::sum('total_amount'),
    ];
    return view('super-admin.dashboard', compact('tenants', 'stats'));
}
}
```

### `app/Http/Controllers/Tenant/ActivityLogController.php`

```php
<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\TenantActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = TenantActivityLog::with('user');

        // Filter by action
        if ($action = $request->get('action')) {
            $query->where('action', $action);
        }

        // Filter by user
        if ($userId = $request->get('user_id')) {
            $query->where('user_id', $userId);
        }

        // Filter by date
        if ($date = $request->get('date')) {
            $query->whereDate('created_at', $date);
        }

        $logs = $query->latest()->paginate(25);
        $users = \App\Models\User::where('tenant_id', auth()->user()->tenant_id)->get();

        // Action list for filter dropdown
        $actions = TenantActivityLog::select('action')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('tenantView.activity-logs.index', compact('logs', 'users', 'actions'));
    }
}
```

### `app/Http/Controllers/Tenant/AuthController.php`

```php
<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use App\Services\AccountLockService;
use App\Services\LoginLogService;
use App\Services\TenantActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenantView.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        // 1. User not found
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // 2. Tenant status check
        $tenant = Tenant::find($user->tenant_id);

        if (!$tenant) {
            throw ValidationException::withMessages([
                'email' => 'Account not linked to any business. Contact support.',
            ]);
        }

        if ($tenant->status === 'suspended') {
            LoginLogService::logFailed($request, 'tenant_suspended', $user);
            throw ValidationException::withMessages([
                'email' => 'Your business account has been suspended. Contact support.',
            ]);
        }

        // 3. Account locked check
        if (AccountLockService::isLocked($user)) {
            $minutes = AccountLockService::getRemainingLockMinutes($user);
            LoginLogService::logFailed($request, 'account_locked', $user);
            throw ValidationException::withMessages([
                'email' => "Account locked. Try again in {$minutes} minute(s).",
            ]);
        }

        // 4. Inactive user check
        if (!$user->is_active && !$user->locked_until) {
            LoginLogService::logFailed($request, 'account_inactive', $user);
            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated. Contact your administrator.',
            ]);
        }

        // 5. Auth attempt
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            $gotLocked = AccountLockService::recordFailedAttempt($user);

            if ($gotLocked) {
                $minutes = AccountLockService::LOCK_DURATION_MINUTES;
                LoginLogService::logFailed($request, 'account_locked', $user);
                throw ValidationException::withMessages([
                    'email' => "Too many failed attempts. Account locked for {$minutes} minutes.",
                ]);
            }

            $remaining = AccountLockService::getAttemptsRemaining($user);
            LoginLogService::logFailed($request, 'invalid_credentials', $user);
            throw ValidationException::withMessages([
                'email' => "Invalid credentials. {$remaining} attempt(s) remaining.",
            ]);
        }

        // 6. Success
        $user = Auth::user();
        AccountLockService::resetAttempts($user);
        LoginLogService::logSuccess($request, $user);
        $request->session()->regenerate();

        //  Activity Logs
        \App\Models\TenantActivityLog::create([
            'tenant_id' => $user->tenant_id,
            'user_id' => $user->id,
            'action' => 'login',
            'description' => 'User logged in',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // JSON response for AJAX/Fetch, Redirect for standard form
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('tenant.dashboard')
            ]);
        }

        return redirect()->route('tenant.dashboard');
    }

    public function logout(Request $request)
    {
        // ✅ Pehle user info capture karo
        $userId = auth()->id();
        $tenantId = auth()->user()?->tenant_id;

        // ✅ Phir manually log karo (trait bypass)
        if ($userId && $tenantId) {
            \App\Models\TenantActivityLog::create([
                'tenant_id' => $tenantId,
                'user_id' => $userId,
                'action' => 'logout',
                'description' => 'User logged out',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('tenantView.login');
    }
}
```

### `app/Http/Controllers/TokenController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    // Show the token generation form to the receptionist
    public function create()
    {
        // Fetch only the records belonging to the current logged-in clinic
        $patients = Patient::all();
        $doctors = Doctor::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();

        return view('tokens.create', compact('patients', 'doctors', 'services'));
    }


       // Show the live queue of tokens for the receptionist
    public function index()
    {
        // Get all tokens for the current clinic, ordered by newest first
        $tokens = Token::with(['patient', 'doctor', 'service'])
                        ->orderBy('id', 'desc')
                        ->get();

        return view('tokens.index', compact('tokens'));
    }

      // Doctor's personal dashboard to see their queue
    public function doctorDashboard()
    {
        // BYPASS: Directly find the doctor from the doctors table
        $doctorProfile = \App\Models\Doctor::find(auth()->user()->doctor_id);

        if (!$doctorProfile) {
            abort(403, 'You are not assigned as a doctor in this system.');
        }

        // Get tokens specifically for THIS doctor, ordered by oldest first (FIFO Queue)
        $tokens = Token::where('doctor_id', $doctorProfile->id)
                        ->with(['patient', 'service'])
                        ->whereIn('status', ['waiting', 'in-progress'])
                        ->orderBy('id', 'asc') // Oldest token gets priority
                        ->get();

        // FIX: Variable ka naam $currentPatient se $currentToken kar diya
        $currentToken = $tokens->firstWhere('status', 'in-progress');

               // Separate the waiting tokens
        $waitingTokens = $tokens->where('status', 'waiting');

        return view('tokens.doctor-dashboard', compact('currentToken', 'waitingTokens', 'doctorProfile'));
    }


       // Action: Doctor clicks "Call Next Patient"
    public function callNextToken()
    {
        // BYPASS: Directly find the doctor
        $doctorProfile = \App\Models\Doctor::find(auth()->user()->doctor_id);

        if (!$doctorProfile) {
            return redirect()->back()->with('error', 'Doctor profile not found.');
        }

        // Find the OLDEST waiting token for this doctor
        $nextToken = Token::where('doctor_id', $doctorProfile->id)
                            ->where('status', 'waiting')
                            ->orderBy('id', 'asc')
                            ->first();

        if ($nextToken) {
            // Update token status to in-progress and save the current time
            $nextToken->update([
                'status' => 'in-progress',
                'called_at' => now(),
            ]);

            return redirect()->back()->with('success', "Patient {$nextToken->patient->name} ({$nextToken->token_number}) called successfully!");
        }

        // If no one is waiting
        return redirect()->back()->with('info', 'No patients in the waiting queue right now.');
    }


        // Action: Doctor clicks "Complete Patient" after checkup
    public function completeToken($id)
    {
        // Find the token (Security scope will automatically ensure it belongs to this clinic)
        $token = Token::findOrFail($id);

        // Update status to completed and save the finish time
        $token->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->back()->with('success', "Patient {$token->patient->name} checkup completed!");
    }


      // Handle the form submission and generate the token
       // Handle the form submission and generate the token
    public function store(Request $request)
    {
        // Validate the new form data
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20',
            'patient_age' => 'required|numeric|min:0|max:150',
            'patient_gender' => 'required|in:male,female,other',
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'nullable|exists:services,id',
        ]);

        // 1. Check if patient with this phone number already exists
        $patient = \App\Models\Patient::firstWhere('phone', $validated['patient_phone']);

        // 2. If not, create a new patient
        if (!$patient) {
            $patient = \App\Models\Patient::create([
                'name' => $validated['patient_name'],
                'phone' => $validated['patient_phone'],
                'age' => $validated['patient_age'],
                'gender' => $validated['patient_gender'],
            ]);
        }

        // Get the selected doctor
        $doctor = Doctor::findOrFail($validated['doctor_id']);

        // CHECK DAILY LIMIT: Count how many tokens this doctor has TODAY
        $todayTokensCount = Token::where('doctor_id', $doctor->id)
            ->whereDate('created_at', today())
            ->where('status', '!=', 'cancelled')
            ->count();

        // If limit is reached, stop and go back with an error message
        if ($todayTokensCount >= $doctor->daily_patient_limit) {
            return redirect()->back()->with('error', "Sorry! Dr. {$doctor->name} has reached their daily limit of {$doctor->daily_patient_limit} patients.");
        }

        // Generate a unique Token Number
        $lastTokenCount = Token::where('tenant_id', app('currentTenant')->id)->count();
        $tokenNumber = 'T-' . str_pad($lastTokenCount + 1, 3, '0', STR_PAD_LEFT);

        // Create the token with the newly found/created patient ID
        Token::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $validated['service_id'] ?? null,
            'token_number' => $tokenNumber,
            'status' => 'waiting',
            'is_walk_in' => true,
        ]);

        // Redirect back with a success message
        return redirect()->route('tokens.create')->with('success', "Token {$tokenNumber} generated successfully for Dr. {$doctor->name}!");
    }



}
```

### `app/Http/Controllers/TrialController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Domain;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TrialController extends Controller
{
    public function showForm()
    {
        return view('trial.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'owner_name'    => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email',
            'phone'         => 'required|string|max:20',
            'city'          => 'required|string|max:255',
            'location'      => 'required|string|max:255',
            'password'      => 'required|string|min:8|confirmed',
            'business_type' => 'required|string|in:mart,restaurant,cafe,retail,clinic,general_store',
            'outlets'       => 'required|string|in:1,2-5,6-10,10+',
            'domain'        => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('tenants', 'domain')->whereNull('deleted_at'),
                Rule::unique('domains', 'domain'),
            ],
            'website'       => 'nullable|string|max:1',
        ]);

        // Free plan dhundo ya bana do
        $plan = Plan::where('price', 0)->first();
        if (!$plan) {
            $plan = Plan::create([
                'name'          => 'Free Trial',
                'slug'          => 'free-trial',
                'price'         => 0,
                'billing_cycle' => 'one-time',
                'trial_days'    => 14,
                'is_active'     => 1,
                'limits'        => ['terminals' => 1, 'products' => 200, 'users' => 3],
                'features'      => [
                    'clinic'     => true,
                    'pos'        => true,
                    'pharmacy'   => true,
                    'restaurant' => true,
                    'retail'     => true,
                ],
            ]);
        }

        $trialDays = $plan->trial_days > 0 ? $plan->trial_days : 14;
        $trialEndsAt = now()->addDays($trialDays);
        $fullDomain = $validated['domain'] . '.yoursaas.com';

        DB::beginTransaction();

        try {
            $tenant = Tenant::create([
                'name'            => $validated['business_name'],
                'domain'          => $validated['domain'],
                'owner_name'      => $validated['owner_name'],
                'owner_email'     => $validated['email'],
                'phone'           => $validated['phone'],
                'city'            => $validated['city'],
                'location'        => $validated['location'],
                'web_access_url'  => $fullDomain,
                'plan_id'         => $plan->id,
                'status'          => 'trial',
                'is_active'       => 1,
                'business_type'   => $validated['business_type'],
                'outlets'         => $validated['outlets'],
                'trial_ends_at'   => $trialEndsAt,
                'will_expire_at'  => $trialEndsAt,
                'enabled_modules' => $plan->features,
            ]);

            Domain::create([
                'domain'    => $fullDomain,
                'tenant_id' => $tenant->id,
            ]);

            $user = User::create([
                'name'              => $validated['owner_name'],
                'email'             => $validated['email'],
                'password'          => Hash::make($validated['password']),
                'tenant_id'         => $tenant->id,
                'role'              => 'owner',
                'is_active'         => 1,
                'email_verified_at' => now(),
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('owner');
            }

            $tenant->subscriptions()->create([
                'plan_id'    => $plan->id,
                'starts_at'  => now(),
                'ends_at'    => $trialEndsAt,
                'status'     => 'trial',
                'trial_days' => $trialDays,
                'type'       => 'trial',
                'amount'     => 0,
            ]);

            DB::commit();

            Auth::login($user);

            return redirect()
                ->route('tenant.dashboard')
                ->with('success', "Welcome! Your {$trialDays}-day free trial has started.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Something went wrong. Please try again.'])
                ->withInput();
        }
    }
}
```

## Middleware


### `app/Http/Middleware/CheckPlatformPermission.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlatformPermission
{
    /**
     * Handle an incoming request.
     * Usage in route: ->middleware('permission:tenants.create')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = auth('platform')->user();

        if (!$admin || !$admin->hasPermissionTo($permission, 'platform')) {
            // Agar AJAX request hai toh JSON do, warna 403 page dikhao
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You do not have permission to perform this action.'
                ], 403);
            }

            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
```

### `app/Http/Middleware/CheckTrialExpiry.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTrialExpiry
{
    public function handle(Request $request, Closure $next): Response
    {
        // Agar user login hai
        if (auth()->check()) {
            $user = auth()->user();

            // Agar user kisi Tenant (Clinic) se belong karta hai (Super Admin nahi hai)
            if ($user->tenant_id) {
                $tenant = $user->tenant;

                // Agar tenant ka trial khatam ho gaya hai
                if ($tenant->trial_ends_at && $tenant->trial_ends_at->isPast()) {
                    
                    // Agar user 'billing' (payment) page par ja raha hai, toh usko jane do
                    // Warna usko billing page par redirect kar do
                    if (!$request->is('billing')) {
                        return redirect()->route('billing')->with('error', 'Your 14-day trial has expired. Please upgrade to continue.');
                    }
                }
            }
        }

        // Agar trial nahi khatam, toh user ko aage jaane do (Dashboard par)
        return $next($request);
    }
}
```

### `app/Http/Middleware/IdentifyTenant.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Domain;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Pehle check karo domain se aaya hai?
        $hostname = $request->getHost();
        $domain = Domain::where('domain', $hostname)->first();

        if ($domain) {
            app()->instance('currentTenant', $domain->tenant);
        } 
        // 2. Agar localhost hai aur user logged in hai
        elseif (Auth::check() && Auth::user()->tenant_id) {
            app()->instance('currentTenant', Auth::user()->tenant);
        }

        return $next($request);
    }
}
```

### `app/Http/Middleware/PlatformAuth.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlatformAuth
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!Auth::guard('platform')->check()) {
            return redirect()->route('platform.login');
        }

        return $next($request);
    }
}
```

### `app/Http/Middleware/SecurityHeaders.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Add security headers to every response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent clickjacking — allow same origin only
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // XSS protection (legacy browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy — send origin only on cross-origin requests
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy — disable unnecessary browser features
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        return $response;
    }
}
```

### `app/Http/Middleware/TrustProxies.php`

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Trusted proxies — production mein actual load balancer IP daalo
     */
    protected $proxies = '*';

    /**
     * Headers to trust from proxy
     */
    protected $headers = [
        Request::HEADER_FORWARDED,
        Request::HEADER_X_FORWARDED_FOR,
        Request::HEADER_X_FORWARDED_HOST,
        Request::HEADER_X_FORWARDED_PORT,
        Request::HEADER_X_FORWARDED_AWS,
        Request::HEADER_X_FORWARDED_PROTO,
    ];
}
```

## Form Requests


### `app/Http/Requests/Auth/LoginRequest.php`

```php
<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
```

### `app/Http/Requests/Doctor/StoreDoctorRequest.php`

```php
<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permissions middleware route pe lagega
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'consultation_fee' => 'required|numeric|min:0',
            'daily_patient_limit' => 'nullable|integer|min:1',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
```

### `app/Http/Requests/Doctor/UpdateDoctorRequest.php`

```php
<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'consultation_fee' => 'required|numeric|min:0',
            'daily_patient_limit' => 'nullable|integer|min:1',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ];
    }
}
```

### `app/Http/Requests/ProfileUpdateRequest.php`

```php
<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
```

### `app/Http/Requests/StoreStaffRequest.php`

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Route parameter se ID le lo (yeh object ya string dono ho sakta hai)
        $staffId = $this->route('staff');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staffId,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string|in:receptionist,doctor,cashier,pharmacist,manager',
        ];

        // POST (Create) hone par password required, PUT (Edit) hone par optional
        if ($this->isMethod('PUT')) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        } else {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }
}
```

## Services


### `app/Services/AccountLockService.php`

```php
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class AccountLockService
{
    /**
     * Max failed attempts before lock
     */
    const MAX_ATTEMPTS = 5;

    /**
     * Lock duration in minutes
     */
    const LOCK_DURATION_MINUTES = 15;

    /**
     * Check if user is currently locked
     */
    public static function isLocked(User $user): bool
    {
        if (!$user->locked_until) {
            return false;
        }

        // Agar lock time guzar gaya toh auto-unlock
        if ($user->locked_until->isPast()) {
            self::unlock($user);
            return false;
        }

        return true;
    }

    /**
     * Get remaining lock time in minutes
     */
    public static function getRemainingLockMinutes(User $user): ?int
    {
        if (!$user->locked_until || $user->locked_until->isPast()) {
            return null;
        }

        return now()->diffInMinutes($user->locked_until);
    }

    /**
     * Record a failed attempt — return true if account gets locked
     */
    public static function recordFailedAttempt(User $user): bool
    {
        $user->increment('login_attempts');

        // Refresh to get updated value
        $user->refresh();

        if ($user->login_attempts >= self::MAX_ATTEMPTS) {
            self::lock($user);
            return true; // locked
        }

        return false; // not locked yet
    }

    /**
     * Lock the account
     */
    public static function lock(User $user): void
    {
        $user->update([
            'login_attempts' => self::MAX_ATTEMPTS,
            'locked_until'   => now()->addMinutes(self::LOCK_DURATION_MINUTES),
            'is_active'     => false,
        ]);
    }

    /**
     * Unlock the account
     */
    public static function unlock(User $user): void
    {
        $user->update([
            'login_attempts' => 0,
            'locked_until'   => null,
            'is_active'     => true,
        ]);
    }

    /**
     * Reset attempts on successful login
     */
    public static function resetAttempts(User $user): void
    {
        $user->update([
            'login_attempts' => 0,
            'locked_until'   => null,
        ]);
    }

    /**
     * Get attempts remaining before lock
     */
    public static function getAttemptsRemaining(User $user): int
    {
        return max(0, self::MAX_ATTEMPTS - $user->login_attempts);
    }
}
```

### `app/Services/DoctorService.php`

```php
<?php

namespace App\Services;

use App\Repositories\DoctorRepository;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Doctor;

class DoctorService
{
    protected $repository;

    // Dependency Injection 
    public function __construct(DoctorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDoctors()
    {
        return $this->repository->getAll();
    }

    public function createDoctor(array $data): Doctor
    {
        // Agar yahan koi complex business logic hota (jaise check karna ke kya account balance hai etc)
        // toh yahan likhte. Abhi simple hai isliye direct repository ko bhej rahe.
        return $this->repository->create($data);
    }

    public function getActiveDoctorsForDropdown()
    {
        return $this->repository->getActiveDoctors();
    }

        public function updateDoctor($id, array $data): Doctor
    {
        return $this->repository->update($id, $data);
    }

        public function deleteDoctor($id): bool
    {
        return $this->repository->delete($id);
    }

        public function toggleDoctorStatus($id)
    {
        $doctor = $this->repository->findOrFail($id);
        $this->repository->update($id, ['is_active' => !$doctor->is_active]);
    }
}
```

### `app/Services/LoginLogService.php`

```php
<?php

namespace App\Services;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class LoginLogService
{
    /**
     * Successful login log
     */
    public static function logSuccess(Request $request, User $user): LoginLog
    {
        return self::createLog($request, 'success', null, $user);
    }

    /**
     * Failed login log
     */
    public static function logFailed(Request $request, string $reason, ?User $user = null): LoginLog
    {
        return self::createLog($request, 'failed', $reason, $user);
    }

    /**
     * Core log creator
     */
    private static function createLog(
        Request $request,
        string $status,
        ?string $reason,
        ?User $user
    ): LoginLog {
        $device = self::parseDevice($request);

        // Agar user nahi mila toh email se dhundhne ki koshish (tenant_id ke liye)
        if (!$user && $request->filled('email')) {
            $user = User::where('email', $request->email)->first();
        }

        return LoginLog::create([
            'user_id'        => $user?->id,
            'tenant_id'      => $user?->tenant_id,
            'email'          => $request->input('email'),
            'ip_address'     => $request->ip() ?? '127.0.0.1',
            'user_agent'     => $request->userAgent() ?? 'CLI/Tinker',
            'device_type'    => $device['type'],
            'browser'        => $device['browser'],
            'browser_version'=> $device['browser_version'],
            'os'             => $device['os'],
            'os_version'     => $device['os_version'],
            'status'         => $status,
            'reason'         => $reason,
            'created_at'     => now(),
        ]);
    }

    /**
     * User agent parse karo — bina package ke (no jenssegers/agent needed)
     */
    private static function parseDevice(Request $request): array
    {
        $ua = $request->userAgent() ?? '';
        $result = [
            'type'            => 'desktop',
            'browser'         => 'Unknown',
            'browser_version' => '',
            'os'              => 'Unknown',
            'os_version'      => '',
        ];

        // ── Device Type ──
        if (preg_match('/Mobile|Android.*Mobile|iPhone|iPod/i', $ua)) {
            $result['type'] = 'mobile';
        } elseif (preg_match('/iPad|Android(?!.*Mobile)|Tablet/i', $ua)) {
            $result['type'] = 'tablet';
        }

        // ── Browser ──
        $browsers = [
            'Edge'            => '/Edg(?:e|A|iOS)?\/(\d+[\.\d]*)/',
            'Opera'           => '/OPR\/(\d+[\.\d]*)/',
            'Firefox'         => '/Firefox\/(\d+[\.\d]*)/',
            'Chrome'          => '/Chrome\/(\d+[\.\d]*)/',
            'Safari'          => '/Version\/(\d+[\.\d]*).*Safari/',
            'Brave'           => '/Brave\/(\d+[\.\d]*)/',
        ];

        foreach ($browsers as $name => $pattern) {
            if (preg_match($pattern, $ua, $matches)) {
                $result['browser'] = $name;
                $result['browser_version'] = $matches[1] ?? '';
                break;
            }
        }

        // ── OS ──
        $oss = [
            'Windows'  => '/Windows NT (\d+[\.\d]*)/',
            'macOS'    => '/Mac OS X (\d+[._\d]*)/',
            'Linux'    => '/Linux/i',
            'Android'  => '/Android (\d+[\.\d]*)/',
            'iOS'      => '/iPhone OS (\d+[_\d]*)/',
        ];

        foreach ($oss as $name => $pattern) {
            if (preg_match($pattern, $ua, $matches)) {
                $result['os'] = $name;
                $result['os_version'] = str_replace('_', '.', $matches[1] ?? '');
                break;
            }
        }

        return $result;
    }
}
```

### `app/Services/PlatformPasswordService.php`

```php
<?php

namespace App\Services;

use App\Models\PlatformAdmin;
use App\Models\PlatformPasswordHistory;
use Illuminate\Support\Facades\Hash;

class PlatformPasswordService
{
    const HISTORY_LIMIT = 5;

    /**
     * Password strength check — returns score 0-4
     */
    public static function strength(string $password): array
    {
        $score = 0;
        $feedback = [];

        if (strlen($password) >= 8) {
            $score++;
        } else {
            $feedback[] = 'Minimum 8 characters required';
        }

        if (preg_match('/[a-z]/', $password)) $score++;
        else $feedback[] = 'Add lowercase letters';

        if (preg_match('/[A-Z]/', $password)) $score++;
        else $feedback[] = 'Add uppercase letters';

        if (preg_match('/[0-9]/', $password)) $score++;
        else $feedback[] = 'Add numbers';

        if (preg_match('/[^a-zA-Z0-9]/', $password)) $score++;
        else $feedback[] = 'Add special characters (!@#$%)';

        $labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        $colors = ['red', 'orange', 'yellow', 'emerald', 'green'];

        return [
            'score' => $score,
            'label' => $labels[$score],
            'color' => $colors[$score],
            'feedback' => $feedback,
        ];
    }

    /**
     * Check if password was used recently
     */
    public static function isOldPassword(PlatformAdmin $admin, string $password): bool
    {
        return $admin->passwordHistory()
            ->take(self::HISTORY_LIMIT)
            ->get()
            ->contains(function ($history) use ($password) {
                return Hash::check($password, $history->password);
            });
    }

    /**
     * Record password change in history
     */
    public static function recordHistory(PlatformAdmin $admin, string $hashedPassword): void
    {
        PlatformPasswordHistory::create([
            'platform_admin_id' => $admin->id,
            'password' => $hashedPassword,
        ]);

        // Keep only last N records
        $count = $admin->passwordHistory()->count();
        if ($count > self::HISTORY_LIMIT) {
            $ids = $admin->passwordHistory()
                ->orderBy('created_at')
                ->take($count - self::HISTORY_LIMIT)
                ->pluck('id');
            PlatformPasswordHistory::destroy($ids);
        }
    }
}
```

### `app/Services/PlatformSessionService.php`

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PlatformSessionService
{
    /**
     * Get all active sessions for current admin
     */
    public static function getActiveSessions(int $adminId): array
    {
        $sessions = DB::table('sessions')
            ->where('last_activity', '>=', now()->subDays(7)->timestamp)
            ->get();

        $result = [];
        $currentSessionId = Session::getId();

        foreach ($sessions as $session) {
            $payload = @unserialize(@base64_decode($session->payload));

            if (!$payload || !isset($payload['platform_admin_id']) || $payload['platform_admin_id'] != $adminId) {
                continue;
            }

            $ua = $payload['user_agent'] ?? '';
            $device = self::parseDevice($ua);

            $result[] = [
                'id' => $session->id,
                'ip' => $session->ip_address,
                'device_type' => $device['type'],
                'browser' => $device['browser'],
                'os' => $device['os'],
                'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                'is_current' => $session->id === $currentSessionId,
            ];
        }

        return $result;
    }

    /**
     * Kill a specific session (not current)
     */
    public static function killSession(string $sessionId): bool
    {
        if ($sessionId === Session::getId()) {
            return false;
        }
        return DB::table('sessions')->where('id', $sessionId)->delete() > 0;
    }

    /**
     * Kill all other sessions (keep current)
     */
    public static function killAllOtherSessions(int $adminId): int
    {
        $sessions = DB::table('sessions')->get();
        $currentId = Session::getId();
        $killed = 0;

        foreach ($sessions as $session) {
            if ($session->id === $currentId) continue;

            $payload = @unserialize(@base64_decode($session->payload));
            if ($payload && isset($payload['platform_admin_id']) && $payload['platform_admin_id'] == $adminId) {
                DB::table('sessions')->where('id', $session->id)->delete();
                $killed++;
            }
        }

        return $killed;
    }

    private static function parseDevice(string $ua): array
    {
        $result = ['type' => 'desktop', 'browser' => 'Unknown', 'os' => 'Unknown'];

        if (preg_match('/Mobile|Android.*Mobile|iPhone|iPod/i', $ua)) $result['type'] = 'mobile';
        elseif (preg_match('/iPad|Android(?!.*Mobile)|Tablet/i', $ua)) $result['type'] = 'tablet';

        foreach (['Edg(e|A|iOS)?\/' => 'Edge', 'OPR\/' => 'Opera', 'Firefox\/' => 'Firefox', 'Chrome\/' => 'Chrome', 'Version\/.*Safari' => 'Safari'] as $pattern => $name) {
            if (preg_match('/' . $pattern . '(\d+[\.\d]*)/i', $ua, $m)) {
                $result['browser'] = $name;
                break;
            }
        }

        foreach (['Windows NT' => 'Windows', 'Mac OS X' => 'macOS', 'Linux' => 'Linux', 'Android' => 'Android', 'iPhone OS' => 'iOS'] as $pattern => $name) {
            if (stripos($ua, $pattern) !== false) {
                $result['os'] = $name;
                break;
            }
        }

        return $result;
    }
}
```

### `app/Services/StaffService.php`

```php
<?php

namespace App\Services;

use App\Repositories\StaffRepository;
use Illuminate\Support\Facades\Hash;

class StaffService
{
    protected $repository;

    public function __construct(StaffRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getStaffList()
    {
        return $this->repository->getStaffList();
    }

    public function createStaff(array $data)
    {
        // Password ko hash karna zaroori hai
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        // Agar role nahi bheja toh default receptionist
        $data['role'] = $data['role'] ?? 'receptionist';
        
        return $this->repository->createStaff($data);
    }

    public function updateStaff($id, array $data)
    {
        // Agar password field blank hai toh update mat karo (warna rahega nahi)
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $this->repository->updateStaff($id, $data);
    }

    public function deleteStaff($id)
    {
        return $this->repository->deleteStaff($id);
    }

public function toggleStaffStatus($id)
{
    // Pehle current status nikalo
    $staff = \App\Models\User::findOrFail($id);
    
    // Status ko opposite kar do (1 ko 0, 0 ko 1)
    $newStatus = !$staff->is_active;
    
    // Ab update karo
    $this->repository->updateStaff($id, ['is_active' => $newStatus]);
}
}
```

### `app/Services/TenantActivityService.php`

```php
<?php

namespace App\Services;

use App\Models\TenantActivityLog;
use Illuminate\Http\Request;

class TenantActivityService
{
    // Simple log
    public static function log(string $action, string $description = null, $subject = null): TenantActivityLog
    {
        return TenantActivityLog::log($action, $description, $subject);
    }

    // Login log
    public static function logLogin(): TenantActivityLog
    {
        return self::log('login', 'User logged in');
    }

    // Logout log
    public static function logLogout(): TenantActivityLog
    {
        return self::log('logout', 'User logged out');
    }

    // Patient created
    public static function logPatientCreated($patient): TenantActivityLog
    {
        return self::log('patient.create', "Created patient: {$patient->name}", $patient);
    }

    // Token created
    public static function logTokenCreated($token): TenantActivityLog
    {
        return self::log('token.create', "Created token #{$token->token_number}", $token);
    }

    // Token completed
    public static function logTokenCompleted($token): TenantActivityLog
    {
        return self::log('token.complete', "Completed token #{$token->token_number}", $token);
    }

    // Prescription created
    public static function logPrescriptionCreated($prescription): TenantActivityLog
    {
        return self::log('prescription.create', "Created prescription #{$prescription->id}", $prescription);
    }

    // Invoice generated
    public static function logInvoiceGenerated($invoice): TenantActivityLog
    {
        return self::log('invoice.generate', "Generated invoice of Rs. {$invoice->total_amount}", $invoice);
    }

    // Invoice paid
    public static function logInvoicePaid($invoice): TenantActivityLog
    {
        return self::log('invoice.pay', "Received payment Rs. {$invoice->total_amount}", $invoice);
    }

    // Sale completed
    public static function logSaleCompleted($sale): TenantActivityLog
    {
        return self::log('sale.complete', "Completed sale #{$sale->sale_number} (Rs. {$sale->total_amount})", $sale);
    }

    // Doctor created
    public static function logDoctorCreated($doctor): TenantActivityLog
    {
        return self::log('doctor.create', "Added doctor: {$doctor->name}", $doctor);
    }

    // Doctor updated
    public static function logDoctorUpdated($doctor): TenantActivityLog
    {
        return self::log('doctor.update', "Updated doctor: {$doctor->name}", $doctor);
    }

    // Doctor deleted
    public static function logDoctorDeleted($doctor): TenantActivityLog
    {
        return self::log('doctor.delete', "Deleted doctor: {$doctor->name}", $doctor);
    }
}
```

## Repositories


### `app/Repositories/DoctorRepository.php`

```php
<?php

namespace App\Repositories;

use App\Models\Doctor;

class DoctorRepository
{
    protected $model;

    public function __construct(Doctor $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->latest()->paginate(10);
    }

    public function create(array $data): Doctor
    {
        return $this->model->create($data);
    }

    public function findOrFail($id): Doctor
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, array $data): Doctor
    {
        $doctor = $this->findOrFail($id);
        $doctor->update($data);
        return $doctor;
    }

    public function delete($id): bool
    {
        return $this->model->destroy($id);
    }
    
    // Active doctors drop down ke liye
    public function getActiveDoctors()
    {
        return $this->model->where('is_active', true)->get();
    }
}
```

### `app/Repositories/StaffRepository.php`

```php
<?php

namespace App\Repositories;

use App\Models\User;

class StaffRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getStaffList()
    {
        // Super Admin ko list nahi karna (Admin panel handle karega)
        return $this->model->where('role', '!=', 'super_admin')->latest()->paginate(10);
    }

    public function createStaff(array $data)
    {
        return $this->model->create($data);
    }

    public function updateStaff($id, array $data)
    {
        $staff = $this->model->findOrFail($id);
        $staff->update($data);
        return $staff;
    }

    public function deleteStaff($id)
    {
        return $this->model->destroy($id);
    }
}
```

## Scopes


### `app/Scopes/TenantScope.php`

```php
<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Sirf Container check karega. Auth nahi chhedega!
        if (app()->bound('currentTenant')) {
            $tenant = app('currentTenant');
            $builder->where($model->getTable() . '.tenant_id', $tenant->id);
        }
    }
}
```

## Traits


### `app/Traits/BelongsToTenant.php`

```php
<?php

namespace App\Traits;

use App\Scopes\TenantScope;

trait BelongsToTenant
{
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            // Sirf Container check karega. Auth nahi chhedega!
            if (app()->bound('currentTenant') && is_null($model->tenant_id)) {
                $model->tenant_id = app('currentTenant')->id;
            }
        });
    }
}
```

## Notifications


### `app/Notifications/TenantPasswordResetNotification.php`

```php
<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class TenantPasswordResetNotification extends ResetPasswordNotification implements ShouldQueue
{
    use Queueable;

    public function toMail($notifiable)
    {
        // Generate the full reset URL
        $resetUrl = url(config('app.url') . route('tenant.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Reset Your saasPOS Password')
            ->view('emails.tenant-password-reset', [
                'name' => $notifiable->name,
                'resetUrl' => $resetUrl,
            ]);
    }
}
```

## Providers


### `app/Providers/AppServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
```

## View Components


### `app/View/Components/AppLayout.php`

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('tenantView.layouts.app');
    }
}
```

### `app/View/Components/GuestLayout.php`

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
```
