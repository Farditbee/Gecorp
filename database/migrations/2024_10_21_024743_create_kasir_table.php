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
        Schema::create('kasir', function (Blueprint $table) {
            $table->id('id');
            $table->string('id_member')->nullable();
            $table->string('id_users');
            $table->date('tgl_transaksi');
            $table->string('id_toko');
            $table->string('no_nota');
            $table->integer('total_item');
            $table->double('total_nilai');
            $table->integer('total_diskon')->nullable();
            $table->double('jml_bayar');
            $table->double('kembalian');
            $table->enum('metode', ['Tunai', 'Non-Tunai']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasir');
    }
};
