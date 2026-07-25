<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // ── Tenant Guard — Tenant users ke liye alag guard ──
        'tenant' => [
            'driver' => 'session',
            'provider' => 'tenant_users',
        ],

        // ── Platform Guard — Super admin panel ──
        'platform' => [
            'driver' => 'session',
            'provider' => 'platform_admins',
        ],

        // ── API Guard — Sanctum tokens ──
        'sanctum' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // ── Tenant Provider — Same model, alag identity check ──
        'tenant_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // ── Platform Provider ──
        'platform_admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\PlatformAdmin::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        // ── Tenant Password Broker ──
        'tenant_users' => [
            'provider' => 'tenant_users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        // ── Platform Password Broker ──
        'platform_admins' => [
            'provider' => 'platform_admins',
            'table' => 'platform_password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];