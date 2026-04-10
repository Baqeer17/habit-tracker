<?php
define('LARAVEL_START', microtime(true));

/**
 * Final Registry Nuke & DevOps Sanitization
 * Forcing Vercel to rebuild all indicators from a clean state.
 */

// 1. Set env overrides SEBELUM apapun
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');
putenv('VIEW_COMPILED_PATH=/tmp/views');

// 2. Buat direktori writable
foreach (['/tmp/views', '/tmp/cache', '/tmp/sessions'] as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 3. Sanitasi bootstrap/cache — hapus jika path bukan /var/task
$cacheFiles = glob(__DIR__ . '/../bootstrap/cache/*.php');
foreach ($cacheFiles as $file) {
    $contents = file_get_contents($file);
    if (strpos($contents, '/var/task') === false) {
        @unlink($file);
    }
}

// 4. Baru boot Laravel melalui public/index.php
require __DIR__ . '/../public/index.php';
