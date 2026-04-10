<?php

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

// 3. Baru boot Laravel melalui public/index.php
// Kita HAPUS baris define LARAVEL_START karena sudah ada di file di bawah ini:
require __DIR__ . '/../public/index.php';