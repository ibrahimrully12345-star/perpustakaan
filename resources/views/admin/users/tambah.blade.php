@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row pt-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Tambah Pengguna Baru</h5>
                </div>
                <div class="card-body p-4">
                    <form action="/users/simpan" method="POST" onsubmit="return confirm('Apakah Anda yakin untuk menambahkan user bernama \'' + this.name.value + '\' dengan role \'' + this.role.value + '\' ke dalam sistem?')">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <input type="password" id="adminUserPassword" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('adminUserPassword', this)" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Role / Hak Akses</label>
                            <select name="role" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Role --</option>
                                <option value="peminjam">Peminjam (Anggota)</option>
                                <option value="petugas">Petugas</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4">Simpan Pengguna</button>
                            <a href="/users" class="btn btn-light px-4 border">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.classList.toggle('bi-eye', !isHidden);
        icon.classList.toggle('bi-eye-slash', isHidden);
    }
</script>