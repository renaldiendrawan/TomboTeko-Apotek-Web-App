@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="p-5 text-center">
                    <div class="text-center mb-4">
                        <i class="fas fa-envelope-open-text fa-3x text-info mb-3"></i>
                        <h1 class="h4 text-gray-900">Verifikasi Alamat Email Anda</h1>
                    </div>

                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            Link verifikasi baru telah dikirim ke alamat email Anda.
                        </div>
                    @endif

                    <p class="mb-4 text-gray-800">
                        Sebelum melanjutkan, silakan periksa kotak masuk email Anda untuk melihat tautan (link) verifikasi.
                    </p>
                    <p class="small text-gray-600 mb-4">
                        Jika Anda tidak menerima email tersebut, klik tombol di bawah ini untuk mengirim ulang.
                    </p>

                    <form class="user" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-info btn-user btn-block text-white">
                            Kirim Ulang Link Verifikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection