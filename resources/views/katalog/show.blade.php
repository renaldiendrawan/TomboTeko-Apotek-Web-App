@extends('layouts.admin')

@section('title', 'Detail Obat')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Obat</h1>
        <a href="{{ route('katalog.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-body p-5">
            <div class="row">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    @if($obat->gambar)
                        <img src="{{ asset('storage/' . $obat->gambar) }}" alt="{{ $obat->nm_obat }}"
                            class="img-fluid rounded shadow-sm" style="max-height: 300px; object-fit: contain;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm"
                            style="height: 300px;">
                            <i class="fas fa-prescription-bottle-alt fa-7x text-gray-300"></i>
                        </div>
                    @endif
                </div>

                <div class="col-md-8">
                    <h2 class="font-weight-bold text-gray-900 mb-1">{{ $obat->nm_obat }}</h2>
                    <span class="badge badge-primary mb-3 px-3 py-2" style="font-size: 0.9rem;">{{ $obat->jenis }}</span>

                    <h3 class="text-success font-weight-bold mb-4">
                        Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}
                        <small class="text-muted" style="font-size: 1rem;">/ {{ $obat->satuan }}</small>
                    </h3>

                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <th width="35%" class="text-gray-600 px-0"><i class="fas fa-barcode mr-2"></i> Kode Obat
                                </th>
                                <td class="px-0 font-weight-bold">: {{ $obat->kd_obat }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 px-0"><i class="fas fa-boxes mr-2"></i> Stok Tersedia</th>
                                <td class="px-0">:
                                    @if($obat->stok > 10)
                                        <span class="badge badge-success px-2 py-1">{{ $obat->stok }} {{ $obat->satuan }}</span>
                                    @else
                                        <span class="badge badge-warning px-2 py-1">{{ $obat->stok }} {{ $obat->satuan }} (Sisa
                                            Sedikit)</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 px-0"><i class="fas fa-calendar-times mr-2"></i> Tanggal
                                    Kedaluwarsa</th>
                                <td class="px-0">: <span
                                        class="text-danger font-weight-bold">{{ $obat->tanggal_kedaluwarsa ? \Carbon\Carbon::parse($obat->tanggal_kedaluwarsa)->translatedFormat('d F Y') : '-' }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 pt-4 border-top">
                        <button type="button" class="btn btn-primary btn-lg shadow-sm" data-toggle="modal"
                            data-target="#infoPembelianModal">
                            <i class="fas fa-shopping-basket mr-2"></i> Info Pembelian
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="infoPembelianModal" tabindex="-1" role="dialog" aria-labelledby="infoPembelianModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="infoPembelianModalLabel">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Pembelian
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="fas fa-store fa-4x text-gray-300 mb-4"></i>
                    <h5 class="font-weight-bold text-gray-800 mb-3">Pemesanan Langsung di Apotek</h5>
                    <p class="mb-1 text-gray-600">Pemesanan dan pembelian obat ini dapat dilakukan langsung di kasir
                        <strong>TomboTeko Apotek</strong>.</p>
                    <p class="mb-4 text-gray-600">Silakan sebutkan atau tunjukkan Kode Obat berikut kepada petugas kami:</p>

                    <div class="bg-light py-3 px-4 rounded border border-primary d-inline-block shadow-sm">
                        <h2 class="font-weight-bold text-primary mb-0" style="letter-spacing: 2px;">{{ $obat->kd_obat }}
                        </h2>
                    </div>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection