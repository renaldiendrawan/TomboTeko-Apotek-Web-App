<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = ['nota', 'tgl_nota', 'user_id', 'kd_suplier', 'diskon', 'total_bayar'];

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
     * Relasi: Pembelian dimiliki oleh satu supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'kd_suplier', 'id');
    }

    /**
     * Relasi: Satu pembelian memiliki banyak detail pembelian
     */
    public function detail()
    {
        return $this->hasMany(PembelianDetail::class, 'nota', 'id');
    }

    /**
     * Alias untuk relasi detail
     */
    public function pembelianDetail()
    {
        return $this->hasMany(PembelianDetail::class, 'nota', 'id');
    }
}
