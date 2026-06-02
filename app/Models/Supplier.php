<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $fillable = [
        'kd_suplier',
        'nm_suplier',
        'alamat',
        'kota',
        'telepon',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi: Satu supplier memiliki banyak obat
     */
    public function obat()
    {
        return $this->hasMany(Obat::class, 'kd_suplier', 'id');
    }

    /**
     * Relasi: Satu supplier memiliki banyak pembelian
     */
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'kd_suplier', 'id');
    }
}
