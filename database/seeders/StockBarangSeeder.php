<?php

namespace Database\Seeders;

use App\Models\StockBarang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockBarang::create([
            "id" => 1,
            "id_barang" => 1,
            "nama_barang" => "Asus ROG Zephyrus G14",
            "stok" => 30,
            "hpp_awal" => 1000.00,
            "hpp_baru" => 800.00,
            "nilai_total" => 24000.00,
        ]);

        StockBarang::create([
            "id" => 1,
            "id_barang" => 2,
            "nama_barang" => "Samsung Galaxy S21",
            "stok" => 30,
            "hpp_awal" => 2000.00,
            "hpp_baru" => 1533.00,
            "nilai_total" => 46000.00,
        ]);
    }
}
