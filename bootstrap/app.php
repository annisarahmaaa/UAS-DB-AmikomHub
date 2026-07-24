<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$storagePath = (isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL']) || (is_dir('/tmp') && !@is_writable(__DIR__.'/../storage/framework/views')))
    ? '/tmp/storage'
    : null;

if ($storagePath) {
    foreach ([
        '/tmp/storage/app/public',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
    ] as $folder) {
        if (!is_dir($folder)) {
            @mkdir($folder, 0777, true);
        }
    }
}

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 1. Mendaftarkan alias middleware admin kamu
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // 2. Mengecualikan URL Callback Midtrans dari proteksi token CSRF
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

if ($storagePath) {
    $app->useStoragePath($storagePath);
    $app->useBootstrapPath('/tmp/storage/bootstrap');
    if (!is_dir('/tmp/storage/bootstrap/cache')) {
        @mkdir('/tmp/storage/bootstrap/cache', 0777, true);
    }
}

return $app;