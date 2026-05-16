@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
        <h3 class="fw-bold">Data Transaksi Peminjaman</h3>
        <a href="/peminjaman/tambah" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-circle"></i> Tambah Transaksi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible border-0 shadow-sm fade show mb-4" role="alert" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body p-3">
            <form action="/peminjaman" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="cari" class="form-control border-start-0 ps-0" 
                               placeholder="Cari nama peminjam atau judul buku..." value="{{ request('cari') }}">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted small fw-bold">Status</span>
                        <select name="status" class="form-select">
                            <option value="">-- Semua Status --</option>
                            <option value="reservasi" {{ request('status') == 'reservasi' ? 'selected' : '' }}>Reservasi</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-dark w-100 rounded-pill fw-bold">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    @if(request('cari') || request('status'))
                        <a href="/peminjaman" class="btn btn-sm btn-outline-secondary w-100 rounded-pill">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Peminjam</th>
                            <th>Judul Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo / Batas</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($semuaPeminjaman as $p)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">{{ $p->user->name }}</td>
                            <td class="text-secondary fw-semibold">{{ $p->buku->judul }}</td>
                            <td>
                                @if($p->status == 'reservasi')
                                    <small class="text-muted">Mulai: {{ \Carbon\Carbon::parse($p->tgl_reservasi)->format('d/m/Y') }}</small>
                                @else
                                    {{ $p->tgl_pinjam ? \Carbon\Carbon::parse($p->tgl_pinjam)->format('d/m/Y') : '-' }}
                                @endif
                            </td>
                            <td>
                                @if($p->status == 'reservasi')
                                    <span class="text-danger small fw-bold">Batas Ambil: {{ \Carbon\Carbon::parse($p->batas_ambil)->format('H:i') }}</span>
                                @else
                                    {{ $p->tgl_kembali_plan ? \Carbon\Carbon::parse($p->tgl_kembali_plan)->format('d/m/Y') : '-' }}
                                @endif
                            </td>
                            <td class="pe-4">
                                {{-- Desain warna badge dinamis agar variatif sesuai status transaksi --}}
                                @if($p->status == 'reservasi')
                                    <span class="badge bg-info text-dark px-2 py-1 text-uppercase" style="font-size: 11px;">Reservasi</span>
                                @elseif($p->status == 'dipinjam')
                                    <span class="badge bg-warning text-dark px-2 py-1 text-uppercase" style="font-size: 11px;">Dipinjam</span>
                                @elseif($p->status == 'selesai')
                                    <span class="badge bg-success px-2 py-1 text-uppercase" style="font-size: 11px;">Selesai</span>
                                @else
                                    <span class="badge bg-danger px-2 py-1 text-uppercase" style="font-size: 11px;">Batal</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-cart-x d-block mb-2 fs-2"></i>
                                Data transaksi tidak ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection