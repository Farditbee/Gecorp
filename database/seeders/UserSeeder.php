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
            "password" =>bcrypt("admin123"),
            "email" => "super_admin@gmail.com",
            "alamat" => "Cirebon",
            "no_hp" => 8527571268,
        ]);

        User::create([
            "id" => 2,
            "id_toko" => 2,
            "id_level" => 3,
            "nama" => "jSk_adminbhn",
            "username" => "adminbhn",
            "password" =>bcrypt("admin123"),
            "email" => "bhns@gmail.com",
            "alamat" => "Semarang",
            "no_hp" => 836268927,
        ]);

        User::create([
            "id" => 3,
            "id_toko" => 3,
            "id_level" => 3,
            "nama" => "dls_admin",
            "username" => "admindls",
            "password" =>bcrypt("admin123"),
            "email" => "gec@gmail.com",
            "alamat" => "Cirebon",
            "no_hp" => 836268927,
        ]);

        User::create([
            "id" => 4,
            "id_toko" => 4,
            "id_level" => 3,
            "nama" => "gps_admingps",
            "username" => "admingps",
            "password" =>bcrypt("admin123"),
            "email" => "gps@gmail.com",
            "alamat" => "Cirebon",
            "no_hp" => 836268927,
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
            "singkatan" => "GSS",
            "id_level_harga" => json_encode([]),
            "wilayah" => "Jakarta",
            "alamat" => 'Jakpus',
        ]);

        Toko::create([
            "id" => 2,
            "nama_toko" => "Bahana",
            "singkatan" => "BHN",
            "id_level_harga" => json_encode(["5","4","3","2"]),
            "wilayah" => "Semarang",
            "alamat" => "IDHsheu",
        ]);

        Toko::create([
            "id" => 3,
            "nama_toko" => "Dell Seri",
            "singkatan" => "DLS",
            "id_level_harga" => json_encode(["5","4","3","2"]),
            "wilayah" => "Cirebon",
            "alamat" => "Plumbon",
        ]);

        Toko::create([
            "id" => 4,
            "nama_toko" => "GPS Laptop",
            "singkatan" => "GPS",
            "id_level_harga" => json_encode(["5","4","3","2"]),
            "wilayah" => "Cirebon",
            "alamat" => "Plered",
        ]);
    }
}
