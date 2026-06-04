<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PelangganController;
use App\Models\Obat;

// Menampilkan halaman depan beserta data 6 obat secara acak
Route::get('/', function () {
    $obat_sekilas = Obat::where('stok', '>', 0)->inRandomOrder()->take(6)->get();
    return view('welcome', compact('obat_sekilas'));
});

// Route bawaan Laravel untuk Login, Register, dll
Auth::routes();

// KELOMPOK ROUTE YANG WAJIB LOGIN (Dapat diakses Admin, Apoteker, & Pelanggan)
Route::middleware(['auth'])->group(function () {

    // Route Dashboard / Home (Semua role masuk ke sini setelah login)
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Route Profil
    Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'index'])->name('profil.index');
    Route::put('/profil/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profil.update');

    // ==========================================
    // 1. AKSES BERSAMA (ADMIN & APOTEKER)
    // ==========================================
    Route::middleware(['role:admin,apoteker'])->group(function () {

        // Manajemen Obat
        Route::get('/obat/kadaluarsa', [ObatController::class, 'kadaluarsa'])->name('obat.kadaluarsa');
        Route::resource('obat', ObatController::class);

        // Halaman Utama Transaksi Penjualan (Bisa dilihat Admin & Apoteker)
        Route::resource('transaksi', TransaksiController::class);
    });

    // ==========================================
    // 2. HAK AKSES KHUSUS APOTEKER
    // ==========================================
    Route::middleware(['role:apoteker'])->group(function () {

        // Aksi Kasir Spesifik Apoteker (Selain fungsi standard dari Route::resource)
        Route::post('/transaksi/tambah', [TransaksiController::class, 'tambahKeKeranjang'])->name('transaksi.tambah');
        Route::post('/transaksi/checkout', [TransaksiController::class, 'checkout'])->name('transaksi.checkout');
        Route::get('/transaksi/struk/{id}', [TransaksiController::class, 'struk'])->name('transaksi.struk');

        // Histori Penjualan
        Route::get('/histori-penjualan', [LaporanController::class, 'penjualan'])->name('histori.penjualan');
    });

    // ==========================================
    // 3. HAK AKSES KHUSUS ADMIN
    // ==========================================
    Route::middleware(['role:admin'])->group(function () {

        // Transaksi Pembelian Obat (Admin)
        Route::get('/pembelian', [App\Http\Controllers\PembelianController::class, 'index'])->name('pembelian.index');
        Route::get('/pembelian/create', [App\Http\Controllers\PembelianController::class, 'create'])->name('pembelian.create');
        Route::post('/pembelian', [App\Http\Controllers\PembelianController::class, 'store'])->name('pembelian.store');
        Route::get('/pembelian/{id}', [App\Http\Controllers\PembelianController::class, 'show'])->name('pembelian.show');

        // Master Data
        Route::resource('supplier', SupplierController::class);
        Route::resource('pelanggan', PelangganController::class);

        // Laporan Lengkap
        Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'dashboard'])->name('laporan.index');
        Route::get('/laporan/penjualan', [App\Http\Controllers\LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('/laporan/penjualan/export', [App\Http\Controllers\LaporanController::class, 'exportPenjualan'])->name('laporan.export_penjualan');
        Route::get('/laporan/pembelian', [App\Http\Controllers\LaporanController::class, 'pembelian'])->name('laporan.pembelian');

        // Master Data Apoteker
        Route::resource('apoteker', App\Http\Controllers\ApotekerController::class);
    });

    // ==========================================
    // 4. HAK AKSES KHUSUS PELANGGAN
    // ==========================================
    Route::middleware(['role:pelanggan'])->group(function () {

        // Halaman melihat daftar obat yang dijual
        Route::get('/katalog-obat', [ObatController::class, 'katalog'])->name('katalog.index');

        // Halaman melihat detail obat (saat diklik)
        Route::get('/katalog-obat/{id}', [ObatController::class, 'detailKatalog'])->name('katalog.show');

        // Halaman Riwayat Belanja Pelanggan
        Route::get('/riwayat-belanja', [TransaksiController::class, 'riwayatPelanggan'])->name('riwayat.index');
        Route::get('/riwayat-belanja/{id}', [TransaksiController::class, 'showRiwayatPelanggan'])->name('riwayat.show');
    });

});