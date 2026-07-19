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