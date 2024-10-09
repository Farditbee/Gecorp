<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_retur', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('id_users');
            $table->string('id_transaksi');
            $table->string('id_member');
            $table->string('id_toko');
            $table->integer('item');
            $table->double('total_harga');
            $table->string('nama_user');
            $table->softDeletes();

        });
    }
};
