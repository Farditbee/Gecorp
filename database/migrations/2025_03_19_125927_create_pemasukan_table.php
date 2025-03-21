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
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->id();
            $table->string('id_toko');
            $table->string('nama_pemasukan')->nullable();
            $table->string('id_jenis_pemasukan')->nullable();
            $table->double('nilai')->nullable();
            $table->enum('is_pinjam', (['0', '1', '2']));
            $table->string('ket_pinjam')->nullable();
            $table->date('tanggal');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan');
    }
};
