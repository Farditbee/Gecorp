<?php

namespace App\Imports;

use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class SupplierImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) {
            try {
                if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])) {
                    throw new \Exception('Data tidak valid pada baris: ' . json_encode($row));
                }

                Supplier::create([
                    'nama_supplier' => $row[0],
                    'email' => $row[1],
                    'alamat' => $row[2],
                    'contact' => $row[3]
                ]);
            } catch (\Exception $e) {
                Log::error('Error pada baris: ' . json_encode($row) . ' - ' . $e->getMessage());
                continue;
            }
        }
    }
}