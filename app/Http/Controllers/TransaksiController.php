<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan Antarmuka Kasir
     */
    public function create()
    {
        // Ambil data obat yang stoknya masih ada DAN BELUM kedaluwarsa
        $obats = DB::table('obat')
            ->where('stok', '>', 0)
            ->whereDate('tanggal_kedaluwarsa', '>=', Carbon::today()) // <-- Tambahan filter ini
            ->get();

        // Ambil data pelanggan (jika tabel ada)
        $pelanggans = DB::table('pelanggan')->get();

        return view('transaksi.create', compact('obats', 'pelanggans'));
    }

    /**
     * Menyimpan Data Transaksi ke Database
     */
    public function store(Request $request)
    {
        // 1. Tambahkan validasi untuk inputan diskon
        $request->validate([
            'kd_obat' => 'required|array',
            'jumlah' => 'required|array',
            'harga' => 'required|array',
            'diskon' => 'nullable|numeric|min:0' // Memastikan diskon adalah angka
        ]);

        // Gunakan DB Transaction agar jika gagal di tengah jalan, data di-rollback
        DB::beginTransaction();
        try {
            // 2. Buat Nomor Nota Otomatis
            $nota = 'TRX-' . date('YmdHis');
            $subtotal = 0;

            // 3. Hitung subtotal dasar dari array inputan kasir
            foreach ($request->kd_obat as $key => $obat_id) {
                $subtotal += ($request->harga[$key] * $request->jumlah[$key]);
            }

            // 4. Tangkap nilai diskon dari form (jika kosong, anggap 0)
            $diskon = $request->diskon ?? 0;

            // 5. Hitung total akhir setelah dipotong diskon
            $total_bayar = $subtotal - $diskon;

            // Cegah total bayar menjadi minus (jika kasir salah ketik diskon lebih besar dari belanja)
            if ($total_bayar < 0) {
                $total_bayar = 0;
            }

            // 6. Simpan ke tabel `penjualan` (Header)
            $penjualan_id = DB::table('penjualan')->insertGetId([
                'nota' => $nota,
                'tgl_nota' => date('Y-m-d'),
                'user_id' => auth()->user()->id,
                'kd_pelanggan' => $request->kd_pelanggan,
                'diskon' => $diskon, // <-- Menyimpan nilai diskon yang sebenarnya
                'total_bayar' => $total_bayar, // <-- Total yang sudah dikurangi diskon
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 7. Simpan ke tabel `penjualan_detail` (Item) & Potong Stok
            foreach ($request->kd_obat as $key => $obat_id) {
                $jumlah_beli = $request->jumlah[$key];

                DB::table('penjualan_detail')->insert([
                    'nota' => $penjualan_id,
                    'kd_obat' => $obat_id,
                    'jumlah' => $jumlah_beli,
                    'harga' => $request->harga[$key],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Potong stok di tabel obat
                DB::table('obat')->where('id', $obat_id)->decrement('stok', $jumlah_beli);
            }

            DB::commit();

            return redirect()->route('transaksi.index')->with('success', 'Transaksi dengan nota ' . $nota . ' berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Halaman Histori Penjualan
     */
    public function index()
    {
        // Ambil data penjualan beserta nama pelanggannya (jika ada)
        $transaksis = DB::table('penjualan')
            ->leftJoin('pelanggan', 'penjualan.kd_pelanggan', '=', 'pelanggan.id')
            ->select('penjualan.*', 'pelanggan.nm_pelanggan')
            ->orderBy('penjualan.id', 'desc') // Urutkan dari yang terbaru
            ->paginate(15);

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Menampilkan Detail Transaksi (Struk/Nota)
     */
    public function show($id)
    {
        // 1. Ambil Header Transaksi
        $penjualan = DB::table('penjualan')
            ->leftJoin('pelanggan', 'penjualan.kd_pelanggan', '=', 'pelanggan.id')
            ->select('penjualan.*', 'pelanggan.nm_pelanggan')
            ->where('penjualan.id', $id)
            ->first();

        if (!$penjualan) {
            return redirect()->route('transaksi.index')->with('error', 'Data transaksi tidak ditemukan.');
        }

        // 2. Ambil Detail Item Obat yang Dibeli
        $details = DB::table('penjualan_detail')
            ->join('obat', 'penjualan_detail.kd_obat', '=', 'obat.id')
            ->select('penjualan_detail.*', 'obat.nm_obat', 'obat.satuan')
            ->where('penjualan_detail.nota', $id)
            ->get();

        return view('transaksi.show', compact('penjualan', 'details'));
    }

    /**
     * KHUSUS PELANGGAN: Menampilkan Daftar Riwayat Belanja
     */
    public function riwayatPelanggan()
    {
        // Cari ID pelanggan berdasarkan nama user yang sedang login
        $pelanggan = DB::table('pelanggan')->where('nm_pelanggan', auth()->user()->name)->first();

        if (!$pelanggan) {
            $riwayats = collect(); // Kosong jika belum ada transaksi
        } else {
            // Ambil histori transaksi khusus untuk pelanggan ini
            $riwayats = DB::table('penjualan')
                ->where('kd_pelanggan', $pelanggan->id)
                ->orderBy('tgl_nota', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(10);
        }

        return view('pelanggan.riwayat.index', compact('riwayats'));
    }

    /**
     * KHUSUS PELANGGAN: Menampilkan Detail/Struk Riwayat
     */
    public function showRiwayatPelanggan($id)
    {
        $pelanggan = DB::table('pelanggan')->where('nm_pelanggan', auth()->user()->name)->first();

        // --- UBAH BAGIAN INI ---
        // Lakukan JOIN ke tabel users untuk mendapatkan nama kasir
        $penjualan = DB::table('penjualan')
            ->leftJoin('users', 'penjualan.user_id', '=', 'users.id')
            ->select('penjualan.*', 'users.name as nama_kasir')
            ->where('penjualan.id', $id)
            ->where('penjualan.kd_pelanggan', $pelanggan->id ?? 0)
            ->first();
        // -----------------------

        if (!$penjualan) {
            return redirect()->route('riwayat.index')->with('error', 'Transaksi tidak ditemukan atau bukan milik Anda.');
        }

        $details = DB::table('penjualan_detail')
            ->join('obat', 'penjualan_detail.kd_obat', '=', 'obat.id')
            ->select('penjualan_detail.*', 'obat.nm_obat', 'obat.satuan')
            ->where('penjualan_detail.nota', $id)
            ->get();

        return view('pelanggan.riwayat.show', compact('penjualan', 'details'));
    }

}