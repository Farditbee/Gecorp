<?php
namespace App\Models;

use App\Http\Controllers\BarangController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembelianBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembelian_barang';

    public $timestamps = false;

    protected $fillable = [
        'id_supplier', 
        'no_nota', 
        'tgl_nota', 
        'total_item', 
        'total_nilai'
    ];

    public function detail()
    {
        return $this->hasMany(DetailPembelianBarang::class, 'id_pembelian_barang');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

}
