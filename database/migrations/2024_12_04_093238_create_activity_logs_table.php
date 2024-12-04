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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('ip_address');
            $table->string('action'); // Deskripsi aksi (contoh: 'Tambah Barang', 'Update Transaksi')
            $table->json('parameters')->nullable(); // Menyimpan parameter input (opsional)
            $table->timestamp('action_time'); // Waktu aksi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
