<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a listing of the pelanggan.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Pelanggan::query();

        // Logika Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('nm_pelanggan', 'like', '%' . $request->search . '%')
                ->orWhere('kd_pelanggan', 'like', '%' . $request->search . '%');
        }

        $pelanggans = $query->orderBy('id', 'desc')->paginate(10)->appends($request->all());

        return view('pelanggan.index', compact('pelanggans'));
    }

    /**
     * Show the form for creating a new pelanggan.
     */
    public function create()
    {
        return view('pelanggan.create');
    }

    /**
     * Store a newly created pelanggan in storage.
     */
    public function store(Request $request)
    {
        // Ubah bagian ini (di dalam fungsi store DAN update)
        $validated = $request->validate([
            'kd_pelanggan' => 'required|unique:pelanggan|max:20', // (atau rules yang sesuai sebelumnya)
            'nm_pelanggan' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:50', // <-- UBAH DARI nullable MENJADI required
            'telepon' => 'required|string|max:15',
        ], [
            'kd_pelanggan.required' => 'Kode pelanggan wajib diisi',
            'kd_pelanggan.unique' => 'Kode pelanggan sudah terdaftar',
            'nm_pelanggan.required' => 'Nama pelanggan wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'kota.required' => 'Kota wajib diisi', // <-- TAMBAHKAN PESAN INI
            'telepon.required' => 'Nomor telepon wajib diisi',
        ]);

        Pelanggan::create($validated);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified pelanggan.
     */
    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update the specified pelanggan in storage.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            // PERBAIKAN: Tambahkan pengecualian ID di akhir aturan unique (. $pelanggan->id)
            'kd_pelanggan' => 'required|unique:pelanggan,kd_pelanggan,' . $pelanggan->id . '|max:20',
            'nm_pelanggan' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:50',
            'telepon' => 'required|string|max:15',
        ], [
            'kd_pelanggan.required' => 'Kode pelanggan wajib diisi',
            'kd_pelanggan.unique' => 'Kode pelanggan sudah terdaftar',
            'nm_pelanggan.required' => 'Nama pelanggan wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'kota.required' => 'Kota wajib diisi',
            'telepon.required' => 'Nomor telepon wajib diisi',
        ]);

        $pelanggan->update($validated);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil diperbarui');
    }

    /**
     * Remove the specified pelanggan from storage.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        try {
            $pelanggan->delete();
            return redirect()->route('pelanggan.index')
                ->with('success', 'Pelanggan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('pelanggan.index')
                ->with('error', 'Tidak dapat menghapus pelanggan yang masih memiliki riwayat penjualan');
        }
    }
}
