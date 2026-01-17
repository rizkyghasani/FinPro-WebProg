<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App; 
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale', config('app.locale')); 
        
        // Log::info('Membaca Session Locale: ' . $locale); // Debug

        if (! in_array($locale, ['en', 'id'])) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);
        
        // Log::info('Locale Diatur ke: ' . App::getLocale()); // Debug

        return $next($request);
    }
}