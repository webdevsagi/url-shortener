<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
->withRouting(
    web: __DIR__ . "/../routes/web.php",
    api: __DIR__ . "/../routes/api.php", // הוסיפו את השורה הזו
    commands: __DIR__ . "/../routes/console.php",
    health: "/up",
)
    ->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'api.key' => \App\Http\Middleware\CheckApiKey::class,

    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
