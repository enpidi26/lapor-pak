<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Warga;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');
Route::get('/bantuan', fn () => view('bantuan'))->name('bantuan');

/*
|--------------------------------------------------------------------------
| Masuk & daftar
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [Auth\LoginController::class, 'create'])->name('login');
    Route::post('/masuk', [Auth\LoginController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('login.store');

    Route::get('/daftar', [Auth\RegisterController::class, 'create'])->name('register');
    Route::post('/daftar', [Auth\RegisterController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('register.store');
});

Route::post('/keluar', Auth\LogoutController::class)
    ->middleware('auth')
    ->name('logout');

Route::get('/demo-masuk/{peran}', [DemoController::class, 'masuk'])
    ->middleware('guest')
    ->name('demo.masuk');

/*
|--------------------------------------------------------------------------
| Ruang warga
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:warga'])->group(function () {
    Route::get('/beranda', Warga\DashboardController::class)->name('beranda');

    Route::get('/laporan', [Warga\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/buat', [Warga\LaporanController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [Warga\LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{laporan}', [Warga\LaporanController::class, 'show'])->name('laporan.show');
    Route::get('/laporan/{laporan}/edit', [Warga\LaporanController::class, 'edit'])->name('laporan.edit');
    Route::put('/laporan/{laporan}', [Warga\LaporanController::class, 'update'])->name('laporan.update');
    Route::delete('/laporan/{laporan}', [Warga\LaporanController::class, 'destroy'])->name('laporan.destroy');
});

/*
|--------------------------------------------------------------------------
| Profil & berkas (semua pengguna yang sudah masuk)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profil', [Warga\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [Warga\ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [Warga\ProfilController::class, 'updatePassword'])->name('profil.password');
    Route::get('/avatar', [Warga\ProfilController::class, 'avatar'])->name('profil.avatar');

    Route::get('/laporan/{laporan}/foto', [Warga\LaporanController::class, 'foto'])->name('laporan.foto');
});

/*
|--------------------------------------------------------------------------
| Ruang pengurus (admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::get('/laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{laporan}', [Admin\LaporanController::class, 'show'])->name('laporan.show');
    Route::patch('/laporan/{laporan}/status', [Admin\LaporanController::class, 'updateStatus'])
        ->name('laporan.status');
});
