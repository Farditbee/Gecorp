<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JenisBarangController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Data Jenis Barang',
            'Tambah Data',
            'Edit Data'
        ];
    }

    public function getjenisbarang(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = JenisBarang::query();

        $query->with([])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_jenis_barang) LIKE ?", ["%$searchTerm%"]);
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
                'nama_jenis_barang' => $item->nama_jenis_barang,
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

        $jenisbarang = JenisBarang::orderBy('id', 'desc')->get();

        return view('master.jenisbarang.index', compact('menu', 'jenisbarang'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0], $this->title[1]];

        return view('master.jenisbarang.create', compact('menu'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_jenis_barang' => 'required|max:255',
        ], [
            'nama_jenis_barang.required' => 'Jenis Barang tidak boleh kosong.',
        ]);

        ActivityLogger::log('Tambah Jenis Barang', $request->all());

        try {

            JenisBarang::create([
                'nama_jenis_barang' => $request->nama_jenis_barang,
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
        return redirect()->route('master.jenisbarang.index')->with('success', 'Sukses menambahkan Jenis Barang Baru');
    }

    public function edit(string $id)
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0], $this->title[2]];

        $jenisbarang = JenisBarang::findOrFail($id);

        return view('master.jenisbarang.edit', compact('menu', 'jenisbarang'));
    }

    public function update(Request $request, string $id)
    {
        $jenisbarang = JenisBarang::findOrFail($id);

        ActivityLogger::log('Update Jenis Barang', ['id' => $id, 'data' => $request->all()]);

        try {

            $jenisbarang->update([
                'nama_jenis_barang' => $request->nama_jenis_barang,
            ]);

            return redirect()->route('master.jenisbarang.index')->with('success', 'Sukses Mengubah Data Jenis Barang');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(string $id)
    {
        $jenisbarang = JenisBarang::findOrFail($id);

        ActivityLogger::log('Delete Jenis Barang', ['id' => $id]);

        try {
            DB::beginTransaction();

            $jenisbarang->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sukses menghapus Data Jenis Barang'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data Jenis Barang: ' . $th->getMessage()
            ], 500);
        }
    }
}
