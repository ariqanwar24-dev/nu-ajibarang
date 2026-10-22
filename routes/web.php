<?php

use App\Http\Controllers\AccountStatusController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VerifikasiAnggotaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KartuAnggotaController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{berita}', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/berita/{berita}/galeri/{foto}', [BeritaController::class, 'galeri'])->name('berita.galeri.show');

Route::get('/pengurus', [PengurusController::class, 'index'])->name('pengurus.index');
Route::get('/pengurus/{pengurus}', [PengurusController::class, 'show'])->name('pengurus.show');

Route::middleware(['auth', 'active', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::get('/pengguna', [AdminUserController::class, 'index'])->name('pengguna.index');
    Route::patch('/pengguna/{user}/approve', [AdminUserController::class, 'approve'])->name('pengguna.approve');
    Route::patch('/pengguna/{user}/reject', [AdminUserController::class, 'reject'])->name('pengguna.reject');
    Route::patch('/pengguna/{user}/role', [AdminUserController::class, 'updateRole'])
        ->middleware('super_admin')
        ->name('pengguna.role');

    Route::get('/anggota', [VerifikasiAnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/anggota/create', [AnggotaController::class, 'adminCreate'])->name('anggota.create');
    Route::post('/anggota', [AnggotaController::class, 'adminStore'])->name('anggota.store');
    Route::patch('/anggota/{anggota}/verifikasi', [VerifikasiAnggotaController::class, 'update'])->name('anggota.verifikasi');
    Route::delete('/anggota/{anggota}', [VerifikasiAnggotaController::class, 'destroy'])->name('anggota.destroy');

    Route::get('/berita', [BeritaController::class, 'adminIndex'])->name('berita.index');
    Route::get('/berita/create', [BeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{berita}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{berita}', [BeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{berita}', [BeritaController::class, 'destroy'])->name('berita.destroy');
    Route::patch('/berita/{berita}/galeri/{foto}', [BeritaController::class, 'replaceFoto'])
        ->name('berita.galeri.replace');
    Route::delete('/berita/{berita}/galeri/{foto}', [BeritaController::class, 'destroyFoto'])
        ->name('berita.galeri.destroy');
    Route::patch('/berita/{berita}/review', [BeritaController::class, 'review'])
        ->middleware('super_admin')
        ->name('berita.review');

    Route::get('/pengurus', [PengurusController::class, 'adminIndex'])->name('pengurus.index');
    Route::get('/pengurus/create', [PengurusController::class, 'create'])->name('pengurus.create');
    Route::post('/pengurus', [PengurusController::class, 'store'])->name('pengurus.store');
    Route::get('/pengurus/{pengurus}/edit', [PengurusController::class, 'edit'])->name('pengurus.edit');
    Route::put('/pengurus/{pengurus}', [PengurusController::class, 'update'])->name('pengurus.update');
    Route::delete('/pengurus/{pengurus}', [PengurusController::class, 'destroy'])->name('pengurus.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/akun/status', [AccountStatusController::class, 'show'])->name('account.status');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })
        ->middleware('verified')
        ->name('dashboard');

    Route::get('/anggota/create', [AnggotaController::class, 'create'])->name('anggota.create');
    Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
    Route::get('/kartu', [KartuAnggotaController::class, 'index'])->name('kartu.index');
});

require __DIR__.'/auth.php';
