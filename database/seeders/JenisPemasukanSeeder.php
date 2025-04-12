<?php

namespace Database\Seeders;

use App\Models\JenisPemasukan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisPemasukanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisPemasukan::create([
            "id" => 1,
            "nama_jenis" => "Modal Awal",
        ]);
    }
}
