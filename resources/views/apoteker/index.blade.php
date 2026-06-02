@extends('layouts.admin')
@section('title', 'Data Apoteker')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-md text-primary mr-2"></i> Manajemen Apoteker</h1>
        <a href="{{ route('apoteker.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Apoteker
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Apoteker</h6>
            <form id="form-search" action="{{ route('apoteker.index') }}" method="GET" class="row align-items-center m-0">
                <div class="col-auto px-1 mb-1 mb-sm-0">
                    <select name="jk" id="filterJk" class="form-control form-control-sm cursor-pointer">
                        <option value="">-- Semua Gender --</option>
                        <option value="Laki-laki" {{ request('jk') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ request('jk') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="col-auto px-1 mb-1 mb-sm-0">
                    <select name="sort" id="sortData" class="form-control form-control-sm cursor-pointer">
                        <option value="">-- Urutkan --</option>
                        <option value="abjad_asc" {{ request('sort') == 'abjad_asc' ? 'selected' : '' }}>Abjad (A-Z)</option>
                        <option value="abjad_desc" {{ request('sort') == 'abjad_desc' ? 'selected' : '' }}>Abjad (Z-A)
                        </option>
                    </select>
                </div>

                <div class="col-auto px-1">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" id="searchInput" class="form-control"
                            placeholder="Cari nama/kode..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
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
                                <th class="px-4">Kode</th>
                                <th>Nama Apoteker</th>
                                <th>Jenis Kelamin</th>
                                <th>No. Telepon</th>
                                <th class="text-center px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($apotekers as $a)
                                <tr>
                                    <td class="px-4 font-weight-bold text-primary">{{ $a->kd_apoteker }}</td>
                                    <td class="font-weight-bold text-dark">{{ $a->nm_apoteker }}</td>
                                    <td>{{ $a->jk }}</td>
                                    <td>{{ $a->telepon }}</td>
                                    <td class="text-center px-4">
                                        <button class="btn btn-sm btn-info text-white mb-1 btn-detail"
                                            data-kode="{{ $a->kd_apoteker }}" data-nama="{{ $a->nm_apoteker }}"
                                            data-jk="{{ $a->jk }}" data-telp="{{ $a->telepon }}" data-alamat="{{ $a->alamat }}"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('apoteker.edit', $a->id) }}"
                                            class="btn btn-sm btn-warning text-dark mb-1" title="Edit"><i
                                                class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-danger mb-1 btn-delete" data-id="{{ $a->id }}"
                                            data-nama="{{ $a->nm_apoteker }}" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data apoteker.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-end">{{ $apotekers->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-user-md mr-2"></i> Detail Apoteker</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td width="35%" class="text-muted">Kode</td>
                            <td width="5%">:</td>
                            <td class="font-weight-bold text-primary" id="det_kode"></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama Lengkap</td>
                            <td>:</td>
                            <td class="font-weight-bold text-dark" id="det_nama"></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jenis Kelamin</td>
                            <td>:</td>
                            <td id="det_jk"></td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. Telepon</td>
                            <td>:</td>
                            <td id="det_telp"></td>
                        </tr>
                        <tr>
                            <td class="text-muted align-top">Alamat</td>
                            <td class="align-top">:</td>
                            <td id="det_alamat"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi
                        Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center p-4">
                    <h5 class="text-dark mb-2">Hapus Apoteker <span id="del_nama"
                            class="font-weight-bold text-danger"></span>?</h5>
                    <p class="text-muted mb-0">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer bg-light justify-content-center">
                    <form id="formDelete" method="POST">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-secondary px-4 rounded-pill"
                            data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger px-4 rounded-pill">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function () {

                // Fungsi untuk mengambil data tabel baru via AJAX
                function fetchTabel() {
                    let url = $('#form-search').attr('action') + '?' + $('#form-search').serialize();
                    $.get(url, function (data) {
                        $('#tabel-container').html($(data).find('#tabel-container').html());
                    });
                }

                // Live Search AJAX (saat mengetik)
                let timer;
                $('#searchInput').on('keyup', function () {
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        fetchTabel();
                    }, 500);
                });

                // Live Filter & Sort AJAX (saat memilih dropdown)
                $('#filterJk, #sortData').on('change', function () {
                    fetchTabel();
                });

                // Tampilkan Detail
                $(document).on('click', '.btn-detail', function () {
                    $('#det_kode').text($(this).data('kode'));
                    $('#det_nama').text($(this).data('nama'));
                    $('#det_jk').text($(this).data('jk'));
                    $('#det_telp').text($(this).data('telp'));
                    $('#det_alamat').text($(this).data('alamat'));
                    $('#detailModal').modal('show');
                });

                // Tampilkan Modal Hapus
                $(document).on('click', '.btn-delete', function () {
                    $('#del_nama').text($(this).data('nama'));
                    $('#formDelete').attr('action', "{{ url('apoteker') }}/" + $(this).data('id'));
                    $('#deleteModal').modal('show');
                });
            });
        </script>
    @endpush
@endsection