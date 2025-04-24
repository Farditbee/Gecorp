<?php

namespace Database\Seeders;

use App\Models\JenisPengeluaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisPengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisPengeluaran::insert([
            ["id" => 1, "nama_jenis" => "Biaya Perlengkapan"],
            ["id" => 2, "nama_jenis" => "Biaya Operasional"],
            ["id" => 3, "nama_jenis" => "Biaya Gaji Staff"],
            ["id" => 4, "nama_jenis" => "Biaya Transport"],
            ["id" => 5, "nama_jenis" => "Biaya Listrik"],
            ["id" => 6, "nama_jenis" => "Biaya Iklan"],
            ["id" => 7, "nama_jenis" => "Biaya Administrasi"],
            ["id" => 8, "nama_jenis" => "Biaya K3"],
            ["id" => 9, "nama_jenis" => "Biaya Perbaikan Bangunan"],
            ["id" => 10, "nama_jenis" => "Biaya Tak Terduga"],
            ["id" => 11, "nama_jenis" => "Pembelian Asset"],
        ]);

    }
}
