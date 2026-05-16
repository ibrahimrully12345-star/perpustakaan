<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
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

        // FITUR BARU: Menangkap nilai checkbox 'remember' (bernilai true jika dicentang)
        $remember = $request->has('remember');

        // Memasukkan variabel $remember ke dalam Auth::attempt agar session tersimpan lama
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            if (Auth::user()->role == 'admin' || Auth::user()->role == 'petugas') {
                return redirect('/dashboard');
            }
            return redirect('/katalog');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function showRegister() {
        return view('auth.register');
    }

    // FUNGSI 1: Untuk Registrasi Mandiri (Hanya Peminjam) - Dipakai di halaman depan
    public function register(Request $request) {
        // PERBAIKAN VALIDASI: Minimal 6 karakter dan wajib COCOK dengan input confirmation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed' // 'confirmed' otomatis mengecek password_confirmation
        ], [
            'password.min' => 'Password minimal harus 6 karakter!',
            'password.confirmed' => 'Konfirmasi ulangi password tidak cocok!',
            'email.unique' => 'Alamat email ini sudah terdaftar!'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'peminjam', // Terkunci otomatis
        ]);

        return redirect('/login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    // FUNGSI 2: Untuk Admin Tambah User (Bisa Pilih Role) - Dipakai di Dashboard Admin
    public function storeUser(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Dinamis sesuai pilihan Admin
        ]);

        return redirect('/users')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    // FUNGSI 3: Menampilkan halaman Edit User
    public function editUser($id) {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // FUNGSI 4: Memproses Update User
    public function updateUser(Request $request, $id) {
        $user = User::findOrFail($id);
        
        // Validasi hanya untuk Nama dan Role
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required'
        ]);

        $user->name = $request->name;
        $user->role = $request->role;

        // Logika password dan email dihapus demi privasi user
        $user->save();

        return redirect('/users')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function destroyUser($id) {
        $user = User::findOrFail($id);
        if (Auth::id() == $id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus!');
    }
}