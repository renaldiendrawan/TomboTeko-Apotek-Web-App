@extends('layouts.admin')

@section('title', 'Katalog Obat')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Katalog Obat</h1>
    </div>

    <form id="form-pencarian" action="{{ route('katalog.index') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class="input-group shadow-sm h-100">
                    <input type="text" id="searchInput" name="search" class="form-control border-0 px-4"
                        placeholder="Ketik nama obat atau kode..." value="{{ $search }}" autocomplete="off">
                    <div class="input-group-append">
                        <button class="btn btn-primary px-4" type="button" disabled>
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-2">
                <select name="jenis" id="filterJenis" class="form-control shadow-sm border-0" style="height: 100%;">
                    <option value="">-- Semua Jenis --</option>
                    @foreach($list_jenis as $j)
                        <option value="{{ $j }}" {{ (isset($filter_jenis) && $filter_jenis == $j) ? 'selected' : '' }}>
                            {{ $j }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <select name="sort" id="sortHarga" class="form-control shadow-sm border-0" style="height: 100%;">
                    <option value="">-- Urutkan Berdasarkan --</option>
                    <option value="a-z" {{ (isset($sort) && $sort == 'a-z') ? 'selected' : '' }}>Nama Obat (A - Z)</option>
                    <option value="z-a" {{ (isset($sort) && $sort == 'z-a') ? 'selected' : '' }}>Nama Obat (Z - A)</option>
                    <option value="termurah" {{ (isset($sort) && $sort == 'termurah') ? 'selected' : '' }}>Harga Terendah
                    </option>
                    <option value="termahal" {{ (isset($sort) && $sort == 'termahal') ? 'selected' : '' }}>Harga Tertinggi
                    </option>
                </select>
            </div>
        </div>
    </form>

    <div id="katalog-container">
        <div class="row">
            @forelse($obats as $obat)
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 obat-card">
                        <div class="card-body text-center p-4">
                            <div class="mb-3"
                                style="height: 120px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if($obat->gambar)
                                    <img src="{{ asset('storage/' . $obat->gambar) }}" alt="{{ $obat->nm_obat }}"
                                        class="img-fluid rounded" style="max-height: 100%; object-fit: contain;">
                                @else
                                    <i class="fas fa-prescription-bottle-alt fa-4x text-gray-300"></i>
                                @endif
                            </div>

                            <h5 class="font-weight-bold text-gray-900 mb-1"
                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <a href="{{ route('katalog.show', $obat->id) }}"
                                    class="text-dark text-decoration-none">{{ $obat->nm_obat }}</a>
                            </h5>
                            <span class="badge badge-info mb-2">{{ $obat->jenis }}</span>

                            <h5 class="font-weight-bold text-success mb-3">Rp
                                {{ number_format($obat->harga_jual, 0, ',', '.') }}
                            </h5>
                            <p class="text-sm text-muted mb-3">Tersedia: {{ $obat->stok }} {{ $obat->satuan }}</p>

                            <a href="{{ route('katalog.show', $obat->id) }}"
                                class="btn btn-outline-primary btn-sm btn-block rounded-pill">
                                Lihat Detail Obat
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center shadow-sm" role="alert">
                        <i class="fas fa-box-open mr-2"></i> Pencarian tidak ditemukan atau stok kosong.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $obats->links() }}
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            let timer;

            // Fungsi untuk mengambil data katalog via AJAX
            function fetchKatalogLive() {
                // Ambil URL dengan parameter dari inputan saat ini
                let url = $('#form-pencarian').attr('action') + '?' + $('#form-pencarian').serialize();

                // Lakukan pemanggilan data ke server di belakang layar
                $.get(url, function (data) {
                    // Ekstrak HTML bagian #katalog-container saja dari respons server
                    let htmlBaru = $(data).find('#katalog-container').html();
                    // Ganti isi kontainer yang lama dengan yang baru (Efek Live Search)
                    $('#katalog-container').html(htmlBaru);
                });
            }

            // Trigger pencarian saat pengguna mengetik dengan delay 0.5 detik (Debounce)
            $('#searchInput').on('keyup', function () {
                clearTimeout(timer);
                timer = setTimeout(function () {
                    fetchKatalogLive();
                }, 500);
            });

            // Trigger pencarian langsung saat dropdown filter atau urutan diubah
            $('#filterJenis, #sortHarga').on('change', function () {
                fetchKatalogLive();
            });
        });
    </script>
@endpush