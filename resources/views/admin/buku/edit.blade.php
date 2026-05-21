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
            <label>Tahun Terbit</label>
            <input type="number" name="tahun_terbit" class="form-control" value="{{ $buku->tahun_terbit }}">
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