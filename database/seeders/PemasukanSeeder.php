<?php

namespace Database\Seeders;

use App\Models\Pemasukan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PemasukanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pemasukan::create([
            "id" => 1,
            "id_toko" => 1,
            "id_jenis_pemasukan" => 1,
            "nama_pemasukan" => "Donatur Haji 969",
            "nilai" => 10000000,
            "tanggal" => '2024-03-15',
        ]);
        Pemasukan::create([
            "id" => 2,
            "id_toko" => 1,
            "nama_pemasukan" => "Pinjem Bank",
            "nilai" => 5000000,
            "is_pinjam" => '1',
            "ket_pinjam" => "Peminjaman Modal ke Bank BTN",
            "tanggal" => '2024-03-19',
        ]);
    }
}
