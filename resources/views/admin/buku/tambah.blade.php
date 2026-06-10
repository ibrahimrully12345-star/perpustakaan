@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="fw-bold mb-4">Tambah Buku Baru</h3>
    <div class="card shadow-sm col-md-8">
        <div class="card-body">
            <form action="/buku" method="POST" enctype="multipart/form-data" 
            onsubmit="return confirm('Apakah Anda yakin untuk menambahkan buku berjudul \'' + this.judul.value + '\' penulis \'' + this.penulis.value + '\' ke dalam data?')">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Judul Buku</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="penulis" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-calendar3 me-1 text-primary"></i>Tahun Terbit</label>
                        <div class="input-group shadow-sm rounded-3 overflow-hidden">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-calendar2-week text-primary"></i></span>
                            <input type="number" name="tahun_terbit" class="form-control border-0 bg-light fw-semibold text-center no-number-arrows" min="1900" max="{{ date('Y') }}" inputmode="numeric" pattern="[0-9]*" placeholder="Contoh: 2024" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cover Buku (Gambar)</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Buku</button>
                <a href="/katalog-admin" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection