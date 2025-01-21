<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Member;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
