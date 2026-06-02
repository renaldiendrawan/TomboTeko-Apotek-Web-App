@extends('layouts.admin')

@section('title', 'Dashboard Apoteker (Operasional)')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-stethoscope text-primary mr-2"></i> Dashboard Operasional</h1>
    </div>

    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Jenis Obat
                                Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_jenis_obat }} Jenis</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-pills fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kuantitas Stok
                                Fisik</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($total_stok_obat, 0, ',', '.') }} Unit</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-boxes fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-3 font-weight-bold text-dark">Status Darurat Apotek</h5>
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card bg-danger text-white shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1 text-white-50">Obat Kedaluwarsa</div>
                    <div class="h5 mb-0 font-weight-bold">{{ $obat_kedaluwarsa }} Jenis</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card bg-dark text-white shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1 text-white-50">Stok Fisik Habis (Kosong)</div>
                    <div class="h5 mb-0 font-weight-bold">{{ $stok_habis }} Jenis</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card bg-warning text-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1 text-dark">Stok Menipis (<= 20)</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $stok_menipis }} Jenis</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary">Obat Terlaris</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th class="text-center">Terjual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($obat_terjual as $ot)
                                        <tr>
                                            <td class="font-weight-bold"><a
                                                    href="{{ route('obat.show', $ot->id) }}">{{ $ot->nm_obat }}</a></td>
                                            <td class="text-center text-success">{{ $ot->total_terjual }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Belum ada penjualan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow mb-4 border-bottom-danger">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-danger">Perlu Restock</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th class="text-center">Sisa Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($obat_kritis as $kritis)
                                        <tr>
                                            <td><a href="{{ route('obat.show', $kritis->id) }}"
                                                    class="text-primary">{{ $kritis->nm_obat }}</a></td>
                                            <td
                                                class="text-center font-weight-bold {{ $kritis->stok <= 0 ? 'text-danger' : 'text-warning' }}">
                                                {{ $kritis->stok }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection