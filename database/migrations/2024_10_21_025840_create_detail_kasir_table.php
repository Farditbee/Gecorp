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
        Schema::create('detail_kasir', function (Blueprint $table) {
            $table->id('id');
            $table->string('id_detail_pembelian');
            $table->string('id_kasir');
            $table->string('id_barang');
            $table->string('id_supplier');
            $table->string('qrcode')->nullable();
            $table->string('qrcode_path')->nullable();
            $table->integer('reture_qty')->nullable();
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->integer('diskon')->nullable();
            $table->decimal('total_harga', 15, 2);
            $table->decimal('hpp_jual', 15, 2);
            $table->boolean('reture')->default(false)->nullable();
            $table->string('reture_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_kasir');
    }
};
