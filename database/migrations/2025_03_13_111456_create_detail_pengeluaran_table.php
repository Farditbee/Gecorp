<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengeluaran')->constrained('pengeluaran')->onDelete('cascade');
            $table->double('nilai');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_pengeluaran');
    }
};
