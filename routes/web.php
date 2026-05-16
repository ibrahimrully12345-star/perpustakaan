<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

// --- HALAMAN UMUM ---
Route::get('/', function () { return view('welcome'); });

// --- AUTHENTICATION ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// --- SEMUA HALAMAN YANG BUTUH LOGIN (AKSES TERPROTEKSI) ---
Route::middleware('auth')->group(function () {
    
    //  PENGALIHAN UTAMA SETELAH LOGIN
    Route::get('/dashboard', function () {
        // Jika yang login adalah peminjam, langsung buang ke halaman katalog
        if (Auth::user()->role == 'peminjam') {
            return redirect('/katalog');
        }

        // --- LOGIKA OTOMATIS BATALKAN RESERVASI (UNTUK ADMIN/PETUGAS) ---
        $expired = Peminjaman::where('status', 'reservasi')
                    ->where('batas_ambil', '<', now())
                    ->get();

        foreach($expired as $e) {
            Buku::where('id', $e->buku_id)->increment('stok', 1);
            $e->update(['status' => 'batal']);
        }
        // --- SELESAI LOGIKA ---

        return view('admin.dashboard', [
            'jumlahBuku'   => Buku::count(),
            'jumlahUser'   => User::count(),
            'jumlahPinjam' => Peminjaman::where('status', 'dipinjam')->count(),
        ]);
    });

    //  HALAMAN KATALOG PEMINJAM
    Route::get('/katalog', [BukuController::class, 'index']);
    Route::get('/buku', [BukuController::class, 'index']);

    //  MANAJEMEN BUKU (SISI ADMIN)
    Route::get('/katalog-admin', function (Request $request) {
        $cari = $request->cari;
        $semuaBuku = Buku::when($cari, function ($query) use ($cari) {
            return $query->where('judul', 'like', "%$cari%")
                         ->orWhere('penulis', 'like', "%$cari%");
        })->get();
        return view('admin.buku.index', compact('semuaBuku'));
    });

    Route::get('/buku/tambah', [BukuController::class, 'create']);
    Route::post('/buku', [BukuController::class, 'store']);
    Route::get('/buku/edit/{id}', [BukuController::class, 'edit']);
    Route::put('/buku/{id}', [BukuController::class, 'update']);
    Route::delete('/buku/{id}', [BukuController::class, 'destroy']);

    //  MANAJEMEN USERS (KHUSUS ADMIN)
    // Tampilan Utama Tabel Pengguna + Fitur Pencarian & Filter
    Route::get('/users', function (Request $request) {
        $cari = $request->cari;
        $role = $request->role;

        $semuaUser = User::when($cari, function ($query) use ($cari) {
            return $query->where(function ($q) use ($cari) {
                $q->where('name', 'like', "%$cari%")
                  ->orWhere('email', 'like', "%$cari%");
            });
        })->when($role, function ($query) use ($role) {
            return $query->where('role', $role);
        })->get();

        return view('admin.users.index', compact('semuaUser'));
    });

    // Jalur Tambah User Baru
    Route::get('/users/tambah', function() { 
        return view('admin.users.tambah'); 
    });
    Route::post('/users/simpan', [AuthController::class, 'storeUser']);

    // Jalur Aksi Edit Pengguna (Pastikan URL ini tertulis jelas)
    Route::get('/users/edit/{id}', [AuthController::class, 'editUser']);
    
    // Jalur Aksi Simpan Perubahan Pengguna (PUT)
    Route::put('/users/{id}', [AuthController::class, 'updateUser']);
    
    // Jalur Aksi Hapus Pengguna (DELETE)
    Route::delete('/users/{id}', [AuthController::class, 'destroyUser']);

    //  TRANSAKSI PEMINJAMAN (SISI PETUGAS)
    Route::get('/peminjaman/tambah', function () {
        return view('admin.peminjaman.tambah', [
            'users' => User::all(),
            'bukus' => Buku::where('stok', '>', 0)->get()
        ]);
    });

    Route::post('/peminjaman/simpan', function (Request $request) {
        $jumlahPinjam = Peminjaman::where('user_id', $request->user_id)
                        ->where('status', 'dipinjam')
                        ->count();

        if ($jumlahPinjam >= 2) {
            return back()->with('error', 'Peminjam ini sudah meminjam 2 buku!');
        }

        Peminjaman::create([
            'user_id'           => $request->user_id,
            'buku_id'           => $request->buku_id, 
            'tgl_pinjam'        => $request->tanggal_pinjam, 
            'tgl_kembali_plan'  => $request->tanggal_kembali, 
            'status'            => 'dipinjam',
            'denda'             => 0,
        ]);

        Buku::where('id', $request->buku_id)->decrement('stok', 1);
        return redirect('/peminjaman')->with('success', 'Transaksi berhasil dicatat!');
    });

    //  PROSES PENGEMBALIAN & HITUNG DENDA
    Route::get('/peminjaman/kembalikan/{id}', function ($id) {
        $pinjam = \App\Models\Peminjaman::findOrFail($id);
        
        if($pinjam->status == 'dipinjam') {
            $hariIni = \Carbon\Carbon::today();
            $jatuhTempo = \Carbon\Carbon::parse($pinjam->tgl_kembali_plan)->startOfDay();

            $denda = 0;
            if ($hariIni->gt($jatuhTempo)) {
                $selisihHari = $hariIni->diffInDays($jatuhTempo);
                $denda = $selisihHari * 1000;
            }

            $pinjam->update([
                'status' => 'selesai',
                'denda'  => $denda,
                'updated_at' => now()
            ]);

            \App\Models\Buku::where('id', $pinjam->buku_id)->increment('stok', 1);

            return redirect('/peminjaman')->with('success', 'Berhasil dikembalikan!');
        }
        return back();
    });

    //  DATA TRANSAKSI PEMINJAMAN 
    Route::get('/peminjaman', function (Request $request) {
        $cari = $request->cari;
        $status = $request->status;

        $semuaPeminjaman = Peminjaman::with(['user', 'buku'])
            ->when($cari, function ($query) use ($cari) {
                return $query->where(function ($q) use ($cari) {
                    $q->whereHas('user', function ($u) use ($cari) {
                        $u->where('name', 'like', "%$cari%");
                    })
                    ->orWhereHas('buku', function ($b) use ($cari) {
                        $b->where('judul', 'like', "%$cari%");
                    });
                });
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'asc') // <--- UBAH DI SINI (dari desc ke asc)
            ->get();

        return view('admin.peminjaman.index', compact('semuaPeminjaman'));
    });

    // RIWAYAT PENGEMBALIAN 
    Route::get('/pengembalian', function () {
        $riwayatSelesai = Peminjaman::where('status', 'selesai')
                            ->with(['user', 'buku'])
                            ->orderBy('updated_at', 'asc') // <--- UBAH DI SINI (dari desc ke asc)
                            ->get();
        return view('admin.pengembalian.index', compact('riwayatSelesai')); 
    });

    //  LAPORAN
    Route::get('/laporan', function (Illuminate\Http\Request $request) {
        $tgl_mulai = $request->tgl_mulai;
        $tgl_selesai = $request->tgl_selesai;

        $query = \App\Models\Peminjaman::with(['user', 'buku'])->where('status', 'selesai');

        if ($tgl_mulai && $tgl_selesai) {
            $query->whereDate('updated_at', '>=', $tgl_mulai)
                  ->whereDate('updated_at', '<=', $tgl_selesai);
        }

        $laporan = $query->get();

        $totalDenda = $laporan->sum(function($item) {
            return abs($item->denda);
        });

        return view('admin.laporan.index', compact('laporan', 'totalDenda', 'tgl_mulai', 'tgl_selesai'));
    });

    //  PROSES RESERVASI (SISI PEMINJAM)
    Route::post('/reservasi/{id}', function ($id) {
        $sekarang = now();
        $hari = $sekarang->format('N'); 
        $jam = $sekarang->format('H:i');

        if ($hari <= 5) { 
            if ($jam < '09:00' || $jam > '20:00') {
                return back()->with('error', 'Gagal! Reservasi hanya bisa dilakukan saat jam operasional (09:00 - 20:00).');
            }
        } else { 
            if ($jam < '09:00' || $jam > '15:00') {
                return back()->with('error', 'Gagal! Reservasi hanya bisa dilakukan saat jam operasional (09:00 - 15:00).');
            }
        }

        $buku = \App\Models\Buku::findOrFail($id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        $aktif = \App\Models\Peminjaman::where('user_id', Auth::id())
                ->whereIn('status', ['reservasi', 'dipinjam'])
                ->count();
        
        if ($aktif >= 2) {
            return back()->with('error', 'Batas maksimal peminjaman adalah 2 buku.');
        }

        \App\Models\Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $id,
            'tgl_reservasi' => now(),
            'batas_ambil' => now()->addHours(2),
            'status' => 'reservasi',
            'denda' => 0,
        ]);

        $buku->decrement('stok', 1);

        return back()->with('success', 'Berhasil Reservasi! Segera ambil buku dalam 2 jam.');
    });

    //  ROUTE BUKU SAYA 
    Route::get('/pinjaman-saya', function () {
        $pinjaman = \App\Models\Peminjaman::where('user_id', Auth::id())
                    ->with('buku')
                    ->orderBy('created_at', 'asc')
                    ->get();
        return view('pinjaman_saya', compact('pinjaman'));
    });

    // KONFIRMASI AMBIL BUKU (SISI PETUGAS)
    Route::get('/peminjaman/ambil/{id}', function ($id) {
        $p = \App\Models\Peminjaman::findOrFail($id);
        
        $p->update([
            'status' => 'dipinjam',
            'tgl_pinjam' => now(),
            'tgl_kembali_plan' => now()->addDays(3), 
        ]);

        return back()->with('success', 'Buku berhasil diambil! Status berubah menjadi DIPINJAM.');
    });

    // --- FITUR UBAH PASSWORD ---
    Route::get('/password/ubah', function () {
        return view('auth.ubah_password'); // Sesuaikan letak folder jika kamu menaruhnya di folder lain
    });

    Route::put('/password/ubah', function (Request $request) {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', // 'confirmed' otomatis mengecek field_confirmation
        ], [
            'new_password.min' => 'Password baru minimal harus 6 karakter!',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok!'
        ]);

        // Menggunakan tipe data model User yang spesifik agar VSCode tidak bingung
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek apakah password lama sesuai dengan yang ada di database
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini yang Anda masukkan salah!');
        }

        // Update password baru ke database (Otomatis di-hash)
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    });

    // --- FITUR PENGATURAN AKUN ---
    Route::get('/pengaturan-akun', function () {
        return view('auth.pengaturan_akun');
    });

    Route::put('/pengaturan-akun', function (Request $request) {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            // Validasi email unik, kecuali untuk email milik user itu sendiri saat ini
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ], [
            'email.unique' => 'Alamat email ini sudah digunakan oleh pengguna lain!'
        ]);

        /** @var \App\Models\User $user */
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Informasi profil akun Anda berhasil diperbarui!');
    });

});