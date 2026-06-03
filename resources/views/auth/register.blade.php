@extends('layouts.app')

@section('title', 'Register - TomboTeko')

@section('content')
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <!-- Gambar Samping -->
                <div class="col-lg-5 d-none d-lg-block bg-register-image"
                    style="background: url('https://images.unsplash.com/photo-1576602976047-174e57a47881?q=80&w=600&auto=format&fit=crop'); background-position: center; background-size: cover;">
                </div>

                <div class="col-lg-7">
                    <div class="p-5">

                        <!-- Header: Tombol Back & Logo TomboTeko -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <h4 class="font-weight-bold text-primary mb-0" style="letter-spacing: 1px;">
                                <i class="fas fa-capsules mr-1"></i> TomboTeko
                            </h4>
                        </div>

                        <div class="text-center mb-4">
                            <h1 class="h5 text-gray-800 font-weight-bold">Buat Akun Pelanggan Baru</h1>
                            <p class="text-muted small">Silakan lengkapi data diri Anda di bawah ini</p>
                        </div>

                        <!-- Form Registrasi (ditambahkan id="registerForm" dan "novalidate") -->
                        <form class="user" method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                            @csrf

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text"
                                        class="form-control form-control-user @error('name') is-invalid @enderror"
                                        name="name" id="name" value="{{ old('name') }}" required placeholder="Nama Lengkap"
                                        autofocus>
                                    @error('name')
                                        <span class="invalid-feedback d-block"
                                            style="margin-left: 1rem; font-size: 80%;"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <input type="email"
                                        class="form-control form-control-user @error('email') is-invalid @enderror"
                                        name="email" id="email" value="{{ old('email') }}" required
                                        placeholder="Email">
                                    @error('email')
                                        <span class="invalid-feedback d-block"
                                            style="margin-left: 1rem; font-size: 80%;"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0" style="position: relative;">
                                    <input type="password"
                                        class="form-control form-control-user @error('password') is-invalid @enderror"
                                        name="password" id="password" required placeholder="Password">
                                    <i class="fas fa-eye" id="togglePassword"
                                        style="position: absolute; right: 25px; top: 18px; cursor: pointer; color: #b7b9cc;"></i>
                                    @error('password')
                                        <span class="invalid-feedback d-block"
                                            style="margin-left: 1rem; font-size: 80%;"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-sm-6" style="position: relative;">
                                    <input type="password" class="form-control form-control-user"
                                        name="password_confirmation" id="password_confirm" required
                                        placeholder="Ulangi Password">
                                    <i class="fas fa-eye" id="togglePasswordConfirm"
                                        style="position: absolute; right: 25px; top: 18px; cursor: pointer; color: #b7b9cc;"></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="text"
                                    class="form-control form-control-user @error('telepon') is-invalid @enderror"
                                    name="telepon" id="telepon" value="{{ old('telepon') }}" required
                                    placeholder="Nomor Telepon / WhatsApp">
                                @error('telepon')
                                    <span class="invalid-feedback d-block"
                                        style="margin-left: 1rem; font-size: 80%;"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-8 mb-3 mb-sm-0">
                                    <input type="text"
                                        class="form-control form-control-user @error('alamat') is-invalid @enderror"
                                        name="alamat" id="alamat" value="{{ old('alamat') }}" required placeholder="Alamat">
                                    @error('alamat')
                                        <span class="invalid-feedback d-block"
                                            style="margin-left: 1rem; font-size: 80%;"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <input type="text"
                                        class="form-control form-control-user @error('kota') is-invalid @enderror"
                                        name="kota" id="kota" value="{{ old('kota') }}" required placeholder="Kota">
                                    @error('kota')
                                        <span class="invalid-feedback d-block"
                                            style="margin-left: 1rem; font-size: 80%;"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit"
                                class="btn btn-primary btn-user btn-block mt-4 shadow-sm font-weight-bold">
                                Daftar Akun Sekarang
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="{{ route('login') }}">Sudah punya akun? Login di sini!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan Library SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toggle Password Utama
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        // Toggle Konfirmasi Password
        const togglePasswordConfirm = document.querySelector('#togglePasswordConfirm');
        const passwordConfirm = document.querySelector('#password_confirm');
        togglePasswordConfirm.addEventListener('click', function () {
            const typeConfirm = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirm.setAttribute('type', typeConfirm);
            this.classList.toggle('fa-eye-slash');
        });

        // Validasi Form Menggunakan SweetAlert2
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            let name = document.getElementById('name').value.trim();
            let email = document.getElementById('email').value.trim();
            let telp = document.getElementById('telepon').value.trim();
            let alamat = document.getElementById('alamat').value.trim();
            let kota = document.getElementById('kota').value.trim();
            let pass = password.value;
            let passConf = passwordConfirm.value;

            // 1. Cek apakah ada field yang kosong
            if (!name || !email || !pass || !passConf || !telp || !alamat || !kota) {
                e.preventDefault(); // Hentikan proses submit
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap!',
                    text: 'Harap isi semua kolom formulir sebelum mendaftar.',
                    confirmButtonColor: '#4e73df'
                });
                return;
            }

            // 2. Cek apakah password dan konfirmasi password cocok
            if (pass !== passConf) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Cocok!',
                    text: 'Konfirmasi password harus persis sama dengan password utama.',
                    confirmButtonColor: '#e74a3b'
                });
                return;
            }
        });

        // Pengecekan Error dari Backend (Laravel Validation Error)
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Registrasi Gagal!',
                text: 'Silakan periksa kembali data Anda. Email mungkin sudah terdaftar atau format tidak sesuai.',
                confirmButtonColor: '#e74a3b'
            });
        @endif

        // Pengecekan Sukses dari Backend
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Akun Anda berhasil dibuat. Silakan login untuk melanjutkan.',
                confirmButtonColor: '#1cc88a'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        @endif
    </script>
@endsection