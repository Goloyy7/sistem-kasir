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
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
        'admin.auth'  => \App\Http\Middleware\AdminAuthenticate::class,
        'admin.guest' => \App\Http\Middleware\RedirectIfAdminAuthenticated::class,
        'user.auth'   => \App\Http\Middleware\KasirAuthenticate::class,
        'user.guest'  => \App\Http\Middleware\RedirectIfKasirAuthenticated::class,
        'user.is_active'  => \App\Http\Middleware\EnsureUserIsActive::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
