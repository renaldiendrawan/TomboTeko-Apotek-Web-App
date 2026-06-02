<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // Jika pelanggan tersasar ke /dashboard, kembalikan ke katalog
        if ($user->role == 'pelanggan') {
            return redirect()->route('katalog.index');
        }

        // =======================================================
        // 1. DATA BERSAMA (SHARED) - Muncul di Admin & Apoteker
        // =======================================================
        $total_jenis_obat = Obat::count();
        $total_stok_obat = Obat::sum('stok');

        if (Schema::hasTable('penjualan_detail')) {
            $obat_terjual = DB::table('obat')
                ->join('penjualan_detail', 'obat.id', '=', 'penjualan_detail.kd_obat')
                ->select('obat.id', 'obat.nm_obat', 'obat.jenis', DB::raw('SUM(penjualan_detail.jumlah) as total_terjual'))
                ->groupBy('obat.id', 'obat.nm_obat', 'obat.jenis')
                ->orderBy('total_terjual', 'desc')
                ->take(10)
                ->get();
        } else {
            $obat_terjual = collect([]);
        }

        // =======================================================
        // 2. DATA KHUSUS ADMIN (Finansial)
        // =======================================================
        if ($user->role == 'admin') {
            $total_pendapatan = DB::table('penjualan')->sum('total_bayar');
            $laba_bersih = DB::table('penjualan_detail')
                ->join('obat', 'penjualan_detail.kd_obat', '=', 'obat.id')
                ->sum(DB::raw('(penjualan_detail.harga - obat.harga_beli) * penjualan_detail.jumlah'));
            $total_transaksi = DB::table('penjualan')->count();

            return view('dashboard.admin', compact(
                'total_jenis_obat',
                'total_stok_obat',
                'obat_terjual',
                'total_pendapatan',
                'laba_bersih',
                'total_transaksi'
            ));
        }

        // =======================================================
        // 3. DATA KHUSUS APOTEKER (Operasional Darurat)
        // =======================================================
        if ($user->role == 'apoteker') {
            $stok_habis = Obat::where('stok', '<=', 0)->count();
            $stok_menipis = Obat::where('stok', '>', 0)->where('stok', '<=', 20)->count();
            $obat_kedaluwarsa = Obat::whereDate('tanggal_kedaluwarsa', '<', now())->count();

            // Ambil data obat yang perlu direstock
            $obat_kritis = Obat::where('stok', '<=', 20)->orderBy('stok', 'asc')->take(5)->get();

            return view('dashboard', compact(
                'total_jenis_obat',
                'total_stok_obat',
                'obat_terjual',
                'stok_habis',
                'stok_menipis',
                'obat_kedaluwarsa',
                'obat_kritis'
            ));
        }

        return view('dashboard', compact('total_jenis_obat', 'total_stok_obat', 'obat_terjual'));
    }
}