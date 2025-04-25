<?php

namespace App\Imports;

use App\Models\Barang;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Milon\Barcode\Facades\DNS1DFacade;

class BarangImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) {
            try {
                // Validasi data
                if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[4])) {
                    throw new \Exception('Data tidak valid pada baris: ' . json_encode($row));
                }

                // Cek apakah barcode sudah ada di database
                if (Barang::where('barcode', $row[0])->exists()) {
                    continue; // Lewati jika barcode sudah ada
                }

                // Buat folder barcodes jika belum ada
                $barcodeFolder = 'barcodes';
                if (!Storage::disk('public')->exists($barcodeFolder)) {
                    Storage::disk('public')->makeDirectory($barcodeFolder);
                }

                // Buat nama file barcode
                $barcodeFilename = "{$row[0]}.png";
                $barcodeFullPath = "{$barcodeFolder}/{$barcodeFilename}";

                // Cek apakah barcode sudah ada, jika belum buat
                if (!Storage::disk('public')->exists($barcodeFullPath)) {
                    $barcodeImage = DNS1DFacade::getBarcodePNG($row[0], 'C128', 3, 100);

                    if (!$barcodeImage) {
                        throw new \Exception('Gagal membuat barcode PNG dari base64');
                    }

                    Storage::disk('public')->put($barcodeFullPath, base64_decode($barcodeImage));
                }

                // Simpan data ke database
                Barang::create([
                    'barcode' => $row[0],
                    'nama_barang' => $row[1],
                    'id_jenis_barang' => $row[2],
                    'id_brand_barang' => $row[3],
                    'garansi' => $row[4],
                    'barcode_path' => $barcodeFullPath,
                    'gambar_path' => null,
                    'level_harga' => null,
                ]);
            } catch (\Exception $e) {
                Log::error('Error pada baris: ' . json_encode($row) . ' - ' . $e->getMessage());
                continue; // Lanjutkan ke baris berikutnya
            }
        }
    }
}
