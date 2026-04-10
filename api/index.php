<?php

/**
 * Mahabba - Vercel Deployment Bridge
 * Menghubungkan Vercel Serverless Function ke Laravel Core
 */

// 1. Membuat folder writable secara dinamis di memory sementara Vercel (/tmp)
// Ini krusial untuk mencegah error "Read-only file system"
$paths = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
];

foreach ($paths as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

// 2. Memanggil entry point utama Laravel
// Kita tidak mendefinisikan LARAVEL_START di sini karena sudah didefinisikan di public/index.php
require __DIR__ . '/../public/index.php';