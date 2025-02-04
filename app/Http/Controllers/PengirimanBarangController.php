<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPembelianBarang;
use App\Models\DetailPengirimanBarang;
use App\Models\DetailToko;
use App\Models\PengirimanBarang;
use App\Models\StockBarang;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengirimanBarangController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Pengiriman Barang',
            'Tambah Data',
            'Detail Data',
            'Edit Data'
        ];
    }

    public function getpengirimanbarang(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        // Ambil id_toko dari request
        $id_toko = $request->input('id_toko');

        // Inisialisasi query
        $query = PengirimanBarang::query();

        // Jika id_toko bukan 1, filter berdasarkan toko_pengirim atau toko_penerima
        if ($id_toko != 1) {
            $query->where('toko_pengirim', $id_toko)
                ->orWhere('toko_penerima', $id_toko);
        }

        $query->with(['toko', 'tokos', 'user'])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                // Pencarian pada kolom langsung
                $query->orWhereRaw("LOWER(no_resi) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(ekspedisi) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(status) LIKE ?", ["%$searchTerm%"]);

                // Pencarian pada relasi 'supplier->nama_supplier'
                $query->orWhereHas('toko', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                });
                $query->orWhereHas('tokos', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                });
                $query->orWhereHas('user', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama) LIKE ?", ["%$searchTerm%"]);
                });
            });
        }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            // Lakukan filter berdasarkan tanggal
            $query->whereBetween('tgl_kirim', [$startDate, $endDate]);
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
                'no_resi' => $item->no_resi,
                'ekspedisi' => $item->ekspedisi,
                'id_toko_pengirim' => $item->toko->id ?? null,
                'toko_pengirim' => $item->toko->nama_toko ?? null, // Mengambil nama toko pengirim
                'nama_pengirim' => $item->user->nama ?? null, // Mengambil nama pengirim dari relasi user
                'toko_penerima' => $item->tokos->nama_toko ?? null, // Mengambil nama toko penerima
                'id_toko_penerima' => $item->tokos->id ?? null, // Mengambil nama toko penerima
                'status' => match ($item->status) {
                    'success' => 'Sukses',
                    'progress' => 'Progress',
                    'failed' => 'Gagal',
                    default => $item->status,
                },
                'tgl_kirim' => \Carbon\Carbon::parse($item->tgl_kirim)->format('d-m-Y'),
                'tgl_terima' => $item->tgl_terima ? \Carbon\Carbon::parse($item->tgl_terima)->format('d-m-Y') : null,
                'total_item' => $item->total_item,
                'total_nilai' => 'Rp. ' . number_format($item->total_nilai, 0, ',', '.'),
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

    public function index(Request $request)
    {
        $menu = [$this->title[0], $this->label[1]];
        $toko = Toko::all();
        $barang = Barang::all();
        $user = User::all();
        $users = Auth::user();

        // Memeriksa apakah ada parameter `start_date` dan `end_date` pada request
        $query = PengirimanBarang::query();

        if ($users->id_level == 1) {
            // Jika user dengan id_level 1, dapat melihat semua data
            $query = $query->orderBy('id', 'desc');
        } else {
            // Jika level user bukan 1, hanya tampilkan data toko terkait
            $query = $query->where('toko_penerima', $users->id_toko)
                ->orWhere('toko_pengirim', $users->id_toko)
                ->orderBy('id', 'desc');
        }

        // Menerapkan filter tanggal jika parameter `start_date` dan `end_date` ada
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $query = $query->whereBetween('tgl_kirim', [$startDate, $endDate]);
        }

        $pengiriman_barang = $query->get();

        return view('transaksi.pengirimanbarang.index', compact('menu', 'toko', 'barang', 'user', 'pengiriman_barang', 'users'));
    }



    public function detail(string $id)
    {
        $menu = [$this->title[0], $this->label[0], $this->title[2]];
        $detail_pengiriman = DetailPengirimanBarang::where('id_pengiriman_barang', $id)->get();  // Ambil data pengiriman dari database
        // $selectedTokoId = $detail_pengiriman->toko_pengirim;  // Asumsikan kamu menyimpan id toko pengirim di dalam pengiriman
        $pengiriman_barang = PengirimanBarang::findOrFail($id);
        // $pengiriman_barang = PengirimanBarang::all();

        return view('transaksi.pengirimanbarang.detail', compact('menu', 'detail_pengiriman', 'pengiriman_barang'));
    }

    public function create(Request $request)
    {
        $menu = [$this->title[0], $this->label[1], $this->title[1]];
        $toko = Toko::all();
        $detail_toko = DetailToko::all();
        $stock = StockBarang::all();

        $myToko = $toko->where('id', Auth::user()->id_toko)->first();

        return view('transaksi.pengirimanbarang.create', compact('menu', 'toko', 'stock', 'detail_toko', 'myToko'));
    }

    public function store(Request $request)
    {
        // try {
            $toko = Toko::all();
            $myToko = $toko->where('id', Auth::user()->id_toko)->first();
            DB::beginTransaction();
            // dd($request);

            // Simpan data dasar pengiriman
            $pengiriman_barang = PengirimanBarang::create([
                'no_resi' => $request->no_resi,
                'toko_pengirim' => $myToko->id,
                'nama_pengirim' => Auth::user()->nama,
                'ekspedisi' => $request->ekspedisi,
                'toko_penerima' => $request->toko_penerima,
                'tgl_kirim' => $request->tgl_kirim
            ]);

            DB::commit();
            // Redirect ke tab "detail pengiriman" dengan data pengiriman yang baru disimpan
            return redirect()->route('transaksi.pengirimanbarang.create')
                ->with('tab', 'detail')
                ->with('pengiriman_barang', $pengiriman_barang);
            // ->with('stock', $stock);
    }

    public function getUsersByToko($id_toko)
    {
        $users = User::where('id_toko', $id_toko)
            ->where('id_level', 2) // Tambahkan kondisi ini untuk filter admin
            ->get();
        if ($users->isEmpty()) {
            return response()->json(['error' => 'No users found'], 404);
        }
        return response()->json($users);
    }

    public function getBarangStock($id_barang, $id_toko)
    {
        // Mengambil barang yang tersedia berdasarkan id_toko dari tabel StockBarang
        if ($id_toko == 1) {
            $barangs = StockBarang::all();

            return response()->json($barangs);
        } else {
            $barangs = DetailToko::where('id_barang', $id_barang)
                ->where('id_toko', $id_toko)
                ->first();

            return response()->json($barangs);
        }
    }

    // public function getHargaBarang($id_barang, $id_toko)
    // {
    //     if ($id_toko == 1) {
    //         $stock = StockBarang::where('id_barang', $id_barang)->first();

    //         if ($stock) {
    //             return response()->json(['harga' => $stock->hpp_baru]);
    //         } else {
    //             return response()->json(['error' => 'Barang tidak ditemukan'], 404);
    //         }
    //     } else {
    //         $detail = DetailToko::where('id_barang', $id_barang)
    //             ->where('id_toko', $id_toko) // Menyesuaikan dengan toko yang bersangkutan
    //             ->first();
    //         if ($detail) {
    //             // return response()->json(['harga' => $detail->harga]);
    //             return response()->json($detail);
    //         } else {
    //             return response()->json(['error' => 'Barang tidak ditemukan'], 404);
    //         }
    //     }
    //     // Ambil harga dari tabel stock_barang berdasarkan id_barang
    // }

    // qrcode
    public function getHargaBarang(Request $request)
    {
        $request->validate([
            'id_toko' => 'required|string',
            'id_barang' => 'required|string',
        ]);

        $qrCode = $request->id_barang;
    
        try {

            $barang = DetailPembelianBarang::where('qrcode', $qrCode)->first();
            if (!$barang) {
                return response()->json([
                    'error' => true,
                    'message' => 'Barang tidak ditemukan berdasarkan qrcode',
                    'status_code' => 404,
                ], 404);
            }
            
            $id_barang = $barang->id_barang;

            if ($request->id_toko == 1) {
                $stock = StockBarang::where('id_barang', $id_barang)->first();

                if ($stock) {
                    return response()->json([
                        'error' => false,
                        'message' => 'Successfully',
                        'status_code' => 200,
                        'data' => [
                            'id_barang' => $stock->id_barang,
                            'id_supplier' => $barang->id_supplier,
                            'nama_barang' => $stock->barang->nama_barang,
                            'qty' => $stock->stock,
                            'harga' => $stock->hpp_baru,
                        ],
                    ]);
                }
            } else {
                $stock = DetailToko::where('id_barang', $id_barang)
                    ->where('id_toko', $request->id_toko)
                    ->first();

                if ($stock) {
                    return response()->json([
                        'error' => false,
                        'message' => 'Successfully',
                        'status_code' => 200,
                        'data' => [
                            'id_barang' => $stock->id_barang,
                            'id_supplier' => $barang->id_supplier,
                            'nama_supplier' => $barang->supplier->nama_supplier,
                            'nama_barang' => $stock->barang->nama_barang,
                            'qty' => $stock->qty,
                            'harga' => $stock->harga,
                        ],
                    ]);
                }
            }
    
            return response()->json([
                'error' => true,
                'message' => 'Barang tidak ditemukan',
                'status_code' => 404,
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching harga barang: ' . $e->getMessage());
    
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        // dd($request);
        $idBarangs = $request->input('id_barang', []);
        $qtys = $request->input('qty', []);
        $hargaBarangs = $request->input('harga', []);

        foreach ($idBarangs as $index => $id_barang) {
            $qty = $qtys[$index] ?? null;
            $harga = $hargaBarangs[$index] ?? null;

            if (is_null($qty) || is_null($harga)) {
                continue;
            }

            if ($qty <= 0 || $harga <= 0) {
                return redirect()->back()->with('error', 'Failed: Data harap diisi dengan benar.');
            }
        }

        try {
            DB::beginTransaction();

            $pengiriman_barang = PengirimanBarang::findOrFail($id);

            $totalItem = 0;
            $totalNilai = 0;

            $count = count($idBarangs);
            for ($i = 0; $i < $count; $i++) {
                $id_barang = $idBarangs[$i];
                $qty = $qtys[$i] ?? null;
                $harga = $hargaBarangs[$i] ?? null;

                if (is_null($qty) || is_null($harga)) {
                    continue;
                }

                if ($id_barang && $qty > 0 && $harga > 0) {
                    $barang = StockBarang::where('id_barang', $id_barang)->first();

                    $detail = DetailPengirimanBarang::updateOrCreate(
                        [
                            'id_pengiriman_barang' => $pengiriman_barang->id,
                            'id_barang' => $id_barang,
                        ],
                        [
                            'nama_barang' => $barang->nama_barang,
                            'qty' => $qty,
                            'harga' => $harga,
                            'total_harga' => $qty * $harga,
                        ]
                    );

                    $totalItem += $detail->qty;
                    $totalNilai += $detail->total_harga;
                }
            }

            $pengiriman_barang->total_item = $totalItem;
            $pengiriman_barang->total_nilai = $totalNilai;
            $pengiriman_barang->save();

            DB::commit();

            return redirect()->route('transaksi.pengirimanbarang.index')->with('success', 'Data Pengiriman Barang berhasil Ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Failed to update pengeriman barang. ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $menu = [$this->title[0], $this->label[0], $this->title[3]];
        $pengiriman_barang = PengirimanBarang::with('detail')->findOrFail($id);

        return view('transaksi.pengirimanbarang.edit', compact('menu', 'pengiriman_barang',));
    }

    public function editBarang($id)
    {
        $menu = [$this->title[0], $this->label[0], $this->title[3]];
        $pengiriman_barang = PengirimanBarang::with('detail')->findOrFail($id);

        return view('transaksi.pengirimanbarang.edit_barang', compact('menu', 'pengiriman_barang',));
    }

    public function updateStatus(Request $request, $id)
    {
        // dd($request->all());
        // Ambil data pengiriman_barang
        $pengiriman_barang = PengirimanBarang::findOrFail($id);
        $toko_pengirim = $pengiriman_barang->toko_pengirim;
        $toko_penerima = $pengiriman_barang->toko_penerima;

        $detail_ids = $request->input('detail_ids', []);
        $statuses = $request->input('status_detail', []);

        try {
            DB::beginTransaction();

            foreach ($detail_ids as $key => $detail_id) {
                $detail = DetailPengirimanBarang::findOrFail($detail_id);

                if (isset($statuses[$key]) && $statuses[$key] == 'success' && $detail->status != 'success') {

                    // Update the status in detail pembelian
                    $detail->status = 'success';
                    $detail->save();

                    if ($toko_pengirim != 1) {
                        $detailTokoPengirim = DetailToko::where('id_toko', $toko_pengirim)
                            ->where('id_barang', $detail->id_barang)
                            ->first();

                        if ($detailTokoPengirim) {
                            if ($detailTokoPengirim->qty >= $detail->qty) {
                                $detailTokoPengirim->qty -= $detail->qty;
                                $detailTokoPengirim->save();
                            } else {
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Stok tidak mencukupi di toko pengirim untuk barang dengan ID: ' . $detail->id_barang);
                            }
                        } else {
                            DB::rollBack();
                            return redirect()->back()->with('error', 'Barang dengan ID: ' . $detail->id_barang . ' tidak ditemukan di detail_toko pengirim.');
                        }
                    } else {
                        $stockBarang = StockBarang::where('id_barang', $detail->id_barang)->first();
                        if ($stockBarang) {
                            if ($stockBarang->stock >= $detail->qty) {
                                $stockBarang->stock -= $detail->qty;
                                $stockBarang->save();
                            } else {
                                // Jika stok tidak mencukupi, rollback transaksi
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk barang: ' . $stockBarang->nama_barang);
                            }
                        }
                    }

                    $detailToko = DetailToko::where('id_toko', $toko_penerima)
                        ->where('id_barang', $detail->id_barang)
                        ->first();
                    if ($detailToko) {
                        $detailToko->qty += $detail->qty;
                        $detailToko->save();
                    } else {
                        DetailToko::create([
                            'id_toko' => $toko_penerima,
                            'id_barang' => $detail->id_barang,
                            'qty' => $detail->qty,
                            'harga' => $detail->harga
                        ]);
                    }
                }
            }

            // Cek apakah semua barang dalam detail pembelian memiliki status 'success'
            $allSuccess = $pengiriman_barang->detail()->where('status', '!=', 'success')->count() === 0;

            if ($allSuccess) {
                // Jika semua barang sudah success, ubah status pembelian jadi success
                $pengiriman_barang->status = 'success';
                $pengiriman_barang->tgl_terima = now();
                $pengiriman_barang->save();
            }

            DB::commit();  // Commit transaction setelah semua operasi berhasil
            return redirect()->route('transaksi.pengirimanbarang.index')->with('success', 'Status Berhasil Diubah');
        } catch (\Exception $e) {
            DB::rollBack();  // Rollback jika terjadi error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storetempPengiriman(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'id_supplier' => 'required',
            'qty' => 'required',
            'harga' => 'required',
            'id_pengiriman_barang' => 'required',
        ]);

        $totalharga = $request->qty * $request->harga;

        try {

            DB::table('temp_detail_pengiriman')->insert([
                'id_pengiriman_barang' => $request->id_pengiriman_barang,
                'id_barang' => $request->id_barang,
                'id_supplier' => $request->id_barang,
                'qty' => $request->qty,
                'harga' => $request->harga,
                'total_harga' => $totalharga,
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Data berhasil ditambahkan ke temp',
                'status_code' => 200,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }

    public function deleteTempPengiriman(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'id_supplier' => 'required',
            'id_pengiriman_barang' => 'required',
        ]);

        try {
            DB::table('temp_detail_pengiriman')
            ->where('id', $request->id)
            ->where('id_pengiriman_barang', $request->id_pengiriman_barang)
            ->where('id_barang', $request->id_barang)
            ->where('id_supplier', $request->id_supplier)
            ->delete();

            return response()->json([
                'error' => false,
                'message' => 'Data berhasil dihapus dari temp',
                'status_code' => 200,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }

    public function updatetempPengiriman(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'id_supplier' => 'required',
            'qty' => 'required',
            'harga' => 'required',
            'id_pengiriman_barang' => 'required',
        ]);

        $totalharga = $request->qty * $request->harga;

        try {

            DB::table('temp_detail_pengiriman')
            ->where('id_pengiriman_barang', $request->id_pengiriman_barang)
            ->where('id_barang', $request->id_barang)
            ->where('id_supplier', $request->id_supplier)
            ->update([
                'qty' => $request->qty,
                'harga' => $request->harga,
                'total_harga' => $totalharga,
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Data berhasil diupdate di temp',
                'status_code' => 200,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }

    public function getTempPengiriman(Request $request)
    {
        $request->validate([
            'id_pengiriman_barang' => 'required',
        ]);

        try {
            $temp = DB::table('temp_detail_pengiriman')
            ->where('id_pengiriman_barang', $request->id_pengiriman_barang)
            ->get();

            return response()->json([
                'error' => false,
                'message' => 'Data berhasil diambil',
                'status_code' => 200,
                'data' => $temp,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }
}
