<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | E-Perpus</title>
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
        .card-login {
            border-radius: 20px;
            border: none;
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            background: rgba(173, 139, 106, 0.95);
            backdrop-filter: blur(10px);
        }
        .login-header {
            background-color: #2b1506;
            color: white;
            padding: 25px; /* Sedikit diperkecil agar lebih ringkas */
            text-align: center;
        }
        .form-control {
            border-radius: 10px;
            padding: 10px 15px; /* Padding input disesuaikan */
            border: 1px solid #ddd;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(43, 21, 6, 0.2);
            border-color: #2b1506;
        }
        .btn-login {
            background: linear-gradient(to right, #3a180a, #5f4818);
            border: none;
            color: white;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            color: white;
        }
        .text-dark-brown {
            color: #2b1506;
        }
        
        /* CSS CUSTOM: Membuat garis tepi kotak checkbox jadi hitam pekat */
        .form-check-input {
            border: 2px solid #000000 !important; 
            background-color: #ffffff;
        }
        .form-check-input:checked {
            background-color: #2b1506 !important;
            border-color: #000000 !important;
        }
    </style>
</head>
<body>

<div class="card card-login shadow-lg mx-3">
    <div class="login-header">
        <i class="bi bi-book-half fs-1"></i>
        <h4 class="fw-bold mt-2 mb-0">E-PERPUS</h4>
        <p class="small opacity-75 mb-0">Silakan masuk ke akun Anda</p>
    </div>
    <div class="card-body p-4"> @if($errors->any())
            <div class="alert alert-danger py-2 small border-0 shadow-sm" style="border-radius: 10px;">
                <i class="bi bi-exclamation-circle me-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold text-dark-brown">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" placeholder="nama@email.com" required autofocus>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-dark-brown">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" placeholder="Masukkan password" required>
                </div>
            </div>

            <div class="mb-4">
                <div class="form-check text-start">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" style="cursor: pointer;">
                    <label class="form-check-label small fw-bold text-dark-brown" for="rememberMe" style="cursor: pointer; user-select: none;">
                        Remember Me
                    </label>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-login rounded-pill py-2 shadow-sm">Masuk Sekarang</button>
            </div>
        </form>

        <div class="text-center mt-4 small text-dark-brown">
            Belum punya akun? <a href="/register" class="fw-bold text-decoration-none" style="color: #2b1506; border-bottom: 2px solid #2b1506;">Daftar Akun</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>