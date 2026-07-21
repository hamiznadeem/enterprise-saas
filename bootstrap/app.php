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
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        $middleware->alias([
            'trial' => \App\Http\Middleware\CheckTrialExpiry::class,
            'tenant.identifier' => \App\Http\Middleware\IdentifyTenant::class,
            'platform.auth' => \App\Http\Middleware\PlatformAuth::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
            'permission' => \App\Http\Middleware\CheckPlatformPermission::class,
            'active.user' => \App\Http\Middleware\EnsureUserIsActive::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
