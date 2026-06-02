@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil</h1>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4 border-bottom-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-edit mr-2"></i>Edit Informasi Anda
                    </h6>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('profil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Nama Lengkap</label>
                                <input type="text" class="form-control bg-light" value="{{ $user->name }}" readonly>
                                <small class="text-muted">Nama tidak dapat diubah.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Email</label>
                                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                                <small class="text-muted">Email tidak dapat diubah.</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label font-weight-bold">Nomor Telepon <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="telepon" id="telepon"
                                class="form-control @error('telepon') is-invalid @enderror"
                                value="{{ old('telepon', $pelanggan->telepon ?? '') }}" required>
                            @error('telepon')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="alamat" class="form-label font-weight-bold">Alamat <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="alamat" id="alamat"
                                    class="form-control @error('alamat') is-invalid @enderror"
                                    value="{{ old('alamat', $pelanggan->alamat ?? '') }}" required>
                                @error('alamat')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="kota" class="form-label font-weight-bold">Kota <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="kota" id="kota"
                                    class="form-control @error('kota') is-invalid @enderror"
                                    value="{{ old('kota', $pelanggan->kota ?? '') }}" required>
                                <!-- Tambahkan required di sini -->
                                @error('kota')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-body text-center py-5">
                    <img class="img-profile rounded-circle mb-3" src="{{ asset('sbadmin/img/undraw_profile.svg') }}"
                        style="width: 100px; height: 100px;">
                    <h5 class="font-weight-bold text-gray-900 mb-0">{{ $user->name }}</h5>
                    <p class="text-muted mb-2">{{ $pelanggan->kd_pelanggan ?? 'Belum ada kode' }}</p>
                    <span class="badge badge-success px-3 py-2">Pelanggan Aktif</span>
                </div>
            </div>
        </div>
    </div>
@endsection