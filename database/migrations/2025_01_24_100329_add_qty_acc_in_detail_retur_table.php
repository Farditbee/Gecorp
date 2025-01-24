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
            $table->integer('qty_acc')->nullable()->after('qty');
            $table->enum('status_reture', ['pending', 'success', 'ongoing', 'failed'])->default('pending')->nullable()->after('status');
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
