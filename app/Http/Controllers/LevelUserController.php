<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\LevelUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\String_;

class LevelUserController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Data Level User',
            'Tambah Data',
            'Edit Data'
        ];
    }

    public function getleveluser(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'desc' : 'asc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = LevelUser::query();

        $query->with([])->orderBy('no_urut', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_level) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(informasi) LIKE ?", ["%$searchTerm%"]);
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
                'nama_level' => $item->nama_level,
                'informasi' => $item->informasi,
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

        $leveluser = LevelUser::orderBy('id', 'desc')->get();

        return view('master.leveluser.index', compact('menu', 'leveluser'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0], $this->title[1]];

        return view('master.leveluser.create', compact('menu'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_level' => 'required|max:255',
            'informasi' => 'required|max:255',
        ], [
            'nama_level.required' => 'Nama Level User tidak boleh kosong.',
            'informasi.required' => 'Informasi tidak boleh kosong.',
        ]);

        ActivityLogger::log('Tambah Level User', $request->all());

        try {

            LevelUser::create([
                'nama_level' => $request->nama_level,
                'informasi' => $request->informasi,
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }

        return redirect()->route('master.leveluser.index')->with('success', 'Sukses menambahkan User Baru');
    }

    public function edit(string $id)
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0], $this->title[2]];

        $leveluser = LevelUser::findOrFail($id);

        return view('master.leveluser.edit', compact('menu', 'leveluser'));
    }

    public function update(Request $request, string $id)
    {
        $leveluser = LevelUser::findOrFail($id);

        ActivityLogger::log('Update Level User', ['id' => $id]);

        try {

            $leveluser->update([
                'nama_level' => $request->nama_level,
                'informasi' => $request->informasi,
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }

        return redirect()->route('master.leveluser.index')->with('success', 'Sukses Mengubah Data Level User');
    }

    public function delete(string $id)
    {
        $leveluser = LevelUser::findOrFail($id);

        ActivityLogger::log('Delete Level User', ['id' => $id]);

        try {
            DB::beginTransaction();

            $leveluser->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sukses menghapus Data Level User'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data Level User: ' . $th->getMessage()
            ], 500);
        }
    }
}
