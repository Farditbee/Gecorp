    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('promo', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('id_barang');
            $table->string('id_toko');
            $table->string('nama_barang');
            $table->integer('minimal')->default(1);
            $table->integer('diskon')->nullable();
            $table->integer('jumlah')->nullable();
            $table->integer('terjual')->nullable();
            $table->dateTime('dari');
            $table->dateTime('sampai');
            $table->enum('status', ['done', 'ongoing', 'queue'])->default('ongoing');
            $table->softDeletes();
        });
    }

};
