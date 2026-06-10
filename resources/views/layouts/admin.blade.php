<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard {{ ucfirst(auth()->user()->role) }} | E-Perpus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f4f1; }
        .sidebar { height: 100vh; width: 250px; position: fixed; background: #2b1506; color: white; transition: all 0.3s; z-index: 1000; }
        .main-content { margin-left: 250px; min-height: 100vh; }
        .nav-link { color: #d7ccc8; padding: 12px 20px; transition: 0.3s; }
        .nav-link:hover { color: white; background: #4e342e; border-radius: 5px; }
        .navbar-admin { background-color: #6b4934; }
        
        /* Sidebar Menu Aktif */
        .nav-link.active { 
            color: white !important; 
            background: #8d6e63 !important; 
            border-radius: 5px;
        }

        /* Styling Tambahan untuk Dropdown Profil */
        .profile-dropdown .dropdown-toggle {
            color: #ffffff;
            text-decoration: none;
            padding: 6px 15px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            transition: 0.3s;
        }
        .profile-dropdown .dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 12px;
        }
        .dropdown-item {
            padding: 10px 20px;
            transition: 0.2s;
        }
        .dropdown-item:hover {
            background-color: #f8f4f1;
            color: #2b1506;
        }
        .btn-logout-item {
            color: #dc3545;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            padding: 10px 20px;
            transition: 0.2s;
        }
        .btn-logout-item:hover {
            background-color: #fff5f5;
            color: #c91a0a;
        }

        input[type='number']::-webkit-outer-spin-button,
        input[type='number']::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type='number'] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
    </style>
</head>
<body>
    <div class="sidebar d-flex flex-column p-3 shadow">
        <h4 class="fw-bold mb-4 text-center mt-2"><i class="bi bi-book-half"></i> E-PERPUS</h4>
        <ul class="nav nav-pills flex-column mb-auto">
            
            {{-- MENU JIKA ROLE PEMINJAM --}}
            @if(Auth::user()->role == 'peminjam')
                <li class="nav-item">
                    <a href="/katalog" class="nav-link {{ request()->is('katalog*') ? 'active' : '' }}"><i class="bi bi-grid me-2"></i> Katalog Buku</a>
                </li>
                <li>
                    <a href="/pinjaman-saya" class="nav-link {{ request()->is('pinjaman-saya*') ? 'active' : '' }}"><i class="bi bi-journal-bookmark me-2"></i> Pinjaman Saya</a>
                </li>
            
            {{-- MENU JIKA ROLE ADMIN ATAU PETUGAS --}}
            @else
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                </li>
                <li>
                    <a href="/katalog-admin" class="nav-link {{ request()->is('katalog-admin*') ? 'active' : '' }}"><i class="bi bi-book me-2"></i> Data Buku</a>
                </li>
                @if(Auth::user()->role == 'admin')
                <li>
                    <a href="/users" class="nav-link {{ request()->is('users*') ? 'active' : '' }}"><i class="bi bi-people me-2"></i> Data Users</a>
                </li>
                @endif
                <li>
                    <a href="/peminjaman" class="nav-link {{ request()->is('peminjaman*') ? 'active' : '' }}"><i class="bi bi-cart-check me-2"></i> Peminjaman</a>
                </li>
                <li>
                    <a href="/pengembalian" class="nav-link {{ request()->is('pengembalian*') ? 'active' : '' }}"><i class="bi bi-arrow-left-right me-2"></i> Pengembalian</a>
                </li>
                <li>
                    <a href="/laporan" class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}"><i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan</a>
                </li>
            @endif

        </ul>
        <div class="small text-center opacity-50 mb-2">© 2026 E-Perpus Digital</div>
    </div>

    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-dark navbar-admin shadow-sm mb-4 px-4 py-3">
            <div class="container-fluid">
                <span class="navbar-text text-white d-none d-md-inline fw-bold">
                    @if(Auth::user()->role == 'peminjam')
                        Ruang Baca Virtual | E-Perpus
                    @else
                        Manajemen Perpustakaan Digital
                    @endif
                </span>
                
                <div class="ms-auto profile-dropdown dropdown">
    <a class="dropdown-toggle d-flex align-items-center small" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle fs-5 me-2"></i>
        <span>{{ Auth::user()->name }}</span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="dropdownMenuLink">
        <div class="px-3 py-2 border-bottom mb-1">
            <div class="fw-bold small text-dark text-truncate" style="max-width: 160px;">{{ Auth::user()->name }}</div>
            <span class="badge bg-light text-secondary border small mt-1 text-capitalize" style="font-size: 11px;">
                <i class="bi bi-shield-lock me-1"></i>{{ Auth::user()->role }}
            </span>
        </div>
        
        <li>
            <a class="dropdown-item small text-muted" href="/pengaturan-akun">
             <i class="bi bi-gear me-2"></i>Pengaturan Akun
            </a>
        </li>

        <li>
            <a class="dropdown-item small text-dark fw-semibold" href="/password/ubah">
                <i class="bi bi-key me-2 text-warning"></i>Ubah Password
            </a>
        </li>
        
        <li><hr class="dropdown-divider my-1"></li>
        
        <li>
            <form action="/logout" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn-logout-item small fw-bold d-flex align-items-center">
                    <i class="bi bi-box-arrow-right me-2 fs-6"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</div>
            </div>
        </nav>

        <div class="px-4">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>