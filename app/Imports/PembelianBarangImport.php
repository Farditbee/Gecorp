<?php

namespace App\Imports;

use App\Models\PembelianBarang;
use App\Models\DetailPembelianBarang;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PembelianBarangImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DB::beginTransaction();
            try {
                // Validate row data
                if (empty($row['no_nota']) || empty($row['id_supplier']) ||
                    empty($row['id_barang']) || empty($row['qty']) ||
                    empty($row['harga_barang']) || empty($row['qrcode'])) {
                    throw new \Exception('Data tidak valid pada baris: ' . json_encode($row));
                }

                // Simpan atau update data PembelianBarang
                $pembelian = PembelianBarang::updateOrCreate(
                    ['id' => $row['id_pembelian_barang'] ?? null],
                    [
                        'no_nota' => $row['no_nota'],
                        'id_supplier' => $row['id_supplier'],
                        'id_users' => 1,
                        'tgl_nota' => $row['tgl_nota'] ?? now(),
                    ]
                );

                // Buat path QR code berdasarkan qrcode dari Excel
                $qrCodeFolder = public_path('qrcodes/pembelian');
                if (!file_exists($qrCodeFolder)) {
                    mkdir($qrCodeFolder, 0755, true);
                }

                $qrCodeFilename = "{$row['qrcode']}.png";
                $qrCodePath = "qrcodes/pembelian/{$qrCodeFilename}";

                // Simpan DetailPembelianBarang
                $detail = DetailPembelianBarang::create([
                    'id_pembelian_barang' => $pembelian->id,
                    'id_barang' => $row['id_barang'],
                    'id_supplier' => $row['id_supplier'],
                    'qty' => $row['qty'],
                    'harga_barang' => $row['harga_barang'],
                    'total_harga' => $row['qty'] * $row['harga_barang'],
                    'qrcode' => $row['qrcode'],
                    'qrcode_path' => $qrCodePath,
                    'status' => "success",
                ]);

                // Update total pembelian
                $totals = DetailPembelianBarang::where('id_pembelian_barang', $pembelian->id)
                    ->selectRaw('SUM(qty) as total_item, SUM(total_harga) as total_nilai')
                    ->first();

                $pembelian->update([
                    'total_item' => $totals->total_item,
                    'total_nilai' => $totals->total_nilai,
                    'status' => 'success',
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error pada baris: ' . json_encode($row) . ' - ' . $e->getMessage());
                continue;
            }
        }
    }

    public function rules(): array
    {
        return [
            'no_nota' => 'required|string',
            'id_supplier' => 'required|exists:supplier,id',
            'id_barang' => 'required|exists:barang,id',
            'qty' => 'required|numeric|min:1',
            'harga_barang' => 'required|numeric|min:0',
            'qrcode' => 'required|string',
        ];
    }
}