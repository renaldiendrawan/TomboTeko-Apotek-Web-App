<?php

namespace App\Http\Controllers;

use App\Models\Apoteker;
use App\Models\User; // <-- TAMBAHKAN BARIS INI
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
        // 1. Validasi Input (Tambahkan validasi email)
        $request->validate([
            'email' => 'required|email|unique:users,email', // <-- Tambahan untuk akun login
            'kd_apoteker' => 'required|unique:apoteker,kd_apoteker',
            'nm_apoteker' => 'required|string|max:100',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        // 2. Buat Akun User untuk Login
        $user = User::create([
            'name' => $request->nm_apoteker,
            'email' => $request->email,
            'role' => 'apoteker',
            'password' => bcrypt('password123'), // Default password
        ]);

        // 3. Simpan Profil Apoteker dan hubungkan dengan ID User
        Apoteker::create([
            'user_id' => $user->id,
            'kd_apoteker' => $request->kd_apoteker,
            'nm_apoteker' => $request->nm_apoteker,
            'jk' => $request->jk,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('apoteker.index')->with('success', 'Data Apoteker dan Akun Login berhasil dibuat!');
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
        // 1. Validasi ditambah dengan aturan email
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $apoteker->user_id, // Abaikan email milik user ini
            'kd_apoteker' => 'required|unique:apoteker,kd_apoteker,' . $apoteker->id,
            'nm_apoteker' => 'required|string|max:100',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        // 2. Update data profil Apoteker
        $apoteker->update([
            'kd_apoteker' => $request->kd_apoteker,
            'nm_apoteker' => $request->nm_apoteker,
            'jk' => $request->jk,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
        ]);

        // 3. Update data Akun Login (User)
        if ($apoteker->user) {
            $apoteker->user->update([
                'name' => $request->nm_apoteker, // Sinkronkan nama
                'email' => $request->email
            ]);
        }

        return redirect()->route('apoteker.index')->with('success', 'Data Apoteker dan Akun berhasil diperbarui!');
    }

    public function destroy(Apoteker $apoteker)
    {
        $apoteker->delete();
        return redirect()->route('apoteker.index')->with('success', 'Data Apoteker berhasil dihapus!');
    }
}