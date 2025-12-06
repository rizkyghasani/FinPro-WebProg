<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil dari sesi, atau default ke 'id' (Bahasa Indonesia)
        $locale = $request->session()->get('locale', config('app.locale')); 
        
        // Pastikan locale yang diminta valid
        if (! in_array($locale, ['en', 'id'])) {
            $locale = 'id';
        }

        // Set bahasa aplikasi
        app()->setLocale($locale);

        return $next($request);
    }
}

