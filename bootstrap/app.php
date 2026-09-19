<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\MaintenanceMode;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\ArrayMaintenanceMode;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->validateCsrfTokens(except: [
            'api/midtrans/*',
            'midtrans/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($e instanceof AuthenticationException || $e instanceof ValidationException) {
                return null;
            }

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ], 500);
            }

            $sessToken = $request->hasSession() ? $request->session()->token() : 'NO_SESSION';
            $sessId = $request->hasSession() ? $request->session()->getId() : 'NO_ID';
            $inputToken = $request->input('_token', 'NONE');
            $cookieVal = $request->cookie('olara_session', 'NONE');

            return response(
                "<div style='font-family:sans-serif;max-width:680px;margin:60px auto;padding:28px;border:1px solid #fecaca;background:#fef2f2;border-radius:12px;color:#991b1b;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);'>"
                ."<h2 style='margin-top:0;font-size:20px;'>⚠️ Terjadi Kendala di Server</h2>"
                ."<p style='font-size:15px;color:#1f2937;line-height:1.5;'><b>Penyebab:</b> ".htmlspecialchars($e->getMessage()).'</p>'
                ."<p style='font-size:13px;color:#6b7280;'><b>File:</b> ".htmlspecialchars($e->getFile()).' (Baris '.$e->getLine().')</p>'
                ."<div style='font-size:12px;background:#fff;padding:12px;border:1px solid #e5e7eb;border-radius:6px;color:#374151;margin:15px 0;'>"
                .'<p><b>Input Token:</b> '.htmlspecialchars((string) $inputToken).'</p>'
                .'<p><b>Session Token:</b> '.htmlspecialchars((string) $sessToken).'</p>'
                .'<p><b>Session ID:</b> '.htmlspecialchars((string) $sessId).'</p>'
                .'<p><b>Cookie Present:</b> '.($cookieVal !== 'NONE' ? 'YES' : 'NO').'</p>'
                .'</div>'
                ."<div style='margin-top:20px;display:flex;gap:12px;'>"
                ."<a href='/login' style='display:inline-block;padding:9px 18px;background:#10b981;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;'>Kembali ke Login</a>"
                ."<a href='/' style='display:inline-block;padding:9px 18px;background:#f3f4f6;color:#374151;border:1px solid #d1d5db;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;'>Halaman Depan</a>"
                .'</div>'
                .'</div>',
                500
            );
        });
    })->create();

$app->singleton(
    MaintenanceMode::class,
    ArrayMaintenanceMode::class
);

if ($storagePath = ($_ENV['LARAVEL_STORAGE_PATH'] ?? $_SERVER['LARAVEL_STORAGE_PATH'] ?? getenv('LARAVEL_STORAGE_PATH'))) {
    $app->useStoragePath($storagePath);
}

return $app;
