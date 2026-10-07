<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Rute khusus siswa sebelum route resource agar tidak tertabrak binding /siswa/{siswa}
    Route::get('/siswa/kelas/{kelas}', [SiswaController::class, 'perKelas'])->name('siswa.kelas');
    Route::get('/siswa/angkatan/{angkatan}', [SiswaController::class, 'perAngkatan'])->name('siswa.angkatan');
    Route::get('/siswa/lulus', [SiswaController::class, 'lulus'])->name('siswa.lulus');
    Route::post('/siswa/luluskan', [SiswaController::class, 'luluskan'])->name('siswa.luluskan');
    Route::post('/siswa/batalkan-lulus', [SiswaController::class, 'batalkanLulus'])->name('siswa.batalkan-lulus');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::match(['get', 'post'], '/siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
    Route::post('/siswa/bulk-destroy', [SiswaController::class, 'bulkDestroy'])->name('siswa.bulk-destroy');

    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', KelasController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
