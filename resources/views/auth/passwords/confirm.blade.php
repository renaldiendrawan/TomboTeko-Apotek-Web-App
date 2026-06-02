@extends('layouts.app')

@section('title', 'Konfirmasi Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="p-5">
                    <div class="text-center">
                        <i class="fas fa-shield-alt fa-3x text-warning mb-3"></i>
                        <h1 class="h4 text-gray-900 mb-2">Konfirmasi Password</h1>
                        <p class="mb-4 text-gray-600">Harap konfirmasi password Anda sebelum melanjutkan ke halaman berikutnya. Tindakan ini demi keamanan akun Anda.</p>
                    </div>

                    <form class="user" method="POST" action="{{ route('password.confirm') }}">
                        @csrf
                        <div class="form-group">
                            <input type="password" name="password" class="form-control form-control-user @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="Masukkan Password Anda...">
                            @error('password')
                                <span class="invalid-feedback d-block" style="margin-left: 1rem; font-size: 80%;">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-warning btn-user btn-block text-gray-900 font-weight-bold">
                            Konfirmasi Password
                        </button>
                    </form>
                    <hr>
                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a class="small" href="{{ route('password.request') }}">Lupa Password Anda?</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection