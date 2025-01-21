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
        Schema::table('detail_retur', function (Blueprint $table) {
            $table->double('hpp_jual')->nullable()->after('harga');
            $table->enum('metode', ['Cash', 'Barang'])->after('hpp_jual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_retur', function (Blueprint $table) {
            //
        });
    }
};
