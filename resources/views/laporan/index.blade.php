@extends('layouts.admin')
@section('title', 'Laporan & Dashboard - Sistem Apotek')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-chart-pie text-primary mr-2"></i> Laporan</h1>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pendapatan Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                {{ number_format($penjualan_hari_ini, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">{{ $jumlah_transaksi_hari_ini }} Transaksi</small>
                        </div>
                        <div class="col-auto"><i class="fas fa-calendar-day fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pendapatan Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                {{ number_format($penjualan_bulan_ini, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Obat Bermasalah</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $obat_kadaluarsa }} <span
                                    style="font-size: 14px; font-weight: normal;">Kedaluwarsa</span></div>
                            <small class="text-danger">{{ $obat_hampir_kadaluarsa }} hampir expired</small>
                        </div>
                        <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stok Menipis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stok_rendah }} Item</div>
                            <small class="text-muted">Sisa stok &le; 10</small>
                        </div>
                        <div class="col-auto"><i class="fas fa-boxes fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body bg-light rounded">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0"><i class="fas fa-chart-line fa-3x text-primary"></i></div>
                        <div class="flex-grow-1 ml-4">
                            <h5 class="card-title font-weight-bold mb-1 text-dark">Laporan Penjualan (Pemasukan)</h5>
                            <p class="card-text text-muted small mb-2">Analisis pendapatan dari pelanggan beserta grafik
                                tren.</p>
                            <a href="{{ route('laporan.penjualan') }}" class="btn btn-sm btn-primary px-3 shadow-sm">Buka
                                Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body bg-light rounded">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0"><i class="fas fa-shopping-cart fa-3x text-danger"></i></div>
                        <div class="flex-grow-1 ml-4">
                            <h5 class="card-title font-weight-bold mb-1 text-dark">Laporan Pembelian (Pengeluaran)</h5>
                            <p class="card-text text-muted small mb-2">Riwayat belanja/restock obat ke supplier beserta
                                grafiknya.</p>
                            <a href="{{ route('laporan.pembelian') }}" class="btn btn-sm btn-danger px-3 shadow-sm">Buka
                                Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow h-100 border-bottom-primary">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-star mr-1"></i> 5 Obat Paling Laris
                        Dibeli Pelanggan</h6>

                    <form action="{{ route('laporan.index') }}" method="GET" class="m-0">
                        <select name="periode_laris"
                            class="form-control form-control-sm shadow-sm font-weight-bold text-primary border-primary"
                            onchange="this.form.submit()"
                            style="border-radius: 20px; cursor: pointer; background-color: #f8f9fc;">
                            <option value="all" {{ (isset($periode_laris) && $periode_laris == 'all') ? 'selected' : '' }}>
                                Sepanjang Waktu</option>
                            <option value="hari_ini" {{ (isset($periode_laris) && $periode_laris == 'hari_ini') ? 'selected' : '' }}>Hari Ini</option>
                            <option value="bulan_ini" {{ (isset($periode_laris) && $periode_laris == 'bulan_ini') ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="tahun_ini" {{ (isset($periode_laris) && $periode_laris == 'tahun_ini') ? 'selected' : '' }}>Tahun Ini</option>
                        </select>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Kode Obat</th>
                                    <th>Nama Obat</th>
                                    <th class="text-center">Total Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($top_products as $top)
                                    <tr>
                                        <td class="px-4 font-weight-bold text-secondary">{{ $top->kd_obat }}</td>
                                        <td class="font-weight-bold text-dark">{{ $top->nm_obat }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-light text-success border border-success px-3 py-2">
                                                <i class="fas fa-arrow-trend-up mr-1"></i> {{ $top->total_terjual }}
                                                {{ $top->satuan }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada data penjualan pada
                                            periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection