@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0 pb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-2"><i class="bi bi-gear-fill text-secondary"></i> Pengaturan Akun</h3>
            <p class="text-muted mb-4">Perbarui informasi profil dasar akun Anda di sini.</p>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
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
                    <form action="/pengaturan-akun" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">NAMA LENGKAP</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">ALAMAT EMAIL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                            </div>
                            <div class="form-text text-muted small">Pastikan email aktif agar akun Anda tetap aman.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark rounded-pill py-2 fw-bold">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection