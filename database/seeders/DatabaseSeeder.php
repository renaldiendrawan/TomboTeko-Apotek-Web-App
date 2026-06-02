<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Seeding Users
        DB::table('users')->insert([
            [
                'name' => 'Administrator',
                'email' => 'admin@apotek.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apoteker',
                'email' => 'apoteker@apotek.com',
                'password' => Hash::make('password123'),
                'role' => 'apoteker',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pelanggan Setia',
                'email' => 'pelanggan@apotek.com',
                'password' => Hash::make('password123'),
                'role' => 'pelanggan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Seeding Supplier
        $supplierId = DB::table('supplier')->insertGetId([
            'kd_suplier' => 'SUP-001',
            'nm_suplier' => 'PT Kimia Farma',
            'alamat' => 'Jl. Veteran No. 10',
            'kota' => 'Jakarta',
            'telepon' => '081234567890',
        ]);

        // 3. Seeding Pelanggan
        $pelangganId = DB::table('pelanggan')->insertGetId([
            'kd_pelanggan' => 'PLG-001',
            'nm_pelanggan' => 'Budi Santoso',
            'alamat' => 'Jl. Merdeka No. 45',
            'kota' => 'Surabaya',
            'telepon' => '089876543210',
        ]);

        // 4. Seeding Obat (Termasuk yang kadaluarsa untuk testing)
        $obatAId = DB::table('obat')->insertGetId([
            'kd_obat' => 'OBT-001',
            'nm_obat' => 'Paracetamol 500mg',
            'jenis' => 'Tablet',
            'satuan' => 'Strip',
            'harga_beli' => 3000,
            'harga_jual' => 5000,
            'stok' => 100,
            'kd_suplier' => $supplierId,
            'tanggal_kedaluwarsa' => Carbon::now()->addYears(2),
        ]);

        $obatBId = DB::table('obat')->insertGetId([
            'kd_obat' => 'OBT-002',
            'nm_obat' => 'Amoxicillin',
            'jenis' => 'Kapsul',
            'satuan' => 'Strip',
            'harga_beli' => 8000,
            'harga_jual' => 12000,
            'stok' => 50,
            'kd_suplier' => $supplierId,
            'tanggal_kedaluwarsa' => Carbon::now()->subDays(5), // Sudah kadaluarsa untuk testing
        ]);

        // 5. Seeding Penjualan
        $penjualanId = DB::table('penjualan')->insertGetId([
            'nota' => 'PJ-1001',
            'tgl_nota' => now(),
            'kd_pelanggan' => $pelangganId,
            'diskon' => 0,
            'total_bayar' => 15000,
        ]);

        // Seeding Detail Penjualan
        DB::table('penjualan_detail')->insert([
            'nota' => $penjualanId,
            'kd_obat' => $obatAId,
            'jumlah' => 3,
            'harga' => 5000,
        ]);
    }
}