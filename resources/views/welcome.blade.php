<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Perpus Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body, html { height: 100%; margin: 0; font-family: sans-serif; }
        .bg-image {
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=2000');
            height: 100%; 
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
            color: white;
        }
        .navbar { background: transparent !important; }
        .hero-text {
            text-align: center;
            position: absolute;
            top: 35%; left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
        }
        .hero-text h1 { font-size: 3.5rem; font-weight: bold; background: rgba(0,0,0,0.4); display: inline-block; padding: 10px 20px; border-radius: 10px; }
        .hero-text p { font-size: 1.2rem; background: rgba(0,0,0,0.4); padding: 5px 15px; border-radius: 5px; margin-top: 10px; }
        .footer-info {
            position: absolute;
            bottom: 0; width: 100%;
            background: rgba(0, 0, 0, 0.8);
            padding: 20px 0;
            font-size: 0.9rem;
        }

        /* FITUR BARU: Efek Hover Cokelat Klasik pada Tombol */
        .btn-custom-outline {
            color: #f8f4f1;
            border: 2px solid #f8f4f1;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-custom-outline:hover {
            background-color: #2b1506 !important; /* Warna cokelat tua khas E-Perpus */
            border-color: #2b1506 !important;
            color: #d7ccc8 !important; /* Warna krem lembut agar teks tetap jelas */
            transform: translateY(-2px); /* Efek sedikit terangkat saat disorot */
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>

    <div class="bg-image">
        <nav class="navbar navbar-expand-lg navbar-dark pt-3">
            <div class="container">
                <a class="navbar-brand fw-bold" href="#"><i class="bi bi-book"></i> E-PERPUS</a>
                <div class="ms-auto">
                    <a href="/login" class="btn btn-custom-outline rounded-pill px-4 me-2">Login</a>
                    <a href="/register" class="btn btn-custom-outline rounded-pill px-4 me-2">Daftar</a>
                </div>
            </div>
        </nav>

        <div class="hero-text">
            <h1>Selamat Datang di E-Perpus</h1>
            <p>Cari buku favoritmu, reservasi online, dan ambil maksimal dalam waktu 2 jam.</p>
        </div>

        <div class="footer-info">
            <div class="container">
                <div class="row text-center text-md-start">
                    <div class="col-md-4 mb-3">
                        <h6><i class="bi bi-clock"></i> Jam Operasional</h6>
                        <div class="row small">
                            <div class="col-6">Senin - Jumat:</div>
                            <div class="col-6">09:00 - 20:00 WIB</div>
                            <div class="col-6">Sabtu - Minggu:</div>
                            <div class="col-6">09:00 - 15:00 WIB</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6>Hubungi Kami</h6>
                        <div class="small">
                            <i class="bi bi-whatsapp"></i> +62 822-2986-7580<br>
                            <i class="bi bi-telephone"></i> +62 821-3231-3588
                        </div>
                
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6>Lokasi Kami:</h6>
                        <p class="small"><i class="bi bi-geo-alt"></i> Jalan Sukarno Gg. 4 Pengandaian Sumenep</p>
                    </div>
                </div>
                <hr class="mt-2">
                <p class="text-center mb-0 small">© 2026 E-Perpus Digital</p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>