@extends('layouts.admin')

@section('title', 'Detail Obat')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Informasi Detail Obat</h1>
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Lengkap: {{ $obat->nm_obat }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    @if($obat->gambar)
                        <img src="{{ asset('storage/' . $obat->gambar) }}" class="img-fluid rounded shadow-sm"
                            style="max-height: 250px;">
                    @else
                        <i class="fas fa-prescription-bottle-alt fa-7x text-gray-300"></i>
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-striped">
                        <tr>
                            <th width="30%">Kode Obat</th>
                            <td>{{ $obat->kd_obat }}</td>
                        </tr>
                        <tr>
                            <th>Nama Obat</th>
                            <td class="font-weight-bold">{{ $obat->nm_obat }}</td>
                        </tr>
                        <tr>
                            <th>Jenis & Satuan</th>
                            <td>{{ $obat->jenis }} / {{ $obat->satuan }}</td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td class="text-success font-weight-bold">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Sisa Stok</th>
                            <td>
                                <span class="badge {{ $obat->stok > 0 ? 'badge-success' : 'badge-danger' }} px-2 py-1">
                                    {{ $obat->stok }} {{ $obat->satuan }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Kedaluwarsa</th>
                            <td class="text-danger font-weight-bold">
                                {{ $obat->tanggal_kedaluwarsa ? \Carbon\Carbon::parse($obat->tanggal_kedaluwarsa)->translatedFormat('d F Y') : '-' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection