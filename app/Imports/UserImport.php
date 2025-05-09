<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Toko;
use App\Models\LevelUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserImport implements ToCollection
{
    public function collection(Collection $rows)
    {   
        foreach ($rows->skip(1) as $row) {
            try {
                // Validate required fields
                if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3]) || 
                    empty($row[4]) || empty($row[5]) || empty($row[6]) || empty($row[7])) {
                    throw new \Exception('Data tidak valid pada baris: ' . json_encode($row));
                }

                // Validate toko exists
                $toko = Toko::find($row[0]);
                if (!$toko) {
                    throw new \Exception('Toko dengan ID ' . $row[0] . ' tidak ditemukan');
                }

                // Validate level exists
                $level = LevelUser::find($row[1]);
                if (!$level) {
                    throw new \Exception('Level dengan ID ' . $row[1] . ' tidak ditemukan');
                }

                // Create user with encrypted password
                User::create([
                    'id_toko' => $row[0],
                    'id_level' => $row[1],
                    'nama' => $row[2],
                    'username' => $row[3],
                    'password' => Hash::make($row[4]),
                    'email' => $row[5],
                    'alamat' => $row[6],
                    'no_hp' => $row[7]
                ]);
            } catch (\Exception $e) {
                Log::error('Error pada baris: ' . json_encode($row) . ' - ' . $e->getMessage());
                continue;
            }
        }
    }
}
