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
            "nama_supplier" => "Master Supplier",
            "email" => "supplier1@gmail.com",
            "alamat" => "Cirebon",
            "contact" => "089918828581",
        ]);
    }
}
