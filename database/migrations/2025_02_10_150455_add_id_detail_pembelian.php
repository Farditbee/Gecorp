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
        Schema::table('detail_pengiriman_barang', function (Blueprint $table) {
            $table->string('id_detail_pembelian')->nullable()->after('id_pengiriman_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pengiriman_barang', function (Blueprint $table) {
            //
        });
    }
};
