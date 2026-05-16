@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
        <h3 class="fw-bold" style="color: #2b1506;">Riwayat Pengembalian Buku</h3>
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
                                @if($r->denda > 0)
                                    <span class="badge bg-danger px-2 py-1">Rp {{ number_format($r->denda, 0, ',', '.') }}</span>
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
                                Belum ada riwayat pengembalian.
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