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
        // 1. Force HTTPS in production (fixes asset URL mismatch on Render)
        if (app()->environment('production')) {
            \URL::forceScheme('https');
            \URL::forceRootUrl(config('app.url'));
            // Livewire asset URL fix (safe no-op if Livewire is not installed)
            \Illuminate\Support\Facades\Config::set('livewire.asset_url', config('app.url'));
        }

        // 2. Ensure writable directories exist for serverless Vercel environment
        foreach (['/tmp/views', '/tmp/cache', '/tmp/sessions'] as $path) {
            if (!is_dir($path)) {
                @mkdir($path, 0755, true);
            }
        }
    }
}