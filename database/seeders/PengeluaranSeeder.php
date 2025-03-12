<?php

namespace Database\Seeders;

use App\Models\Pengeluaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengeluaran::create([
            "id" => 1,
            "id_toko" => 3,
            "id_jenis_pengeluaran" => 1,
            "nama_pengeluaran" => "Beli Alat Pancing",
            "Nilai" => 50000,
        ]);
    }
}
