<?php

namespace Database\Seeders;

use App\Models\DetailPembelianBarang;
use App\Models\PembelianBarang;
use App\Models\StockBarang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Font\NotoSans;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembelianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    // Jangan lupa truncate table sebelum seeding di tabel yang berelasi
    // di tabel PembelianBarang, DetailPembelianBarang, StockBarang

    public function run(): void
    {

        DB::table('pembelian_barang')->truncate();
        DB::table('detail_pembelian_barang')->truncate();
        DB::table('temp_detail_pengiriman')->truncate();

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

        PembelianBarang::create([
            "id" => 1,
            "id_supplier" => 1,
            "id_user" => 1,
            "no_nota" => "221",
            "tgl_nota" => "2025-03-12 11:44:21",
            "total_item" => 20,
            "total_nilai" => 30000.00,
            "status" => "success",
        ]);

        PembelianBarang::create([
            "id" => 1,
            "id_supplier" => 2,
            "id_user" => 1,
            "no_nota" => "621",
            "tgl_nota" => "2025-03-12 12:17:21",
            "total_item" => 40,
            "total_nilai" => 40000.00,
            "status" => "success",
        ]);
        
        // Generate QR code and path for the first item
        $qrCodeData1 = $this->generateQrCode(1, 1, 1);

        DetailPembelianBarang::create([
            "id" => 1,
            "qrcode" => $qrCodeData1['qrcode'],
            "qrcode_path" => $qrCodeData1['qrcode_path'],
            "id_supplier" => 1,
            "id_pembelian_barang" => 1,
            "id_barang" => 1,
            "qty" => 10,
            "harga_barang" => 1000.00,
            "total_harga" => 10000.00,
            "status" => "success",
        ]);

        $qrCodeData2 = $this->generateQrCode(1, 2, 1);

        DetailPembelianBarang::create([
            "id" => 2,
            "qrcode" => $qrCodeData2['qrcode'],
            "qrcode_path" => $qrCodeData2['qrcode_path'],
            "id_supplier" => 1,
            "id_pembelian_barang" => 1,
            "id_barang" => 2,
            "qty" => 10,
            "harga_barang" => 2000.00,
            "total_harga" => 20000.00,
            "status" => "success",
        ]);

        $qrCodeData3 = $this->generateQrCode(2, 1, 2);

        DetailPembelianBarang::create([
            "id" => 3,
            "qrcode" => $qrCodeData3['qrcode'],
            "qrcode_path" => $qrCodeData3['qrcode_path'],
            "id_supplier" => 2,
            "id_pembelian_barang" => 2,
            "id_barang" => 1,
            "qty" => 20,
            "harga_barang" => 700.00,
            "total_harga" => 14000.00,
            "status" => "success",
        ]);

        $qrCodeData4 = $this->generateQrCode(2, 2, 2);

        DetailPembelianBarang::create([
            "id" => 4,
            "qrcode" => $qrCodeData4['qrcode'],
            "qrcode_path" => $qrCodeData4['qrcode_path'],
            "id_supplier" => 2,
            "id_pembelian_barang" => 2,
            "id_barang" => 2,
            "qty" => 20,
            "harga_barang" => 1300.00,
            "total_harga" => 26000.00,
            "status" => "success",
        ]);

    }

    protected function generateQrCode($pembelianId, $barangId, $supplierId)
    {
        // Generate QR Code Value
        $tglNota = Carbon::now()->format('dmY');
        $qrCodeValue = "{$tglNota}SP{$supplierId}ID{$pembelianId}-{$barangId}";

        // Path QR code for this barang
        $qrCodePath = "qrcodes/pembelian/{$pembelianId}-{$barangId}.png";
        $fullPath = storage_path('app/public/' . $qrCodePath);

        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        // Generate QR Code
        $qrCode = QrCode::create($qrCodeValue)
            ->setEncoding(new Encoding('UTF-8'))
            ->setSize(200)
            ->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write(
            $qrCode,
            null,
            Label::create("Barang ID: {$barangId}")
                ->setFont(new NotoSans(12))
        );

        $result->saveToFile($fullPath);

        return [
            'qrcode' => $qrCodeValue,
            'qrcode_path' => $qrCodePath,
        ];
    }
}
