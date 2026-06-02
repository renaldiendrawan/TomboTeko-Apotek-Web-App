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
        Schema::create('pembelian_detail', function (Blueprint $table) {
            $table->id();

            // Foreign Key ke Pembelian
            $table->foreignId('nota')
                ->constrained('pembelian', 'id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Foreign Key ke Obat
            $table->foreignId('kd_obat')
                ->constrained('obat', 'id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->integer('jumlah')->default(0);
            $table->decimal('harga', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_detail');
    }
};
