<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_detail';

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
     * Relasi: Detail penjualan dimiliki oleh satu penjualan
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'nota', 'id');
    }

    /**
     * Relasi: Detail penjualan dimiliki oleh satu obat
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
