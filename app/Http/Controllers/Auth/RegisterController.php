<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pelanggan; 
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; 

class RegisterController extends Controller
{
    use RegistersUsers;

    // Ubah dari '/home' menjadi '/' agar pelanggan baru langsung ke halaman depan
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validasi data inputan (tambahkan validasi alamat, kota, telepon)
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telepon' => ['required', 'string', 'max:15'],
            'alamat' => ['required', 'string', 'max:255'],
            'kota' => ['required', 'string', 'max:50'],
        ]);
    }

    /**
     * Simpan data ke tabel Users DAN Pelanggan
     */
    protected function create(array $data)
    {
        // Menggunakan DB::transaction agar jika salah satu gagal, semua dibatalkan
        return DB::transaction(function () use ($data) {
            
            // 1. Simpan ke tabel users
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'pelanggan', // Otomatis role pelanggan
            ]);

            // 2. Buat Kode Pelanggan Otomatis (Contoh: PLG-0001)
            $lastPelanggan = Pelanggan::orderBy('id', 'desc')->first();
            $lastId = $lastPelanggan ? $lastPelanggan->id : 0;
            $kd_pelanggan = 'PLG-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

            // 3. Simpan ke tabel pelanggan
            // nm_pelanggan mengambil langsung dari inputan name user
            Pelanggan::create([
                'kd_pelanggan' => $kd_pelanggan,
                'nm_pelanggan' => $data['name'], 
                'alamat' => $data['alamat'],
                'kota' => $data['kota'] ?? '-',
                'telepon' => $data['telepon'],
            ]);

            return $user;
        });
    }
}