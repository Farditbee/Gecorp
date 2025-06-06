<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\LevelHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LevelHargaController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Data Level Harga',
            'Tambah Data',
            'Edit Data'
        ];
    }

    public function getlevelharga(Request $request)
    {

        $meta['orderBy'] = $request->descending ? 'desc' : 'asc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = LevelHarga::query();

        $query->with([])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_level_harga) LIKE ?", ["%$searchTerm%"]);
            });
        }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $data = $query->paginate($meta['limit']);

        $paginationMeta = [
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'total_pages' => $data->lastPage()
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
                'nama_level_harga' => $item->nama_level_harga,
            ];
        });

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => true,
            'message' => 'Sukses',
            'pagination' => $data['meta']
        ], 200);
    }

    public function index()
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0]];

        $levelharga = LevelHarga::orderBy('id', 'desc')->get();

        return view('master.levelharga.index', compact('menu', 'levelharga'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0], $this->title[1]];

        return view('master.levelharga.create', compact('menu'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_level_harga' => 'required|max:255',
        ], [
            'nama_level_harga.required' => 'Nama level harga tidak boleh kosong.',
        ]);

        ActivityLogger::log('Tambah Level Harga', $request->all());

        try {

            LevelHarga::create([
                'nama_level_harga' => $request->nama_level_harga,
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }

        return redirect()->route('master.levelharga.index')->with('success', 'Berhasil menambahkan Level Baru');
    }

    public function edit(string $id)
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0], $this->title[2]];

        $levelharga = LevelHarga::findOrFail($id);

        return view('master.levelharga.edit', compact('menu', 'levelharga'));
    }

    public function update(Request $request, string $id)
    {
        $levelharga = LevelHarga::findOrFail($id);

        ActivityLogger::log('Update Level Harga', ['id' => $id]);

        try {

            $levelharga->update([
                'nama_level_harga' => $request->nama_level_harga,
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }

        return redirect()->route('master.levelharga.index')->with('success', 'Sukses Mengubah Data Level Harga');
    }

    public function delete(string $id)
    {
        $levelharga = LevelHarga::findOrFail($id);

        ActivityLogger::log('Delete Level Harga', ['id' => $id]);

        try {
            DB::beginTransaction();

            $levelharga->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sukses menghapus Data Level Harga'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data Level Harga: ' . $th->getMessage()
            ], 500);
        }
    }
}
