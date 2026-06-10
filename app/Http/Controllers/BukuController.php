<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Menampilkan Katalog Buku (Sisi Peminjam)
    public function index(Request $request)
    {
        $cari = $request->query('cari');

        $semuaBuku = Buku::when($cari, function($query, $cari) {
            return $query->where('judul', 'like', "%{$cari}%")
                         ->orWhere('penulis', 'like', "%{$cari}%");
        })
        ->orderBy('created_at', 'desc')
        ->get();

        return view('daftar_buku', compact('semuaBuku'));
    }

    // Menampilkan Katalog Buku (Sisi Admin)
    public function indexAdmin(Request $request)
    {
        $cari = $request->cari;
        $semuaBuku = Buku::when($cari, function ($query) use ($cari) {
            return $query->where('judul', 'like', "%$cari%")
                         ->orWhere('penulis', 'like', "%$cari%");
        })->orderBy('created_at', 'desc')->get();
        
        return view('admin.buku.index', compact('semuaBuku'));
    }

    public function create()
    {
        return view('admin.buku.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'judul.required' => 'Judul buku tidak boleh kosong!',
            'penulis.required' => 'Nama penulis tidak boleh kosong!',
            'penerbit.required' => 'Nama penerbit tidak boleh kosong!',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi!',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka!',
            'tahun_terbit.min' => 'Tahun terbit minimal 1900!',
            'tahun_terbit.max' => 'Tahun terbit tidak boleh melebihi tahun sekarang!',
            'stok.required' => 'Stok buku tidak boleh kosong!',
            'stok.integer' => 'Stok harus berupa angka bulat!',
            'stok.min' => 'Stok buku tidak boleh bernilai minus!',
            'gambar.image' => 'Berkas harus berupa gambar!',
            'gambar.mimes' => 'Format gambar harus jpeg, png, atau jpg!',
            'gambar.max' => 'Ukuran gambar maksimal adalah 2MB!'
        ]);

        $namaFile = null;
        if ($request->hasFile('gambar')) {
            $namaFile = time().'.'.$request->gambar->extension();
            $request->gambar->move(public_path('img'), $namaFile);
        }

        Buku::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => (int) $request->tahun_terbit,
            'stok' => $request->stok,
            'gambar' => $namaFile,
        ]);

        return redirect('/katalog-admin')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        return view('admin.buku.edit', compact('buku'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'stok.min' => 'Stok buku tidak boleh bernilai minus!',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka!',
            'tahun_terbit.min' => 'Tahun terbit minimal 1900!',
            'tahun_terbit.max' => 'Tahun terbit tidak boleh melebihi tahun sekarang!'
        ]);

        $data = $request->all();
        $data['tahun_terbit'] = (int) $request->tahun_terbit;
        
        if ($request->hasFile('gambar')) {
            if ($buku->gambar && file_exists(public_path('img/' . $buku->gambar))) {
                unlink(public_path('img/' . $buku->gambar));
            }

            $namaFile = time().'.'.$request->gambar->extension();
            $request->gambar->move(public_path('img'), $namaFile);
            $data['gambar'] = $namaFile;
        }

        $buku->update($data);
        return redirect('/katalog-admin')->with('success', 'Buku berhasil diupdate!');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        
        if ($buku->gambar && file_exists(public_path('img/' . $buku->gambar))) {
            unlink(public_path('img/' . $buku->gambar));
        }

        $buku->delete();
        return back()->with('success', 'Buku berhasil dihapus!');
    }
}