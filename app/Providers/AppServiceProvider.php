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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        if (app()->environment('production')) {
            $path = config('view.compiled');
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }

        // Force HTTPS if:
        // 1. Explicitly set in ENV (Production/Force)
        // 2. Request Host contains 'ngrok' (Dynamic detection)
        // 3. X-Forwarded-Proto is https (Load Balancer/Tunnel)
        // BUT skip if we are on localhost/127.0.0.1 explicitly (unless FORCE_HTTPS is true)
        
        $isNgrok = str_contains($this->app->request->getHost(), 'ngrok');
        $isLocal = $this->app->environment('local') && !str_contains($this->app->request->getHost(), 'ngrok');

        if (env('FORCE_HTTPS', false) || $isNgrok || $this->app->request->header('X-Forwarded-Proto') === 'https') {
             // Safety check: Don't force HTTPS on pure localhost unless forced
             if (!$isLocal || env('FORCE_HTTPS', false)) {
                URL::forceScheme('https');
                $this->app['request']->server->set('HTTPS', 'on');
             }
        }
    }
}