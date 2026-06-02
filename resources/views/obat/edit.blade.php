@extends('layouts.admin')

@section('title', 'Edit Obat - Sistem Apotek')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit text-primary mr-2"></i> Edit Data Obat
            </h1>
            <small class="text-muted"><a href="{{ route('obat.index') }}">Daftar Obat</a> / Edit</small>
        </div>
        <a href="{{ route('obat.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white font-weight-bold text-primary">
                    <i class="fas fa-prescription-bottle mr-1"></i> Form Edit Data Obat
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('obat.update', $obat->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 form-group">
                            <label for="nama_obat" class="form-label">Nama Obat <span class="text-danger">*</span></label>
                            <input type="text" name="nama_obat"
                                class="form-control @error('nama_obat') is-invalid @enderror" id="nama_obat"
                                value="{{ old('nama_obat', $obat->nm_obat) }}" required>
                            @error('nama_obat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-group">
                            <label for="kd_suplier" class="form-label">Pemasok / Supplier <span
                                    class="text-danger">*</span></label>
                            <select name="kd_suplier" class="form-control @error('kd_suplier') is-invalid @enderror"
                                id="kd_suplier" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('kd_suplier', $obat->kd_suplier) == $supplier->id)>
                                        {{ $supplier->nm_suplier ?? $supplier->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kd_suplier')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-group">
                            <label for="gambar" class="form-label">Gambar Obat <small class="text-muted">(Biarkan kosong
                                    jika tidak ingin mengubah)</small></label>
                            @if(isset($obat) && $obat->gambar)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $obat->gambar) }}" alt="Gambar {{ $obat->nm_obat }}"
                                        class="img-thumbnail" width="100">
                                </div>
                            @endif
                            <input type="file" name="gambar" id="gambar"
                                class="form-control-file @error('gambar') is-invalid @enderror" accept="image/*">
                            @error('gambar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3 form-group">
                            <label for="kategori" class="form-label">Jenis Obat <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-control @error('kategori') is-invalid @enderror"
                                id="kategori" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Tablet" @selected(strtolower(old('kategori', $obat->jenis ?? '')) == 'tablet')>
                                    Tablet</option>
                                <option value="Kapsul" @selected(strtolower(old('kategori', $obat->jenis ?? '')) == 'kapsul')>
                                    Kapsul</option>
                                <option value="Sirup" @selected(strtolower(old('kategori', $obat->jenis ?? '')) == 'sirup')>
                                    Sirup</option>
                                <option value="Injeksi" @selected(strtolower(old('kategori', $obat->jenis ?? '')) == 'injeksi')>Injeksi</option>
                                <option value="Salep" @selected(strtolower(old('kategori', $obat->jenis ?? '')) == 'salep')>
                                    Salep</option>
                                <option value="Tetes" @selected(strtolower(old('kategori', $obat->jenis ?? '')) == 'tetes')>
                                    Tetes</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3 form-group">
                                <label for="harga_beli" class="form-label">Harga Beli (Rp) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="harga_beli"
                                    class="form-control @error('harga_beli') is-invalid @enderror" id="harga_beli"
                                    value="{{ old('harga_beli', $obat->harga_beli) }}" required>
                            </div>

                            <div class="col-md-4 mb-3 form-group">
                                <label for="harga" class="form-label">Harga Jual (Rp) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                                    id="harga" value="{{ old('harga', $obat->harga_jual) }}" required>
                            </div>

                            <div class="col-md-4 mb-3 form-group">
                                <label for="stok" class="form-label">Stok Fisik <span class="text-danger">*</span></label>
                                <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror"
                                    id="stok" value="{{ old('stok', $obat->stok) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3 form-group">
                                <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                                <select name="satuan" class="form-control @error('satuan') is-invalid @enderror" id="satuan"
                                    required>
                                    <option value="">-- Pilih Satuan --</option>
                                    <option value="Strip" @selected(old('satuan', $obat->satuan) == 'Strip')>Strip</option>
                                    <option value="Botol" @selected(old('satuan', $obat->satuan) == 'Botol')>Botol</option>
                                    <option value="Box" @selected(old('satuan', $obat->satuan) == 'Box')>Box</option>
                                    <option value="Tube" @selected(old('satuan', $obat->satuan) == 'Tube')>Tube</option>
                                    <option value="Ampul" @selected(old('satuan', $obat->satuan) == 'Ampul')>Ampul</option>
                                    <option value="Pcs" @selected(old('satuan', $obat->satuan) == 'Pcs')>Pcs</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3 form-group">
                                <label for="tanggal_kedaluwarsa" class="form-label">Tanggal Kedaluwarsa <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="tanggal_kedaluwarsa"
                                    class="form-control @error('tanggal_kedaluwarsa') is-invalid @enderror"
                                    id="tanggal_kedaluwarsa"
                                    value="{{ old('tanggal_kedaluwarsa', isset($obat->tanggal_kedaluwarsa) ? \Carbon\Carbon::parse($obat->tanggal_kedaluwarsa)->format('Y-m-d') : '') }}"
                                    required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="fas fa-save mr-1"></i> Perbarui Obat
                            </button>
                            <a href="{{ route('obat.index') }}" class="btn btn-secondary shadow-sm">
                                <i class="fas fa-times mr-1"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-white font-weight-bold text-info">
                    <i class="fas fa-info-circle mr-1"></i> Informasi Sistem
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Kode Obat:</dt>
                        <dd class="col-sm-7 font-weight-bold text-primary">{{ $obat->kd_obat ?? 'N/A' }}</dd>

                        <dt class="col-sm-5">Dibuat:</dt>
                        <dd class="col-sm-7">
                            {{ isset($obat->created_at) ? \Carbon\Carbon::parse($obat->created_at)->format('d M Y') : 'N/A' }}
                        </dd>

                        <dt class="col-sm-5">Diperbarui:</dt>
                        <dd class="col-sm-7">
                            {{ isset($obat->updated_at) ? \Carbon\Carbon::parse($obat->updated_at)->format('d M Y') : 'N/A' }}
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow border-bottom-danger">
                <div class="card-header bg-danger text-white font-weight-bold">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                </div>
                <div class="card-body text-center">
                    <p class="small text-muted mb-3">Tindakan menghapus data bersifat permanen dan tidak dapat dibatalkan.
                    </p>
                    <form method="POST" action="{{ route('obat.destroy', $obat->id) }}"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat ini secara permanen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 font-weight-bold">
                            <i class="fas fa-trash-alt mr-1"></i> Hapus Obat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection