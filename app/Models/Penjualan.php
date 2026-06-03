<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = ['nota', 'tgl_nota', 'user_id', 'kd_pelanggan', 'diskon', 'total_bayar'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'tgl_nota' => 'date',
        'diskon' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi: Penjualan dimiliki oleh satu pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'kd_pelanggan', 'id');
    }

    /**
     * Relasi: Satu penjualan memiliki banyak detail penjualan
     */
    public function detail()
    {
        return $this->hasMany(PenjualanDetail::class, 'nota', 'id');
    }

    /**
     * Alias untuk relasi detail
     */
    public function penjualanDetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'nota', 'id');
    }
}
