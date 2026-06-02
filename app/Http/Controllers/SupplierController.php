<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a listing of the supplier.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Supplier::query();

        // Logika Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('nm_suplier', 'like', '%' . $request->search . '%')
                ->orWhere('kd_suplier', 'like', '%' . $request->search . '%');
        }

        // Simpan query pencarian ke pagination agar tidak hilang saat ganti halaman
        $suppliers = $query->orderBy('id', 'desc')->paginate(10)->appends($request->all());

        return view('supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kd_suplier' => 'required|unique:supplier|max:20', // (atau rules yang sesuai sebelumnya)
            'nm_suplier' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:50', // <-- UBAH DARI nullable MENJADI required
            'telepon' => 'required|string|max:15',
        ], [
            'kd_suplier.required' => 'Kode supplier wajib diisi',
            'kd_suplier.unique' => 'Kode supplier sudah terdaftar',
            'nm_suplier.required' => 'Nama supplier wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'kota.required' => 'Kota wajib diisi', // <-- TAMBAHKAN PESAN INI
            'telepon.required' => 'Nomor telepon wajib diisi',
        ]);

        Supplier::create($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'kd_suplier' => 'required|unique:supplier|max:20', // (atau rules yang sesuai sebelumnya)
            'nm_suplier' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:50', // <-- UBAH DARI nullable MENJADI required
            'telepon' => 'required|string|max:15',
        ], [
            'kd_suplier.required' => 'Kode supplier wajib diisi',
            'kd_suplier.unique' => 'Kode supplier sudah terdaftar',
            'nm_suplier.required' => 'Nama supplier wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'kota.required' => 'Kota wajib diisi', // <-- TAMBAHKAN PESAN INI
            'telepon.required' => 'Nomor telepon wajib diisi',
        ]);

        $supplier->update($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diperbarui');
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return redirect()->route('supplier.index')
                ->with('success', 'Supplier berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('supplier.index')
                ->with('error', 'Tidak dapat menghapus supplier yang masih memiliki obat terkait');
        }
    }
}
