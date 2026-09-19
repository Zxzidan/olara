<?php

use App\Http\Middleware\AutoLoginMiddleware;
use Illuminate\Contracts\Foundation\MaintenanceMode;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\ArrayMaintenanceMode;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            AutoLoginMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

$app->singleton(
    MaintenanceMode::class,
    ArrayMaintenanceMode::class
);

if ($storagePath = ($_ENV['LARAVEL_STORAGE_PATH'] ?? $_SERVER['LARAVEL_STORAGE_PATH'] ?? getenv('LARAVEL_STORAGE_PATH'))) {
    $app->useStoragePath($storagePath);
}

return $app;
