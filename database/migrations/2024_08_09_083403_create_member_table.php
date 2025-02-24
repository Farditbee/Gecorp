<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('member', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('id_toko');
            $table->string('level_info')->nullable();
            $table->string('nama_member');
            $table->string('no_hp');
            $table->string('alamat');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
