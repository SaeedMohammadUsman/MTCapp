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
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\SetLocale::class,
        ]);
        // $middleware->web();
        // $middleware->append(\Illuminate\Cookie\Middleware\EncryptCookies::class);
        // $middleware->append(\Illuminate\Session\Middleware\StartSession::class);
        // $middleware->append(\App\Http\Middleware\SetLocale::class);
        // $middleware->alias([
        //     'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        //     'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        //     'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        // ]);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
