<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('transaksi_kasir', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->integer('no_nota');
            $table->string('id_member');
            $table->string('id_toko');
            $table->string('id_user');
            $table->dateTime('tgl_transaksi');
            $table->string('nama_member');
            $table->string('nama_toko');
            $table->string('item');
            $table->string('barang');
            $table->double('total_harga');
            $table->string('payment');
            $table->string('nama_user');
            $table->softDeletes();
        });
    }

};
