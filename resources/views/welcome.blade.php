<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MeditraX - Solusi Kesehatan Anda</title>

    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        .hero-bg {
            background: linear-gradient(rgba(78, 115, 223, 0.85), rgba(34, 74, 190, 0.85)), url('https://images.unsplash.com/photo-1576602976047-174e57a47881?q=80&w=1920&auto=format&fit=crop') no-repeat center center;
            background-size: cover;
            color: white;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
        }

        .obat-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease-in-out;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
</head>

<body id="page-top" class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-3">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ url('/') }}">
                <i class="fas fa-pills mr-2"></i>MeditraX
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto align-items-center">
                    @if (Route::has('login'))
                        @auth
                            @if(auth()->user()->role == 'pelanggan')

                                <li class="nav-item mr-3 mb-2 mb-lg-0 d-flex align-items-center">
                                    <a href="{{ route('katalog.index') }}" class="nav-link text-dark font-weight-bold">
                                        <i class="fas fa-pills mr-1 text-primary"></i> Katalog Obat
                                    </a>
                                </li>

                                <li class="nav-item mr-4 mb-2 mb-lg-0 d-flex align-items-center">
                                    <a href="{{ url('/profil') }}" class="nav-link text-dark font-weight-bold">
                                        <i class="fas fa-user-circle mr-1 text-primary"></i> Profil Saya
                                    </a>
                                </li>

                                <li class="nav-item d-flex align-items-center mr-3 mb-2 mb-lg-0">
                                    <span class="fw-bold text-dark">Halo, {{ auth()->user()->name }}</span>
                                </li>
                                <li class="nav-item">
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">
                                            <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                                        </button>
                                    </form>
                                </li>

                            @else
                                <li class="nav-item">
                                    <a href="{{ url('/dashboard') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                        <i class="fas fa-tachometer-alt mr-1"></i> Masuk ke Dashboard
                                    </a>
                                </li>
                            @endif

                        @else
                            <li class="nav-item mr-3 mb-2 mb-lg-0">
                                <a href="{{ route('login') }}" class="nav-link text-gray-800 font-weight-bold">
                                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                                </a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                        Daftar Pelanggan Baru
                                    </a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-bg text-center py-5 mb-5 shadow">
        <div class="container py-5">
            <h1 class="display-4 font-weight-bold mb-4 text-white">Selamat Datang di MeditraX</h1>
            <p class="lead mb-5 text-gray-200">Sistem Informasi Penjualan Obat Terpercaya. Temukan kebutuhan kesehatan
                Anda dengan mudah, cepat, dan aman.</p>
            <a href="#katalog-preview"
                class="btn btn-light btn-lg rounded-pill px-5 text-primary font-weight-bold shadow-sm">
                Lihat Obat Tersedia <i class="fas fa-arrow-down ml-2"></i>
            </a>
        </div>
    </header>

    <section id="katalog-preview" class="container mb-5">
        <div class="text-center mb-5">
            <h2 class="h3 font-weight-bold text-gray-800">Sekilas Obat Tersedia</h2>
            <p class="text-muted">Katalog singkat dari obat-obatan yang kami sediakan untuk Anda.</p>
        </div>

        <div class="row">
            @forelse($obat_sekilas as $obat)
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 obat-card">
                        <div class="card-body text-center p-4">
                            <div class="mb-3"
                                style="height: 150px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if($obat->gambar)
                                    <img src="{{ asset('storage/' . $obat->gambar) }}" alt="{{ $obat->nm_obat }}"
                                        class="img-fluid rounded" style="max-height: 100%; object-fit: contain;">
                                @else
                                    <i class="fas fa-prescription-bottle-alt fa-5x text-gray-300"></i>
                                @endif
                            </div>

                            <h5 class="font-weight-bold text-gray-900 mb-1">{{ $obat->nm_obat }}</h5>
                            <span class="badge badge-info mb-3">{{ $obat->jenis }}</span>

                            <h4 class="font-weight-bold text-success mb-3">
                                Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}
                            </h4>
                            <p class="text-sm text-muted mb-4">
                                <i class="fas fa-box-open mr-1"></i> Stok tersedia: {{ $obat->stok }} {{ $obat->satuan }}
                            </p>

                            @auth
                                <a href="{{ route('katalog.index') }}" class="btn btn-outline-primary btn-block rounded-pill">
                                    <i class="fas fa-shopping-cart mr-1"></i> Beli Sekarang
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-block rounded-pill">
                                    <i class="fas fa-sign-in-alt mr-1"></i> Lihat Selengkapnya
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Saat ini belum ada data obat yang tersedia atau
                        stok sedang kosong.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body py-3">
                        Apakah Anda mencari obat lain?
                        @auth
                            <a href="{{ route('katalog.index') }}" class="text-white font-weight-bold"
                                style="text-decoration: underline;">Lihat Semua Katalog Kami</a>
                        @else
                            <a href="{{ route('register') }}" class="text-white font-weight-bold"
                                style="text-decoration: underline;">Daftar Sekarang</a> untuk melihat katalog lengkapnya!
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white text-center py-4 shadow-sm mt-5">
        <div class="container">
            <span class="text-muted font-weight-bold">Copyright &copy; MeditraX {{ date('Y') }}</span>
        </div>
    </footer>

    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
</body>

</html>