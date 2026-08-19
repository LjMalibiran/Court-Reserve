<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureUserIsVerified;
use App\Http\Middleware\RoleMiddleware; // Imported the new RoleMiddleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        $middleware->alias([
            'verified.phone' => EnsureUserIsVerified::class,
            'role' => RoleMiddleware::class, // Added the role alias here
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();