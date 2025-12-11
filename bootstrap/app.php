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

        // ======================================================
        // ALIASES REALES EN LARAVEL 12
        // ======================================================
        $middleware->alias([
    'auth'       => \Illuminate\Auth\Middleware\Authenticate::class,
    'tenant'     => \App\Http\Middleware\TenantMiddleware::class,
    'superadmin' => \App\Http\Middleware\SuperAdminMiddleware::class,
]);

// AÑADE ESTO PARA QUE SIEMPRE SE APLIQUE EL MIDDLEWARE EN LAS RUTAS /tenant/*
    $middleware->group('tenant-area', [
        \Illuminate\Auth\Middleware\Authenticate::class,
        \App\Http\Middleware\TenantMiddleware::class,
    ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
