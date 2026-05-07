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
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function ($response, $exception) {
            if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
                return redirect('/login')->with('error', 'Your session has expired. Please log in again.');
            }
            return $response;
        });
    })->create();