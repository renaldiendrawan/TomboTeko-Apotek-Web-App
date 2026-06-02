<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ObatController extends Controller
{
    public function __construct()
    {

    }

    public function show($id)
    {
        // Menampilkan detail obat khusus untuk Admin/Apoteker
        $obat = Obat::findOrFail($id);
        return view('obat.show', compact('obat'));
    }

    /**
     * Display a listing of the obat.
     */
    /**
     * Menampilkan daftar obat utama dengan fitur pencarian
     */
    /**
     * Menampilkan daftar obat utama dengan fitur pencarian, filter, dan kedaluwarsa
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $filter_jenis = $request->query('jenis');
        $sort = $request->query('sort');
        $filter = $request->query('filter'); // <-- Tambahan untuk menangkap klik tombol Kedaluwarsa

        $query = \App\Models\Obat::query();

        // 1. Live Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nm_obat', 'like', "%{$search}%")
                    ->orWhere('kd_obat', 'like', "%{$search}%");
            });
        }

        // 2. Filter Jenis (Bawaan Anda sebelumnya)
        if ($filter_jenis) {
            $query->where('jenis', $filter_jenis);
        }

        // 3. Fitur Filter Obat Kedaluwarsa (Mencari obat yang expired atau < 30 hari lagi)
        if ($filter == 'kadaluarsa') {
            $query->whereDate('tanggal_kedaluwarsa', '<=', now()->addDays(30));
        }

        // 4. Sorting (Pengurutan Bawaan Anda)
        if ($sort == 'abjad_asc') {
            $query->orderBy('nm_obat', 'asc');
        } elseif ($sort == 'abjad_desc') {
            $query->orderBy('nm_obat', 'desc');
        } elseif ($sort == 'harga_asc') {
            $query->orderBy('harga_jual', 'asc');
        } elseif ($sort == 'harga_desc') {
            $query->orderBy('harga_jual', 'desc');
        } elseif ($sort == 'exp_asc') {
            $query->orderBy('tanggal_kedaluwarsa', 'asc');
        } elseif ($sort == 'exp_desc') {
            $query->orderBy('tanggal_kedaluwarsa', 'desc');
        } else {
            $query->orderBy('id', 'desc'); // Default terbaru
        }

        // Ambil data dengan Pagination (appends request agar filter tidak hilang saat ganti halaman)
        $obats = $query->paginate(10)->appends($request->all());

        // Ambil daftar jenis unik dari database untuk Dropdown
        $list_jenis = \App\Models\Obat::select('jenis')->whereNotNull('jenis')->distinct()->pluck('jenis');

        // Hitung notifikasi kedaluwarsa
        $jumlah_kadaluarsa = \App\Models\Obat::whereDate('tanggal_kedaluwarsa', '<', now())->count();

        return view('obat.index', compact('obats', 'search', 'filter_jenis', 'sort', 'list_jenis', 'jumlah_kadaluarsa'));
    }
    /**
     * Menampilkan Halaman Khusus Obat Kedaluwarsa
     */
    /**
     * Menampilkan Halaman Khusus Obat Kedaluwarsa dengan Filter & Pencarian
     */
    public function kadaluarsa(Request $request)
    {
        $search = $request->query('search');
        $filter_jenis = $request->query('jenis');
        $sort = $request->query('sort');

        // Query dasar: Hanya mengambil obat yang sudah kedaluwarsa
        $query = \App\Models\Obat::whereDate('tanggal_kedaluwarsa', '<', now());

        // 1. Live Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nm_obat', 'like', "%{$search}%")
                    ->orWhere('kd_obat', 'like', "%{$search}%");
            });
        }

        // 2. Filter Jenis
        if ($filter_jenis) {
            $query->where('jenis', $filter_jenis);
        }

        // 3. Sorting (Pengurutan)
        if ($sort == 'abjad_asc') {
            $query->orderBy('nm_obat', 'asc');
        } elseif ($sort == 'abjad_desc') {
            $query->orderBy('nm_obat', 'desc');
        } elseif ($sort == 'exp_asc') {
            $query->orderBy('tanggal_kedaluwarsa', 'asc'); // Yang paling lama kedaluwarsa di atas
        } elseif ($sort == 'exp_desc') {
            $query->orderBy('tanggal_kedaluwarsa', 'desc'); // Yang baru saja kedaluwarsa di atas
        } else {
            $query->orderBy('tanggal_kedaluwarsa', 'asc'); // Default urutan
        }

        // Ambil data dengan Pagination
        $obats = $query->paginate(10)->appends($request->all());

        // Ambil daftar jenis unik khusus untuk dropdown
        $list_jenis = \App\Models\Obat::select('jenis')->whereNotNull('jenis')->distinct()->pluck('jenis');

        return view('obat.kadaluarsa', compact('obats', 'search', 'filter_jenis', 'sort', 'list_jenis'));
    }

    /**
     * Show the form for creating a new obat.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('obat.create', compact('suppliers'));
    }

    /**
     * Store a newly created obat in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi inputan dari form (Tambahkan harga_beli)
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kategori' => 'required|string',
            'kd_suplier' => 'required',
            'harga_beli' => 'required|numeric|min:0', // <-- Tambahan validasi
            'harga' => 'required|numeric|min:0',
            'stok_awal' => 'required|integer|min:0',
            'satuan' => 'required|string',
            'tanggal_kedaluwarsa' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Generate Kode Obat Otomatis
        $lastObat = \App\Models\Obat::orderBy('id', 'desc')->first();
        $lastId = $lastObat ? $lastObat->id : 0;
        $kd_obat = 'OBT-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        // 3. Proses Upload Gambar
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('obat_images', 'public');
        }

        // 4. Simpan ke Database
        \App\Models\Obat::create([
            'kd_obat' => $kd_obat,
            'nm_obat' => $request->nama_obat,
            'jenis' => $request->kategori,
            'kd_suplier' => $request->kd_suplier,
            'satuan' => $request->satuan,
            'harga_beli' => $request->harga_beli, // <-- Ubah bagian ini
            'harga_jual' => $request->harga,
            'stok' => $request->stok_awal,
            'tanggal_kedaluwarsa' => $request->tanggal_kedaluwarsa,
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified obat.
     */
    public function edit($id)
    {
        $obat = \App\Models\Obat::findOrFail($id);

        // Ambil data supplier untuk mengisi dropdown di form edit
        $suppliers = \App\Models\Supplier::all();

        // Pastikan nama view sesuai dengan milik Anda (misal 'obat.edit' atau 'obat_edit')
        return view('obat.edit', compact('obat', 'suppliers', 'id'));
    }
    /**
     * Update the specified obat in storage.
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi inputan form edit (Tambahkan kd_suplier & tanggal_kedaluwarsa)
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kd_suplier' => 'required', // <-- Tambahan
            'kategori' => 'required|string',
            'harga_beli' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string',
            'tanggal_kedaluwarsa' => 'required|date', // <-- Tambahan
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $obat = \App\Models\Obat::findOrFail($id);

        // 2. Proses Upload Gambar Baru
        if ($request->hasFile('gambar')) {
            if ($obat->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists($obat->gambar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($obat->gambar);
            }
            $gambarPath = $request->file('gambar')->store('obat_images', 'public');
            $obat->gambar = $gambarPath;
        }

        // 3. Update data ke Database
        $obat->nm_obat = $request->nama_obat;
        $obat->kd_suplier = $request->kd_suplier; // <-- Update Suplier
        $obat->jenis = $request->kategori;
        $obat->satuan = $request->satuan;
        $obat->harga_beli = $request->harga_beli;
        $obat->harga_jual = $request->harga;
        $obat->stok = $request->stok;
        $obat->tanggal_kedaluwarsa = $request->tanggal_kedaluwarsa; // <-- Update Tanggal Kedaluwarsa

        $obat->save();

        return redirect()->route('obat.index')->with('success', 'Data obat ' . $request->nama_obat . ' berhasil diperbarui!');
    }

    /**
     * Remove the specified obat from storage.
     */
    public function destroy(Obat $obat)
    {
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('success', 'Obat berhasil dihapus');
    }

    /**
     * Fungsi untuk menampilkan katalog obat bagi Pelanggan
     */
    public function katalog(Request $request)
    {
        // Menangkap request dari form pencarian, filter, dan sort
        $search = $request->query('search');
        $filter_jenis = $request->query('jenis');
        $sort = $request->query('sort');

        // Query dasar: Hanya tampilkan obat yang stoknya lebih dari 0
        $query = Obat::where('stok', '>', 0);

        // 1. Logika Pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nm_obat', 'like', "%{$search}%")
                    ->orWhere('kd_obat', 'like', "%{$search}%");
            });
        }

        // 2. Logika Filter Jenis
        if ($filter_jenis) {
            $query->where('jenis', $filter_jenis);
        }

        // 3. Logika Sorting (Pengurutan)
        if ($sort == 'termurah') {
            $query->orderBy('harga_jual', 'asc');
        } elseif ($sort == 'termahal') {
            $query->orderBy('harga_jual', 'desc');
        } elseif ($sort == 'a-z') {
            $query->orderBy('nm_obat', 'asc');
        } elseif ($sort == 'z-a') {
            $query->orderBy('nm_obat', 'desc');
        } else {
            $query->orderBy('nm_obat', 'asc'); // Default urut abjad
        }

        // Eksekusi query dengan pagination, appends() agar url query tidak hilang saat pindah halaman
        $obats = $query->paginate(12)->appends($request->all());

        // Mengambil daftar jenis obat yang unik dari database untuk dropdown filter
        $list_jenis = Obat::select('jenis')->distinct()->pluck('jenis');

        return view('katalog.index', compact('obats', 'search', 'filter_jenis', 'sort', 'list_jenis'));
    }

    /**
     * Fungsi untuk menampilkan detail obat saat diklik Pelanggan
     */
    public function detailKatalog($id)
    {
        $obat = Obat::findOrFail($id);

        return view('katalog.show', compact('obat'));
    }
}
