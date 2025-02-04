<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DataReture;
use App\Models\DetailToko;
use App\Models\Member;
use App\Models\StockBarang;
use App\Models\Supplier;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MasterController extends Controller
{
    public function getToko(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Toko::query();

        if (!empty($request['is_admin'])) {
            $query->where('id', '!=', 1);
        }

        if (!empty($request['is_delete'])) {
            $query->where('id', '!=', $request['is_delete']);
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(singkatan) LIKE ?", ["%$searchTerm%"]);
            });
        }

        $query->orderBy('id', $meta['orderBy']);

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_toko'],
                'optional' => $item['singkatan'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getMember(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $id_toko = $request->id_toko;

        if ($id_toko == 1) {
            $query = Member::query();
        } else {
            $query = Member::where('id_toko', $id_toko);
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_member) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(no_hp) LIKE ?", ["%$searchTerm%"]);
            });
        }

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_member'] . ' / ' . $item['no_hp'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => true,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getBarang(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Barang::query();

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_barang) LIKE ?", ["%$searchTerm%"]);
            });
        }

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_barang'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getSuplier(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Supplier::query();

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_supplier) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(contact) LIKE ?", ["%$searchTerm%"]);
            });
        }
        
        // Subquery untuk mengecualikan supplier yang sudah ada di tabel data_retur dengan status pending
        $query->whereNotIn('supplier.id', function($subquery) {
            $subquery->select('id_supplier')
                     ->from('data_retur')
                     ->where('status', 'pending');
        });
        
        // Join dengan tabel detail_kasir dan detail_retur
        $query->join('detail_kasir', 'supplier.id', '=', 'detail_kasir.id_supplier')
          ->join('detail_retur', function($join) {
              $join->on('detail_kasir.id_kasir', '=', 'detail_retur.id_transaksi')
                   ->where('detail_retur.status', '=', 'success')
                   ->where('detail_retur.status_reture', '=', 'pending');
          })
          ->select('supplier.*')
          ->distinct();

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_supplier'] . ' / ' . $item['contact'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getBarangPengiriman(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;
    
        $id_toko = $request->id_toko;
    
        if ($id_toko == 1) {
            $query = StockBarang::join('barang', 'stock_barang.id_barang', '=', 'barang.id')
                ->select('stock_barang.id_barang', 'barang.nama_barang', 'stock_barang.stock as qty', 'barang.barcode');
        } else {
            $query = DetailToko::join('barang', 'detail_toko.id_barang', '=', 'barang.id')
                ->where('detail_toko.id_toko', $id_toko)
                ->select('detail_toko.id_barang', 'barang.nama_barang', 'detail_toko.qty', 'barang.barcode');
        }
    
        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));
    
            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(barang.id) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(barang.nama_barang) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(barang.barcode) LIKE ?", ["%$searchTerm%"]);
            });
        }
    
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
    
        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id_barang'],
                'text' => $item['nama_barang'] . '/' . $item['qty'] . '/' . $item['barcode'],
            ];
        }, $data['data']);
    
        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }
}
