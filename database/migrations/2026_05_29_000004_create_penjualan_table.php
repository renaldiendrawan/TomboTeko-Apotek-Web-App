<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('nota')->unique();
            $table->date('tgl_nota');

            // Foreign Key ke Pelanggan
            $table->foreignId('kd_pelanggan')
                ->nullable()
                ->constrained('pelanggan', 'id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('total_bayar', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
