@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h3 class="fw-bold mb-4 pt-3">Edit Data Pengguna</h3>

    <div class="card shadow-sm border-0 col-md-6">
        <div class="card-body p-4">
            <form action="/users/{{ $user->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin sudah sesuai untuk mengupdate data pengguna \'' + '{{ $user->name }}' + '\' ini?')">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Email (Privasi - Tidak dapat diubah)</label>
                    <!-- Gunakan readonly agar admin hanya bisa melihat tanpa mengubah -->
                    <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                    <small class="text-muted">Email digunakan sebagai identitas login permanen.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Role / Hak Akses</label>
                    <select name="role" class="form-select" required>
                        <option value="peminjam" {{ $user->role == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                        <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <!-- Input Password dihapus total dari sini -->

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    <a href="/users" class="btn btn-light px-4 border">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection