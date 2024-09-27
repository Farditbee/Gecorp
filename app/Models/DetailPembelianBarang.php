<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPembelianBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detail_pembelian_barang';

    public $timestamps = false;

    protected $fillable = [
        'id_pembelian_barang', 
        'id_barang', 
        'nama_barang', 
        'qty', 
        'harga_barang', 
        'total_harga'
    ];

    public function pembelian()
    {
        return $this->belongsTo(PembelianBarang::class, 'id_pembelian_barang');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
