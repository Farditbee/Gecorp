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
        Schema::create('kasbon', function(Blueprint $table) {
            $table->id('id');
            $table->string('id_kasir');
            $table->string('id_member');
            $table->decimal('utang', 15, 2);
            $table->decimal('utang_sisa', 15, 2)->nullable();
            $table->enum('status', ['L', 'BL'])->default('BL');
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
