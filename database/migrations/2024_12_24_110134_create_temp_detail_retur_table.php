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
        Schema::create('temp_detail_retur', function (Blueprint $table) {
            $table->id();
            $table->string('qrcode');
            $table->string('id_users');
            $table->string('id_retur');
            $table->string('id_transaksi');
            $table->string('id_barang');
            $table->string('no_nota');
            $table->integer('qty');
            $table->double('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_detail_retur');
    }
};
