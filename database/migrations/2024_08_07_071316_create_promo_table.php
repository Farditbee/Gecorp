<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('promo', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('id_barang');
            $table->string('id_toko');
            $table->string('nama_barang');
            $table->string('nama_toko');
            $table->double('harga_promo');
            $table->integer('qty');
            $table->datetime('tgl_mulai');
            $table->datetime('tgl_selesai');
            $table->softDeletes();
        });
    }

};
