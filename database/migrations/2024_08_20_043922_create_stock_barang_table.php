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
            $table->double('hpp_awal')->nullable();
            $table->double('hpp_baru')->nullable();
            $table->double('nilai_total')->nullable();
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
