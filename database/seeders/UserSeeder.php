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

        // Level User Seeder
        LevelUser::create([
            "id" => 1,
            "nama_level" => "Super Admin",
            "informasi" => "Mengontrol dan Akses semua Sistem",
        ]);
        LevelUser::create([
            "id" => 2,
            "nama_level" => "Admin GSS",
            "informasi" => "Mengontrol Toko GSS",
        ]);
        LevelUser::create([
            "id" => 3,
            "nama_level" => "Admin",
            "informasi" => "Mengontrol Toko",
        ]);
        LevelUser::create([
            "id" => 4,
            "nama_level" => "Karyawan",
            "informasi" => "Melakukan Transaksi Kasir",
        ]);
        LevelUser::create([
            "id" => 5,
            "nama_level" => "Mitra",
            "informasi" => "Contoh Mitra",
        ]);
        LevelUser::create([
            "id" => 6,
            "nama_level" => "Akunting",
            "informasi" => "Contoh Akunting",
        ]);

        // Toko Seeder
        Toko::create([
            "id" => 1,
            "nama_toko" => "GSS",
            "id_level_harga" => json_encode([]),
            "wilayah" => "Jakarta",
            "alamat" => 'Jakpus',
        ]);
    }
}
