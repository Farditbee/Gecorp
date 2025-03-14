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
            "nilai" => 50000,
            "tanggal" => '2024-03-12',
        ]);
        Pengeluaran::create([
            "id" => 2,
            "id_toko" => 3,
            "nama_pengeluaran" => "Beli Hp",
            "nilai" => 17000000,
            "is_hutang" => '1',
            "ket_hutang" => "Suryadi ngutang hp iphone 13",
            "tanggal" => '2024-03-13',
        ]);
    }
}
