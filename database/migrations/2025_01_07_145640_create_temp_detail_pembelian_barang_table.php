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
        Schema::create('temp_detail_pembelian_barang', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('qrcode')->nullable();
            $table->string('qrcode_path')->nullable();
            $table->string('id_pembelian_barang')->nullable();
            $table->string('id_barang')->nullable();
            $table->integer('qty')->nullable();
            $table->double('harga_barang')->nullable();
            $table->double('total_harga')->nullable();
            $table->json('level_harga')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_detail_pembelian_barang');
    }
};
