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
        Schema::create('temp_detail_pengiriman', function (Blueprint $table) {
            $table->id('id');
            $table->string('id_pengiriman_barang')->nullable();
            $table->string('id_detail_pembelian');
            $table->string('id_barang')->nullable();
            $table->string('id_supplier')->nullable();
            $table->integer('qty')->nullable();
            $table->double('harga')->nullable();
            $table->double('total_harga')->nullable();
            $table->timestamps();
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
