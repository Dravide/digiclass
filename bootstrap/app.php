<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.custom' => \App\Http\Middleware\AuthMiddleware::class,
            'guest.custom' => \App\Http\Middleware\GuestMiddleware::class,
            'admin' => \App\Http\Middleware\OperatorMiddleware::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'role.redirect' => \App\Http\Middleware\RoleRedirect::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'cors' => \App\Http\Middleware\CorsMiddleware::class,
            'license.validation' => \App\Http\Middleware\LicenseValidation::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
