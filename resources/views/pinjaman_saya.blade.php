@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0 pb-5">
    <h3 class="fw-bold text-dark mb-4">Buku Saya</h3>

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Buku</th>
                            <th>Status</th>
                            <th>Keterangan Waktu</th>
                            <th class="pe-4">Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pinjaman as $p)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $p->buku->judul }}</span><br>
                                <small class="text-muted">{{ $p->buku->penulis }}</small>
                            </td>
                            <td>
                                @if($p->status == 'reservasi')
                                    <span class="badge bg-info text-dark">RESERVASI</span>
                                @elseif($p->status == 'dipinjam')
                                    <span class="badge bg-warning text-dark">DIPINJAM</span>
                                @elseif($p->status == 'selesai')
                                    <span class="badge bg-success">SELESAI</span>
                                @else
                                    <span class="badge bg-danger">{{ strtoupper($p->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($p->status == 'reservasi')
                                    <small class="text-danger fw-bold">Ambil sebelum: {{ \Carbon\Carbon::parse($p->batas_ambil)->format('H:i') }}</small>
                                @elseif($p->status == 'dipinjam')
                                    <small class="text-primary fw-bold">Batas Kembali: {{ \Carbon\Carbon::parse($p->tgl_kembali_plan)->format('d/m/Y') }}</small>
                                @elseif($p->status == 'selesai')
                                    <small class="text-muted">Dikembalikan: {{ \Carbon\Carbon::parse($p->updated_at)->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td class="pe-4">
                                @if($p->denda > 0)
                                    <span class="text-danger fw-bold">Rp {{ number_format($p->denda, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-success fw-bold">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x d-block mb-2 fs-3"></i>
                                Belum ada riwayat peminjaman.
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