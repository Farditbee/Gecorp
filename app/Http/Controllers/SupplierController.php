<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Data Supplier',
            'Tambah Data',
            'Edit Data'
        ];
    }

    public function getsupplier(Request $request)
    {

        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Supplier::query();

        $query->with([])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_supplier) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(contact) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(alamat) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(email) LIKE ?", ["%$searchTerm%"]);
            });
        }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

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
                'nama' => $item->nama_supplier,
                'email' => $item->email,
                'alamat' => $item->alamat,
                'kontak' => $item->contact,
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

        $supplier = Supplier::orderBy('id', 'desc')->get();

        return view('master.supplier.index', compact('menu', 'supplier'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0], $this->title[1]];

        return view('master.supplier.create', compact('menu'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validatedData = $request->validate([
            'nama_supplier' => 'required|max:255',
            'email' => 'required|max:255',
            'alamat' => 'required|max:255',
            'contact' => 'required|max:255',
        ], [
            'nama_supplier.required' => 'Nama Supplier tidak boleh kosong.',
            'email.required' => 'Email tidak boleh kosong.',
            'alamat.required' => 'Alamat tidak boleh kosong.',
            'contact.required' => 'Contact tidak boleh kosong.',
        ]);

        try {
            Supplier::create([
                'nama_supplier' => $request->nama_supplier,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'contact' => $request->contact,
            ]);

            ActivityLogger::log('Tambah Supplier', $data);
            
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }

        return redirect()->route('master.supplier.index')->with('success', 'Berhasil menambahkan Supplier Baru');
    }

    public function edit(string $id)
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0], $this->title[2]];
        $supplier = Supplier::findOrFail($id);
        return view('master.supplier.edit', compact('menu', 'supplier'));
    }


    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        try {
            $supplier->update([
                'nama_supplier' => $request->nama_supplier,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'contact' => $request->contact,
            ]);

            ActivityLogger::log('Ubah Supplier', $request->all());

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
        return redirect()->route('master.supplier.index')->with('success', 'Sukses Mengubah Data Supplier');
    }

    public function delete(String $id)
    {
        $supplier = Supplier::findOrFail($id);

        try {
            DB::beginTransaction();

            $supplier->delete();

            DB::commit();

            ActivityLogger::log('Hapus Supplier', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Sukses menghapus Data Supplier'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data Supplier: ' . $th->getMessage()
            ], 500);
        }
    }
}
