<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AuthController;

// --- HALAMAN UMUM ---
Route::get('/', function () { return view('welcome'); });

// --- AUTHENTICATION ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout']);

// --- SEMUA HALAMAN YANG BUTUH LOGIN (AKSES TERPROTEKSI) ---
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('role:admin,petugas');

    Route::middleware('role:admin')->group(function () {
        Route::get('/katalog-admin', [BukuController::class, 'indexAdmin']);
        Route::get('/buku/tambah', [BukuController::class, 'create']);
        Route::post('/buku', [BukuController::class, 'store']);
        Route::get('/buku/edit/{id}', [BukuController::class, 'edit']);
        Route::put('/buku/{id}', [BukuController::class, 'update']);
        Route::delete('/buku/{id}', [BukuController::class, 'destroy']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [AuthController::class, 'indexUser']);
        Route::get('/users/tambah', [AuthController::class, 'showCreateUser']);
        Route::post('/users/simpan', [AuthController::class, 'storeUser']);
        Route::get('/users/edit/{id}', [AuthController::class, 'editUser']);
        Route::put('/users/{id}', [AuthController::class, 'updateUser']);
        Route::delete('/users/{id}', [AuthController::class, 'destroyUser']);
    });

    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index']);
        Route::get('/peminjaman/tambah', [PeminjamanController::class, 'create']);
        Route::post('/peminjaman/simpan', [PeminjamanController::class, 'store']);
        Route::get('/peminjaman/kembalikan/{id}', [PeminjamanController::class, 'kembalikan']);
        Route::get('/peminjaman/ambil/{id}', [PeminjamanController::class, 'ambilBuku']);
        Route::get('/pengembalian', [PeminjamanController::class, 'riwayatPengembalian']);
        Route::get('/laporan', [PeminjamanController::class, 'laporan']);
    });

    Route::middleware('role:peminjam')->group(function () {
        Route::get('/katalog', [BukuController::class, 'index']);
        Route::get('/buku', [BukuController::class, 'index']);
        Route::post('/reservasi/{id}', [PeminjamanController::class, 'reservasi']);
        Route::get('/pinjaman-saya', [PeminjamanController::class, 'pinjamanSaya']);
    });

    Route::middleware('role:admin,petugas,peminjam')->group(function () {
        Route::get('/password/ubah', [AuthController::class, 'showUbahPassword']);
        Route::put('/password/ubah', [AuthController::class, 'ubahPassword']);
        Route::get('/pengaturan-akun', [AuthController::class, 'showPengaturanAkun']);
        Route::put('/pengaturan-akun', [AuthController::class, 'updatePengaturanAkun']);
    });
});