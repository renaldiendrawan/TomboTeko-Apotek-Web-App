@extends('layouts.admin')
@section('title', 'Laporan Pembelian Obat')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-chart-line text-primary mr-2"></i> Laporan Penjualan Obat</h1>
        <div class="d-print-none">
            <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-secondary shadow-sm mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-print fa-sm text-white-50 mr-1"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <div class="card shadow mb-4 d-print-none border-left-danger">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-filter mr-1"></i> Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.pembelian') }}" method="GET" class="row align-items-end">
                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="small font-weight-bold">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="small font-weight-bold">Bulan</label>
                    <select name="bulan" class="form-control">
                        <option value="">Semua</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ request('bulan') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="small font-weight-bold">Tahun</label>
                    <select name="tahun" class="form-control">
                        <option value="">Semua</option>
                        @for($i = date('Y'); $i >= date('Y') - 3; $i--)
                            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <button type="submit" class="btn btn-danger w-100"><i class="fas fa-search mr-1"></i> Terapkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Nota Restock</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_transaksi }} Transaksi</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-truck-loading fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran Apotek
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                {{ number_format($total_pembelian, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-wallet fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-danger">Grafik Tren Pengeluaran / Pembelian</h6>
        </div>
        <div class="card-body">
            <div class="chart-area" style="height: 300px;">
                <canvas id="purchaseChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-danger">Rincian Pembelian ke Supplier</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light text-dark text-center">
                        <tr>
                            <th width="15%">Tanggal</th>
                            <th width="15%">No. Nota</th>
                            <th width="20%">Supplier</th>
                            <th width="35%">Obat yang Dibeli</th>
                            <th width="15%">Total (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembelians as $trx)
                            <tr>
                                <td class="text-center">{{ \Carbon\Carbon::parse($trx->tgl_nota)->translatedFormat('d M Y') }}
                                </td>
                                <td class="text-center font-weight-bold text-primary">
                                    <a href="{{ route('pembelian.show', $trx->id) }}" target="_blank">{{ $trx->nota }}</a>
                                </td>
                                <td>{{ $trx->nm_suplier }}</td>
                                <td>
                                    <ul class="mb-0 pl-3">
                                        @if(isset($details[$trx->id]))
                                            @foreach($details[$trx->id] as $item)
                                                <li class="small">
                                                    {{ $item->nm_obat }}
                                                    <span class="text-primary font-weight-bold">({{ $item->jumlah }}
                                                        {{ $item->satuan }})</span>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </td>
                                <td class="text-right font-weight-bold text-danger">
                                    {{ number_format($trx->total_bayar, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Tidak ada data pembelian pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-print-none">
            <div class="d-flex justify-content-end mt-2">{{ $pembelians->links('pagination::bootstrap-4') }}</div>
        </div>
    </div>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            $(document).ready(function () {
                var labels = {!! json_encode($chart_labels) !!};
                var data = {!! json_encode($chart_totals) !!};

                var ctx = document.getElementById("purchaseChart").getContext('2d');
                var purchaseChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: "Total Pengeluaran (Rp)",
                            lineTension: 0.3,
                            backgroundColor: "rgba(231, 74, 59, 0.05)",
                            borderColor: "rgba(231, 74, 59, 1)", // Merah (Danger)
                            pointRadius: 4,
                            pointBackgroundColor: "rgba(231, 74, 59, 1)",
                            pointBorderColor: "rgba(255, 255, 255, 1)",
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: "rgba(231, 74, 59, 1)",
                            pointHoverBorderColor: "rgba(255, 255, 255, 1)",
                            pointHitRadius: 10,
                            pointBorderWidth: 2,
                            data: data,
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
                        scales: {
                            x: { grid: { display: false, drawBorder: false } },
                            y: {
                                ticks: {
                                    callback: function (value, index, values) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
                                },
                                grid: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyColor: "#858796",
                                titleColor: '#6e707e',
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                padding: 15,
                                displayColors: false,
                                callbacks: {
                                    label: function (context) {
                                        return 'Pengeluaran: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>

        <style>
            @media print {
                body {
                    background-color: #fff !important;
                }

                .card {
                    border: none !important;
                    box-shadow: none !important;
                }

                .card-header {
                    border-bottom: 2px solid #000 !important;
                }

                .table-responsive {
                    overflow: visible !important;
                }

                .pagination {
                    display: none !important;
                }
            }
        </style>
    @endpush
@endsection