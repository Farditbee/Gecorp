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
        Schema::create('detail_pengiriman_barang', function (Blueprint $table) {
            $table->id('id');
            $table->string('id_pengiriman_barang')->nullable();
            $table->string('id_detail_pembelian')->nullable();
            $table->string('id_barang')->nullable();
            $table->string('id_supplier')->nullable();
            $table->integer('qty')->nullable();
            $table->double('harga')->nullable();
            $table->double('total_harga')->nullable();
            $table->enum('status', ['success', 'failed', 'progress'])->default('progress')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
