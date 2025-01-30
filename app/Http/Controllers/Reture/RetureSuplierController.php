<?php

namespace App\Http\Controllers\Reture;

use App\Http\Controllers\Controller;
use App\Models\DataReture;
use App\Models\DetailRetur;
use App\Models\StockBarang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RetureSuplierController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Reture Supplier',
        ];
    }

    public function index()
    {
        $menu = [$this->title[0], $this->label[3]];
        return view('reture.suplier.index', compact('menu'));
    }

    public function get(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;
    
        $query = DataReture::where('id_supplier', '!=', null);
    
        $query->orderBy('id', $meta['orderBy']);
    
        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));
    
            $query->where(function ($query) use ($searchTerm) {
                // Pencarian pada kolom langsung
                $query->orWhereRaw("LOWER(id_supplier) LIKE ?", ["%$searchTerm%"]);
            });
        }
    
        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $query->join('supplier', 'data_retur.id_supplier', '=', 'supplier.id')
                ->select('data_retur.*', 'supplier.nama_supplier');
    
        $data = $query->paginate($meta['limit']);
    
        $paginationMeta = [
            'total'        => $data->total(),
            'per_page'     => $data->perPage(),
            'current_page' => $data->currentPage(),
            'total_pages'  => $data->lastPage()
        ];
    
        $data = [
            'data' => $data->items(),
            'meta' => $paginationMeta
        ];
    
        if (empty($data['data'])) {
            return response()->json([
                'status_code' => 400,
                'errors' => true,
                'message' => 'Tidak ada data'
            ], 400);
        }
    
        $mappedData = collect($data['data'])->map(function ($item) {
            return [
                'id' => $item['id'],
                'nama_supplier' => $item['nama_supplier'],
                'no_nota' => $item['no_nota'],
                'status' => $item['status'],
            ];
        });
    
        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Sukses',
            'pagination' => $data['meta']
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_nota' => 'required|string',
            'id_retur' => 'required|array',
            'id_transaksi' => 'required|array',
            'id_barang' => 'required|array',
            'metode_reture' => 'required|array',
            'qty_acc' => 'required|array',
        ]);
    
        try {
            DB::beginTransaction();
    
            foreach ($request->id_retur as $index => $idRetur) {
                $idTransaksi = $request->id_transaksi[$index];
                $idBarang = $request->id_barang[$index];
                $metodeReture = $request->metode_reture[$index];
                $qtyAcc = $request->qty_acc[$index];
    
                // Update data di tabel detail_retur
                DetailRetur::where('id_retur', $idRetur)
                    ->where('id_transaksi', $idTransaksi)
                    ->where('id_barang', $idBarang)
                    ->update([
                        'metode_reture' => $metodeReture,
                        'status_reture' => 'success',
                    ]);
    
                // Update stok di tabel stock_barang
                StockBarang::where('id_barang', $idBarang)
                            ->increment('stock', $qtyAcc);
            }
    
            // Update status di tabel data_retur
            DataReture::where('no_nota', $request->no_nota)
                        ->update(['status' => 'done']);
    
            DB::commit();
    
            return response()->json([
                'status_code' => 200,
                'errors' => false,
                'message' => 'Data berhasil diupdate',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating data: ' . $e->getMessage());
    
            return response()->json([
                'status_code' => 500,
                'errors' => true,
                'message' => 'Terjadi kesalahan saat mengupdate data'. $e->getMessage(),
            ], 500);
        }
    }
}
