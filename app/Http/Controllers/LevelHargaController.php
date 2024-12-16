<?php

namespace App\Http\Controllers;

use App\Models\LevelHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelHargaController extends Controller
{
    public function getlevelharga(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = LevelHarga::query();

        $query->with([])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                // Pencarian pada kolom langsung
                $query->orWhereRaw("LOWER(nama_level_harga) LIKE ?", ["%$searchTerm%"]);
            });
        }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            // Lakukan filter berdasarkan tanggal
            $query->whereBetween('created_at', [$startDate, $endDate]);
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
        $levelharga = LevelHarga::orderBy('id', 'desc')->get();
        return view('master.levelharga.index', compact('levelharga'));
    }

    public function create()
    {
        return view('master.levelharga.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_level_harga' => 'required|max:255',
        ],[
            'nama_level_harga.required' => 'Nama level harga tidak boleh kosong.',
        ]);
        try {
            LevelHarga::create([
                'nama_level_harga' => $request->nama_level_harga,
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
        return redirect()->route('master.levelharga.index')->with('success', 'Berhasil menambahkan Level Baru');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $levelharga = LevelHarga::findOrFail($id);
        return view('master.levelharga.edit', compact('levelharga'));
    }

    public function update(Request $request, string $id)
    {
        $levelharga = LevelHarga::findOrFail($id);
        try {
           $levelharga->update([
            'nama_level_harga'=> $request->nama_level_harga,
           ]);
     } catch (\Throwable $th) {
        return redirect()->back()->with('error', $th->getMessage())->withInput();
    }
    return redirect()->route('master.levelharga.index')->with('success', 'Sukses Mengubah Data Level Harga');
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        $levelharga = LevelHarga::findOrFail($id);
        try {
            $levelharga->delete();
        DB::commit();

        return redirect()->route('master.levelharga.index')->with('success', 'Berhasil menghapus Data Level Harga');
        } catch (\Throwable $th) {
        DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Data Level Harga ' . $th->getMessage());
        }
    }
}
