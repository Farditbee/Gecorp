<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            LevelHargaSeeder::class,
            JenisPemasukanSeeder::class,
            JenisPengeluaranSeeder::class,
            SupplierSeeder::class,
            MemberSeeder::class,
            BarangSeeder::class,
            // PembelianSeeder::class,
            // StockBarangSeeder::class,
            // DetailStockSeeder::class,
        ]);
    }
}
