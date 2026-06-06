<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    /**
     * Menampilkan daftar transaksi pembelian (Halaman Utama)
     */
    public function index(Request $request)
    {
        $query = DB::table('pembelian')
            ->join('supplier', 'pembelian.kd_suplier', '=', 'supplier.id')
            ->select('pembelian.*', 'supplier.nm_suplier');

        // Fitur Pencarian (Berdasarkan No Nota atau Nama Supplier)
        if ($request->has('search') && $request->search != '') {
            $query->where('pembelian.nota', 'like', '%' . $request->search . '%')
                ->orWhere('supplier.nm_suplier', 'like', '%' . $request->search . '%');
        }

        // Urutkan dari transaksi terbaru
        $pembelians = $query->orderBy('pembelian.tgl_nota', 'desc')
            ->orderBy('pembelian.id', 'desc')
            ->paginate(10)
            ->appends($request->all());

        return view('pembelian.index', compact('pembelians'));
    }

    /**
     * Menampilkan detail spesifik dari satu transaksi pembelian
     */
    public function show($id)
    {
        // 1. Ambil Data Header Transaksi & Data Supplier
        $pembelian = DB::table('pembelian')
            ->join('supplier', 'pembelian.kd_suplier', '=', 'supplier.id')
            ->select('pembelian.*', 'supplier.nm_suplier', 'supplier.telepon', 'supplier.alamat')
            ->where('pembelian.id', $id)
            ->first();

        if (!$pembelian) {
            return redirect()->route('pembelian.index')->with('error', 'Data transaksi tidak ditemukan.');
        }

        // 2. Ambil Rincian Obat yang Dibeli
        // Catatan: Di database Anda, kolom 'nota' pada pembelian_detail merujuk ke ID tabel pembelian
        $details = DB::table('pembelian_detail')
            ->join('obat', 'pembelian_detail.kd_obat', '=', 'obat.id')
            ->select('pembelian_detail.*', 'obat.nm_obat', 'obat.kd_obat as kode_obat', 'obat.satuan')
            ->where('pembelian_detail.nota', $id)
            ->get();

        return view('pembelian.show', compact('pembelian', 'details'));
    }

    /**
     * Menampilkan Form Transaksi Pembelian (Restock)
     */
    public function create()
    {
        // Ambil semua data obat
        $obats = DB::table('obat')->get();
        // Ambil semua data supplier
        $suppliers = DB::table('supplier')->get();

        return view('pembelian.create', compact('obats', 'suppliers'));
    }

    /**
     * Menyimpan Transaksi Pembelian & Menambah Stok Obat
     */
    public function store(Request $request)
    {
        // 1. Validasi (Tambahkan aturan untuk diskon)
        $request->validate([
            'kd_suplier' => 'required',
            'kd_obat' => 'required|array',
            'jumlah' => 'required|array',
            'harga' => 'required|array',
            'diskon' => 'nullable|numeric|min:0' // <-- Memastikan diskon adalah angka tidak minus
        ]);

        DB::beginTransaction();
        try {
            $nota = 'PEM-' . date('YmdHis');
            $subtotal = 0;

            // 2. Tangkap nilai diskon
            $diskon = $request->diskon ?? 0;

            // 3. Hitung subtotal
            foreach ($request->kd_obat as $key => $obat_id) {
                $subtotal += ($request->harga[$key] * $request->jumlah[$key]);
            }

            // 4. Hitung total bersih dan cegah angka minus
            $total_bersih = $subtotal - $diskon;
            if ($total_bersih < 0) {
                $total_bersih = 0;
            }

            // 5. Simpan Header Pembelian
            $pembelian_id = DB::table('pembelian')->insertGetId([
                'nota' => $nota,
                'tgl_nota' => date('Y-m-d'),
                'user_id' => auth()->user()->id,
                'kd_suplier' => $request->kd_suplier,
                'diskon' => $diskon, // <-- Simpan nilai diskon aktual
                'total_bayar' => $total_bersih, // <-- Simpan hasil perhitungan akhir
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 6. Simpan Detail & Tambah Stok
            foreach ($request->kd_obat as $key => $obat_id) {
                $jumlah_beli = $request->jumlah[$key];

                DB::table('pembelian_detail')->insert([
                    'nota' => $pembelian_id,
                    'kd_obat' => $obat_id,
                    'jumlah' => $jumlah_beli,
                    'harga' => $request->harga[$key], // Menyimpan harga beli saat itu
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // TAMBAH STOK DI TABEL OBAT
                DB::table('obat')->where('id', $obat_id)->increment('stok', $jumlah_beli);
            }

            DB::commit();

            // Langsung arahkan ke halaman struk/nota pembelian
            return redirect()->route('pembelian.show', $pembelian_id)->with('success', 'Transaksi restock berhasil! Stok obat telah bertambah.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses pembelian: ' . $e->getMessage());
        }
    }
}