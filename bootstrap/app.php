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
        // ALIASES DE MIDDLEWARE PERSONALIZADOS
        // ======================================================
        $middleware->alias([
            'tenant'     => \App\Http\Middleware\TenantMiddleware::class,
            'superadmin' => \App\Http\Middleware\SuperAdminMiddleware::class,
        ]);

        // SI QUIERES *OBLIGAR* tenant a afectar TODO el web stack:
        // (no recomendable)
        // $middleware->web([
        //     \App\Http\Middleware\TenantMiddleware::class,
        // ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
