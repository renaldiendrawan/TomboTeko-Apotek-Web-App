@extends('layouts.admin')
@section('title', 'Riwayat Belanja')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-history text-primary mr-2"></i>Riwayat Belanja Saya</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="20%">No. Nota</th>
                            <th width="20%">Total Bayar (Rp)</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayats as $index => $trx)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($trx->tgl_nota)->translatedFormat('d M Y') }}
                                </td>
                                <td class="text-center font-weight-bold">{{ $trx->nota }}</td>
                                <td class="text-right">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('riwayat.show', $trx->id) }}" class="btn btn-sm btn-info shadow-sm">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Anda belum memiliki riwayat transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection