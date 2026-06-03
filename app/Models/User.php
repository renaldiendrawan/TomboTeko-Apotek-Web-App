<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Wajib ditambahkan agar role bisa diinput
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi 1-to-1 ke profil Apoteker
    public function apoteker()
    {
        return $this->hasOne(Apoteker::class, 'user_id');
    }

    // Relasi 1-to-Many ke Penjualan
    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'user_id');
    }

    // Relasi 1-to-Many ke Pembelian
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'user_id');
    }
}