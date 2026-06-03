<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>TomboTeko - @yield('title', 'Dashboard')</title>

    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    @stack('css')
</head>

<body id="page-top">
    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center"
                href="{{ auth()->user()->role == 'pelanggan' ? route('katalog.index') : url('/dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="sidebar-brand-text mx-3">TomboTeko</div>
            </a>

            <hr class="sidebar-divider my-0">

            @if(auth()->user()->role == 'admin' || auth()->user()->role == 'apoteker')

                <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/dashboard') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <hr class="sidebar-divider">
                <div class="sidebar-heading">Operasional Apotek</div>

                <li class="nav-item {{ request()->is('obat*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/obat') }}">
                        <i class="fas fa-fw fa-box"></i>
                        <span>Manajemen Obat</span>
                    </a>
                </li>

                @if(auth()->user()->role == 'apoteker')
                    <li class="nav-item {{ request()->is('transaksi*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/transaksi') }}">
                            <i class="fas fa-fw fa-shopping-cart"></i>
                            <span>Transaksi Penjualan</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role == 'admin')

                    <li class="nav-item {{ request()->is('pembelian*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('pembelian.index') }}">
                            <i class="fas fa-fw fa-truck-loading"></i>
                            <span>Transaksi Pembelian</span>
                        </a>
                    </li>
                    <hr class="sidebar-divider">
                    <div class="sidebar-heading">Master Data & Laporan</div>

                    <li class="nav-item {{ request()->is('laporan*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/laporan') }}">
                            <i class="fas fa-fw fa-chart-area"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('apoteker*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/apoteker') }}">
                            <i class="fas fa-fw fa-user-md"></i>
                            <span>Data Apoteker</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is('pelanggan*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/pelanggan') }}">
                            <i class="fas fa-fw fa-users"></i>
                            <span>Data Pelanggan</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('supplier*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/supplier') }}">
                            <i class="fas fa-fw fa-truck"></i>
                            <span>Data Supplier</span>
                        </a>
                    </li>
                @endif

            @endif

            @if(auth()->user()->role == 'pelanggan')

                <li class="nav-item {{ request()->routeIs('katalog.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('katalog.index') }}">
                        <i class="fas fa-fw fa-store"></i>
                        <span>Katalog Obat</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('profil.index') }}">
                        <i class="fas fa-fw fa-user"></i>
                        <span>Profil Saya</span>
                    </a>
                </li>

            @endif

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name ?? 'User' }}
                                    ({{ ucfirst(Auth::user()->role) }})</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('sbadmin/img/undraw_profile.svg') }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; TomboTeko {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin ingin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" di bawah jika Anda siap untuk mengakhiri sesi Anda saat ini.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('sbadmin/js/sb-admin-2.min.js') }}"></script>

    @stack('js')
</body>

</html>