<?php

/**
 * Final Registry Nuke: 2026-04-10 07:44
 * Forcing Vercel to rebuild the service provider index from a clean state.
 */

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "<html><head><title>Laravel Boot Error</title></head><body style='font-family:sans-serif;padding:20px;line-height:1.6;'>";
    echo "<h1 style='color:#e3342f;'>Critical Boot Error</h1>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " on line " . $e->getLine() . "</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre style='background:#f1f1f1;padding:10px;border-radius:5px;overflow-x:auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</body></html>";

    error_log("Laravel Boot Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
}
