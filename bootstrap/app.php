<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            // \App\Http\Middleware\SecurityHeaders::class,
        ]);

        $middleware->alias([
            // ── Platform Middlewares ──
            'platform.auth'       => \App\Http\Middleware\PlatformAuth::class,
            'permission'          => \App\Http\Middleware\CheckPlatformPermission::class,

            // ── Tenant Middlewares ──
            'tenant.identifier'   => \App\Http\Middleware\IdentifyTenant::class,
            'trial'               => \App\Http\Middleware\CheckTrialExpiry::class,
            'active.user'         => \App\Http\Middleware\EnsureUserIsActive::class,
            'email.verified'      => \App\Http\Middleware\EnsureEmailVerified::class,
            'two-factor.verified' => \App\Http\Middleware\EnsureTwoFactorVerified::class,
            'check.branch'        => \App\Http\Middleware\CheckBranch::class,
            'password.expiry'     => \App\Http\Middleware\CheckPasswordExpiry::class,

            // ── NEW: Tenant RBAC ──
            'tenant.role'         => \App\Http\Middleware\CheckTenantRole::class,
            'tenant.permission'   => \App\Http\Middleware\CheckTenantPermission::class,
            'active.branch'       => \App\Http\Middleware\EnsureBranchActive::class,

            // ── Security ──
            'security.headers'    => \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();