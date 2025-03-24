<?php

namespace Database\Seeders;

use App\Models\Mutasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MutasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mutasi::create([
            "id" => 1,
            "id_toko_pengirim" => "1",
            "id_toko_penerima" => "2",
            "nilai" => "5000000",
        ]);
    }
}
