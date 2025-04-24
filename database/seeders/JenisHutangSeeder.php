<?php

namespace Database\Seeders;

use App\Models\JenisHutang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisHutangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisHutang::create([
            "id" => 1,
            "nama_jenis" => "Hutang Modal",
        ]);
    }
}
