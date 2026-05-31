@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h3 class="fw-bold mb-4 pt-3">Tambah Transaksi Peminjaman</h3>

    <div class="card shadow-sm border-0 col-md-6" style="border-radius: 12px;">
        <div class="card-body p-4">
            {{-- Alert Error jika validasi Backend gagal --}}
            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm mb-3" style="border-radius: 8px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            <form action="/peminjaman/simpan" method="POST" onsubmit="return confirm('Apakah Anda yakin untuk menambahkan transaksi peminjaman ini ke dalam data?')">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Peminjam</label>
                    <select name="user_id" class="form-select" required>
                        <option value="" selected disabled>-- Pilih Peminjam --</option>
                        @foreach($users as $u)
                            @if($u->role == 'peminjam')
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Buku</label>
                    <select name="buku_id" class="form-select" required>
                        <option value="" selected disabled>-- Pilih Judul Buku --</option>
                        @foreach($bukus as $b)
                            <option value="{{ $b->id }}">{{ $b->judul }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Pinjam</label>
                        {{-- FIX: Ditambahkan id dan onchange mengunci batas tanggal minimum kanan --}}
                        <input type="date" name="tanggal_pinjam" id="tgl_pinjam" class="form-control" 
                               value="{{ date('Y-m-d') }}" onchange="document.getElementById('tgl_kembali').min = this.value" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Batas Kembali</label>
                        {{-- FIX: Ditambahkan id dan min bawaan hari ini --}}
                        <input type="date" name="tanggal_kembali" id="tgl_kembali" class="form-control" min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 8px;">Simpan Transaksi</button>
                    <a href="/peminjaman" class="btn btn-light border px-4" style="border-radius: 8px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection