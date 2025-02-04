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
        DB::table('stock_barang')->truncate();

        PembelianBarang::create([
            "id" => 1,
            "id_supplier" => "1",
            "id_users" => "1",
            "no_nota" => "123",
            "tgl_nota" => "2021-08-01",
            "total_item" => 40,
            "total_nilai" => 16000000,
            "status" => "success",
        ]);

        // Generate QR code and path for the first item
        $qrCodeData1 = $this->generateQrCode(1, 1, 1);

        DetailPembelianBarang::create([
            "id" => 1,
            "qrcode" => $qrCodeData1['qrcode'],
            "qrcode_path" => $qrCodeData1['qrcode_path'],
            "id_pembelian_barang" => "1",
            "id_barang" => "1",
            "id_supplier" => "1",
            "qty" => 20,
            "harga_barang" => 500000,
            "total_harga" => 10000000,
            "status" => "success",
        ]);

        // Generate QR code and path for the second item
        $qrCodeData2 = $this->generateQrCode(1, 2, 1);

        DetailPembelianBarang::create([
            "id" => 2,
            "qrcode" => $qrCodeData2['qrcode'],
            "qrcode_path" => $qrCodeData2['qrcode_path'],
            "id_pembelian_barang" => "1",
            "id_barang" => "2",
            "id_supplier" => "1",
            "qty" => 20,
            "harga_barang" => 300000,
            "total_harga" => 6000000,
            "status" => "success",
        ]);

        StockBarang::create([
            "id" => 1,
            "id_barang" => "1",
            "stock" => 20,
            "hpp_awal" => 500000,
            "hpp_baru" => 500000,
            "nilai_total" => 10000000,
        ]);

        StockBarang::create([
            "id" => 2,
            "id_barang" => "2",
            "stock" => 20,
            "hpp_awal" => 300000,
            "hpp_baru" => 300000,
            "nilai_total" => 6000000,
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
