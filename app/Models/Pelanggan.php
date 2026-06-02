<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'kd_pelanggan',
        'nm_pelanggan',
        'alamat',
        'kota',
        'telepon',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi: Satu pelanggan memiliki banyak penjualan
     */
    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'kd_pelanggan', 'id');
    }
}
