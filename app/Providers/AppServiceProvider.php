<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Force HTTPS in production
        if (app()->environment('production')) {
            \URL::forceScheme('https');
        }

        // 2. Ensure writable directories exist for serverless Vercel environment
        foreach (['/tmp/views', '/tmp/cache', '/tmp/sessions'] as $path) {
            if (!is_dir($path)) {
                @mkdir($path, 0755, true);
            }
        }
    }
}