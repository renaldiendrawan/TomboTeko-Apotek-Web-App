@extends('layouts.admin')

@section('title', 'Histori Penjualan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-history text-primary mr-2"></i> Histori Penjualan</h1>
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-desktop mr-1"></i> Buka Kasir
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Apotek</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">No. Nota</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Diskon</th>
                        <th>Total Bayar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $trx)
                        <tr>
                            <td class="px-4 fw-bold text-primary">{{ $trx->nota }}</td>
                            <td>{{ \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d M Y, H:i') }}</td>
                            <td>
                                @if($trx->nm_pelanggan)
                                    <span class="badge bg-info text-white">{{ $trx->nm_pelanggan }}</span>
                                @else
                                    <span class="text-muted">Umum</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($trx->diskon, 0, ',', '.') }}</td>
                            <td class="font-weight-bold text-success">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('transaksi.show', $trx->id) }}" class="btn btn-sm btn-secondary shadow-sm" title="Lihat Struk">
                                    <i class="fas fa-receipt mr-1"></i> Lihat Struk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-cash-register fa-3x mb-3 opacity-50"></i>
                                <p>Belum ada histori penjualan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-end mt-2">
            {{ $transaksis->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection