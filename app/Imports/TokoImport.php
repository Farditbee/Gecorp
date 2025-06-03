<?php

namespace App\Imports;

use App\Models\Toko;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class TokoImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) {
            try {
                // Validate required fields
                if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])) {
                    throw new \Exception('Data tidak valid pada baris: ' . json_encode($row));
                }

                // Create new toko record
                Toko::create([
                    'nama_toko' => $row[0],
                    'singkatan' => $row[1],
                    'wilayah' => $row[2],
                    'alamat' => $row[3]
                ]);

            } catch (\Exception $e) {
                Log::error('Error pada baris: ' . json_encode($row) . ' - ' . $e->getMessage());
                continue;
            }
        }
    }
}