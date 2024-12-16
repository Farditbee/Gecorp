<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    public function getbrand(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Brand::query();

        $query->with([])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                // Pencarian pada kolom langsung
                $query->orWhereRaw("LOWER(nama_brand) LIKE ?", ["%$searchTerm%"]);
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
                'nama_brand' => $item->nama_brand,
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
        $brand = Brand::with('jenis')
            ->orderBy('id', 'desc')
            ->get();
        // $jenis = JenisBarang::all();
        return view('master.brand.index', compact('brand'));
    }

    public function create()
    {
        $jenis = JenisBarang::all();
        return view('master.brand.create', compact('jenis'), [
            'jenis' => JenisBarang::all()->pluck('id', 'nama_jenis_barang'),
        ]);
    }

    public function getBrandsByJenis(Request $request)
    {
        // Validasi bahwa id_jenis_barang dikirim melalui AJAX
        $request->validate([
            'id_jenis_barang' => 'required|exists:jenis_barang,id'
        ]);

        // Ambil semua Brand yang memiliki id_jenis_barang sesuai dengan yang dipilih
        $brands = Brand::where('id_jenis_barang', $request->id_jenis_barang)->get();

        // Kembalikan data dalam bentuk JSON
        return response()->json($brands);
    }
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'nama_brand' => 'required|string|max:255',
            ], [
                'nama_brand.required' => 'Nama Brand tidak boleh kosong.',
            ]);

            Brand::create([
                'nama_brand' => $request->nama_brand,
            ]);

            DB::commit();

            return redirect()->route('master.brand.index')->with('success', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit(string $id)
    {

        $brand = Brand::with('jenis')->findOrFail($id);

        $jenis = JenisBarang::all();
        return view('master.brand.edit', compact('brand', 'jenis'));
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        $validatedData = $request->validate([
            'nama_brand' => 'required|string|max:255',
        ], [
            'nama_brand.required' => 'Nama Brand tidak boleh kosong.',
        ]);

        $brand = Brand::findOrFail($id);

        $brand->update([
            'nama_brand' => $request->nama_brand,
        ]);

        DB::commit();

        return redirect()->route('master.brand.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        try {
            $brand = Brand::findOrFail($id);
            $brand->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sukses menghapus Data Brand'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data Brand: ' . $th->getMessage()
            ], 500);
        }
    }
}
