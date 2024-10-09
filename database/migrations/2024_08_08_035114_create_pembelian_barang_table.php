<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian_barang', function (Blueprint $table) {
            $table->id('id');
            $table->string('id_supplier');
            $table->string('id_users')->nullable();
            $table->string('no_nota')->nullable();
            $table->dateTime('tgl_nota')->nullable();
            $table->string('nama_supplier')->nullable();
            $table->string('nama_users')->nullable();
            $table->double('total_item')->nullable();
            $table->double('total_nilai')->nullable();
            $table->enum('status', ['progress', 'success', 'failed','mixed'])->default('progress');
            $table->softDeletes();
        });
    }

};
