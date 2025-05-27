<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Imports\MemberImport;
use App\Models\JenisBarang;
use App\Models\LevelHarga;
use App\Models\LevelUser;
use App\Models\Member;
use App\Models\Toko;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class MemberController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Data Member',
        ];
    }

    public function getmember(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Member::query();

        $query->with(['toko', 'levelHarga', 'jenis_barang'])->orderBy('id', $meta['orderBy']);

        if ($request->has('id_toko')) {
            $idToko = $request->input('id_toko');
            if ($idToko != 1) {
                $query->where('id_toko', $idToko);
            }
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                if ($searchTerm === 'guest') {
                    $query->where('id_member', 0);
                } else {
                    $query->orWhereRaw("LOWER(nama_member) LIKE ?", ["%$searchTerm%"]);
                    $query->orWhereRaw("LOWER(no_hp) LIKE ?", ["%$searchTerm%"]);
                    $query->orWhereRaw("LOWER(alamat) LIKE ?", ["%$searchTerm%"]);

                    $query->orWhereHas('toko', function ($subquery) use ($searchTerm) {
                        $subquery->whereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                    });
                }
            });
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
            $idLevelHarga = is_array($item->id_level_harga) ? $item->id_level_harga : json_decode($item->id_level_harga, true);

            if (!is_array($idLevelHarga) || empty($idLevelHarga)) {
                $idLevelHarga = [];
            }

            $levelData = [];
            if (!empty($idLevelHarga)) {
                $levelData = LevelHarga::whereIn('id', $idLevelHarga)
                    ->get(['jenis_barang', 'nama_level_harga as level_harga'])
                    ->toArray();
            }

            $selectedLevels = [];
            if (!empty($item->level_info)) {
                foreach (json_decode($item->level_info, true) as $info) {
                    preg_match('/(\d+) : (\d+)/', $info, $matches);
                    if (!empty($matches)) {
                        $jenisBarang = JenisBarang::find($matches[1]);
                        $levelHarga = LevelHarga::find($matches[2]);

                        if ($jenisBarang && $levelHarga) {
                            $selectedLevels[] = [
                                'nama_jenis_barang' => $jenisBarang->nama_jenis_barang,
                                'nama_level_harga' => $levelHarga->nama_level_harga,
                            ];
                        }
                    }
                }
            }

            return [
                'id' => $item['id'],
                'nama_member' => $item['nama_member'],
                'nama_toko' => $item['toko']->nama_toko ?? null,
                'level' => $selectedLevels,
                'no_hp' => $item->no_hp,
                'alamat' => $item->alamat,
            ];
        });

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Sukses',
            'pagination' => $data['meta']
        ], 200);
    }

    public function index()
    {
        if (!in_array(Auth::user()->id_level, [1, 2, 3, 4])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[0]];
        $user = Auth::user();

        if ($user->id_level == 1 || $user->id_level == 2) {
            $member = Member::orderBy('id', 'desc')
                ->with(['levelharga', 'toko', 'jenis_barang'])
                ->get();

            $toko = Toko::all();

        } else {
            $member = Member::where('id_toko', $user->id_toko)
                ->orderBy('id', 'desc')
                ->with(['levelharga', 'toko', 'jenis_barang'])
                ->get();

            $toko = Toko::where('id', $user->id_toko)->get();
        }

        $jenis_barang = JenisBarang::all();
        $levelharga = LevelHarga::all();

        $selected_levels = [];
        foreach ($member as $mbr) {
            if (!empty($mbr->level_info)) {
                foreach (json_decode($mbr->level_info, true) as $info) {
                    preg_match('/(\d+) : (\d+)/', $info, $matches);
                    $selected_levels[$mbr->id][$matches[1]] = $matches[2]; // $matches[1] adalah id_jenis_barang, $matches[2] adalah id_level_harga
                }
            }
        }

        return view('master.member.index', compact('menu', 'member', 'toko', 'jenis_barang', 'levelharga', 'selected_levels'));
    }

    public function getLevelHarga($id_toko)
    {
        $toko = Toko::where('id', $id_toko)->first();

        if ($toko) {

            $levelHargaIds = json_decode($toko->id_level_harga, true);

            $levelHarga = LevelHarga::whereIn('id', $levelHargaIds)->get();

            return response()->json($levelHarga);
        }

        return response()->json(['error' => 'Toko tidak ditemukan'], 404);
    }

    public function create()
    {
        $toko = Toko::all();
        $leveluser = LevelUser::all();
        $levelharga = LevelHarga::all();
        $jenis_barang = JenisBarang::all();

        return view('master.member.create', compact('toko', 'leveluser', 'levelharga', 'jenis_barang'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'id_toko' => 'required',
                'nama_member' => 'required',
                'no_hp' => 'required',
                'alamat' => 'required'
            ],
            [
                'id_toko.required' => 'Toko Wajib diisi.',
                'nama_member.required' => 'Nama Member tidak boleh kosong',
                'no_hp.required' => 'No Hp Wajib diisi',
                'alamat.required' => 'Alamat Wajib diisi',
            ]
        );

        ActivityLogger::log('Tambah Member', $request->all());

        $level_harga = $request->input('level_harga');

        foreach ($level_harga as $jenis_barang_id => $level_harga_id) {
            if (!empty($level_harga_id)) {
                $levelInfo[] = "{$jenis_barang_id} : {$level_harga_id}";
            }
        }

        try {
            Member::create([
                'id_toko' => $request->id_toko,
                'nama_member' => $request->nama_member,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'level_info' => json_encode($levelInfo),
            ]);

        } catch (Throwable $th) {
            return redirect()->route('master.member.index')->with('error', $th->getMessage())->withInput();
        }

        return redirect()->route('master.member.index')->with('success', 'Sukses menambahkan Member Baru');
    }

    public function edit($id)
    {
        $member = Member::with('levelharga')->findOrFail($id);
        $jenis_barang = JenisBarang::all();
        $levelharga = LevelHarga::all();

        $selected_levels = $member->level_data;

        return view('member.edit', compact('member', 'jenis_barang', 'levelharga', 'selected_levels'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate(
                [
                ],
                [
                ]
            );

            $member = Member::findOrFail($id);

            $level_harga = $request->input('level_harga', []);

            $levelInfo = [];
            if (!empty($level_harga)) {
                foreach ($level_harga as $jenis_barang_id => $level_harga_id) {
                    if (!empty($level_harga_id)) {
                        $levelInfo[] = "{$jenis_barang_id} : {$level_harga_id}";
                    }
                }
            }

            $updateData = [
                'id_toko' => $request->id_toko,
                'nama_member' => $request->nama_member,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'level_info' => json_encode($levelInfo),
            ];

            $updated = $member->update($updateData);

            if (!$updated) {
                throw new \Exception('Gagal memperbarui data member');
            }

            ActivityLogger::log('Update Member', ['id' => $id, 'data' => $updateData]);

            DB::commit();
            return redirect()->route('master.member.index')->with('success', 'Sukses memperbarui Member');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('master.member.index')
                ->with('error', 'Gagal memperbarui Member: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function delete(string $id)
    {
        $member = Member::findOrFail($id);

        ActivityLogger::log('Delete Member', ['id' => $id]);

        try {
            DB::beginTransaction();

            $member->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sukses menghapus Data Member'
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data Member: ' . $th->getMessage()
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new MemberImport, $request->file('file'));

        return back()->with('success', 'Data berhasil diimpor!');
    }
}
