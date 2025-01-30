<?php

namespace Database\Seeders;

use App\Models\LevelHarga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelHargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LevelHarga::create([
            "id" => 1,
            "nama_level_harga" => "Level 1",
        ]);
        LevelHarga::create([
            "id" => 2,
            "nama_level_harga" => "Level 2",
        ]);
        LevelHarga::create([
            "id" => 3,
            "nama_level_harga" => "Level 3",
        ]);
        LevelHarga::create([
            "id" => 4,
            "nama_level_harga" => "User 1",
        ]);
        LevelHarga::create([
            "id" => 5,
            "nama_level_harga" => "User 2",
        ]);
    }
}
