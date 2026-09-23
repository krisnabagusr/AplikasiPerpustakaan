<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Perpustakaan Sekolah
|--------------------------------------------------------------------------
*/

// Rute Autentikasi (Tamu / Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Rute Terproteksi (Hanya Admin/Petugas yang sudah login)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Data Buku
    Route::resource('buku', BukuController::class)->except(['show']);

    // CRUD Data Anggota (parameter explicitly mapped to 'anggota')
    Route::resource('anggota', AnggotaController::class)
        ->parameters(['anggota' => 'anggota'])
        ->except(['show']);

    // Transaksi Peminjaman & Pengembalian
    Route::resource('peminjaman', PeminjamanController::class)
        ->parameters(['peminjaman' => 'peminjaman'])
        ->only(['index', 'create', 'store', 'destroy']);
        
    Route::post('peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
});
