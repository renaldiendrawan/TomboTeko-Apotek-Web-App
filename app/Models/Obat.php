<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';

    protected $fillable = [
        'kd_obat', 
        'nm_obat', 
        'jenis', 
        'satuan', 
        'harga_beli', 
        'harga_jual', 
        'stok', 
        'tanggal_kedaluwarsa', 
        'gambar', 
        'kd_suplier' // Tambahkan ini
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'tanggal_kedaluwarsa' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi: Obat dimiliki oleh satu supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'kd_suplier', 'id');
    }

    /**
     * Relasi: Satu obat memiliki banyak detail penjualan
     */
    public function penjualanDetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'kd_obat', 'id');
    }

    /**
     * Relasi: Satu obat memiliki banyak detail pembelian
     */
    public function pembelianDetail()
    {
        return $this->hasMany(PembelianDetail::class, 'kd_obat', 'id');
    }
}
