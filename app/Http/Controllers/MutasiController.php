<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Pemasukan;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MutasiController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Mutasi',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!in_array(Auth::user()->id_level, [1, 2, 3, 4, 6])) {
            abort(403, 'Unauthorized');
        }
        $menu = [$this->title[0], $this->label[5]];
        $toko = Toko::all();

        return view('mutasi.index', compact('menu', 'toko'));
    }

    public function getmutasi(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Mutasi::query();

        $query->with(['tokoPengirim', 'tokoPenerima'])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereHas('tokoPengirim', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                })->orWhereHas('tokoPenerima', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                });
            });
        }

        if ($request->has('id_toko')) {
            $idToko = $request->input('id_toko');
            if ($idToko != 1) {
                $query->where(function($q) use ($idToko) {
                    $q->where('id_toko_pengirim', $idToko)
                      ->orWhere('id_toko_penerima', $idToko);
                });
            }
        }

        if ($request->has('toko')) {
            $idToko = $request->input('toko');
            $query->where(function($q) use ($idToko) {
                $q->where('id_toko_pengirim', $idToko)
                  ->orWhere('id_toko_penerima', $idToko);
            });
        }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalNilai = $query->sum('nilai');
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
                'id_toko_pengirim' => $item['tokoPengirim'] ? $item['tokoPengirim']->id : null,
                'nama_toko_pengirim' => $item['tokoPengirim'] ? $item['tokoPengirim']->singkatan : null,
                'id_toko_penerima' => $item['tokoPenerima'] ? $item['tokoPenerima']->id : null,
                'nama_toko_penerima' => $item['tokoPenerima'] ? $item['tokoPenerima']->singkatan : null,
                'nilai' => 'Rp. ' . number_format($item->nilai ?? 0, 0, '.', '.'),
            ];
        });

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => true,
            'message' => 'Sukses',
            'pagination' => $data['meta'],
            'total_nilai' => 'Rp. ' . number_format($totalNilai, 0, '.', '.')
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'toko_penerima' => 'required|exists:toko,id',
            'toko_pengirim' => 'required|exists:toko,id',
            'nilai' => 'required|numeric|min:0'
        ]);

        try {
            $mutasi = new Mutasi();
            $mutasi->id_toko_penerima = $request->toko_penerima;
            $mutasi->id_toko_pengirim = $request->toko_pengirim;
            $mutasi->nilai = $request->nilai;
            $mutasi->save();

            return response()->json([
                'status_code' => 200,
                'errors' => false,
                'message' => 'Data mutasi berhasil disimpan'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'errors' => true,
                'message' => 'Gagal menyimpan data mutasi'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mutasi $mutasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(String $id)
    {
        DB::beginTransaction();
        try {
            $mutasi = Mutasi::findOrFail($id);
            $mutasi->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sukses menghapus Data mutasi'
            ]);
        } catch (\Throwable $th) { 
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data mutasi: ' . $th->getMessage()
            ], 500);
        }
    }
}
