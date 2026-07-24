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

// 2. Autoload Composer & Bootstrap Application
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// 3. Alihkan Storage Path ke /tmp/storage
$app->useStoragePath('/tmp/storage');

// 4. Jalankan HTTP Kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);

