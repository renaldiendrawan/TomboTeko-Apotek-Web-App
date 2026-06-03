<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Ambil data obat yang stoknya masih ada
        $obats = DB::table('obat')->where('stok', '>', 0)->get();

        // Ambil data pelanggan (jika tabel ada)
        $pelanggans = DB::table('pelanggan')->get();

        return view('transaksi.create', compact('obats', 'pelanggans'));
    }

    /**
     * Menyimpan Data Transaksi ke Database
     */
    public function store(Request $request)
    {
        // Validasi keranjang belanja tidak boleh kosong
        $request->validate([
            'kd_obat' => 'required|array',
            'jumlah' => 'required|array',
            'harga' => 'required|array',
        ]);

        // Gunakan DB Transaction agar jika gagal di tengah jalan, data di-rollback
        DB::beginTransaction();
        try {
            // 1. Buat Nomor Nota Otomatis
            $nota = 'TRX-' . date('YmdHis');
            $total_bayar = 0;

            // Hitung grand total dari array inputan kasir
            foreach ($request->kd_obat as $key => $obat_id) {
                $total_bayar += ($request->harga[$key] * $request->jumlah[$key]);
            }

            // 2. Simpan ke tabel `penjualan` (Header)
            $penjualan_id = DB::table('penjualan')->insertGetId([
                'nota' => $nota,
                'tgl_nota' => date('Y-m-d'),
                'user_id' => auth()->user()->id,
                'kd_pelanggan' => $request->kd_pelanggan,
                'diskon' => 0,
                'total_bayar' => $total_bayar,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Simpan ke tabel `penjualan_detail` (Item) & Potong Stok
            foreach ($request->kd_obat as $key => $obat_id) {
                $jumlah_beli = $request->jumlah[$key];

                DB::table('penjualan_detail')->insert([
                    'nota' => $penjualan_id, // Berdasarkan struktur Anda, ini mengacu ke ID penjualan
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
            // Arahkan ke halaman struk (show) jika berhasil
            // Sementara kita arahkan ke create lagi dengan pesan sukses sebelum halaman show dibuat
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

}