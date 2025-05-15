<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('data_retur', function (Blueprint $table) {
			$table->id('id')->primary();
			$table->string('id_users');
			$table->string('id_toko');
			$table->string('id_member')->nullable();
			$table->string('id_supplier')->nullable();
			$table->string('no_nota');
			$table->date('tgl_retur');
			$table->integer('total_item')->nullable();
			$table->double('total_harga')->nullable();
			$table->enum('tipe_transaksi', ['kasir', 'supplier'])->nullable();
			$table->enum('status', ['done', 'pending', 'progress', 'failed'])->default('pending')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}
};
