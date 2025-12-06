<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route untuk Halaman Utama (Redirect ke Login jika belum login)
Route::get('/', function () {
    // Jika user sudah login, arahkan ke dashboard.
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    // Jika belum login, arahkan ke halaman login.
    return redirect()->route('login');
})->name('welcome');

// Kriteria 6: Route untuk Mengganti Bahasa (Lokalisasi)
Route::get('lang/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'id'])) {
        return back()->with('error', 'Pilihan bahasa tidak valid.');
    }
    Session::put('locale', $locale);
    return back();
})->name('language.switch');


// Route Group yang Membutuhkan Otentikasi dan Lokalisasi
Route::middleware(['auth', 'set.locale'])->group(function () {
    
    // Dashboard (TIDAK ADA MIDDLEWARE 'verified' lagi)
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard'); 

    // Kategori (CRUD)
    Route::resource('categories', CategoryController::class);

    // Transaksi (CRUD)
    Route::resource('transactions', TransactionController::class)->except(['show']);

    // Anggaran (Budget) 
    Route::resource('budgets', BudgetController::class)->except(['show']);

    // Tujuan Keuangan (Goal) 
    Route::resource('goals', App\Http\Controllers\GoalController::class)->except(['show']);

    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    
    // Route untuk Profile (dari Breeze)
    // Route::view('profile', 'profile.edit')->name('profile.edit');
    Route::patch('profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Otentikasi (Login, Register, dll.)
require __DIR__.'/auth.php';
