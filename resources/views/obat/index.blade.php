@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                <div class="card-header bg-white border-bottom px-4 py-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-capsules text-primary mr-2"></i> Daftar Obat
                        </h5>
                        <div>
                            <a href="{{ route('obat.kadaluarsa') }}" class="btn btn-sm btn-danger shadow-sm mr-2">
                                <i class="fas fa-exclamation-triangle"></i> Cek Kedaluwarsa
                                @if(isset($jumlah_kadaluarsa) && $jumlah_kadaluarsa > 0)
                                    <span class="badge badge-light text-danger ml-1">{{ $jumlah_kadaluarsa }}</span>
                                @endif
                            </a>
                            
                            <a href="{{ route('obat.create') }}" class="btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-plus-circle"></i> Tambah Obat
                            </a>
                        </div>
                    </div>

                    <form id="form-filter" action="{{ route('obat.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari nama atau kode obat..." value="{{ $search ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <select name="jenis" id="filterJenis" class="form-control">
                                    <option value="">-- Semua Jenis --</option>
                                    @foreach($list_jenis as $j)
                                        <option value="{{ $j }}" {{ (isset($filter_jenis) && $filter_jenis == $j) ? 'selected' : '' }}>{{ $j }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <select name="sort" id="sortData" class="form-control">
                                    <option value="">-- Urutkan --</option>
                                    <option value="abjad_asc" {{ (isset($sort) && $sort == 'abjad_asc') ? 'selected' : '' }}>Nama Obat (A-Z)</option>
                                    <option value="abjad_desc" {{ (isset($sort) && $sort == 'abjad_desc') ? 'selected' : '' }}>Nama Obat (Z-A)</option>
                                    <option value="harga_asc" {{ (isset($sort) && $sort == 'harga_asc') ? 'selected' : '' }}>Harga Jual (Terendah)</option>
                                    <option value="harga_desc" {{ (isset($sort) && $sort == 'harga_desc') ? 'selected' : '' }}>Harga Jual (Tertinggi)</option>
                                    <option value="exp_asc" {{ (isset($sort) && $sort == 'exp_asc') ? 'selected' : '' }}>Tgl. Kedaluwarsa (Terdekat)</option>
                                    <option value="exp_desc" {{ (isset($sort) && $sort == 'exp_desc') ? 'selected' : '' }}>Tgl. Kedaluwarsa (Terlama)</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <a href="{{ route('obat.index') }}" class="btn btn-secondary w-100"><i class="fas fa-sync-alt mr-1"></i> Reset</a>
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
                                        <th class="px-4">Kode Obat</th>
                                        <th>Nama Obat</th>
                                        <th>Jenis</th>
                                        <th>Stok</th>
                                        <th>Harga Jual</th>
                                        <th>Tgl. Kedaluwarsa</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($obats as $obat)
                                        <tr>
                                            <td class="px-4 fw-bold">{{ $obat->kd_obat }}</td>
                                            <td class="font-weight-bold text-dark">{{ $obat->nm_obat }}</td>
                                            <td><span class="badge badge-info">{{ $obat->jenis }}</span></td>
                                            
                                            <td>
                                                @if($obat->stok <= 0)
                                                    <span class="badge badge-danger">{{ $obat->stok }} {{ $obat->satuan }}</span>
                                                @elseif($obat->stok <= 20)
                                                    <span class="badge badge-warning text-dark">{{ $obat->stok }} {{ $obat->satuan }}</span>
                                                @else
                                                    <span class="badge badge-success">{{ $obat->stok }} {{ $obat->satuan }}</span>
                                                @endif
                                            </td>
                                            
                                            <td>Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</td>
                                            
                                            <td class="{{ \Carbon\Carbon::parse($obat->tanggal_kedaluwarsa)->isPast() ? 'text-danger fw-bold' : '' }}">
                                                {{ \Carbon\Carbon::parse($obat->tanggal_kedaluwarsa)->translatedFormat('d M Y') }}
                                            </td>
                                            
                                            <td>
                                                @if(\Carbon\Carbon::parse($obat->tanggal_kedaluwarsa)->isPast())
                                                    <span class="badge badge-danger rounded-pill">Kedaluwarsa</span>
                                                @elseif($obat->stok <= 0)
                                                    <span class="badge badge-dark rounded-pill">Habis</span>
                                                @else
                                                    <span class="badge badge-success rounded-pill">Aktif</span>
                                                @endif
                                            </td>
                                            
                                            <td class="text-center">
                                                <a href="{{ route('obat.show', $obat->id) }}" class="btn btn-sm btn-info text-white me-1 mb-1" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                                
                                                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'apoteker')
                                                    <a href="{{ route('obat.edit', $obat->id) }}" class="btn btn-sm btn-warning text-dark me-1 mb-1" title="Edit Data">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                @endif

                                                <button type="button" class="btn btn-sm btn-danger mb-1 btn-delete" data-id="{{ $obat->id }}" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                                Data obat tidak ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top px-4 py-3">
                        <div class="d-flex justify-content-end">
                            {{ $obats->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus Data
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-trash-alt fa-4x text-danger mb-4"></i>
                <h5 class="text-gray-900 font-weight-bold mb-3">Apakah Anda Yakin?</h5>
                <p class="text-gray-600 mb-0">Data obat ini akan dihapus secara permanen dari sistem dan tidak dapat dikembalikan lagi.</p>
            </div>
            <div class="modal-footer justify-content-center bg-light">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-dismiss="modal">Batal</button>
                
                <form id="formDelete" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 rounded-pill">Ya, Hapus Obat</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    $(document).ready(function() {
        let timer;
        
        function fetchTabel() {
            let url = $('#form-filter').attr('action') + '?' + $('#form-filter').serialize();
            $.get(url, function(data) {
                let htmlBaru = $(data).find('#tabel-container').html();
                $('#tabel-container').html(htmlBaru);
            });
        }

        // Live Search (Ketik otomatis cari)
        $('#searchInput').on('keyup', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                fetchTabel();
            }, 500); 
        });

        // Trigger pencarian langsung saat dropdown diubah
        $('#filterJenis, #sortData').on('change', function() {
            fetchTabel();
        });
    });

    // Script untuk menangkap klik tombol hapus dan memunculkan Modal
    $(document).on('click', '.btn-delete', function() {
        // Ambil ID dari tombol yang diklik
        let id = $(this).data('id');
        
        // Susun URL untuk hapus
        let url = "{{ url('obat') }}/" + id;
        
        // Masukkan URL tersebut ke action form di dalam modal
        $('#formDelete').attr('action', url);
        
        // Munculkan modalnya
        $('#deleteModal').modal('show');
    });

</script>
@endpush
@endsection