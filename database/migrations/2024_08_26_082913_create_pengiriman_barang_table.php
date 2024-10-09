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
            $table->string('no_resi');
            $table->string('toko_pengirim');
            $table->string('nama_pengirim');
            $table->string('ekspedisi');
            $table->string('toko_penerima');
            $table->date('tgl_kirim');
            $table->double('total_item')->nullable();
            $table->double('total_nilai')->nullable();
            $table->date('tgl_terima')->nullable();
            $table->enum('status', ['progress', 'success', 'failed'])->default('progress');
            $table->softDeletes();
        });
    }

};
