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

    // Email verification check
    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('tenant.verification.notice')
            ->with('status', 'Please verify your email first.');
    }

    // 2FA check
    if ($user->two_factor_enabled && !session('two_factor_verified')) {
        return redirect()->route('two-factor.challenge');
    }

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

// ── Main Protected Routes ──
Route::middleware(['auth', 'active.user', 'email.verified', 'two-factor.verified', 'check.branch', 'tenant.identifier', 'trial'])->group(function () {
    
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

    // POS Routes
    Route::get('/pos', [App\Http\Controllers\PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/search', [App\Http\Controllers\PosController::class, 'searchItems'])->name('pos.search');
    Route::post('/pos/checkout', [App\Http\Controllers\PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/receipt/{sale}', [App\Http\Controllers\PosController::class, 'showReceipt'])->name('pos.receipt');

    // Pharmacy Inventory Alerts
    Route::get('/pharmacy/dashboard', [App\Http\Controllers\PharmacyController::class, 'index'])->name('pharmacy.dashboard');


    // ── Branch Switching ──
    Route::post('/branch/switch', [App\Http\Controllers\Tenant\BranchController::class, 'switchBranch'])->name('branch.switch');
    Route::get('/branch/list', [App\Http\Controllers\Tenant\BranchController::class, 'getBranches'])->name('branch.list');

    // Activity Logs
    Route::get('/activity-logs', [App\Http\Controllers\Tenant\ActivityLogController::class, 'index'])->name('tenant.activity-logs');

    // Tenant Change Password
    Route::get('/change-password', [ProfileController::class, 'showChangePassword'])->name('password.change');
    Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('password.update.tenant');

    // ── Tenant Session Management ──
    Route::get('/sessions', [TenantAuthController::class, 'sessions'])->name('tenant.sessions.index');
    Route::delete('/sessions/{id}', [TenantAuthController::class, 'destroySession'])->name('tenant.sessions.destroy');
    Route::post('/sessions/kill-all', [TenantAuthController::class, 'killAllSessions'])->name('tenant.sessions.kill-all');

    // ── Two-Factor Authentication Settings ──
    Route::get('/two-factor', [App\Http\Controllers\Tenant\TwoFactorController::class, 'index'])->name('two-factor.index');
    Route::post('/two-factor/enable-email', [App\Http\Controllers\Tenant\TwoFactorController::class, 'enableEmail'])->name('two-factor.enable-email');
    Route::get('/two-factor/setup-totp', [App\Http\Controllers\Tenant\TwoFactorController::class, 'setupTOTP'])->name('two-factor.setup-totp');
    Route::post('/two-factor/enable-totp', [App\Http\Controllers\Tenant\TwoFactorController::class, 'enableTOTP'])->name('two-factor.enable-totp');
    Route::post('/two-factor/disable', [App\Http\Controllers\Tenant\TwoFactorController::class, 'disable'])->name('two-factor.disable');
    Route::post('/two-factor/regenerate-codes', [App\Http\Controllers\Tenant\TwoFactorController::class, 'regenerateCodes'])->name('two-factor.regenerate-codes');
});


// ── 2FA Challenge (Auth only — NO two-factor.verified middleware) ──
Route::middleware(['auth'])->group(function () {
    Route::get('/two-factor/challenge', [App\Http\Controllers\Tenant\TwoFactorController::class, 'showChallenge'])->name('two-factor.challenge');
    Route::post('/two-factor/verify', [App\Http\Controllers\Tenant\TwoFactorController::class, 'verifyChallenge'])->name('two-factor.verify');
    Route::post('/two-factor/resend', [App\Http\Controllers\Tenant\TwoFactorController::class, 'resendOTP'])->name('two-factor.resend');
});


// ── Tenant Email Verification ──
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('tenant.dashboard');
        }
        return view('tenantView.auth.verify-email');
    })->name('tenant.verification.notice');

    Route::post('/email/verification-notification', function () {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('tenant.dashboard');
        }
        auth()->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('tenant.verification.send');

    Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
        $user = \App\Models\User::findOrFail($id);

        if (!hash_equals(sha1($user->getEmailForVerification()), (string) $hash)) {
            throw new \Illuminate\Auth\Access\AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('tenant.dashboard');
        }

        if ($user->markEmailAsVerified()) {
            \Illuminate\Support\Facades\Cache::forget('spatie.permission.cache');
            return redirect()->route('tenant.dashboard')->with('verified', true);
        }

        return redirect()->route('tenant.verification.notice')->with('error', 'Verification failed.');
    })->middleware(['signed', 'throttle:6,1'])->name('tenant.verification.verify');
});


// Doctor Management
Route::middleware(['auth', 'active.user', 'email.verified', 'two-factor.verified', 'check.branch', 'tenant.identifier', 'trial'])->group(function () {
    Route::get('/doctors', [App\Http\Controllers\DoctorController::class, 'index'])->name('doctors.index');
    Route::post('/doctors', [App\Http\Controllers\DoctorController::class, 'store'])->name('doctors.store');
    Route::put('/doctors/{doctor}', [App\Http\Controllers\DoctorController::class, 'update'])->name('doctors.update');
    Route::delete('/doctors/{doctor}', [App\Http\Controllers\DoctorController::class, 'destroy'])->name('doctors.destroy');
    Route::post('/doctors/{doctor}/toggle-status', [App\Http\Controllers\DoctorController::class, 'toggleStatus'])->name('doctors.toggle-status');
});

// Staff Management 
Route::middleware(['auth', 'active.user', 'email.verified', 'two-factor.verified', 'check.branch', 'tenant.identifier', 'trial'])->group(function () {
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

// Billing Route trail end
Route::view('/billing', 'billing')->name('billing');

// require __DIR__.'/auth.php';