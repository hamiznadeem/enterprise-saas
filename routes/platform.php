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
use App\Http\Controllers\Platform\ReportController as PlatformReportController;

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

    // Platform Reports
    Route::get('/reports/sales', [PlatformReportController::class, 'sales'])
        ->name('platform.reports.sales');
    Route::get('/reports/revenue', [PlatformReportController::class, 'revenue'])
        ->name('platform.reports.revenue');

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