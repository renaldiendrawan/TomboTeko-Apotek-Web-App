@extends('layouts.admin')
@section('title', 'Data Pelanggan')
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-users text-primary mr-2"></i> Manajemen Pelanggan</h1>
    <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambah">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Pelanggan Baru
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="card shadow mb-4 border-bottom-primary">
    <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pelanggan Apotek</h6>
        
        <form id="form-search" action="{{ route('pelanggan.index') }}" method="GET" class="form-inline">
            <div class="input-group input-group-sm">
                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari nama atau kode..." value="{{ request('search') }}">
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
                            <th class="px-4">Kode Pelanggan</th>
                            <th>Nama Pelanggan</th>
                            <th>Telepon / WA</th>
                            <th>Kota</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggans as $p)
                        <tr>
                            <td class="px-4 font-weight-bold text-primary">{{ $p->kd_pelanggan }}</td>
                            <td class="font-weight-bold text-dark">{{ $p->nm_pelanggan }}</td>
                            <td>{{ $p->telepon }}</td>
                            <td><span class="badge badge-info">{{ $p->kota ?? '-' }}</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-secondary mb-1 btn-detail" 
                                    data-kode="{{ $p->kd_pelanggan }}" data-nama="{{ $p->nm_pelanggan }}" 
                                    data-telp="{{ $p->telepon }}" data-kota="{{ $p->kota }}" data-alamat="{{ $p->alamat }}">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                <button class="btn btn-sm btn-info text-white btn-edit mb-1" 
                                    data-id="{{ $p->id }}" data-kode="{{ $p->kd_pelanggan }}" data-nama="{{ $p->nm_pelanggan }}" 
                                    data-telp="{{ $p->telepon }}" data-kota="{{ $p->kota }}" data-alamat="{{ $p->alamat }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger mb-1 btn-delete" 
                                    data-url="{{ route('pelanggan.destroy', $p->id) }}" 
                                    data-nama="{{ $p->nm_pelanggan }}">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Data pelanggan tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-end mt-2">{{ $pelanggans->links('pagination::bootstrap-4') }}</div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-address-card mr-2"></i> Detail Pelanggan</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless table-sm">
                    <tr><td width="35%" class="text-muted">Kode Pelanggan</td><td width="5%">:</td><td class="font-weight-bold text-primary" id="det_kode"></td></tr>
                    <tr><td class="text-muted">Nama Pelanggan</td><td>:</td><td class="font-weight-bold" id="det_nama"></td></tr>
                    <tr><td class="text-muted">No. Telepon / WA</td><td>:</td><td id="det_telp"></td></tr>
                    <tr><td class="text-muted">Kota</td><td>:</td><td id="det_kota"></td></tr>
                    <tr><td class="text-muted align-top">Alamat Lengkap</td><td class="align-top">:</td><td id="det_alamat"></td></tr>
                </table>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('pelanggan.store') }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header bg-primary text-white"><h5 class="modal-title font-weight-bold">Registrasi Pelanggan</h5><button type="button" class="close text-white" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <div class="form-group"><label>Kode Pelanggan *</label><input type="text" name="kd_pelanggan" class="form-control" placeholder="Misal: PLG-001" required></div>
                <div class="form-group"><label>Nama Lengkap *</label><input type="text" name="nm_pelanggan" class="form-control" required></div>
                <div class="row">
                    <div class="col-md-6 form-group"><label>No. Telepon / WA *</label><input type="text" name="telepon" class="form-control" required></div>
                    <div class="col-md-6 form-group"><label>Kota *</label><input type="text" name="kota" id="edit_kota" class="form-control" required></div>
                </div>
                <div class="form-group"><label>Alamat *</label><textarea name="alamat" class="form-control" rows="2" required></textarea></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Data</button></div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEdit" method="POST" class="modal-content border-0 shadow">
            @csrf @method('PUT')
            <div class="modal-header bg-info text-white"><h5 class="modal-title font-weight-bold">Edit Pelanggan</h5><button type="button" class="close text-white" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <div class="form-group"><label>Kode Pelanggan *</label><input type="text" name="kd_pelanggan" id="edit_kode" class="form-control" required readonly></div>
                <div class="form-group"><label>Nama Lengkap *</label><input type="text" name="nm_pelanggan" id="edit_nama" class="form-control" required></div>
                <div class="row">
                    <div class="col-md-6 form-group"><label>No. Telepon / WA *</label><input type="text" name="telepon" id="edit_telp" class="form-control" required></div>
                    <div class="col-md-6 form-group"><label>Kota *</label><input type="text" name="kota" id="edit_kota" class="form-control" required></div>
                </div>
                <div class="form-group"><label>Alamat *</label><textarea name="alamat" id="edit_alamat" class="form-control" rows="2" required></textarea></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-info">Update Data</button></div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Hapus</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-trash text-danger mb-3" style="font-size: 3rem;"></i>
                <h5 class="text-dark font-weight-bold mb-2">Apakah Anda Yakin?</h5>
                <p class="text-muted mb-0">Data <strong id="hapus_nama_item" class="text-danger"></strong> akan dihapus secara permanen dari sistem.</p>
            </div>
            <div class="modal-footer justify-content-center bg-light">
                <form id="formHapus" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary px-4 rounded-pill" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4 rounded-pill">Ya, Hapus Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    // Live Search AJAX
    let timer;
    $('#searchInput').on('keyup', function() {
        clearTimeout(timer);
        timer = setTimeout(function() {
            let url = $('#form-search').attr('action') + '?' + $('#form-search').serialize();
            $.get(url, function(data) {
                $('#tabel-container').html($(data).find('#tabel-container').html());
                attachEventHandlers(); 
            });
        }, 500);
    });

    function attachEventHandlers() {
        $('.btn-detail').click(function() {
            $('#det_kode').text($(this).data('kode'));
            $('#det_nama').text($(this).data('nama'));
            $('#det_telp').text($(this).data('telp'));
            $('#det_kota').text($(this).data('kota') ? $(this).data('kota') : '-');
            $('#det_alamat').text($(this).data('alamat'));
            $('#modalDetail').modal('show');
        });

        $('.btn-edit').click(function() {
            let id = $(this).data('id');
            $('#edit_kode').val($(this).data('kode'));
            $('#edit_nama').val($(this).data('nama'));
            $('#edit_telp').val($(this).data('telp'));
            $('#edit_kota').val($(this).data('kota'));
            $('#edit_alamat').val($(this).data('alamat'));
            $('#formEdit').attr('action', "{{ url('pelanggan') }}/" + id);
            $('#modalEdit').modal('show');
        });

        // Menangkap klik tombol hapus
        $('.btn-delete').click(function() {
            let url = $(this).data('url');
            let nama = $(this).data('nama');
            
            // Masukkan nama ke dalam teks modal
            $('#hapus_nama_item').text(nama);
            
            // Ubah action form sesuai dengan URL data yang diklik
            $('#formHapus').attr('action', url);
            
            // Tampilkan Modal
            $('#modalHapus').modal('show');
        });
    }

    $(document).ready(function() { attachEventHandlers(); });
</script>
@endpush
@endsection