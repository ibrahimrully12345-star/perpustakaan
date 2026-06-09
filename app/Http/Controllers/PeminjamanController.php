<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // Halaman Utama Transaksi Peminjaman (Sisi Petugas/Admin)
    public function index(Request $request)
    {
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
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.peminjaman.index', compact('semuaPeminjaman'));
    }

    public function create()
    {
        return view('admin.peminjaman.tambah', [
            'users' => User::all(),
            'bukus' => Buku::where('stok', '>', 0)->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ], [
            'user_id.required' => 'Peminjam wajib dipilih!',
            'buku_id.required' => 'Buku wajib dipilih!',
            'tanggal_kembali.after_or_equal' => 'Gagal mencatat! Tanggal batas kembali harus sama atau setelah tanggal pinjam.'
        ]);

        $jumlahPinjam = Peminjaman::where('user_id', $request->user_id)
                        ->where('status', 'dipinjam')
                        ->count();

        if ($jumlahPinjam >= 2) {
            return back()->with('error', 'Peminjam ini sudah meminjam 2 buku!');
        }

        Peminjaman::create([
            'user_id'           => $request->user_id,
            'buku_id'           => $request->buku_id,
            'tgl_reservasi'     => now(),
            'batas_ambil'       => now()->addHours(2),
            'tgl_pinjam'        => $request->tanggal_pinjam,
            'tgl_kembali_plan'  => $request->tanggal_kembali,
            'status'            => 'dipinjam',
            'denda'             => 0,
        ]);

        Buku::where('id', $request->buku_id)->decrement('stok', 1);
        return redirect('/peminjaman')->with('success', 'Transaksi berhasil dicatat!');
    }

    public function kembalikan($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        
        if($pinjam->status == 'dipinjam') {
            $hariIni = Carbon::today()->startOfDay();
            $jatuhTempo = Carbon::parse($pinjam->tgl_kembali_plan)->startOfDay();

            $denda = 0;
            
            // FIX: Jika hari ini sudah melewati batas jatuh tempo, hitung denda positif mutlak
            if ($hariIni->gt($jatuhTempo)) {
                $selisihHari = $hariIni->diffInDays($jatuhTempo);
                $denda = abs($selisihHari) * 1000; // <--- FIX: Dipaksa selalu positif mutlak
            }

            // Simpan perubahan ke database
            $pinjam->update([
                'status' => 'selesai',
                'denda'  => $denda,
                'updated_at' => now()
            ]);

            Buku::where('id', $pinjam->buku_id)->increment('stok', 1);

            return redirect('/peminjaman')->with('success', 'Berhasil dikembalikan! ' . ($denda > 0 ? 'Denda tercatat: Rp ' . number_format($denda) : 'Tepat waktu.'));
        }
        return back();
    }

    public function ambilBuku($id)
    {
        $p = Peminjaman::findOrFail($id);
        
        $p->update([
            'status' => 'dipinjam',
            'tgl_pinjam' => now(),
            'tgl_kembali_plan' => now()->addDays(3), 
        ]);

        return back()->with('success', 'Buku berhasil diambil! Status berubah menjadi DIPINJAM.');
    }

    public function riwayatPengembalian(Request $request)
    {
        $cari = $request->cari;
        $denda_filter = $request->denda_filter;

        $query = Peminjaman::where('status', 'selesai')->with(['user', 'buku']);

        if ($cari) {
            $query->where(function ($q) use ($cari) {
                $q->whereHas('user', function ($u) use ($cari) {
                    $u->where('name', 'like', "%$cari%");
                })->orWhereHas('buku', function ($b) use ($cari) {
                    $b->where('judul', 'like', "%$cari%");
                });
            });
        }

        if ($denda_filter == 'berdenda') {
            $query->where('denda', '!=', 0); // <--- FIX: Mengakomodasi jika ada data lama yang minus
        } elseif ($denda_filter == 'bebas') {
            $query->where('denda', 0);
        }

        $riwayatSelesai = $query->orderBy('updated_at', 'desc')->get();

        return view('admin.pengembalian.index', compact('riwayatSelesai'));
    }

    public function laporan(Request $request)
    {
        $tgl_mulai = $request->tgl_mulai;
        $tgl_selesai = $request->tgl_selesai;

        // Ambil data peminjaman selesai dengan relasi user dan buku
        $query = Peminjaman::with(['user', 'buku'])->where('status', 'selesai');

        // Filter jika petugas memilih range tanggal tertentu
        if ($tgl_mulai && $tgl_selesai) {
            $query->whereDate('updated_at', '>=', $tgl_mulai)
                  ->whereDate('updated_at', '<=', $tgl_selesai);
        }

        // Urutkan data berdasarkan waktu pengembalian terbaru (updated_at) paling atas
        $laporan = $query->orderBy('updated_at', 'desc')->get();

        // Hitung total denda keseluruhan secara otomatis mutlak (positif)
        $totalDenda = $laporan->sum(function($item) {
            return abs($item->denda);
        });

        return view('admin.laporan.index', compact('laporan', 'totalDenda', 'tgl_mulai', 'tgl_selesai'));
    }

    // --- SISI PEMINJAM (RESERVASI & RIWAYAT PROFIL) ---
    public function reservasi($id)
    {
        $sekarang = Carbon::now();
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

        $buku = Buku::findOrFail($id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        $aktif = Peminjaman::where('user_id', Auth::id())
                ->whereIn('status', ['reservasi', 'dipinjam'])
                ->count();
        
        if ($aktif >= 2) {
            return back()->with('error', 'Batas maksimal peminjaman adalah 2 buku.');
        }

        $waktu_reservasi = Carbon::now();
        $waktu_batas_ambil = Carbon::now()->addHours(2);

        Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $id,
            'tgl_reservasi' => $waktu_reservasi,
            'batas_ambil' => $waktu_batas_ambil,
            'status' => 'reservasi',
            'denda' => 0,
        ]);

        $buku->decrement('stok', 1);

        return back()->with('success', 'Berhasil Reservasi! Segera ambil buku dalam 2 jam.');
    }

    public function pinjamanSaya()
    {
        $pinjaman = Peminjaman::where('user_id', Auth::id())
                    ->with('buku')
                    ->orderBy('created_at', 'desc')
                    ->get();
        return view('pinjaman_saya', compact('pinjaman'));
    }
}