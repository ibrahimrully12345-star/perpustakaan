<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | E-Perpus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: rgba(243, 228, 216, 0.95);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-register {
            border-radius: 20px;
            border: none;
            width: 100%;
            max-width: 420px; /* Diperkecil sedikit dari 450px agar proporsional */
            overflow: hidden;
            background: rgba(173, 139, 106, 0.95);
            backdrop-filter: blur(10px);
        }
        .register-header {
            background-color: #2b1506;
            color: white;
            padding: 20px; /* Diperkecil agar menghemat ruang atas */
            text-align: center;
        }
        .form-control {
            border-radius: 10px;
            padding: 10px 15px; /* Padding input dibuat lebih ramping */
            border: 1px solid #ddd;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(43, 21, 6, 0.2);
            border-color: #2b1506;
        }
        .btn-register {
            background: linear-gradient(to right, #3a180a, #5f4818);
            border: none;
            color: white;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-register:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            color: white;
        }
        .text-dark-brown {
            color: #2b1506;
        }
    </style>
</head>
<body>

<div class="card card-register shadow-lg mx-3">
    <div class="register-header">
        <i class="bi bi-person-plus fs-2"></i> <h5 class="fw-bold mt-1 mb-0">DAFTAR AKUN</h5>
        <p class="small opacity-75 mb-0" style="font-size: 12px;">Bergabunglah menjadi anggota E-Perpus</p>
    </div>
    <div class="card-body p-4 py-3"> @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-3 py-2 small" style="border-radius: 10px;">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="/register" method="POST">
            @csrf
            <div class="mb-2">
                <label class="form-label small fw-bold text-dark-brown mb-1">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 py-1"><i class="bi bi-person text-muted"></i></span>
                    <input type="text" name="name" class="form-control border-start-0" placeholder="Nama lengkap Anda" value="{{ old('name') }}" required autofocus>
                </div>
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-bold text-dark-brown mb-1">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 py-1"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" placeholder="nama@email.com" value="{{ old('email') }}" required>
                </div>
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-bold text-dark-brown mb-1">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 py-1"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" placeholder="Minimal 6 karakter" required>
                </div>
            </div>
            
            <div class="mb-2">
                <label class="form-label small fw-bold text-dark-brown mb-1">Ulangi Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 py-1"><i class="bi bi-shield-lock text-muted"></i></span>
                    <input type="password" name="password_confirmation" class="form-control border-start-0" placeholder="Ulangi password" required>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-register rounded-pill py-2 shadow-sm">Daftar Sekarang</button>
            </div>
        </form>

        <div class="text-center mt-3 small text-dark-brown">
            Sudah punya akun? <a href="/login" class="fw-bold text-decoration-none" style="color: #2b1506; border-bottom: 2px solid #2b1506;">Masuk di sini</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>