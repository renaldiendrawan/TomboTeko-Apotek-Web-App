@extends('layouts.admin')
@section('title', isset($apoteker) ? 'Edit Apoteker' : 'Tambah Apoteker')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas {{ isset($apoteker) ? 'fa-edit' : 'fa-plus-circle' }} text-primary mr-2"></i>
            {{ isset($apoteker) ? 'Edit Data Apoteker' : 'Pendaftaran Apoteker Baru' }}
        </h1>
        <a href="{{ route('apoteker.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-body p-4">
            <form action="{{ isset($apoteker) ? route('apoteker.update', $apoteker->id) : route('apoteker.store') }}"
                method="POST">
                @csrf
                @if(isset($apoteker)) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Kode Apoteker <span class="text-danger">*</span></label>
                        <input type="text" name="kd_apoteker"
                            class="form-control @error('kd_apoteker') is-invalid @enderror"
                            value="{{ old('kd_apoteker', $apoteker->kd_apoteker ?? $kd_apoteker ?? '') }}" readonly>
                        @error('kd_apoteker')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nm_apoteker"
                            class="form-control @error('nm_apoteker') is-invalid @enderror"
                            value="{{ old('nm_apoteker', $apoteker->nm_apoteker ?? '') }}" required>
                        @error('nm_apoteker')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Alamat Email (Untuk Login) <span
                                class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $apoteker->user->email ?? '') }}" required>
                        @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jk" class="form-control @error('jk') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('jk', $apoteker->jk ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="Perempuan" {{ old('jk', $apoteker->jk ?? '') == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                        @error('jk')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
                            value="{{ old('telepon', $apoteker->telepon ?? '') }}" required>
                        @error('telepon')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-md-12 form-group mb-4">
                        <label class="font-weight-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror"
                            required>{{ old('alamat', $apoteker->alamat ?? '') }}</textarea>
                        @error('alamat')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                    <i class="fas fa-save mr-1"></i> {{ isset($apoteker) ? 'Simpan Perubahan' : 'Daftarkan Apoteker' }}
                </button>
            </form>
        </div>
    </div>
@endsection