<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('detail_kasir', function (Blueprint $table) {
            $table->boolean('reture')->default(false)->nullable()->after('total_harga');
            $table->string('reture_by')->nullable()->after('reture');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_kasir', function (Blueprint $table) {
            //
        });
    }
};
