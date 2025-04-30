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
        Schema::create('detail_kasbon', function (Blueprint $table) {
            $table->id();
            $table->string('id_kasbon');
            $table->dateTime('tgl_bayar');
            $table->double('bayar');
            $table->enum('tipe_bayar', ['Tunai', 'Non-Tunai']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_kasbon');
    }
};
