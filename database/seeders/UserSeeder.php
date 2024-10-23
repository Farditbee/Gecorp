<?php

namespace Database\Seeders;

use App\Models\LevelUser;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        User::create([
            "id" => 1,
            "id_toko" => 1,
            "id_level" => 1,
            "nama" => "Super Admin",
            "username" => "admin",
            "email" => "super_admin@gmail.com",
            "password" =>bcrypt("admin123"),
            "alamat" => "Cirebon",
            "no_hp" => 8527571268,
        ]);

        LevelUser::create([
            "id" => 1,
            "nama_level" => "Super Admin",
            "informasi" => "Saitama",
        ]);

        Toko::create([
            "id" => 1,
            "nama_toko" => "GSS",
            "id_level_harga" => "",
            "wilayah" => "Jakarta",
            "alamat" => 'Jakpus',
        ]);

        // User::create([
        //     "id" => Uuid::uuid4()->getHex(),
        //     "nama" => "Warga",
        //     "email" => "warga@gmail.com",
        //     "password" => bcrypt("warga01"),
        //     "role" => "warga",
        //     "status" => 1
        // ]);
    }
}
