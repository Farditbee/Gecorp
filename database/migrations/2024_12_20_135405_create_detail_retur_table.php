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
        Schema::create('detail_retur', function (Blueprint $table) {
            $table->id();
            $table->string('qrcode');
            $table->string('id_users');
            $table->string('id_retur');
            $table->string('id_transaksi');
            $table->string('id_barang');
            $table->string('no_nota');
            $table->integer('qty');
            $table->integer('qty_acc')->nullable();
            $table->double('harga');
            $table->double('hpp_jual')->nullable();
            $table->double('hpp_baru')->nullable();
            $table->enum('metode', ['Cash', 'Barang']);
            $table->enum('metode_reture', ['Cash', 'Barang'])->nullable();
            $table->string('qrcode_barang')->nullable();
            $table->enum('status', ['success', 'failed', 'pending', 'ongoing'])->default('pending')->nullable();
            $table->enum('status_reture', ['success', 'failed', 'pending', 'ongoing'])->default('pending')->nullable();
            $table->enum('status_kirim', ['success', 'pending', 'progress'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_retur');
    }
};
