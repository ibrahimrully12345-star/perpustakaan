@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" style="color: #2b1506;">Manajemen Data Buku</h3>
        @if(in_array(Auth::user()->role, ['admin', 'petugas']))
        <a href="/buku/tambah" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-plus-lg"></i> Tambah Buku Baru
        </a>
        @endif
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <form action="/katalog-admin" method="GET" class="d-flex">
                <input type="text" name="cari" class="form-control me-2 shadow-sm" placeholder="Cari buku..." value="{{ request('cari') }}">
                <button type="submit" class="btn btn-dark shadow-sm px-3">Cari</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Cover</th>
                            <th>Judul & Penulis</th>
                            <th>Penerbit/Tahun</th>
                            <th>Stok</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($semuaBuku as $buku)
                        <tr>
                            <td class="ps-4">
                                <img src="{{ asset('img/' . $buku->gambar) }}" width="50" class="rounded shadow-sm" onerror="this.src='https://placehold.co/50x70?text=No+Img'">
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $buku->judul }}</div>
                                <small class="text-muted">{{ $buku->penulis }}</small>
                            </td>
                            <td>{{ $buku->penerbit }} ({{ $buku->tahun_terbit }})</td>
                            <td>
                                <span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $buku->stok }}
                                </span>
                            </td>
                            <td class="text-center">
    @if(in_array(Auth::user()->role, ['admin', 'petugas']))
    <div class="d-flex justify-content-center gap-1">
        <a href="/buku/edit/{{ $buku->id }}" class="btn btn-warning btn-sm text-dark px-2 py-1" title="Edit Buku">
            <i class="bi bi-pencil-fill"></i>Edit
        </a>

        <form action="/buku/{{ $buku->id }}" method="POST" onsubmit="return confirm('Yakin hapus buku ini?')" class="m-0">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm px-2 py-1" title="Hapus Buku">
                <i class="bi bi-trash-fill"></i>Hapus
            </button>
        </form>
    </div>
    @else
    <span class="text-muted small">Tidak tersedia</span>
    @endif
</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection