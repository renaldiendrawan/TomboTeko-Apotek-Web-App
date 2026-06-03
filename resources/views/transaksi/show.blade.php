@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4 d-print-none">
    <h1 class="h3 mb-0 text-gray-800">Detail Transaksi</h1>
    <div>
        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary shadow-sm mr-2">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn btn-primary shadow-sm">
            <i class="fas fa-print mr-1"></i> Cetak Struk
        </button>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm mb-4" id="struk-kasir">
            <div class="card-body p-4">
                
                <div class="text-center mb-4">
                    <h4 class="font-weight-bold text-dark mb-1">TomboTeko Apotek</h4>
                    <p class="small text-muted mb-0">Jl. Kesehatan No. 123, Kab. Nganjuk</p>
                    <p class="small text-muted mb-0">Telp: 0812-3456-7890</p>
                    <hr style="border-top: 2px dashed #bbb;">
                </div>

                <div class="row small mb-3">
                    <div class="col-6">
                        <div>Nota: <strong>{{ $penjualan->nota }}</strong></div>
                        <div>Kasir: <strong>{{ auth()->user()->name }}</strong></div>
                    </div>
                    <div class="col-6 text-right">
                        <div>{{ \Carbon\Carbon::parse($penjualan->created_at)->format('d/m/Y H:i') }}</div>
                        <div>Plg: <strong>{{ $penjualan->nm_pelanggan ?? 'Umum' }}</strong></div>
                    </div>
                </div>

                <hr style="border-top: 2px dashed #bbb;">

                <div class="mb-3">
                    <table class="table table-borderless table-sm small mb-0">
                        @php $subtotal = 0; @endphp
                        @foreach($details as $d)
                            @php 
                                $total_item = $d->harga * $d->jumlah;
                                $subtotal += $total_item;
                            @endphp
                            <tr>
                                <td colspan="2" class="pb-0"><strong>{{ $d->nm_obat }}</strong></td>
                            </tr>
                            <tr>
                                <td class="pt-0 text-muted">{{ $d->jumlah }} {{ $d->satuan }} x {{ number_format($d->harga, 0, ',', '.') }}</td>
                                <td class="pt-0 text-right">{{ number_format($total_item, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <hr style="border-top: 2px dashed #bbb;">

                <div class="row small font-weight-bold">
                    <div class="col-6 text-right">Subtotal :</div>
                    <div class="col-6 text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                    
                    <div class="col-6 text-right">Diskon :</div>
                    <div class="col-6 text-right text-danger">- Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</div>
                </div>
                
                <div class="row mt-2 pt-2 border-top">
                    <div class="col-6 text-right h5 font-weight-bold mb-0">TOTAL :</div>
                    <div class="col-6 text-right h5 font-weight-bold mb-0">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</div>
                </div>

                <div class="text-center mt-5">
                    <p class="small text-muted mb-0">Terima kasih atas kunjungan Anda.</p>
                    <p class="small text-muted">Semoga lekas sembuh!</p>
                </div>
                
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #struk-kasir, #struk-kasir * {
            visibility: visible;
        }
        #struk-kasir {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>
@endsection