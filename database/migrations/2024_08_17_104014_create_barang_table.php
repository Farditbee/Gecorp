<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id('id');
            $table->enum('garansi', ['Yes', 'No'])->default('No');
            $table->string('nama_barang');
            $table->string('barcode')->nullable();
            $table->string('barcode_path')->nullable();
            $table->string('gambar_path')->nullable();
            $table->string('id_jenis_barang');
            $table->string('id_brand_barang');
            $table->string('level_harga')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

};
