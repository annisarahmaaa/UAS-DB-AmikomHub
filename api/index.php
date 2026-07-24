<?php

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

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Autoload Composer & Bootstrap Application
require __DIR__ . '/../vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 3. Alihkan Storage Path ke /tmp/storage
$app->useStoragePath('/tmp/storage');

// 4. Handle Request (Laravel 11/13 Standard)
$app->handleRequest(\Illuminate\Http\Request::capture());


