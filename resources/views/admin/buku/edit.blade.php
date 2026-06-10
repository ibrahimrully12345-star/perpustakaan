@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3>Edit Buku: {{ $buku->judul }}</h3>
    <form action="/buku/{{ $buku->id }}" method="POST" enctype="multipart/form-data"
      onsubmit="return confirm('Apakah Anda yakin sudah sesuai untuk mengupdate data buku \'' + '{{ $buku->judul }}' + '\' ini?')">
        @csrf
        @method('PUT') <!-- Penting untuk Update -->
        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" value="{{ $buku->judul }}">
        </div>
        <div class="mb-3">
            <label>Penulis</label>
            <input type="text" name="penulis" class="form-control" value="{{ $buku->penulis }}">
        </div>
        <div class="mb-3">
            <label>Penerbit</label>
            <input type="text" name="penerbit" class="form-control" value="{{ $buku->penerbit }}">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold"><i class="bi bi-calendar3 me-1 text-primary"></i>Tahun Terbit</label>
            <div class="input-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-light border-0"><i class="bi bi-calendar2-week text-primary"></i></span>
                <input type="number" name="tahun_terbit" class="form-control border-0 bg-light fw-semibold text-center" min="1900" max="{{ date('Y') }}" inputmode="numeric" pattern="[0-9]*" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" placeholder="Contoh: 2024">
            </div>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="{{ $buku->stok }}">
        </div>
        <!-- Tambahkan input lainnya seperti penulis, stok, dll mirip form tambah -->
        <div class="mb-3">
            <label>Cover Baru (Kosongkan jika tidak ganti)</label>
            <input type="file" name="gambar" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success">Update Buku</button>
    </form>
</div>
@endsection