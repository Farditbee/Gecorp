<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'member';

    protected $guarded = [''];

    public $incrementing = false;

    protected $keyType = 'string';

    public $primaryKey = 'id';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }

    public function jenis_barang(): BelongsTo
    {
        return $this->belongsTo(JenisBarang::class, 'id_jenis_barang');
    }

    public function levelharga(): BelongsTo
    {
        return $this->belongsTo(LevelHarga::class, 'id_level_harga');
    }

    public function getLevelDataAttribute()
    {
        $levelInfo = json_decode($this->level_info, true); // Decode JSON dari level_info
        $result = [];

        if ($levelInfo) {
            $jenis_barang_ids = [];
            $level_harga_ids = [];

            // Loop untuk mengambil id dari level_info
            foreach ($levelInfo as $info) {
                // Preg_match untuk format tanpa tanda kurung
                if (preg_match('/(\d+) : (\d+)/', $info, $matches)) {
                    $jenis_barang_ids[] = $matches[1]; // id_jenis_barang
                    $level_harga_ids[] = $matches[2]; // id_level_harga
                }
            }

            // Ambil data jenis_barang dan level_harga dari database
            $jenis_barangs = JenisBarang::whereIn('id', $jenis_barang_ids)->get();
            $level_hargas = LevelHarga::whereIn('id', $level_harga_ids)->get();

            // Gabungkan data jenis_barang dan level_harga
            foreach ($levelInfo as $info) {
                // Preg_match lagi untuk mencocokkan setiap pasangan id
                if (preg_match('/(\d+) : (\d+)/', $info, $matches)) {
                    $jenis_barang_id = $matches[1];
                    $level_harga_id = $matches[2];

                    // Temukan nama dari hasil query
                    $jenis_barang = $jenis_barangs->firstWhere('id', $jenis_barang_id);
                    $level_harga = $level_hargas->firstWhere('id', $level_harga_id);

                    // Simpan pasangan nama_jenis_barang dan nama_level_harga
                    $result[] = [
                        'jenis_barang' => $jenis_barang ? $jenis_barang->nama_jenis_barang : 'Jenis barang tidak ditemukan',
                        'level_harga' => $level_harga ? $level_harga->nama_level_harga : 'Level harga tidak ditemukan'
                    ];
                }
            }
        }

        return $result;
    }

}
