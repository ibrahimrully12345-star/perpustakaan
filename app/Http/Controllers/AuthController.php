<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            if (Auth::user()->role == 'admin' || Auth::user()->role == 'petugas') {
                return redirect('/dashboard');
            }
            return redirect('/katalog');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ], [
            'password.min' => 'Password minimal harus 8 karakter!',
            'password.confirmed' => 'Konfirmasi ulangi password tidak cocok!',
            'email.unique' => 'Alamat email ini sudah terdaftar!'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'peminjam',
        ]);

        return redirect('/login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    public function dashboard() {
        if (Auth::user()->role == 'peminjam') {
            return redirect('/katalog');
        }

        // --- LOGIKA OTOMATIS BATALKAN RESERVASI ---
        $expired = Peminjaman::where('status', 'reservasi')
                    ->where('batas_ambil', '<', now())
                    ->get();

        foreach($expired as $e) {
            Buku::where('id', $e->buku_id)->increment('stok', 1);
            $e->update(['status' => 'batal']);
        }

        return view('admin.dashboard', [
            'jumlahBuku'      => Buku::count(),
            'jumlahUser'      => User::count(),
            'jumlahPinjam'    => Peminjaman::where('status', 'dipinjam')->count(),
            'jumlahReservasi' => Peminjaman::where('status', 'reservasi')->count(),
        ]);
    }

    // --- MANAJEMEN USERS (ADMIN) ---
    public function indexUser(Request $request) {
        $cari = $request->cari;
        $role = $request->role;

        $semuaUser = User::when($cari, function ($query) use ($cari) {
            return $query->where(function ($q) use ($cari) {
                $q->where('name', 'like', "%$cari%")
                  ->orWhere('email', 'like', "%$cari%");
            });
        })->when($role, function ($query) use ($role) {
            return $query->where('role', $role);
        })->orderBy('created_at', 'desc')->get();

        return view('admin.users.index', compact('semuaUser'));
    }

    public function showCreateUser() {
        return view('admin.users.tambah');
    }

    public function storeUser(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,petugas,peminjam',
        ], [
            'email.unique' => 'Email ini sudah terdaftar di sistem!',
            'password.min' => 'Password minimal harus 8 karakter!'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/users')->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function editUser($id) {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id) {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,petugas,peminjam',
        ]);

        $user->update([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        return redirect('/users')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroyUser($id) {
        $user = User::findOrFail($id);
        if (Auth::id() == $id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus!');
    }

    // --- UBAH PASSWORD ---
    public function showUbahPassword() {
        return view('auth.ubah_password');
    }

    public function ubahPassword(Request $request) {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.min' => 'Password baru minimal harus 8 karakter!',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok!'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini yang Anda masukkan salah!');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    // --- PENGATURAN AKUN ---
    public function showPengaturanAkun() {
        return view('auth.pengaturan_akun');
    }

    public function updatePengaturanAkun(Request $request) {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
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
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}