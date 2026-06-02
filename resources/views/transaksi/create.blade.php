@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle me-2"></i> <strong>Berhasil!</strong> {{ session('success') }}

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-receipt" style="color: #0066cc; margin-right: 8px;"></i>
                    Kasir - Proses Transaksi
                </h4>
                <small class="text-muted">Pilih obat dan proses pembayaran</small>
            </div>
            <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div
                        class="card-header bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-capsule" style="color: #0066cc; margin-right: 8px;"></i>
                            Pilih Obat
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th class="px-4">Kode Obat</th>
                                        <th>Nama Obat</th>
                                        <th>Harga</th>
                                        <th class="text-center">Stok</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($obats as $o)
                                        <tr style="cursor: pointer;" class="obat-row">
                                            <td class="px-4 fw-bold">{{ $o->kd_obat }}</td>
                                            <td>{{ $o->nm_obat }} <br><small class="text-muted">{{ $o->jenis }}</small></td>
                                            <td>Rp {{ number_format($o->harga_jual, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge {{ $o->stok > 10 ? 'bg-success' : 'bg-warning text-dark' }}">{{ $o->stok }}</span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary add-to-cart" data-id="{{ $o->id }}"
                                                    data-nama="{{ $o->nm_obat }}" data-harga="{{ $o->harga_jual }}"
                                                    data-stok="{{ $o->stok }}">
                                                    <i class="bi bi-cart-plus"></i> Tambah
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data obat yang
                                                tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <form id="formTransaksi" method="POST" action="{{ route('transaksi.store') }}">
                    @csrf

                    <div class="card border-0 shadow-sm sticky-top" style="top: 90px;">
                        <div
                            class="card-header bg-primary text-white px-4 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-bag-check"></i> Keranjang
                            </h5>
                            <span class="badge bg-light text-primary" id="item-count">0</span>
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label small text-muted fw-bold">Pelanggan (Opsional)</label>
                                <select name="kd_pelanggan" class="form-control form-control-sm">
                                    <option value="">-- Umum (Tanpa Member) --</option>
                                    @foreach($pelanggans as $p)
                                        <option value="{{ $p->id }}">{{ $p->kd_pelanggan }} - {{ $p->nm_pelanggan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="cart-items"
                                style="max-height: 250px; overflow-y: auto; margin-bottom: 20px; border: 1px solid #eee; border-radius: 5px;">
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-basket" style="font-size: 2rem; opacity: 0.5;"></i>
                                    <p class="mt-2 small">Keranjang kosong</p>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal:</span>
                                    <span class="fw-bold" id="subtotal">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Diskon (Rp):</span>
                                    <input type="number" name="diskon" class="form-control form-control-sm" id="diskon"
                                        placeholder="0" style="width: 100px; text-align: right;" value="0">
                                </div>
                                <div class="d-flex justify-content-between"
                                    style="border-bottom: 2px solid #dee2e6; padding-bottom: 10px;">
                                    <span class="text-muted">Total:</span>
                                    <span class="fw-bold h5" id="total" style="color: #0066cc;">Rp 0</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted fw-bold">Uang Pembayaran (Rp)</label>
                                <input type="number" class="form-control form-control-lg" id="bayar" placeholder="0"
                                    required>
                            </div>

                            <div class="mb-4 p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                <small class="text-muted d-block mb-1 fw-bold">Kembalian</small>
                                <h4 class="mb-0" id="kembalian" style="color: #27ae60;">Rp 0</h4>
                            </div>

                            <button type="button" class="btn btn-success btn-lg w-100 mb-2" id="btn-proses">
                                <i class="bi bi-check-circle"></i> Proses Transaksi
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm w-100" id="btn-reset">
                                <i class="bi bi-trash"></i> Bersihkan Keranjang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="warningModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Peringatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="fas fa-shopping-basket text-warning mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-dark fw-bold mb-2" id="warningTitle">Oops!</h5>
                    <p class="text-muted mb-0" id="warningMessage">Pesan peringatan di sini.</p>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill" data-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="resetCartModal" tabindex="-1" aria-labelledby="resetCartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="resetCartModalLabel">
                        <i class="fas fa-trash-alt me-2"></i> Kosongkan Keranjang
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="fas fa-trash text-danger mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-dark fw-bold mb-2">Apakah Anda Yakin?</h5>
                    <p class="text-muted mb-0">Semua obat yang sudah dimasukkan ke keranjang akan dihapus dan diulang dari
                        awal.</p>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger px-4 rounded-pill" id="confirmResetCart">Ya,
                        Kosongkan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="prosesTransaksiModal" tabindex="-1" aria-labelledby="prosesTransaksiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="prosesTransaksiModalLabel">
                        <i class="fas fa-check-circle me-2"></i> Konfirmasi Pembayaran
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="fas fa-wallet text-success mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-dark fw-bold mb-2">Proses Transaksi?</h5>
                    <p class="text-muted mb-0">Pastikan uang yang diterima sudah sesuai. Transaksi ini akan disimpan dan
                        stok obat akan otomatis terpotong.</p>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success px-4 rounded-pill" id="confirmProsesTransaksi">Ya, Proses
                        Sekarang</button>
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
        const bayarInput = document.getElementById('bayar');
        const kembalianSpan = document.getElementById('kembalian');

        // Format currency
        function formatCurrency(value) {
            return 'Rp ' + parseInt(value).toLocaleString('id-ID');
        }

        // Add to cart
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id = this.dataset.id;
                const nama = this.dataset.nama;
                const harga = parseInt(this.dataset.harga);
                const stok = parseInt(this.dataset.stok);

                const existing = cart.find(item => item.id === id);
                if (existing) {
                    if (existing.qty < stok) {
                        existing.qty++;
                    } else {
                        alert('Stok tidak mencukupi!');
                    }
                } else {
                    if (stok > 0) {
                        cart.push({ id, nama, harga, qty: 1, maxStok: stok });
                    } else {
                        alert('Stok Habis!');
                    }
                }

                updateCart();
            });
        });

        // Update cart display & inject hidden inputs for form submission
        function updateCart() {
            if (cart.length === 0) {
                cartItemsContainer.innerHTML = '<div class="text-center text-muted py-4"><i class="bi bi-basket" style="font-size: 2rem; opacity: 0.5;"></i><p class="mt-2 small">Keranjang kosong</p></div>';
            } else {
                cartItemsContainer.innerHTML = cart.map((item, idx) => `
                                                                <div class="d-flex justify-content-between align-items-center mb-0 p-2 border-bottom bg-white">
                                                                    <input type="hidden" name="kd_obat[]" value="${item.id}">
                                                                    <input type="hidden" name="harga[]" value="${item.harga}">
                                                                    <input type="hidden" name="jumlah[]" value="${item.qty}">

                                                                    <div>
                                                                        <p class="mb-1 fw-bold small">${item.nama}</p>
                                                                        <small class="text-muted">${formatCurrency(item.harga)}</small>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0" onclick="changeQty(${idx}, -1)">-</button>
                                                                        <span class="fw-bold small">${item.qty}</span>
                                                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0" onclick="changeQty(${idx}, 1)">+</button>
                                                                        <button type="button" class="btn btn-sm btn-outline-danger py-0 ml-2" onclick="removeFromCart(${idx})"><i class="fas fa-trash-alt"></i></button>
                                                                    </div>
                                                                </div>
                                                            `).join('');
            }

            // Calculate totals
            const subtotal = cart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
            const diskon = parseInt(diskonInput.value) || 0;
            const total = subtotal - diskon;

            itemCountBadge.textContent = cart.length;
            subtotalSpan.textContent = formatCurrency(subtotal);
            totalSpan.textContent = formatCurrency(total);

            // Update kembalian
            hitungKembalian(total);
        }

        function hitungKembalian(totalBelanja) {
            const bayar = parseInt(bayarInput.value) || 0;
            const kembalian = bayar - totalBelanja;
            if (kembalian >= 0) {
                kembalianSpan.textContent = formatCurrency(kembalian);
                kembalianSpan.style.color = "#27ae60"; // Hijau
            } else {
                kembalianSpan.textContent = "Uang Kurang!";
                kembalianSpan.style.color = "#e74c3c"; // Merah
            }
        }

        // Change quantity
        window.changeQty = function (idx, change) {
            let newQty = cart[idx].qty + change;
            if (newQty > 0 && newQty <= cart[idx].maxStok) {
                cart[idx].qty = newQty;
                updateCart();
            } else if (newQty > cart[idx].maxStok) {
                alert('Melebihi stok yang ada!');
            }
        };

        // Remove from cart
        window.removeFromCart = function (idx) {
            cart.splice(idx, 1);
            updateCart();
        };

        // Diskon change
        diskonInput.addEventListener('input', updateCart);

        // Bayar input
        bayarInput.addEventListener('input', function () {
            const total = parseInt(totalSpan.textContent.replace(/\D/g, '')) || 0;
            hitungKembalian(total);
        });

        // Process transaction (Submit Form to Backend)
        // Memicu modal saat tombol Proses Transaksi diklik
        document.getElementById('btn-proses').addEventListener('click', function () {
            if (cart.length === 0) {
                alert('Keranjang belanja masih kosong!');
                return;
            }

            const total = parseInt(totalSpan.textContent.replace(/\D/g, '')) || 0;
            const bayar = parseInt(bayarInput.value) || 0;

            if (bayar < total) {
                alert('Pembayaran kurang dari total belanja!');
                return;
            }

            // Munculkan modal
            $('#prosesTransaksiModal').modal('show');
        });

        // Eksekusi Submit Form setelah tombol "Ya, Proses Sekarang" di dalam modal diklik
        document.getElementById('confirmProsesTransaksi').addEventListener('click', function () {
            // Ubah tombol menjadi loading state agar tidak diklik 2 kali oleh kasir
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';

            // Eksekusi form
            document.getElementById('formTransaksi').submit();
        });

        // --------------------------------------------------------
        // KLIK TOMBOL BERSIHKAN KERANJANG
        // --------------------------------------------------------
        document.getElementById('btn-reset').addEventListener('click', function () {
            if (cart.length === 0) {
                // Panggil Modal Peringatan (Bukan browser alert)
                $('#warningTitle').text('Keranjang Kosong!');
                $('#warningMessage').text('Tidak ada obat di keranjang untuk dibersihkan.');
                $('#warningModal').modal('show');
                return;
            }
            $('#resetCartModal').modal('show');
        });

        // --------------------------------------------------------
        // KLIK TOMBOL PROSES TRANSAKSI
        // --------------------------------------------------------
        document.getElementById('btn-proses').addEventListener('click', function () {
            if (cart.length === 0) {
                // Panggil Modal Peringatan
                $('#warningTitle').text('Keranjang Kosong!');
                $('#warningMessage').text('Silakan tambahkan minimal satu obat ke keranjang belanja Anda.');
                $('#warningModal').modal('show');
                return;
            }

            const total = parseInt(totalSpan.textContent.replace(/\D/g, '')) || 0;
            const bayar = parseInt(bayarInput.value) || 0;

            // Validasi uang kurang
            if (bayar < total) {
                // Panggil Modal Peringatan
                $('#warningTitle').text('Pembayaran Kurang!');
                $('#warningMessage').text('Uang pembayaran kurang dari total tagihan.');
                $('#warningModal').modal('show');
                return;
            }

            // Jika aman, munculkan modal konfirmasi sukses
            $('#prosesTransaksiModal').modal('show');
        });
    </script>

    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transition: all 0.2s ease;
        }

        .add-to-cart {
            background-color: #0066cc;
            border-color: #0066cc;
        }

        .add-to-cart:hover {
            background-color: #0052a3;
            border-color: #0052a3;
        }
    </style>
@endsection