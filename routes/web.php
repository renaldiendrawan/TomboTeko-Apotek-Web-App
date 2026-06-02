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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    // Mengambil 6 data obat secara acak/terbaru yang stoknya lebih dari 0
    $obat_sekilas = Obat::where('stok', '>', 0)->inRandomOrder()->take(6)->get();

    // Mengirim data obat ke halaman welcome
    return view('welcome', compact('obat_sekilas'));
});

// Route bawaan Laravel untuk Login, Register, dll
Auth::routes();

// KELOMPOK ROUTE YANG WAJIB LOGIN
Route::middleware(['auth'])->group(function () {

    // Route Dashboard Utama 
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Route Profil (Bisa diakses user yang login)
    Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'index'])->name('profil.index');
    Route::put('/profil/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profil.update');

    // ==========================================
    // 1. AKSES BERSAMA (ADMIN & APOTEKER)
    // ==========================================
    Route::middleware(['role:admin,apoteker'])->group(function () {

        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

        // 1. Letakkan rute Kadaluarsa DI ATAS resource
        Route::get('/obat/kadaluarsa', [App\Http\Controllers\ObatController::class, 'kadaluarsa'])->name('obat.kadaluarsa');

        // 2. Manajemen Obat (CRUD Obat bisa oleh admin & apoteker)
        Route::resource('obat', ObatController::class);

        Route::resource('transaksi', App\Http\Controllers\TransaksiController::class);

    });

    // ==========================================
    // 2. HAK AKSES KHUSUS APOTEKER
    // ==========================================
    Route::middleware(['role:apoteker'])->group(function () {

        // Transaksi Penjualan Obat
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
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

        // Rute Laporan Penjualan:
        Route::get('/laporan/penjualan', [App\Http\Controllers\LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('/laporan/penjualan/export', [App\Http\Controllers\LaporanController::class, 'exportPenjualan'])->name('laporan.export_penjualan');

        // Rute Laporan Pembelian (Tambahan Baru):
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
    });

});