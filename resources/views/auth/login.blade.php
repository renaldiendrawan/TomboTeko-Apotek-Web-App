@extends('layouts.app')

@section('title', 'Login - TomboTeko')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"
                            style="background: url('https://images.unsplash.com/photo-1585435557343-3b092031a831?q=80&w=600&auto=format&fit=crop'); background-position: center; background-size: cover;">
                        </div>

                        <div class="col-lg-6">
                            <div class="p-5">

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <a href="{{ url('/') }}"
                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-sm">
                                        <i class="fas fa-arrow-left mr-1"></i> Beranda
                                    </a>
                                    <h4 class="font-weight-bold text-primary mb-0" style="letter-spacing: 1px;">
                                        <i class="fas fa-capsules mr-1"></i> TomboTeko
                                    </h4>
                                </div>

                                <div class="text-center mb-4">
                                    <h1 class="h5 text-gray-800 font-weight-bold">Selamat Datang Kembali!</h1>
                                    <p class="text-muted small">Silakan login untuk mengakses akun Anda</p>
                                </div>

                                <form class="user" method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                                    @csrf

                                    <div class="form-group">
                                        <input type="email" name="email" id="email"
                                            class="form-control form-control-user @error('email') is-invalid @enderror"
                                            placeholder="Masukkan Email..." value="{{ old('email') }}" required
                                            autofocus>

                                        @error('email')
                                            @php
                                                $pesanError = $message === 'These credentials do not match our records.'
                                                    ? 'Email atau password yang Anda masukkan salah.'
                                                    : $message;
                                            @endphp
                                            <span class="invalid-feedback d-block" role="alert"
                                                style="margin-left: 1rem; font-size: 80%;"><strong>{{ $pesanError }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="form-group" style="position: relative;">
                                        <input type="password" name="password" id="password"
                                            class="form-control form-control-user @error('password') is-invalid @enderror"
                                            placeholder="Password" required>
                                        <i class="fas fa-eye" id="togglePassword"
                                            style="position: absolute; right: 25px; top: 18px; cursor: pointer; color: #b7b9cc;"></i>

                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert"
                                                style="margin-left: 1rem; font-size: 80%;"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small" style="margin-left: 0.5rem;">
                                            <input type="checkbox" class="custom-control-input" id="customCheck"
                                                name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-primary btn-user btn-block shadow-sm font-weight-bold mt-4">
                                        Login
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ route('register') }}">Belum punya akun? Daftar sebagai
                                        Pelanggan!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Script untuk Toggle Password Visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        // Validasi Kolom Kosong Sebelum Submit
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            let email = document.getElementById('email').value.trim();
            let pass = password.value;

            if (!email || !pass) {
                e.preventDefault(); // Hentikan proses submit jika ada yang kosong
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap!',
                    text: 'Harap isi Email dan Password Anda terlebih dahulu.',
                    confirmButtonColor: '#4e73df'
                });
            }
        });

        // Tangkap Error Login dari Laravel dan Tampilkan Pop-up
        @if($errors->has('email') || session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal!',
                text: 'Email atau password yang Anda masukkan salah. Silakan coba lagi.',
                confirmButtonColor: '#e74a3b'
            });
        @endif

        // Tangkap Session Success
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#1cc88a'
            });
        @endif
    </script>
@endsection