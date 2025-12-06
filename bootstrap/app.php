<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SetLocale; // Import Middleware SetLocale Anda

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // Mendaftarkan alias middleware (digunakan di routes/web.php)
        $middleware->alias([
            'set.locale' => SetLocale::class, // Alias untuk Lokalisasi
        ]);

        // Menambahkan middleware ke grup 'web'
        // Middleware Lokalisasi harus berjalan setelah StartSession dan EncryptCookies (default Laravel)
        $middleware->web(append: [
            // Menambahkan SetLocale ke akhir grup 'web' untuk memastikan session sudah siap
            SetLocale::class, 
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Penanganan Exception
    })->create();