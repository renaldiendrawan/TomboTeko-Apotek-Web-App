<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Obat;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Fungsi: Tampilkan laporan penjualan
     */
    public function penjualan(Request $request)
    {
        $query = Penjualan::with(['pelanggan', 'detail.obat']);

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai')) {
            $tanggal_mulai = Carbon::createFromFormat('Y-m-d', $request->tanggal_mulai)->startOfDay();
            $query->whereDate('tgl_nota', '>=', $tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $tanggal_akhir = Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay();
            $query->whereDate('tgl_nota', '<=', $tanggal_akhir);
        }

        // Filter berdasarkan bulan & tahun
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tgl_nota', $request->bulan)
                ->whereYear('tgl_nota', $request->tahun);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('tgl_nota', $request->tahun);
        }

        // === KUMPULKAN DATA UNTUK GRAFIK (CHART.JS) ===
        $chartQuery = clone $query;
        $grafik = $chartQuery->selectRaw('DATE(tgl_nota) as tanggal, SUM(total_bayar) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Siapkan Label (Sumbu X) dan Data (Sumbu Y)
        $chart_labels = $grafik->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->translatedFormat('d M');
        })->toArray();
        $chart_totals = $grafik->pluck('total')->toArray();
        // ==============================================

        // Order by date descending untuk tabel
        $penjualan = $query->orderBy('tgl_nota', 'desc')->paginate(15)->appends($request->all());

        // Calculate totals untuk Card Summary
        $total_transaksi = $penjualan->total();

        $total_penjualan_query = clone $query;
        $total_penjualan = $total_penjualan_query->sum('total_bayar');

        $total_diskon_query = clone $query;
        $total_diskon = $total_diskon_query->sum('diskon');

        return view('laporan.penjualan', compact(
            'penjualan',
            'total_transaksi',
            'total_penjualan',
            'total_diskon',
            'chart_labels',
            'chart_totals'
        ));
    }

    /**
     * Fungsi: Tampilkan Laporan Pembelian (Pengeluaran/Restock)
     */
    public function pembelian(Request $request)
    {
        $query = \Illuminate\Support\Facades\DB::table('pembelian')
            ->join('supplier', 'pembelian.kd_suplier', '=', 'supplier.id')
            ->select('pembelian.*', 'supplier.nm_suplier');

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('pembelian.tgl_nota', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('pembelian.tgl_nota', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('pembelian.tgl_nota', $request->bulan)
                ->whereYear('pembelian.tgl_nota', $request->tahun);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('pembelian.tgl_nota', $request->tahun);
        }

        // === KUMPULKAN DATA UNTUK GRAFIK (CHART.JS) ===
        $chartQuery = clone $query;

        // Gunakan select() dengan DB::raw untuk menimpa kolom sebelumnya agar terhindar dari Error Group By
        $grafik = $chartQuery->select(
            \Illuminate\Support\Facades\DB::raw('DATE(pembelian.tgl_nota) as tanggal'),
            \Illuminate\Support\Facades\DB::raw('SUM(pembelian.total_bayar) as total')
        )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $chart_labels = $grafik->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->translatedFormat('d M'); // <-- Sudah diperbaiki
        })->toArray();
        $chart_totals = $grafik->pluck('total')->toArray();
        // ==============================================

        $pembelians = $query->orderBy('pembelian.tgl_nota', 'desc')->paginate(15)->appends($request->all());

        // Ambil rincian detail item untuk nota yang sedang ditampilkan saja (efisiensi query)
        $pembelian_ids = $pembelians->pluck('id');
        $details = \Illuminate\Support\Facades\DB::table('pembelian_detail')
            ->join('obat', 'pembelian_detail.kd_obat', '=', 'obat.id')
            ->whereIn('pembelian_detail.nota', $pembelian_ids)
            ->select('pembelian_detail.*', 'obat.nm_obat', 'obat.satuan')
            ->get()
            ->groupBy('nota');

        $total_transaksi = $pembelians->total();

        $total_pembelian_query = clone $query;
        $total_pembelian = $total_pembelian_query->sum('pembelian.total_bayar');

        return view('laporan.pembelian', compact(
            'pembelians',
            'details',
            'total_transaksi',
            'total_pembelian',
            'chart_labels',
            'chart_totals'
        ));
    }

    /**
     * Fungsi: Export laporan penjualan ke PDF/Excel
     */
    public function exportPenjualan(Request $request)
    {
        $query = Penjualan::with(['pelanggan', 'detail.obat']);

        // Apply same filters
        if ($request->filled('tanggal_mulai')) {
            $tanggal_mulai = Carbon::createFromFormat('Y-m-d', $request->tanggal_mulai)->startOfDay();
            $query->whereDate('tgl_nota', '>=', $tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $tanggal_akhir = Carbon::createFromFormat('Y-m-d', $request->tanggal_akhir)->endOfDay();
            $query->whereDate('tgl_nota', '<=', $tanggal_akhir);
        }

        $penjualan = $query->orderBy('tgl_nota', 'desc')->get();

        // Get totals
        $total_penjualan = $penjualan->sum('total_bayar');
        $total_diskon = $penjualan->sum('diskon');

        return view('laporan.export_penjualan', [
            'penjualan' => $penjualan,
            'total_penjualan' => $total_penjualan,
            'total_diskon' => $total_diskon,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
        ]);
    }

    /**
     * Fungsi: Tampilkan laporan obat kadaluarsa
     */
    public function obatKadaluarsa(Request $request)
    {
        $hari_ini = Carbon::now()->toDateString();
        $tiga_puluh_hari_ke_depan = Carbon::now()->addDays(30)->toDateString();

        $query = Obat::with('supplier');

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'sudah_kadaluarsa') {
                $query->whereNotNull('tanggal_kedaluwarsa')
                    ->whereDate('tanggal_kedaluwarsa', '<', $hari_ini);
            } elseif ($request->status === 'hampir_kadaluarsa') {
                $query->whereNotNull('tanggal_kedaluwarsa')
                    ->whereDate('tanggal_kedaluwarsa', '>=', $hari_ini)
                    ->whereDate('tanggal_kedaluwarsa', '<=', $tiga_puluh_hari_ke_depan);
            }
        } else {
            // Default: show both expired and expiring soon
            $query->whereNotNull('tanggal_kedaluwarsa')
                ->where(function ($q) use ($hari_ini, $tiga_puluh_hari_ke_depan) {
                    $q->whereDate('tanggal_kedaluwarsa', '<', $hari_ini)
                        ->orWhereDate('tanggal_kedaluwarsa', '<=', $tiga_puluh_hari_ke_depan);
                });
        }

        // Filter berdasarkan jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', 'like', "%{$request->jenis}%");
        }

        // Filter berdasarkan supplier
        if ($request->filled('kd_suplier')) {
            $query->where('kd_suplier', $request->kd_suplier);
        }

        $obat = $query->orderBy('tanggal_kedaluwarsa', 'asc')->paginate(15);

        // Get suppliers for filter
        $supplier = \App\Models\Supplier::all();

        // Calculate stats
        $sudah_kadaluarsa = Obat::whereNotNull('tanggal_kedaluwarsa')
            ->whereDate('tanggal_kedaluwarsa', '<', $hari_ini)
            ->count();
        $hampir_kadaluarsa = Obat::whereNotNull('tanggal_kedaluwarsa')
            ->whereDate('tanggal_kedaluwarsa', '>=', $hari_ini)
            ->whereDate('tanggal_kedaluwarsa', '<=', $tiga_puluh_hari_ke_depan)
            ->count();

        return view('laporan.obat_kadaluarsa', [
            'obat' => $obat,
            'supplier' => $supplier,
            'status' => $request->status,
            'jenis' => $request->jenis,
            'kd_suplier' => $request->kd_suplier,
            'sudah_kadaluarsa' => $sudah_kadaluarsa,
            'hampir_kadaluarsa' => $hampir_kadaluarsa,
            'hari_ini' => $hari_ini,
        ]);
    }

    /**
     * Fungsi: Tampilkan dashboard laporan
     */
    /**
     * Fungsi: Tampilkan dashboard laporan
     */
    public function dashboard(Request $request)
    {
        $hari_ini = Carbon::now()->toDateString();
        $tiga_puluh_hari_ke_depan = Carbon::now()->addDays(30)->toDateString();

        // Data penjualan hari ini
        $penjualan_hari_ini = Penjualan::whereDate('tgl_nota', $hari_ini)->sum('total_bayar');
        $jumlah_transaksi_hari_ini = Penjualan::whereDate('tgl_nota', $hari_ini)->count();

        // Data penjualan bulan ini
        $penjualan_bulan_ini = Penjualan::whereMonth('tgl_nota', Carbon::now()->month)
            ->whereYear('tgl_nota', Carbon::now()->year)
            ->sum('total_bayar');

        // Data obat kadaluarsa
        $obat_kadaluarsa = Obat::whereNotNull('tanggal_kedaluwarsa')
            ->whereDate('tanggal_kedaluwarsa', '<', $hari_ini)
            ->count();

        $obat_hampir_kadaluarsa = Obat::whereNotNull('tanggal_kedaluwarsa')
            ->whereDate('tanggal_kedaluwarsa', '>=', $hari_ini)
            ->whereDate('tanggal_kedaluwarsa', '<=', $tiga_puluh_hari_ke_depan)
            ->count();

        // Data stok rendah
        $stok_rendah = Obat::where('stok', '<=', 10)->count();


        // === LOGIKA BARU: 5 OBAT TERLARIS DENGAN FILTER PERIODE ===
        $periode_laris = $request->query('periode_laris', 'all'); // Default: Sepanjang waktu

        $top_products = Obat::select('obat.*')
            ->selectSub(function ($query) use ($periode_laris) {
                $query->from('penjualan_detail')
                    ->join('penjualan', 'penjualan_detail.nota', '=', 'penjualan.id')
                    ->whereColumn('penjualan_detail.kd_obat', 'obat.id')
                    ->selectRaw('COALESCE(SUM(jumlah), 0)');

                // Terapkan filter berdasarkan pilihan dropdown
                if ($periode_laris == 'hari_ini') {
                    $query->whereDate('penjualan.tgl_nota', Carbon::now()->toDateString());
                } elseif ($periode_laris == 'bulan_ini') {
                    $query->whereMonth('penjualan.tgl_nota', Carbon::now()->month)
                        ->whereYear('penjualan.tgl_nota', Carbon::now()->year);
                } elseif ($periode_laris == 'tahun_ini') {
                    $query->whereYear('penjualan.tgl_nota', Carbon::now()->year);
                }
            }, 'total_terjual')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        return view('laporan.index', [
            'penjualan_hari_ini' => $penjualan_hari_ini,
            'jumlah_transaksi_hari_ini' => $jumlah_transaksi_hari_ini,
            'penjualan_bulan_ini' => $penjualan_bulan_ini,
            'obat_kadaluarsa' => $obat_kadaluarsa,
            'obat_hampir_kadaluarsa' => $obat_hampir_kadaluarsa,
            'stok_rendah' => $stok_rendah,
            'top_products' => $top_products,
            'periode_laris' => $periode_laris, // Kirim variabel ini ke tampilan
        ]);
    }
}
