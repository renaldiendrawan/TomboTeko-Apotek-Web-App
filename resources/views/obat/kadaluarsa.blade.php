@extends('layouts.admin')

@section('title', 'Obat Kedaluwarsa')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Obat Kedaluwarsa</h1>
        <a href="{{ route('obat.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="alert alert-danger shadow-sm border-left-danger" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> <strong>Perhatian!</strong> Daftar di bawah ini adalah obat-obat yang
        tanggal kedaluwarsanya telah melewati batas hari ini. Segera musnahkan fisik obat dan hapus data dari sistem!
    </div>

    <div class="card shadow mb-4 border-bottom-danger">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-danger mb-3"><i class="fas fa-trash-alt mr-2"></i>Daftar Obat Untuk Dihapus
            </h6>

            <form id="form-filter" action="{{ route('obat.kadaluarsa') }}" method="GET">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="search" id="searchInput" class="form-control"
                            placeholder="Cari nama atau kode obat..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="jenis" id="filterJenis" class="form-control">
                            <option value="">-- Semua Jenis --</option>
                            @foreach($list_jenis as $j)
                                <option value="{{ $j }}" {{ (isset($filter_jenis) && $filter_jenis == $j) ? 'selected' : '' }}>
                                    {{ $j }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="sort" id="sortData" class="form-control">
                            <option value="">-- Urutkan --</option>
                            <option value="abjad_asc" {{ (isset($sort) && $sort == 'abjad_asc') ? 'selected' : '' }}>Abjad
                                (A-Z)</option>
                            <option value="abjad_desc" {{ (isset($sort) && $sort == 'abjad_desc') ? 'selected' : '' }}>Abjad
                                (Z-A)</option>
                            <option value="exp_asc" {{ (isset($sort) && $sort == 'exp_asc') ? 'selected' : '' }}>Tgl. Expired
                                (Terlama)</option>
                            <option value="exp_desc" {{ (isset($sort) && $sort == 'exp_desc') ? 'selected' : '' }}>Tgl.
                                Expired (Terbaru)</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('obat.kadaluarsa') }}" class="btn btn-secondary w-100"><i
                                class="fas fa-sync-alt mr-1"></i> Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div id="tabel-container">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-danger text-dark">
                            <tr>
                                <th class="px-4">Kode Obat</th>
                                <th>Nama Obat</th>
                                <th>Jenis</th>
                                <th>Sisa Stok</th>
                                <th>Tgl. Kedaluwarsa</th>
                                <th class="text-center">Aksi (Eksekusi)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($obats as $obat)
                                <tr>
                                    <td class="px-4 font-weight-bold">{{ $obat->kd_obat }}</td>
                                    <td>{{ $obat->nm_obat }}</td>
                                    <td><span class="badge badge-info">{{ $obat->jenis }}</span></td>
                                    <td><span class="badge badge-warning text-dark">{{ $obat->stok }} {{ $obat->satuan }}</span>
                                    </td>
                                    <td class="text-danger font-weight-bold">
                                        {{ \Carbon\Carbon::parse($obat->tanggal_kedaluwarsa)->translatedFormat('d F Y') }}
                                        <br><small class="text-muted">(Sudah Lewat)</small>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm shadow-sm btn-delete-kadaluarsa"
                                            data-id="{{ $obat->id }}">
                                            <i class="fas fa-trash-alt mr-1"></i> Hapus Permanen
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <h5 class="text-gray-800">Aman!</h5>
                                        <p class="text-muted mb-0">Tidak ada data obat yang kedaluwarsa saat ini atau sesuai
                                            pencarian Anda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-end mt-2">
                    {{ $obats->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteKadaluarsaModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteKadaluarsaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title font-weight-bold" id="deleteKadaluarsaModalLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Pemusnahan Data Obat
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="fas fa-biohazard fa-4x text-danger mb-4"></i>
                    <h5 class="text-gray-900 font-weight-bold mb-3">Musnahkan Data Ini?</h5>
                    <p class="text-gray-600 mb-0">Anda akan menghapus data obat yang telah kedaluwarsa secara permanen.
                        Pastikan fisik obat juga telah dimusnahkan.</p>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill" data-dismiss="modal">Batal</button>

                    <form id="formDeleteKadaluarsa" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 rounded-pill">Ya, Musnahkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function () {

            // --- FUNGSI AJAX LIVE SEARCH & FILTER ---
            let timer;

            function fetchTabel() {
                let url = $('#form-filter').attr('action') + '?' + $('#form-filter').serialize();
                $.get(url, function (data) {
                    let htmlBaru = $(data).find('#tabel-container').html();
                    $('#tabel-container').html(htmlBaru);
                });
            }

            // Live Search (Ketik otomatis cari)
            $('#searchInput').on('keyup', function () {
                clearTimeout(timer);
                timer = setTimeout(function () {
                    fetchTabel();
                }, 500);
            });

            // Trigger pencarian langsung saat dropdown diubah
            $('#filterJenis, #sortData').on('change', function () {
                fetchTabel();
            });

            // --- FUNGSI MODAL HAPUS ---
            // Script untuk menangkap klik tombol hapus di halaman kedaluwarsa
            $(document).on('click', '.btn-delete-kadaluarsa', function () {
                // Ambil ID dari tombol yang diklik
                let id = $(this).data('id');

                // Susun URL untuk hapus
                let url = "{{ url('obat') }}/" + id;

                // Masukkan URL tersebut ke action form di dalam modal
                $('#formDeleteKadaluarsa').attr('action', url);

                // Munculkan modalnya
                $('#deleteKadaluarsaModal').modal('show');
            });
        });
    </script>
@endpush