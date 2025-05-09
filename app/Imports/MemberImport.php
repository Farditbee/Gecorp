<?php

namespace App\Imports;

use App\Models\Member;
use App\Models\Toko;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class MemberImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) {
            try {
                // Validate required fields
                if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[4])) {
                    throw new \Exception('Data tidak valid pada baris: ' . json_encode($row));
                }

                // Validate toko exists
                $toko = Toko::find($row[0]);
                if (!$toko) {
                    throw new \Exception('Toko dengan ID ' . $row[0] . ' tidak ditemukan');
                }

                // Parse level_info string into array format
                $levelInfoStr = $row[1];
                $levelInfo = [];
                
                // Remove brackets and split by comma
                $pairs = explode(',', trim($levelInfoStr, '[]'));
                foreach ($pairs as $pair) {
                    $pair = trim($pair, '"'); // Remove quotes
                    if (!empty($pair)) {
                        $levelInfo[] = $pair;
                    }
                }

                Member::create([
                    'id_toko' => $row[0],
                    'level_info' => json_encode($levelInfo),
                    'nama_member' => $row[2],
                    'no_hp' => $row[3],
                    'alamat' => $row[4]
                ]);

            } catch (\Exception $e) {
                Log::error('Error pada baris: ' . json_encode($row) . ' - ' . $e->getMessage());
                continue;
            }
        }
    }
}