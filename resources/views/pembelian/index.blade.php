@extends('layouts.admin')
@section('title', 'Riwayat Pembelian')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-truck-loading text-primary mr-2"></i> Histori Pembelian (Restock)
        </h1>
        <a href="{{ route('pembelian.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-1"></i> Beli / Restock Obat Baru
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger shadow-sm"><i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Riwayat Pembelian ke Supplier</h6>

            <form id="form-search" action="{{ route('pembelian.index') }}" method="GET" class="form-inline">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" id="searchInput" class="form-control"
                        placeholder="Cari No. Nota / Supplier..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <div id="tabel-container">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Tanggal</th>
                                <th>No. Nota</th>
                                <th>Supplier</th>
                                <th class="text-right">Diskon</th>
                                <th class="text-right">Total Bayar</th>
                                <th class="text-center px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pembelians as $p)
                                <tr>
                                    <td class="px-4">{{ \Carbon\Carbon::parse($p->tgl_nota)->translatedFormat('d M Y') }}</td>
                                    <td class="font-weight-bold text-primary">{{ $p->nota }}</td>
                                    <td class="font-weight-bold text-dark">{{ $p->nm_suplier }}</td>
                                    <td class="text-right text-danger">Rp {{ number_format($p->diskon, 0, ',', '.') }}</td>
                                    <td class="text-right font-weight-bold text-success">Rp
                                        {{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                                    <td class="text-center px-4">
                                        <a href="{{ route('pembelian.show', $p->id) }}"
                                            class="btn btn-sm btn-info text-white shadow-sm">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-receipt fa-3x mb-3 d-block text-gray-300"></i>
                                        Belum ada data atau transaksi tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-end mt-2">{{ $pembelians->links('pagination::bootstrap-4') }}</div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            let timer;

            // Fungsi Live Search AJAX
            $('#searchInput').on('keyup', function () {
                clearTimeout(timer);

                // Beri jeda 0.5 detik setelah pengetikan berhenti agar server tidak terbebani
                timer = setTimeout(function () {
                    let url = $('#form-search').attr('action') + '?' + $('#form-search').serialize();

                    $.get(url, function (data) {
                        // Hanya mengganti isi di dalam #tabel-container tanpa reload halaman
                        $('#tabel-container').html($(data).find('#tabel-container').html());
                    });
                }, 500);
            });
        });
    </script>
@endpush