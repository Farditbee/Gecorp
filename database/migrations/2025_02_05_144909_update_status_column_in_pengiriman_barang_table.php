<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengiriman_barang', function (Blueprint $table) {
            DB::statement("ALTER TABLE `pengiriman_barang` MODIFY `status` ENUM('pending', 'progress', 'success', 'canceled') NOT NULL");
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
