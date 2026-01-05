<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BalitaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PengukuranController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [BalitaController::class, 'dashboard'])->name('dashboard');

    Route::middleware('role:admin,kader')->group(function () {
        // Admin dan Kader bisa mengakses halaman ini

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // CRUD Balita
        Route::resource('balita', BalitaController::class)->parameters([
            'balita' => 'balita',
        ]);

        Route::get('balita/{balita}/pengukuran', [PengukuranController::class, 'index'])->name('pengukuran.index');


        // data grafik JSON
        Route::get('balita/{balita}/grafik', [PengukuranController::class, 'grafikData'])->name('pengukuran.grafik');
    });

    Route::middleware(['role:kader'])->group(function () {
        // Pengukuran dan grafik
        Route::get('balita/{balita}/pengukuran/create', [PengukuranController::class, 'create'])->name('pengukuran.create');
        Route::post('balita/{balita}/pengukuran', [PengukuranController::class, 'store'])->name('pengukuran.store');
    });
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    });
});


require __DIR__ . '/auth.php';
