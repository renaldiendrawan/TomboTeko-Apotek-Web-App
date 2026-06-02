<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $table = 'pembelian_detail';

    protected $fillable = [
        'nota',
        'kd_obat',
        'jumlah',
        'harga',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi: Detail pembelian dimiliki oleh satu pembelian
     */
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'nota', 'id');
    }

    /**
     * Relasi: Detail pembelian dimiliki oleh satu obat
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'kd_obat', 'id');
    }

    /**
     * Accessor: Hitung subtotal (jumlah x harga)
     */
    public function getSubtotalAttribute()
    {
        return $this->jumlah * $this->harga;
    }
}
