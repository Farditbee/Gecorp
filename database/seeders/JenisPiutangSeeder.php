<?php

namespace Database\Seeders;

use App\Models\JenisPiutang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisPiutangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisPiutang::create([
            "id" => 1,
            "nama_jenis" => "Piutang Customer",
        ]);
    }
}
