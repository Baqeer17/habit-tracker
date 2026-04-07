<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Baca preferensi bahasa dari user yang login,
     * lalu set locale Laravel agar __() / trans() bisa dipakai nanti.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $language = auth()->user()->language ?? 'id';
            App::setLocale($language);
        }

        return $next($request);
    }
}
