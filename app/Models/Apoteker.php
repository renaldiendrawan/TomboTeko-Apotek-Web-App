<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apoteker extends Model
{
    use HasFactory;

    // Memberitahu Laravel nama tabel yang benar di database
    protected $table = 'apoteker';

    // Mengizinkan kolom-kolom ini untuk diisi data
    protected $fillable = ['user_id', 'kd_apoteker', 'nm_apoteker', 'jk', 'telepon', 'alamat'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}