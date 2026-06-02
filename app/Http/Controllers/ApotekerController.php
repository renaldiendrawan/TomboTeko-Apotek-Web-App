<?php

namespace App\Http\Controllers;

use App\Models\Apoteker;
use Illuminate\Http\Request;

class ApotekerController extends Controller
{
    public function index(Request $request)
    {
        $query = Apoteker::query();

        // 1. Pencarian Data (Live Search)
        if ($request->filled('search')) {
            // Gunakan kurung function agar orWhere tidak mengganggu filter lain
            $query->where(function ($q) use ($request) {
                $q->where('nm_apoteker', 'like', '%' . $request->search . '%')
                    ->orWhere('kd_apoteker', 'like', '%' . $request->search . '%');
            });
        }

        // 2. Filter Jenis Kelamin
        if ($request->filled('jk')) {
            $query->where('jk', $request->jk);
        }

        // 3. Pengurutan (Sorting) Abjad Nama Apoteker
        if ($request->sort == 'abjad_asc') {
            $query->orderBy('nm_apoteker', 'asc'); // A - Z
        } elseif ($request->sort == 'abjad_desc') {
            $query->orderBy('nm_apoteker', 'desc'); // Z - A
        } else {
            $query->orderBy('id', 'desc'); // Default: Yang terbaru ditambahkan di atas
        }

        $apotekers = $query->paginate(10)->appends($request->all());
        return view('apoteker.index', compact('apotekers'));
    }

    public function create()
    {
        // Generate Kode Otomatis (Contoh: APT-001)
        $last = Apoteker::orderBy('id', 'desc')->first();
        $nextId = $last ? $last->id + 1 : 1;
        $kd_apoteker = 'APT-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('apoteker.create', compact('kd_apoteker'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kd_apoteker' => 'required|unique:apoteker,kd_apoteker',
            'nm_apoteker' => 'required|string|max:100',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        Apoteker::create($validated);
        return redirect()->route('apoteker.index')->with('success', 'Data Apoteker berhasil ditambahkan!');
    }

    public function show(Apoteker $apoteker)
    {
        return view('apoteker.show', compact('apoteker'));
    }

    public function edit(Apoteker $apoteker)
    {
        return view('apoteker.edit', compact('apoteker'));
    }

    public function update(Request $request, Apoteker $apoteker)
    {
        $validated = $request->validate([
            'kd_apoteker' => 'required|unique:apoteker,kd_apoteker,' . $apoteker->id,
            'nm_apoteker' => 'required|string|max:100',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        $apoteker->update($validated);
        return redirect()->route('apoteker.index')->with('success', 'Data Apoteker berhasil diperbarui!');
    }

    public function destroy(Apoteker $apoteker)
    {
        $apoteker->delete();
        return redirect()->route('apoteker.index')->with('success', 'Data Apoteker berhasil dihapus!');
    }
}