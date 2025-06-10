<?php

namespace App\Http\Controllers;

use App\Imports\UserImport;
use App\Models\LevelUser;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Data User',
            'Tambah Data',
            'Edit Data'
        ];
    }

    public function getdatauser(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = User::query();

        $query->with(['toko', 'leveluser',])->orderBy('id', $meta['orderBy']);

        // Filter berdasarkan id_toko
        if ($request->has('id_toko')) {
            $idToko = $request->input('id_toko');
            if ($idToko != 1) {
                $query->where('id_toko', $idToko);
            }
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                // Pencarian pada kolom langsung
                $query->orWhereRaw("LOWER(nama) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(email) LIKE ?", ["%$searchTerm%"]);

                // Pencarian pada relasi 'supplier->nama_supplier'
                $query->orWhereHas('toko', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                });

                $query->orWhereHas('leveluser', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_level) LIKE ?", ["%$searchTerm%"]);
                });
            });
        }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            // Lakukan filter berdasarkan tanggal
            $query->whereBetween('id', [$startDate, $endDate]);
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
                'nama_toko' => optional($item['toko'])->nama_toko ?? 'Tidak ada',
                'nama_level' => optional($item['leveluser'])->nama_level ?? 'Tidak ada',
                'nama' => $item->nama,
                'username' => $item->username,
                'email' => $item->email,
                'alamat' => $item->alamat,
                'no_hp' => $item->no_hp,
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
        $menu = [$this->title[0], $this->label[0]];
        $user = Auth::user(); // Mendapatkan user yang sedang login

        // Jika user memiliki leveluser = 1, tampilkan semua data user
        if ($user->id_level == 1) {
            $users = User::with('leveluser', 'toko')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            // Jika leveluser selain 1, hanya tampilkan user dari toko yang sama
            $users = User::with('leveluser', 'toko')
                ->where('id_toko', $user->id_toko)
                ->orderBy('id', 'desc')
                ->get();
        }

        $leveluser = LevelUser::all();

        return view('master.user.index', compact('menu', 'users', 'leveluser'));
    }


    public function create()
    {

        if (!in_array(Auth::user()->id_level, [1, 2, 3, 6])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0], $this->title[1]];
        $toko = Toko::all();
        $leveluser = LevelUser::all();
        return view('master.user.create', compact('menu', 'toko', 'leveluser'), [
            'leveluser' => LevelUser::all()->pluck('nama_level', 'id'),
            'toko' => Toko::all()->pluck('nama_toko', 'id'),
        ]);
    }

    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate(
            [
                'id_toko' => 'required',
                'id_level' => 'required',
                'nama' => 'required|max:255',
                'username' => 'required|max:255',
                'password' => 'required|min:8|regex:/([0-9])/',
                'email' => 'required|max:255',
                'alamat' => 'required|max:255',
                'no_hp' => 'required|max:255',
            ],
            [
                'id_toko.required' => 'Nama Toko tidak boleh kosong.',
                'id_level.required' => 'Nama Level tidak boleh kosong.',
                'nama.required' => 'Nama tidak boleh kosong.',
                'username.required' => 'Username tidak boleh kosong.',
                'password.required' => 'Password tidak boleh kosong.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.regex' => 'Password harus mengandung minimal satu angka.',
                'email.required' => 'Email tidak boleh kosong.',
                'alamat.required' => 'Alamat tidak boleh kosong.',
                'no_hp.required' => 'No Hp tidak boleh kosong.',
            ]
        );
        try {
            User::create([
                'id_toko' => $request->id_toko,
                'id_level' => $request->id_level,
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'email' => $request->email,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
        return redirect()->route('master.user.index')->with('success', 'Berhasil menambahkan User Baru');
    }

    public function edit(string $id)
    {
        if (!in_array(Auth::user()->id_level, [1, 2, 3])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0], $this->title[2]];
        $user = User::with(['leveluser', 'toko'])->findOrFail($id);

        // dd($user);
        $toko = Toko::all();
        $leveluser = LevelUser::all();
        return view('master.user.edit', compact('menu', 'user', 'toko', 'leveluser'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        try {
            $data = [
                'id_toko' => $request->id_toko,
                'id_level' => $request->id_level,
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ];

            // Hanya tambahkan password jika field password tidak kosong
            if (!empty($request->password)) {
                $data['password'] = bcrypt($request->password);
            }

            $user->update($data);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }

        return redirect()->route('master.user.index')->with('success', 'Sukses Mengubah Data User');
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        $user = User::findOrFail($id);
        try {
            $user->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sukses menghapus Data User'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data User: ' . $th->getMessage()
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new UserImport, $request->file('file'));

        return back()->with('success', 'Data berhasil diimpor!');
    }
}
