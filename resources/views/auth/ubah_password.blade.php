@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0 pb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-2"><i class="bi bi-key-fill text-warning"></i> Ubah Password</h3>
            <p class="text-muted mb-4">Demi keamanan akun Anda, harap perbarui password secara berkala.</p>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <form action="/password/ubah" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Masukkan password lama" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('current_password', this)" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>

                        <hr class="text-muted my-3 opacity-25">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Password Baru</label>
                            <div class="input-group">
                                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Minimal 8 karakter" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('new_password', this)" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('new_password_confirmation', this)" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark rounded-pill py-2 fw-bold">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan Password
                            </button>
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