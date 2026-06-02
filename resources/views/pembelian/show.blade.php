@extends('layouts.admin')
@section('title', 'Detail Pembelian')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rincian Transaksi</h1>
        <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow border-left-info h-100">
                <div class="card-header bg-white font-weight-bold text-info py-3">
                    <i class="fas fa-file-invoice mr-2"></i> Informasi Nota
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" width="40%">No. Nota</td>
                            <td>: <strong class="text-dark">{{ $pembelian->nota }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal</td>
                            <td>: <strong
                                    class="text-dark">{{ \Carbon\Carbon::parse($pembelian->tgl_nota)->translatedFormat('d F Y') }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <hr class="my-2">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Supplier</td>
                            <td>: <strong class="text-primary">{{ $pembelian->nm_suplier }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Telepon</td>
                            <td>: {{ $pembelian->telepon }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted align-top">Alamat</td>
                            <td>: {{ $pembelian->alamat }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-white font-weight-bold text-primary py-3">
                    <i class="fas fa-box-open mr-2"></i> Daftar Obat yang Dibeli
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Nama Obat</th>
                                    <th class="text-right">Harga Beli</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right px-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $subtotal_kotor = 0; @endphp
                                @foreach($details as $d)
                                    @php 
                                                                        $subtotal_baris = $d->harga * $d->jumlah;
                                        $subtotal_kotor += $subtotal_baris;
                                    @endphp
                                    <tr>
                                        <td class="px-4">
                                            <div class="font-weight-bold text-dark">{{ $d->nm_obat }}</div>
                                            <small class="text-muted">{{ $d->kode_obat }}</small>

                                                                               </td>

                                                                           <td class="text-right">Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                                            <td class="text-center font-weight-bold">{{ $d->jumlah }} <small class="font-weight-normal text-muted">{{ $d->satuan }}</small></td>
                                            <td class="text-right px-4 font-weight-bold text-dark">Rp {{ number_format($subtotal_baris, 0, ',', '.') }}</td>
                                        </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">

                                                                   <tr>
                                    <td colspan="3" class="text-right text-muted pt-3">Total Harga (Kotor) :</td>
                                    <td class="text-right px-4 font-weight-bold pt-3">Rp {{ number_format($subtotal_kotor, 0, ',', '.') }}</td>
                                </tr>

                                                                   <tr>
                                    <td colspan="3" class="text-right text-danger pb-3 border-top-0">Potongan / Diskon :</td>
                                    <td class="text
                                       -right px-4 text-danger font-weight-bold pb-3 border-top-0">- Rp {{ number_format($pembelian->diskon, 0, ',', '.') }}</td>
                                </tr>

                                                                    <tr class="bg-white">
                                    <td colspan="3" class="text-right font-weight-bold text-uppercase py-3 h5 mb-0 border-bottom-0">Total Bersih (Dibayar) :</td>
                                    <td class="text-right px-4 font-weight-bold text-success py-3 h5 mb-0 border-bottom-0">Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection