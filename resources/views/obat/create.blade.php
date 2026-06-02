@extends('layouts.admin')

@section('title', 'Tambah Obat - Sistem Apotek')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus-circle text-primary mr-2"></i> Tambah Obat Baru
            </h1>
            <small class="text-muted"><a href="{{ route('obat.index') }}">Daftar Obat</a> / Tambah Baru</small>
        </div>
        <a href="{{ route('obat.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-prescription-bottle"></i> Form Data Obat
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('obat.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_obat" class="form-label">Nama Obat <span class="text-danger">*</span></label>
                            <input type="text" name="nama_obat"
                                class="form-control @error('nama_obat') is-invalid @enderror" id="nama_obat"
                                placeholder="Contoh: Paracetamol 500mg" value="{{ old('nama_obat') }}" required>
                            @error('nama_obat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kd_suplier" class="form-label">Pemasok / Supplier <span
                                    class="text-danger">*</span></label>
                            <select name="kd_suplier" class="form-control @error('kd_suplier') is-invalid @enderror"
                                id="kd_suplier" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('kd_suplier') == $supplier->id)>
                                        {{ $supplier->nm_suplier ?? $supplier->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kd_suplier')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar Obat (Opsional)</label>
                            <input type="file" name="gambar" id="gambar"
                                class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                            @error('gambar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Jenis/Kategori <span
                                    class="text-danger">*</span></label>
                            <select name="kategori" class="form-control @error('kategori') is-invalid @enderror"
                                id="kategori" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Tablet" @selected(old('kategori') == 'Tablet')>Tablet</option>
                                <option value="Kapsul" @selected(old('kategori') == 'Kapsul')>Kapsul</option>
                                <option value="Sirup" @selected(old('kategori') == 'Sirup')>Sirup</option>
                                <option value="Injeksi" @selected(old('kategori') == 'Injeksi')>Injeksi</option>
                                <option value="Salep" @selected(old('kategori') == 'Salep')>Salep</option>
                                <option value="Cair" @selected(old('kategori') == 'Cair')>Cair</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="harga_beli" class="form-label">Harga Beli (Rp) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="harga_beli"
                                    class="form-control @error('harga_beli') is-invalid @enderror" id="harga_beli"
                                    placeholder="Misal: 40000" value="{{ old('harga_beli') }}" required>
                                @error('harga_beli')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="harga" class="form-label">Harga Jual (Rp) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                                    id="harga" placeholder="Misal: 50000" value="{{ old('harga') }}" required>
                                @error('harga')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="stok_awal" class="form-label">Stok Fisik <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="stok_awal"
                                    class="form-control @error('stok_awal') is-invalid @enderror" id="stok_awal"
                                    placeholder="Misal: 100" value="{{ old('stok_awal') }}" required>
                                @error('stok_awal')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                                <select name="satuan" class="form-control @error('satuan') is-invalid @enderror" id="satuan"
                                    required>
                                    <option value="">-- Pilih Satuan --</option>
                                    <option value="Strip" @selected(old('satuan') == 'Strip')>Strip</option>
                                    <option value="Botol" @selected(old('satuan') == 'Botol')>Botol</option>
                                    <option value="Box" @selected(old('satuan') == 'Box')>Box</option>
                                    <option value="Tube" @selected(old('satuan') == 'Tube')>Tube</option>
                                    <option value="Ampul" @selected(old('satuan') == 'Ampul')>Ampul</option>
                                    <option value="Pcs" @selected(old('satuan') == 'Pcs')>Pcs</option>
                                </select>
                                @error('satuan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tanggal_kedaluwarsa" class="form-label">Tanggal Kedaluwarsa <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="tanggal_kedaluwarsa"
                                    class="form-control @error('tanggal_kedaluwarsa') is-invalid @enderror"
                                    id="tanggal_kedaluwarsa" value="{{ old('tanggal_kedaluwarsa') }}" required>
                                @error('tanggal_kedaluwarsa')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Obat
                            </button>
                            <a href="{{ route('obat.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panduan Pengisian -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> Panduan
                </div>
                <div class="card-body">
                    <h6 class="card-title">Tips Pengisian</h6>
                    <ul class="small">
                        <li><strong>Nama Obat:</strong> Gunakan nama yang spesifik dan dosis</li>
                        <li><strong>Kategori:</strong> Pilih jenis obat sesuai bentuknya</li>
                        <li><strong>Harga:</strong> Masukkan harga jual per unit</li>
                        <li><strong>Stok Awal:</strong> Jumlah persediaan obat saat ini</li>
                        <li><strong>Satuan:</strong> Unit minimal penjualan</li>
                    </ul>

                    <hr>

                    <h6 class="card-title mt-3">Kategori Obat</h6>
                    <small>
                        <span class="badge bg-info">Tablet</span>
                        <span class="badge bg-info">Kapsul</span>
                        <span class="badge bg-info">Sirup</span>
                        <span class="badge bg-info">Injeksi</span>
                        <span class="badge bg-info">Salep</span>
                        <span class="badge bg-info">Tetes</span>
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection