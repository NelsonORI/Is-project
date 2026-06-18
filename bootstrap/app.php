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

        // Registering route-level middleware aliases
        $middleware->alias([
            'student.active' => \App\Http\Middleware\EnsureStudentIsActive::class,
            'class.rep'      => \App\Http\Middleware\EnsureClassRep::class,
            'admin'          => \App\Http\Middleware\EnsureAdmin::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();