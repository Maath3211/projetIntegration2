<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias definitions
        $middleware->alias([
            'auth' => \App\Http\Middleware\AuthMiddleware::class,
            'locale' => \App\Http\Middleware\SetLocale::class, // Add the locale middleware
        ]);

        // Define the web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class, // Add SetLocale to the web middleware group
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
