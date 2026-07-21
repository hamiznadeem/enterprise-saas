c:\xampp\htdocs\enterprise-saas\
├───.editorconfig
├───.env
├───.env.example
├───.gitattributes
├───.gitignore
├───artisan
├───composer.json
├───composer.lock
├───database_context.md
├───package-lock.json
├───package.json
├───phpunit.xml
├───postcss.config.js
├───project-map.txt
├───README.md
├───tailwind.config.js
├───vite.config.js
├───app\
│   ├───DTOs\
│   ├───Http\
│   │   ├───Controllers\
│   │   │   ├───Auth\
│   │   │   │   └───RegisteredUserController.php
│   │   │   ├───Platform\
│   │   │   │   ├───AuditLogController.php
│   │   │   │   ├───AuthController.php
│   │   │   │   ├───DashboardController.php
│   │   │   │   ├───InvoiceController.php
│   │   │   │   ├───PasswordController.php
│   │   │   │   ├───PlanController.php
│   │   │   │   ├───SessionController.php
│   │   │   │   ├───SettingController.php
│   │   │   │   ├───TenantController.php
│   │   │   │   └───VerificationController.php
│   │   │   ├───Tenant\
│   │   │   │   └───AuthController.php
│   │   │   ├───DashboardController.php
│   │   │   ├───DoctorController.php
│   │   │   ├───InvoiceController.php
│   │   │   ├───LandingController.php
│   │   │   ├───PatientController.php
│   │   │   ├───PharmacyController.php
│   │   │   ├───PosController.php
│   │   │   ├───PrescriptionController.php
│   │   │   ├───ProfileController.php
│   │   │   ├───StaffController.php
│   │   │   ├───TokenController.php
│   │   │   └───TrialController.php
│   │   ├───Middleware\
│   │   └───Requests\
│   ├───Models\
│   │   ├───AuditLog.php
│   │   ├───Doctor.php
│   │   ├───Domain.php
│   │   ├───Invoice.php
│   │   ├───LoginLog.php
│   │   ├───Medicine.php
│   │   ├───Patient.php
│   │   ├───Plan.php
│   │   ├───PlatformAdmin.php
│   │   ├───PlatformInvoice.php
│   │   ├───PlatformPasswordHistory.php
│   │   ├───PlatformSale.php
│   │   ├───PlatformSetting.php
│   │   ├───Prescription.php
│   │   ├───PrescriptionItem.php
│   │   ├───Sale.php
│   │   ├───SaleItem.php
│   │   ├───Service.php
│   │   ├───Tenant.php
│   │   ├───TenantSubscription.php
│   │   ├───Token.php
│   │   ├───User.php
│   │   └───UserBranch.php
│   ├───Providers\
│   │   └───AppServiceProvider.php
│   ├───Repositories\
│   │   ├───DoctorRepository.php
│   │   └───StaffRepository.php
│   ├───Scopes\
│   │   └───TenantScope.php
│   ├───Services\
│   │   ├───AccountLockService.php
│   │   ├───DoctorService.php
│   │   ├───LoginLogService.php
│   │   ├───PlatformPasswordService.php
│   │   ├───PlatformSessionService.php
│   │   └───StaffService.php
│   │   └───TenantActivityService.php
│   ├───Traits\
│   │   └───BelongsToTenant.php
│   └───View\
│       └───Components\
├───bootstrap\
│   ├───app.php
│   ├───providers.php
│   └───cache\
│       ├───packages.php
│       └───services.php
├───config\
│   ├───app.php
│   ├───auth.php
│   ├───cache.php
│   ├───database.php
│   ├───filesystems.php
│   ├───logging.php
│   ├───mail.php
│   ├───permission.php
│   ├───queue.php
│   ├───services.php
│   └───session.php
├───database\
│   ├───.gitignore
│   ├───factories\
│   │   └───UserFactory.php
│   ├───migrations\
│   │   ├───0001_01_01_000000_create_users_table.php
│   │   ├───0001_01_01_000001_create_cache_table.php
│   │   ├───0001_01_01_000002_create_jobs_table.php
│   │   ├───2026_07_07_213156_create_tenants_table.php
│   │   ├───2026_07_07_214011_create_domains_table.php
│   │   ├───2026_07_07_214754_add_tenant_id_to_users_table.php
│   │   ├───2026_07_08_225232_add_trial_ends_at_to_tenants_table.php
│   │   ├───2026_07_08_233248_create_patients_table.php
│   │   ├───2026_07_09_220827_create_doctors_table.php
│   │   ├───2026_07_09_221554_create_services_table.php
│   │   ├───2026_07_09_222012_create_tokens_table.php
│   │   ├───2026_07_09_224325_add_daily_patient_limit_to_doctors_table.php
│   │   ├───2026_07_10_004014_add_doctor_id_to_users_table.php
│   │   ├───2026_07_10_015519_create_invoices_table.php
│   │   ├───2026_07_10_023237_create_medicines_table.php
│   │   ├───2026_07_10_023903_create_prescriptions_table.php
│   │   ├───2026_07_10_024517_create_prescription_items_table.php
│   │   ├───2026_07_10_205347_add_unit_name_to_medicines_table.php
│   │   ├───2026_07_10_210440_create_sales_table.php
│   │   ├───2026_07_10_212959_create_sale_items_table.php
│   │   ├───2026_07_11_211638_add_business_type_to_tenants_table.php
│   │   ├───2026_07_11_212716_add_outlets_to_tenants_table.php
│   │   ├───2026_07_11_232957_add_brand_and_barcode_to_medicines_table.php
│   │   ├───2026_07_13_011113_add_is_active_to_users_table.php
│   │   ├───2026_07_14_174051_create_platform_admins_table.php
│   │   ├───2026_07_14_183107_add_plan_fields_to_tenants_table.php
│   │   ├───2026_07_14_183107_create_plans_table.php
│   │   ├───2026_07_14_192334_add_owner_fields_to_tenants_table.php
│   │   ├───2026_07_14_202832_add_enabled_modules_to_tenants_table.php
│   │   ├───2026_07_14_205714_create_tenant_subscriptions2_table.php
│   │   ├───2026_07_14_213703_create_platform_invoices_table.php
│   │   ├───2026_07_14_230827_create_audit_logs_table.php
│   │   ├───2026_07_14_230827_create_platform_settings_table.php
│   │   ├───2026_07_15_010229_create_platform_sales_table.php
│   │   ├───2026_07_15_205258_create_permission_tables.php
│   │   ├───2026_07_15_232051_alter_tenants_database_nullable.php
│   │   ├───2026_07_15_235529_add_deleted_at_to_tenants_table.php
│   │   ├───2026_07_16_003415_add_properties_to_audit_logs_table.php
│   │   ├───2026_07_16_012205_fix_tenants_domain_unique_index.php
│   │   ├───2026_07_16_204643_add_trial_fields_to_tenants_table.php
│   │   ├───2026_07_16_215331_create_login_logs_table.php
│   │   ├───2026_07_16_215354_create_user_branches_table.php
│   │   ├───2026_07_16_222131_add_account_lock_fields_to_users_table.php
│   │   ├───2026_07_16_225826_add_auth_fields_to_platform_admins_table.php
│   │   ├───2026_07_16_225900_create_platform_password_history_table.php
│   │   └───2026_07_16_225948_create_platform_password_resets_table.php
│   └───seeders\
├───node_modules\...
├───public\
│   ├───.htaccess
│   ├───favicon.ico
│   ├───index.php
│   ├───robots.txt
│   └───build\
├───resources\
│   ├───css\
│   ├───js\
│   └───views\
│       ├───auth\
│       │   ├───confirm-password.blade.php
│       │   ├───forgot-password.blade.php
│       │   ├───login.blade.php
│       │   ├───register.blade.php
│       │   ├───reset-password.blade.php
│       │   └───verify-email.blade.php
│       ├───components\
│       │   ├───application-logo.blade.php
│       │   ├───auth-session-status.blade.php
│       │   ├───danger-button.blade.php
│       │   ├───dropdown-link.blade.php
│       │   ├───dropdown.blade.php
│       │   ├───input-error.blade.php
│       │   ├───input-label.blade.php
│       │   ├───modal.blade.php
│       │   ├───nav-link.blade.php
│       │   ├───primary-button.blade.php
│       │   ├───responsive-nav-link.blade.php
│       │   ├───secondary-button.blade.php
│       │   └───text-input.blade.php
│       ├───doctors\
│       │   └───index.blade.php
│       ├───invoices\
│       │   └───show.blade.php
│       ├───layouts\
│       │   ├───guest.blade.php
│       │   ├───master.blade.php
│       │   ├───navigation.blade.php
│       │   └───platform_navigation.blade.php
│       ├───patients\
│       │   ├───history.blade.php
│       │   └───index.blade.php
│       ├───pharmacy\
│       │   └───dashboard.blade.php
│       ├───platform\
│       │   ├───audit-logs\
│       │   │   └───index.blade.php
│       │   ├───auth\
│       │   │   ├───forgot-password.blade.php
│       │   │   ├───login.blade.php
│       │   │   ├───reset-password.blade.php
│       │   │   └───verify-email.blade.php
│       │   ├───invoices\
│       │   │   ├───index.blade.php
│       │   │   └───show.blade.php
│       │   ├───layouts\
│       │   │   └───app.blade.php
│       │   ├───plans\
│       │   │   └───index.blade.php
│       │   ├───sessions\
│       │   │   └───index.blade.php
│       │   ├───settings\
│       │   │   ├───change-password.blade.php
│       │   │   └───index.blade.php
│       │   ├───tenants\
│       │   │   ├───index.blade.php
│       │   │   └───show.blade.php
│       │   └───dashboard.blade.php
│       ├───pos\
│       │   ├───index.blade.php
│       │   └───receipt.blade.php
│       ├───prescriptions\
│       │   ├───create.blade.php
│       │   └───show.blade.php
│       ├───profile\
│       │   ├───partials\
│       │   │   ├───delete-user-form.blade.php
│       │   │   ├───update-password-form.blade.php
│       │   │   └───update-profile-information-form.blade.php
│       │   └───edit.blade.php
│       ├───staff\
│       │   └───index.blade.php
│       ├───tenantView\
│       │   ├───auth\
│       │   │   └───login.blade.php
│       │   ├───layouts\
│       │   │   └───app.blade.php
│       │   └───dashboard.blade.php
│       ├───tokens\
│       │   ├───create.blade.php
│       │   ├───doctor-dashboard.blade.php
│       │   └───index.blade.php
│       ├───trial\
│       │   └───register.blade.php
│       ├───billing.blade.php
│       └───landing.blade.php
├───routes\
│   ├───auth.php
│   ├───console.php
│   ├───platform.php
│   └───web.php
├───storage\
│   ├───app\
│   ├───framework\
│   └───logs\
├───tests\
│   ├───TestCase.php
│   ├───Feature\
│   └───Unit\
└───vendor\
    ├───autoload.php
    ├───_laravel_ide\
    ├───bin\
    ├───brick\
    ├───carbonphp\
    ├───composer\
    ├───dflydev\
    ├───doctrine\
    ├───dragonmantank\
    ├───egulias\
    ├───fakerphp\
    ├───filp\
    ├───fruitcake\
    ├───graham-campbell\
    ├───guzzlehttp\
    ├───hamcrest\
    ├───laravel\
    ├───league\
    ├───mockery\
    ├───monolog\
    ├───myclabs\
    ├───nesbot\
    ├───nette\
    ├───nikic\
    ├───nunomaduro\
    ├───phar-io\
    ├───phpoption\
    ├───phpunit\
    ├───psr\
    ├───psy\
    ├───ralouphie\
    ├───ramsey\
    ├───sebastian\
    ├───spatie\
    ├───staabm\
    ├───symfony\
    ├───theseer\
    ├───tijsverkoyen\
    ├───vlucas\
    └───voku\

# Routes

## web.php
```php
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TrialController;
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
Route::get('/tenant/dashboard', function () {
    if (!session()->has('tenant_user_id')) {
        return redirect()->route('tenantView.login');
    }
    return view('tenant.dashboard');
})->name('tenant.dashboard');


// ==========================================
// TENANT ROUTES (Auth Required)
// ==========================================

// Dashboard Route
Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->middleware(['auth', 'tenant.identifier', 'trial'])->name('dashboard');


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


// Billing Route trail end
Route::view('/billing', 'billing')->name('billing');

require __DIR__.'/auth.php';
```

## platform.php
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('platform.dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('platform.logout');
    Route::post('/logout-all', [AuthController::class, 'logoutAllDevices'])->name('platform.logout.all');

    Route::resource('plans', PlanController::class)->names('platform.plans');

    // Tenants Management
    Route::get('/tenants', [TenantController::class, 'index'])->name('platform.tenants.index');
    Route::post('/tenants', [TenantController::class, 'store'])->name('platform.tenants.store');
    Route::put('/tenants/{tenant}', [TenantController::class, 'update'])->name('platform.tenants.update');
    Route::post('/tenants/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])->name('platform.tenants.toggle-status');
    Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy'])->name('platform.tenants.destroy');
    Route::get('/tenants/{tenant}', [TenantController::class, 'show'])->name('platform.tenants.show');
    Route::post('/tenants/{tenant}/renew', [TenantController::class, 'renew'])->name('tenants.renew');
    Route::post('/tenants/{tenant}/toggle-module', [TenantController::class, 'toggleModule'])->name('platform.tenants.toggle-module');
    Route::post('/tenants/{tenant}/subscription-log', [TenantController::class, 'addSubscriptionLog'])->name('platform.tenants.subscription-log');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('platform.invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('platform.invoices.show');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('platform.settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('platform.settings.update');

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('platform.audit-logs.index');

    // Password
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('platform.password.change');
    Route::post('/change-password', [PasswordController::class, 'changePassword'])->name('platform.password.update.post');

    // Sessions
    Route::get('/sessions', [SessionController::class, 'index'])->name('platform.sessions.index');
    Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])->name('platform.sessions.destroy');
    Route::post('/sessions/kill-all', [SessionController::class, 'killAll'])->name('platform.sessions.kill-all');
});
```
