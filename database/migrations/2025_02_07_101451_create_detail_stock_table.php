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
        Schema::create('detail_stock', function (Blueprint $table) {
            $table->id();
            $table->string('id_stock');
            $table->string('id_barang');
            $table->string('id_supplier');
            $table->string('id_pembelian');
            $table->string('id_detail_pembelian');
            $table->integer('qty_buy');
            $table->integer('qty_out')->nullable();
            $table->integer('qty_now')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_stock');
    }
};
