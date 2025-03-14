<?php

namespace Database\Seeders;

use App\Models\DetailStockBarang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailStockBarang::create([
            "id" => 1,
            "id_stock" => 1,
            "id_barang" => 1,
            "id_supplier" => 1,
            "id_pembelian" => 1,
            "id_detail_pembelian" => 1,
            "qty_buy" => 10,
            "qty_out" => "",
            "qty_now" => 10,
        ]);

        DetailStockBarang::create([
            "id" => 2,
            "id_stock" => 2,
            "id_barang" => 2,
            "id_supplier" => 1,
            "id_pembelian" => 1,
            "id_detail_pembelian" => 2,
            "qty_buy" => 10,
            "qty_out" => "",
            "qty_now" => 10,
        ]);

        DetailStockBarang::create([
            "id" => 3,
            "id_stock" => 1,
            "id_barang" => 1,
            "id_supplier" => 2,
            "id_pembelian" => 2,
            "id_detail_pembelian" => 3,
            "qty_buy" => 20,
            "qty_out" => "",
            "qty_now" => 20,
        ]);

        DetailStockBarang::create([
            "id" => 4,
            "id_stock" => 2,
            "id_barang" => 2,
            "id_supplier" => 2,
            "id_pembelian" => 2,
            "id_detail_pembelian" => 4,
            "qty_buy" => 20,
            "qty_out" => "",
            "qty_now" => 20,
        ]);

    }
}
