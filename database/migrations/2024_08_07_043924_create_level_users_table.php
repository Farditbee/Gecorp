<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('level_users', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('nama_level');
            $table->string('informasi');
            $table->softDeletes();
        });
    }

};
