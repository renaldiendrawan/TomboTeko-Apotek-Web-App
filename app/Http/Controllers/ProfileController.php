<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelanggan;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Cari data pelanggan. Jika akun lama tidak punya data, buatkan data sementara.
        $pelanggan = Pelanggan::firstOrCreate(
            ['nm_pelanggan' => $user->name],
            [
                'kd_pelanggan' => 'PLG-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'telepon' => '',
                'alamat' => '',
                'kota' => ''
            ]
        );

        return view('profil.index', compact('user', 'pelanggan'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:50',
        ]);

        // Cari atau buat data pelanggan
        $pelanggan = Pelanggan::firstOrCreate(
            ['nm_pelanggan' => $user->name],
            [
                'kd_pelanggan' => 'PLG-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)
            ]
        );

        // Update data pelanggan
        $pelanggan->update([
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
        ]);

        return back()->with('success', 'Data profil dan alamat pengiriman berhasil diperbarui!');
    }
}