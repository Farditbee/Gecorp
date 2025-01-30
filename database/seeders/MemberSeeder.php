<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::create([
            "id" => 1,
            "id_toko" => "2",
            "nama_member" => "Jukis",
            "level_info" => json_encode(["1 : 2", "2 : 2", "3 : 2"]),
            "no_hp" => "089581957723",
            "alamat" => "Jagasatru",
        ]);
    }
}
