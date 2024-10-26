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
            $table->string('id_kasir');
            $table->string('id_barang');
            $table->integer('qty');
            $table->double('harga');
            $table->double('total_harga');
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
