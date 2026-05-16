@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
        <h3 class="fw-bold">Data Pengguna</h3>
        <a href="/users/tambah" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-person-plus"></i> Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible border-0 shadow-sm fade show mb-4" role="alert" style="border-radius: 10px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body p-3">
            <form action="/users" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="cari" class="form-control border-start-0 ps-0" 
                               placeholder="Cari nama atau email pengguna..." value="{{ request('cari') }}">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted small fw-bold">Role</span>
                        <select name="role" class="form-select">
                            <option value="">-- Semua Role --</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="peminjam" {{ request('role') == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-dark w-100 rounded-pill fw-bold">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    @if(request('cari') || request('role'))
                        <a href="/users" class="btn btn-sm btn-outline-secondary w-100 rounded-pill">
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
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($semuaUser as $user)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {{-- Warna dinamis badge berdasarkan tingkatan role --}}
                                @if($user->role == 'admin')
                                    <span class="badge bg-danger px-2 py-1 text-uppercase" style="font-size: 11px;">{{ $user->role }}</span>
                                @elseif($user->role == 'petugas')
                                    <span class="badge bg-warning text-dark px-2 py-1 text-uppercase" style="font-size: 11px;">{{ $user->role }}</span>
                                @else
                                    <span class="badge bg-success px-2 py-1 text-uppercase" style="font-size: 11px;">{{ $user->role }}</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group gap-1">
                                    <a href="/users/edit/{{ $user->id }}" class="btn btn-sm btn-warning" style="border-radius: 6px;">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="/users/{{ $user->id }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 6px;">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-people d-block mb-2 fs-2"></i>
                                Pengguna tidak ditemukan atau data kosong.
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