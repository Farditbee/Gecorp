<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Brand;
use App\Models\JenisBarang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\Facades\DNS1DFacade;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('brand')->truncate();
        DB::table('jenis_barang')->truncate();
        DB::table('barang')->truncate();
        DB::table('pembelian_barang')->truncate();
        DB::table('detail_pembelian_barang')->truncate();
        DB::table('stock_barang')->truncate();
        DB::table('detail_stock')->truncate();
        DB::table('detail_toko')->truncate();
        DB::table('temp_detail_pengiriman')->truncate();
        DB::table('pengiriman_barang')->truncate();
        DB::table('detail_pengiriman_barang')->truncate();
        DB::table('kasir')->truncate();
        DB::table('detail_kasir')->truncate();
        DB::table('data_retur')->truncate();
        DB::table('detail_retur')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Delete all files in the qrcodes/pembelian directory
        $qrCodeDirectory = storage_path('app/public/qrcodes/pembelian');
        if (file_exists($qrCodeDirectory)) {
            $files = glob($qrCodeDirectory . '/*'); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file)) {
                    unlink($file); // delete file
                }
            }
        }

        // Delete all files in the barcodes directory
        $barcodes = storage_path('app/public/barcodes');
        if (file_exists($barcodes)) {
            $files = glob($barcodes . '/*'); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file)) {
                    unlink($file); // delete file
                }
            }
        }

        // Delete all files in the kasir directory
        $trx_kasir = storage_path('app/public/qrcodes/trx_kasir');
        if (file_exists($trx_kasir)) {
            $files = glob($trx_kasir . '/*'); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file)) {
                    unlink($file); // delete file
                }
            }
        }

        // Delete all files in the kasir directory
        $gambar_barang = storage_path('app/public/gambar_barang');
        if (file_exists($gambar_barang)) {
            $files = glob($gambar_barang . '/*'); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file)) {
                    unlink($file); // delete file
                }
            }
        }

        $jenisBarang1 = JenisBarang::create([
            "id" => 1,
            "nama_jenis_barang" => "Laptop",
        ]);

        $brandBarang1 = Brand::create([
            "id" => 1,
            "nama_brand" => "Asus",
        ]);

        $initials1 = strtoupper(substr($jenisBarang1->nama_jenis_barang, 0, 1) . substr($brandBarang1->nama_brand, 0, 1));

        // Generate barcode value for the first item
        $barcodeValue1 = $initials1 . random_int(100000, 999999);

        // Path folder barcode
        $barcodeFolder = storage_path('app/public/barcodes');

        // Buat folder barcode jika belum ada
        if (!file_exists($barcodeFolder)) {
            mkdir($barcodeFolder, 0755, true);
        }

        // Generate barcode as PNG file for the first item
        $barcodeFilename1 = "barcodes/{$barcodeValue1}.png";
        if (!Storage::exists($barcodeFilename1)) {
            $barcodeImage1 = DNS1DFacade::getBarcodePNG($barcodeValue1, 'C128', 3, 100);

            if (!$barcodeImage1) {
                throw new \Exception('Failed to generate barcode PNG as Base64');
            }

            // Save to Storage
            if (!Storage::put($barcodeFilename1, base64_decode($barcodeImage1))) {
                throw new \Exception('Failed to save barcode image to storage');
            }
        }

        Barang::create([
            "id" => 1,
            "garansi" => "No",
            "barcode" => $barcodeValue1,
            "barcode_path" => $barcodeFilename1,
            "nama_barang" => "Asus ROG Zephyrus G14",
            "id_jenis_barang" => $jenisBarang1->id,
            "id_brand_barang" => $brandBarang1->id,
        ]);

        // Create second data dummy
        $jenisBarang2 = JenisBarang::create([
            "id" => 2,
            "nama_jenis_barang" => "Smartphone",
        ]);

        $brandBarang2 = Brand::create([
            "id" => 2,
            "nama_brand" => "Samsung",
        ]);

        $initials2 = strtoupper(substr($jenisBarang2->nama_jenis_barang, 0, 1) . substr($brandBarang2->nama_brand, 0, 1));

        // Generate barcode value for the second item
        $barcodeValue2 = $initials2 . random_int(100000, 999999);

        // Generate barcode as PNG file for the second item
        $barcodeFilename2 = "barcodes/{$barcodeValue2}.png";
        if (!Storage::exists($barcodeFilename2)) {
            $barcodeImage2 = DNS1DFacade::getBarcodePNG($barcodeValue2, 'C128', 3, 100);

            if (!$barcodeImage2) {
                throw new \Exception('Failed to generate barcode PNG as Base64');
            }

            // Save to Storage
            if (!Storage::put($barcodeFilename2, base64_decode($barcodeImage2))) {
                throw new \Exception('Failed to save barcode image to storage');
            }
        }

        Barang::create([
            "id" => 2,
            "garansi" => "Yes",
            "barcode" => $barcodeValue2,
            "barcode_path" => $barcodeFilename2,
            "nama_barang" => "Samsung Galaxy S21",
            "id_jenis_barang" => $jenisBarang2->id,
            "id_brand_barang" => $brandBarang2->id,
        ]);
    }
}