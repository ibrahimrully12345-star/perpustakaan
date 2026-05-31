@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
        <h3 class="fw-bold">Riwayat Pengembalian Buku</h3>
    </div>

    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body p-3">
            <form action="/pengembalian" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="cari" class="form-control border-start-0 ps-0" 
                               placeholder="Cari nama peminjam atau judul buku..." value="{{ request('cari') }}">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted small fw-bold">Status Denda</span>
                        <select name="denda_filter" class="form-select">
                            <option value="">-- Semua Riwayat --</option>
                            <option value="berdenda" {{ request('denda_filter') == 'berdenda' ? 'selected' : '' }}>Hanya Bermasalah (Ada Denda)</option>
                            <option value="bebas" {{ request('denda_filter') == 'bebas' ? 'selected' : '' }}>Tepat Waktu (Tanpa Denda)</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-dark w-100 rounded-pill fw-bold">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    @if(request('cari') || request('denda_filter'))
                        <a href="/pengembalian" class="btn btn-sm btn-outline-secondary w-100 rounded-pill">
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
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Denda</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatSelesai as $index => $r)
                        <tr>
                            <td class="ps-4">{{ $index + 1 }}</td>
                            <td class="fw-bold text-dark">{{ $r->user->name }}</td>
                            <td>{{ $r->buku->judul }}</td>
                            <td>{{ $r->tgl_pinjam ? \Carbon\Carbon::parse($r->tgl_pinjam)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $r->updated_at ? \Carbon\Carbon::parse($r->updated_at)->format('d/m/Y') : '-' }}</td>
                            <td>
                                {{-- FIX: Mengubah syarat dan membungkus dengan fungsi abs() otomatis anti-minus --}}
                                @if($r->denda != 0)
                                    <span class="badge bg-danger px-2 py-1">Rp {{ number_format(abs($r->denda), 0, ',', '.') }}</span>
                                @else
                                    <span class="badge bg-success px-2 py-1">Tidak Ada</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                <span class="badge bg-secondary px-3 py-1 text-uppercase">{{ $r->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-journal-check d-block mb-2 fs-3"></i>
                                Riwayat pengembalian tidak ditemukan.
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