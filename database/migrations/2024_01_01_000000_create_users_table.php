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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('id_toko');
            $table->string('id_level');
            $table->string('nama');
            $table->string('username');
            $table->string('password');
            $table->string('email');
            $table->text('alamat');
            $table->string('no_hp');
            $table->softDeletes();
        });
    }

};
