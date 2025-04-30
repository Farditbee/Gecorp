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
        Schema::create('detail_toko', function (Blueprint $table) {
            $table->id();
            $table->string('qrcode');
            $table->string('id_supplier')->nullable();
            $table->string('id_toko')->nullable();
            $table->string('id_barang')->nullable();
            $table->string('qty')->nullable();
            $table->double('harga')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
