<?php

try {
    // 1. Buat direktori temporary di /tmp (satu-satunya lokasi writable di Vercel Serverless)
    $storageFolders = [
        '/tmp/storage/app/public',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
    ];

    foreach ($storageFolders as $folder) {
        if (!is_dir($folder)) {
            @mkdir($folder, 0777, true);
        }
    }

    $_SERVER['SCRIPT_NAME'] = '/index.php';

    if (!defined('LARAVEL_START')) {
        define('LARAVEL_START', microtime(true));
    }

    if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
        require $maintenance;
    }

    // 2. Autoload Composer & Bootstrap Application
    require_once __DIR__ . '/../vendor/autoload.php';

    /** @var \Illuminate\Foundation\Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // 3. Alihkan Storage Path ke /tmp/storage
    $app->useStoragePath('/tmp/storage');

    // 4. Handle Request (Laravel 11/13 Standard)
    $app->handleRequest(\Illuminate\Http\Request::capture());
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    echo '<div style="font-family: sans-serif; padding: 30px; background: #fff1f2; color: #9f1239; border-radius: 16px; margin: 20px; border: 2px solid #fecdd3;">';
    echo '<h1 style="margin-top:0;">🚨 Vercel Runtime Debugger</h1>';
    echo '<h2 style="color: #be123c;">' . htmlspecialchars($e->getMessage()) . '</h2>';
    echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>';
    echo '<h3 style="margin-top:20px;">Stack Trace:</h3>';
    echo '<pre style="background: #fff; padding: 15px; border-radius: 8px; overflow-x: auto; font-size: 13px;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</div>';
}


