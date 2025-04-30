<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pengiriman_barang', function (Blueprint $table) {
            $table->id('id');
            $table->string('id_retur')->nullable();
            $table->string('no_resi');
            $table->string('toko_pengirim');
            $table->string('nama_pengirim');
            $table->string('ekspedisi');
            $table->string('toko_penerima');
            $table->dateTime('tgl_kirim');
            $table->integer('total_item')->nullable();
            $table->double('total_nilai')->nullable();
            $table->dateTime('tgl_terima')->nullable();
            $table->enum('status', ['pending', 'progress', 'success', 'canceled'])->default('pending');
            $table->enum('tipe_pengiriman', ['mutasi', 'reture'])->default('mutasi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

};
