<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Seeder for brand table
        DB::table('brand')->insert([
            [
                'id' => 1,
                'nama_brand' => 'Brand A',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'nama_brand' => 'Brand B',
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'nama_brand' => 'Brand C',
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'nama_brand' => 'Brand D',
                'deleted_at' => null,
            ],
            [
                'id' => 5,
                'nama_brand' => 'Brand E',
                'deleted_at' => null,
            ],
        ]);

        // Seeder for jenis_barang table
        DB::table('jenis_barang')->insert([
            [
                'id' => 1,
                'nama_jenis_barang' => 'Sparepart',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'nama_jenis_barang' => 'Aksesoris',
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'nama_jenis_barang' => 'Lainnya',
                'deleted_at' => null,
            ],
        ]);
    }
}
