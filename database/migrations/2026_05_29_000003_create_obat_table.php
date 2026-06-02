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
        Schema::create('obat', function (Blueprint $table) {
            $table->id();
            $table->string('kd_obat')->unique()->nullable();
            $table->string('nm_obat');
            $table->string('jenis')->nullable();
            $table->string('satuan')->nullable();
            $table->decimal('harga_beli', 12, 2)->default(0);
            $table->decimal('harga_jual', 12, 2)->default(0);
            $table->integer('stok')->default(0);

            // Foreign Key ke Supplier
            $table->foreignId('kd_suplier')
                ->nullable()
                ->constrained('supplier', 'id')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
