@extends('layouts.admin')

@section('title', 'Dashboard Admin (Manajemen)')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-home text-primary mr-2"></i> Dashboard Admin</h1>
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

    <h5 class="mb-3 font-weight-bold text-dark">Laporan Finansial</h5>
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card bg-primary text-white shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1 text-white-50">Total Transaksi</div>
                    <div class="h5 mb-0 font-weight-bold">{{ number_format($total_transaksi, 0, ',', '.') }} Nota</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card bg-info text-white shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1 text-white-50">Total Pendapatan (Kotor)</div>
                    <div class="h5 mb-0 font-weight-bold">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card bg-success text-white shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1 text-white-50">Total Laba Bersih</div>
                    <div class="h5 mb-0 font-weight-bold">Rp {{ number_format($laba_bersih, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-line mr-2"></i>Daftar Obat yang Terjual
                (Terlaris)</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">No</th>
                            <th>Nama Obat</th>
                            <th>Jenis</th>
                            <th class="text-center">Total Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($obat_terjual as $index => $ot)
                            <tr>
                                <td class="px-4">{{ $index + 1 }}</td>
                                <td class="font-weight-bold"><a href="{{ route('obat.show', $ot->id) }}">{{ $ot->nm_obat }}</a>
                                </td>
                                <td><span class="badge badge-info">{{ $ot->jenis }}</span></td>
                                <td class="text-center text-success font-weight-bold">{{ $ot->total_terjual }} Unit</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada data penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection