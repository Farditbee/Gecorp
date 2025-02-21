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
        Schema::table('pengiriman_barang', function (Blueprint $table) {
            $table->enum('tipe_pengiriman', ['mutasi', 'reture'])->default('mutasi')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengiriman_barang', function (Blueprint $table) {
            //
        });
    }
};
