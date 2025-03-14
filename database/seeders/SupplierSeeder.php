<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            "id" => "1",
            "nama_supplier" => "David SP",
            "email" => "supplier1@gmail.com",
            "alamat" => "Cirebon",
            "contact" => "089918828581",
        ]);

        Supplier::create([
            "id" => "2",
            "nama_supplier" => "Heri SP",
            "email" => "hes@gmail.com",
            "alamat" => "Medan",
            "contact" => "08762451",
        ]);
    }
}
