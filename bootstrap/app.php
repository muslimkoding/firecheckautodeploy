<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RoleOrMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register custom middleware di sini
        $middleware->alias([
            // 'role' => Spatie\Permission\Middlewares\RoleMiddleware::class,
            // 'permission' => Spatie\Permission\Middlewares\PermissionMiddleware::class,
            // 'role_or_permission' => Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
            'superadmin' => SuperAdminMiddleware::class, // Jika pakai custom
            'admin' => AdminMiddleware::class, // Jika pakai custom
            'role_or' => RoleOrMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
