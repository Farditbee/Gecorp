<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_barang', function (Blueprint $table) {
            $table->id('id');
            $table->string('id_barang')->nullable();
            $table->string('nama_barang')->nullable();
            $table->integer('stock')->nullable();
            $table->decimal('hpp_awal', 15, 2)->nullable();
            $table->decimal('hpp_baru', 15, 2)->nullable();
            $table->decimal('nilai_total', 15, 2)->nullable();
            $table->string('level_harga')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_barang');
    }
};
