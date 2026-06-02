@extends('layouts.admin')
@section('title', 'Transaksi Pembelian (Restock)')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4 class="fw-bold text-dark mb-0">
                    <i class="fas fa-truck-loading text-primary mr-2"></i> Transaksi Pembelian (Restock)
                </h4>
                <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Katalog Obat (Harga Beli)</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th class="px-3">Kode</th>
                                        <th>Nama Obat</th>
                                        <th>Harga Beli</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($obats as $o)
                                        <tr>
                                            <td class="px-3 fw-bold">{{ $o->kd_obat }}</td>
                                            <td>{{ $o->nm_obat }} <br><small class="text-muted">Stok saat ini: {{ $o->stok }}
                                                    {{ $o->satuan }}</small></td>
                                            <td>Rp {{ number_format($o->harga_beli, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary add-to-cart" data-id="{{ $o->id }}"
                                                    data-nama="{{ $o->nm_obat }}" data-harga="{{ $o->harga_beli }}">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <form id="formPembelian" method="POST" action="{{ route('pembelian.store') }}">
                    @csrf
                    <div class="card border-0 shadow-sm sticky-top" style="top: 90px;">
                        <div
                            class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold"><i class="fas fa-file-invoice mr-2"></i> Draft Nota Pembelian
                            </h6>
                            <span class="badge badge-light text-primary" id="item-count">0</span>
                        </div>
                        <div class="card-body">

                            <div class="form-group mb-4">
                                <label class="font-weight-bold small text-muted">Pilih Supplier <span
                                        class="text-danger">*</span></label>
                                <select name="kd_suplier" class="form-control" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $s)
                                        <option value="{{ $s->id }}">{{ $s->kd_suplier }} - {{ $s->nm_suplier }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="cart-items"
                                style="max-height: 250px; overflow-y: auto; margin-bottom: 20px; border: 1px solid #eee; border-radius: 5px;">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-box-open fa-2x mb-2 opacity-50"></i>
                                    <p class="small mb-0">Belum ada obat yang ditambahkan</p>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal:</span>
                                    <span class="font-weight-bold" id="subtotal">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">Potongan / Diskon (Rp):</span>
                                    <input type="number" name="diskon" class="form-control form-control-sm text-right"
                                        id="diskon" value="0" style="width: 120px;">
                                </div>
                                <div class="d-flex justify-content-between py-2 border-top border-bottom bg-light px-2">
                                    <span class="font-weight-bold text-dark h5 mb-0 pt-1">Total Tagihan:</span>
                                    <span class="font-weight-bold text-success h4 mb-0" id="total">Rp 0</span>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success btn-lg w-100 shadow-sm" id="btn-proses">
                                <i class="fas fa-save mr-2"></i> Simpan Transaksi (Restock)
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i>Peringatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="fas fa-info-circle fa-4x text-warning mb-4"></i>
                    <h5 class="text-gray-900 font-weight-bold mb-3" id="warningTitle">Peringatan!</h5>
                    <p class="text-gray-600 mb-0" id="warningMessage">Pesan peringatan akan muncul di sini.</p>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill" data-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-question-circle mr-2"></i>Konfirmasi Pembelian
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="fas fa-boxes fa-4x text-primary mb-4"></i>
                    <h5 class="text-gray-900 font-weight-bold mb-3">Proses Transaksi?</h5>
                    <p class="text-gray-600 mb-0">Pastikan data supplier dan item sudah benar. Stok obat akan otomatis
                        <strong>bertambah</strong> ke dalam sistem setelah proses ini.
                    </p>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary px-4 rounded-pill" id="btn-submit-real">Ya, Proses
                        Transaksi</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        const cartItemsContainer = document.getElementById('cart-items');
        const itemCountBadge = document.getElementById('item-count');
        const subtotalSpan = document.getElementById('subtotal');
        const diskonInput = document.getElementById('diskon');
        const totalSpan = document.getElementById('total');

        function formatCurrency(value) { return 'Rp ' + parseInt(value).toLocaleString('id-ID'); }

        // Add to cart
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id = this.dataset.id;
                const nama = this.dataset.nama;
                const harga = parseInt(this.dataset.harga);

                const existing = cart.find(item => item.id === id);
                if (existing) {
                    existing.qty++;
                } else {
                    cart.push({ id, nama, harga, qty: 1 });
                }
                updateCart();
            });
        });

        function updateCart() {
            if (cart.length === 0) {
                cartItemsContainer.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-box-open fa-2x mb-2 opacity-50"></i><p class="small mb-0">Belum ada obat yang ditambahkan</p></div>';
            } else {
                cartItemsContainer.innerHTML = cart.map((item, idx) => `
                                <div class="d-flex justify-content-between align-items-center mb-0 p-2 border-bottom bg-white">
                                    <input type="hidden" name="kd_obat[]" value="${item.id}">

                                    <div style="width: 40%;">
                                        <p class="mb-0 font-weight-bold small">${item.nama}</p>
                                    </div>

                                    <div style="width: 25%;">
                                        <input type="number" name="harga[]" class="form-control form-control-sm text-right" value="${item.harga}" onchange="changeHarga(${idx}, this.value)" title="Ubah jika harga dari supplier berubah">
                                    </div>

                                    <div class="d-flex align-items-center" style="width: 25%;">
                                        <input type="number" name="jumlah[]" class="form-control form-control-sm text-center mx-1" value="${item.qty}" min="1" onchange="changeQty(${idx}, this.value)">
                                    </div>

                                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="removeFromCart(${idx})"><i class="fas fa-times"></i></button>
                                </div>
                            `).join('');
            }

            const subtotal = cart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
            const diskon = parseInt(diskonInput.value) || 0;
            const total = subtotal - diskon;

            itemCountBadge.textContent = cart.length;
            subtotalSpan.textContent = formatCurrency(subtotal);
            totalSpan.textContent = formatCurrency(total);
        }

        window.changeQty = function (idx, val) {
            if (val > 0) { cart[idx].qty = parseInt(val); updateCart(); }
        };

        window.changeHarga = function (idx, val) {
            if (val >= 0) { cart[idx].harga = parseInt(val); updateCart(); }
        };

        window.removeFromCart = function (idx) {
            cart.splice(idx, 1); updateCart();
        };

        diskonInput.addEventListener('input', updateCart);

        // Menangkap klik tombol Simpan Transaksi (Restock)
        document.getElementById('btn-proses').addEventListener('click', function () {

            // Validasi Keranjang Kosong
            if (cart.length === 0) {
                $('#warningTitle').text('Draft Nota Kosong!');
                $('#warningMessage').text('Silakan tambahkan minimal satu obat ke dalam daftar pembelian.');
                $('#warningModal').modal('show');
                return;
            }

            // Validasi Supplier
            let supplier = document.querySelector('select[name="kd_suplier"]').value;
            if (!supplier) {
                $('#warningTitle').text('Supplier Belum Dipilih!');
                $('#warningMessage').text('Silakan pilih Supplier dari menu dropdown terlebih dahulu.');
                $('#warningModal').modal('show');
                return;
            }

            // Jika semua validasi aman, munculkan Modal Konfirmasi
            $('#confirmModal').modal('show');
        });

        // Menangkap klik tombol "Ya, Proses Transaksi" di dalam Modal Konfirmasi
        document.getElementById('btn-submit-real').addEventListener('click', function () {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            document.getElementById('formPembelian').submit();
        });
    </script>
@endsection