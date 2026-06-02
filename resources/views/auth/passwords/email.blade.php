@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block" style="background: url('https://images.unsplash.com/photo-1555252333-9f8e92e65df9?q=80&w=600&auto=format&fit=crop'); background-position: center; background-size: cover;"></div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-2">Lupa Password Anda?</h1>
                                <p class="mb-4">Kami mengerti, banyak hal yang terjadi. Masukkan alamat email Anda di bawah ini dan kami akan mengirimkan tautan untuk mengatur ulang password Anda!</p>
                            </div>
                            
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form class="user" method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-user @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Masukkan Alamat Email...">
                                    @error('email')
                                        <span class="invalid-feedback d-block" style="margin-left: 1rem; font-size: 80%;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Reset Password
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ route('register') }}">Buat Akun Baru!</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="{{ route('login') }}">Sudah punya akun? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection