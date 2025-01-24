<?php

namespace App\Http\Controllers\Reture;

use App\Http\Controllers\Controller;
use App\Models\DataReture;
use App\Models\DetailRetur;
use App\Models\Supplier;
use Illuminate\Http\Request;

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
}
