<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah user_id ke tabel apoteker (Relasi 1-to-1)
        Schema::table('apoteker', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
        });

        // 2. Tambah user_id ke tabel pembelian
        Schema::table('pembelian', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('tgl_nota')->constrained('users')->onDelete('set null');
        });

        // 3. Tambah user_id ke tabel penjualan
        Schema::table('penjualan', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('tgl_nota')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('apoteker', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};