<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DataReture;
use App\Models\DetailPembelianBarang;
use App\Models\DetailPengirimanBarang;
use App\Models\DetailRetur;
use App\Models\DetailStockBarang;
use App\Models\DetailToko;
use App\Models\PengirimanBarang;
use App\Models\StockBarang;
use App\Models\Toko;
use App\Models\User;
use Carbon\Carbon;
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
            'Edit Data',
            'Reture Data',
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
                'no_resi' => $item->no_resi,
                'ekspedisi' => $item->ekspedisi,
                'id_toko_pengirim' => $item->toko->id ?? null,
                'toko_pengirim' => $item->toko->nama_toko ?? null, // Mengambil nama toko pengirim
                'nama_pengirim' => $item->nama_pengirim ?? null, // Mengambil nama pengirim dari relasi user
                'toko_penerima' => $item->tokos->nama_toko ?? null, // Mengambil nama toko penerima
                'id_toko_penerima' => $item->tokos->id ?? null, // Mengambil nama toko penerima
                'status' => match ($item->status) {
                    'success' => 'Sukses',
                    'progress' => 'Progress',
                    'pending' => 'Pending',
                    'failed' => 'Gagal',
                    default => $item->status,
                },
                'tipe_pengiriman' => $item->tipe_pengiriman,
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
        if (!in_array(Auth::user()->id_level, [1, 2, 3])) {
            abort(403, 'Unauthorized');
        }
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
        if (!in_array(Auth::user()->id_level, [1, 2, 3])) {
            abort(403, 'Unauthorized');
        }
        $menu = [$this->title[0], $this->label[1], $this->title[2]];

        $pengiriman_barang = PengirimanBarang::findOrFail($id);

        $userTokoId = Auth::user()->id_toko;

        if ($pengiriman_barang->toko_pengirim !== $userTokoId && $pengiriman_barang->toko_penerima !== $userTokoId && $userTokoId != 1) {
            abort(403, 'Unauthorized access');
        }

        $detail_pengiriman = DetailPengirimanBarang::where('id_pengiriman_barang', $id)->get();

        return view('transaksi.pengirimanbarang.detail', compact('menu', 'detail_pengiriman', 'pengiriman_barang'));
    }


    public function create(Request $request)
    {
        // Pastikan pengguna sudah login, lalu periksa id_level
        abort_if(!Auth::check() || !in_array(Auth::user()->id_level, [1, 2, 3]), 403, 'Unauthorized');

        $menu = [$this->title[0], $this->label[1], $this->title[1]];
        $toko = Toko::all();
        $detail_toko = DetailToko::all();
        $stock = StockBarang::all();

        $myToko = $toko->where('id', Auth::user()->id_toko)->first();

        return view('transaksi.pengirimanbarang.create', compact('menu', 'toko', 'stock', 'detail_toko', 'myToko'));
    }

    public function store(Request $request)
    {
        $toko = Toko::all();

        $myToko = $toko->where('id', Auth::user()->id_toko)->first();

        DB::beginTransaction();


        $tglKirim = Carbon::parse($request->tgl_kirim);
        if ($tglKirim->format('H:i:s') === '00:00:00') {
            // Tambahkan waktu default (waktu saat ini)
            $tglKirim->setTimeFromTimeString(Carbon::now()->format('H:i:s'));
        }

        $pengiriman_barang = PengirimanBarang::create([
            'no_resi' => $request->no_resi,
            'toko_pengirim' => $myToko->id,
            'nama_pengirim' => Auth::user()->nama,
            'ekspedisi' => $request->ekspedisi,
            'toko_penerima' => $request->toko_penerima,
            'tgl_kirim' => $tglKirim
        ]);

        DB::commit();
        return redirect()->route('distribusi.pengirimanbarang.create')
            ->with('tab', 'detail')
            ->with('pengiriman_barang', $pengiriman_barang);
    }

    public function storeReture(Request $request)
    {
        $request->validate([
            'no_resi' => 'required',
            'tgl_kirim' => 'required',
            'ekspedisi' => 'required',
            'toko_penerima' => 'required',
        ]);

        $user = Auth::user();

        DB::beginTransaction();

        $tglKirim = Carbon::parse($request->tgl_kirim);
        if ($tglKirim->format('H:i:s') === '00:00:00') {
            // Tambahkan waktu default (waktu saat ini)
            $tglKirim->setTimeFromTimeString(Carbon::now()->format('H:i:s'));
        }

        $pengiriman_barang = PengirimanBarang::create([
            'no_resi' => $request->no_resi,
            'toko_pengirim' => $user->id_toko,
            'nama_pengirim' => $user->nama,
            'ekspedisi' => $request->ekspedisi,
            'toko_penerima' => $request->toko_penerima,
            'tgl_kirim' => $tglKirim,
            'tipe_pengiriman' => 'reture',
        ]);

        $reture_barang = DataReture::where('id_toko', $user->id_toko)->get();

        // dd($reture_barang);

        $detail_reture = collect(); // Inisialisasi collection kosong

        foreach ($reture_barang as $retur) {
            $detail = DetailRetur::join('detail_pembelian_barang', 'detail_retur.qrcode', '=', 'detail_pembelian_barang.qrcode')
                ->where('detail_retur.id_retur', $retur->id)
                ->where('detail_retur.status_kirim', 'pending')
                ->select(
                    'detail_retur.id as detail_retur_id',
                    'detail_retur.id_retur as retur_id',
                    'detail_retur.*',
                    'detail_pembelian_barang.*'
                )
                ->get();

            $detail_reture = $detail_reture->merge($detail); // Gabungkan hasil query
        }

        // dd($detail_reture);


        DB::commit();

        return redirect()->route('distribusi.pengirimanbarang.reture')
            ->with('tab', 'detail')
            ->with('pengiriman_barang', $pengiriman_barang)
            ->with('detail_reture', $detail_reture);
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

    public function getHargaBarang(Request $request)
    {
        $request->validate([
            'id_toko' => 'required|string',
            'id_barang' => 'required|string',
        ]);

        $id_barang = $request->id_barang;
        $id_toko = $request->id_toko;

        list($qrCode, $id_detail) = explode('/', $id_barang);

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
            $id_supplier = $barang->id_supplier;
            $id_pembelian = $barang->id_pembelian_barang;

            if ($id_toko == 1) {
                // Cek stok dari DetailStockBarang terlebih dahulu
                $stock = DetailStockBarang::where('id_barang', $id_barang)
                    ->where('id_supplier', $id_supplier)
                    ->where('id_detail_pembelian', $barang->id)
                    ->where('id_pembelian', $id_pembelian)
                    ->first();

                if ($stock && $stock->qty_now > 0) {
                    $hppBaru = StockBarang::where('id_barang', $id_barang)->value('hpp_baru');

                    return response()->json([
                        'error' => false,
                        'message' => 'Successfully',
                        'status_code' => 200,
                        'data' => [
                            'id_barang' => $stock->id_barang,
                            'id_supplier' => $stock->id_supplier,
                            'id_detail' => $id_detail ?: $stock->id_detail_pembelian, // Pastikan id_detail terisi
                            'nama_supplier' => $stock->supplier->nama_supplier,
                            'nama_barang' => $stock->barang->nama_barang,
                            'qty' => $stock->qty_now,
                            'harga' => $hppBaru,
                            'qrcode' => $barang->qrcode,
                        ],
                    ]);
                }

                return response()->json([
                    'error' => true,
                    'message' => 'Stok barang kosong',
                    'status_code' => 404,
                ], 404);
            } else {
                // Jika DetailStockBarang tidak ditemukan atau stok kosong, cek di DetailToko
                $stockToko = DetailToko::where('id_barang', $id_barang)
                    ->where('id_toko', $request->id_toko)
                    ->where('qrcode', $qrCode)
                    ->first();

                if ($stockToko && $stockToko->qty > 0) {
                    return response()->json([
                        'error' => false,
                        'message' => 'Successfully',
                        'status_code' => 200,
                        'data' => [
                            'id_barang' => $stockToko->id_barang,
                            'id_supplier' => $barang->id_supplier,
                            'id_detail' => $id_detail ?: $barang->id,
                            'nama_supplier' => $barang->supplier->nama_supplier,
                            'nama_barang' => $stockToko->barang->nama_barang,
                            'qty' => $stockToko->qty,
                            'qrcode' => $stockToko->qrcode,
                            'harga' => $stockToko->harga,
                        ],
                    ]);
                }

                return response()->json([
                    'error' => true,
                    'message' => 'Stok barang kosong',
                    'status_code' => 404,
                ], 404);
            }

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

            return redirect()->route('distribusi.pengirimanbarang.index')->with('success', 'Data Pengiriman Barang berhasil Ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Failed to update pengeriman barang. ' . $e->getMessage());
        }
    }

    public function storeDetailReture(Request $request)
    {
        $request->validate([
            'id_pengiriman' => 'required',
            'returId' => 'required|array',
            'qrcode' => 'required|array',
            'qty' => 'required|array',
            'harga_beli' => 'required|array',
            'detail_ids' => 'required|array',
        ]);

        // dd($request->all());

        $id_pengiriman = $request->id_pengiriman;
        $qrcodes = $request->qrcode;
        $qtys = $request->qty;
        $hargaBelis = $request->harga_beli;
        $detailIds = $request->detail_ids;
        $idRetur = $request->returId;

        // dd($idRetur);

        // $id_retur = null;
        // if (count(array_unique($idRetur)) === 1) {
        //     $id_retur = $idRetur[0];
        // }

        try {

            DB::beginTransaction();

            $pengiriman_barang = PengirimanBarang::findOrFail($id_pengiriman);

            $totalItem = 0;
            $totalNilai = 0;

            foreach ($qrcodes as $index => $qrcode) {
                $qty = $qtys[$index];
                $harga_beli = $hargaBelis[$index];
                $id_detail = $detailIds[$index];

                $detail_pembelian = DetailPembelianBarang::where('qrcode', $qrcode)->first();

                $detail_reture = DetailRetur::where('id', $id_detail)->first();
                $detail_reture->status_kirim = 'progress';
                $detail_reture->save();

                if (!$detail_pembelian) {
                    return redirect()->back()->with('error', 'Detail pembelian barang tidak ditemukan');
                }

                $detail_pengiriman = DetailPengirimanBarang::create([
                    'id_pengiriman_barang' => $pengiriman_barang->id,
                    'id_detail_pembelian' => $detail_pembelian->id,
                    'id_barang' => $detail_pembelian->id_barang,
                    'id_supplier' => $detail_pembelian->id_supplier,
                    'qrcode' => $qrcode,
                    'qty' => $qty,
                    'harga' => $harga_beli,
                    'total_harga' => $qty * $harga_beli,
                ]);

                $totalItem += $detail_pengiriman->qty;
                $totalNilai += $detail_pengiriman->total_harga;

            }

            $pengiriman_barang->total_item = $totalItem;
            $pengiriman_barang->total_nilai = $totalNilai;
            $pengiriman_barang->id_retur = $idRetur;
            $pengiriman_barang->status = 'progress';
            $pengiriman_barang->save();

            DB::commit();

            return redirect()->route('distribusi.pengirimanbarang.reture')->with('success', 'Data Reture Barang berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }

    }

    public function edit($id)
    {
        if (!in_array(Auth::user()->id_level, [1, 2, 3])) {
            abort(403, 'Unauthorized');
        }
        $menu = [$this->title[0], $this->label[1], $this->title[3]];
        $pengiriman_barang = PengirimanBarang::with('detail')->findOrFail($id);
        $userTokoId = Auth::user()->id_toko;

        if ($pengiriman_barang->toko_penerima !== $userTokoId) {
            abort(403, 'Anda bukan dari toko penerima dari data Pengiriman Barang ini.');
        }

        if ($pengiriman_barang->status === 'success') {
            $text = 'Data dengan Nomor Resi: ' . $pengiriman_barang->no_resi . ' Sudah Diverifikasi';
            abort(403, $text);
        }

        return view('transaksi.pengirimanbarang.edit', compact('menu', 'pengiriman_barang', ));
    }

    public function updateStatus(Request $request, $id)
    {
        // Ambil data pengiriman_barang
        $pengiriman_barang = PengirimanBarang::findOrFail($id);
        $toko_pengirim = $pengiriman_barang->toko_pengirim;
        $toko_penerima = $pengiriman_barang->toko_penerima;

        $detail_ids = $request->input('detail_ids', []);
        $statuses = $request->input('status_detail', []);
        $tipe_kirim = $request->tipe_kirim;
        $id_retur = $request->input('id_retur', []);

        $nonEmptyStatuses = array_filter($statuses, function ($status) {
            return trim($status) !== '';
        });

        if (empty($nonEmptyStatuses)) {
            return response()->json([
                'error' => true,
                'message' => 'Harap ceklis barang yang ingin di verify terlebih dahulu',
                'status_code' => 400,
            ], 400);
        }

        // dd($request->all());

        try {
            DB::beginTransaction();

            if ($tipe_kirim == 'reture') {
                $pengiriman_barang->status = 'success';
                $pengiriman_barang->tgl_terima = now();
                $pengiriman_barang->save();

                foreach ($detail_ids as $key => $detail_id) {
                    $detail = DetailPengirimanBarang::findOrFail($detail_id);
                    $detail->status = 'success';
                    $detail->save();
                }

                $detail_retur = DetailRetur::whereIn('id_retur', $id_retur)->get();

                // dd($detail_retur);

                foreach ($detail_retur as $key => $detail) {
                    $detail->status_kirim = 'success';
                    $detail->save();
                }

                DB::commit();

                return redirect()->route('distribusi.pengirimanbarang.reture')->with('success', 'Status Berhasil Diubah');
            }

            foreach ($detail_ids as $key => $detail_id) {
                $detail = DetailPengirimanBarang::findOrFail($detail_id);

                if (isset($statuses[$key]) && $statuses[$key] == 'success' && $detail->status != 'success') {

                    // Update status menjadi success
                    $detail->status = 'success';
                    $detail->save();

                    // Ambil QR Code berdasarkan id_detail_pembelian
                    $barang = DetailPembelianBarang::where('id', $detail->id_detail_pembelian)->first();
                    $qrcode = $barang ? $barang->qrcode : null;

                    // **Kurangi stok jika dari pusat**
                    if ($toko_pengirim == 1) {
                        $stockBarang = StockBarang::where('id_barang', $detail->id_barang)->first();
                        if ($stockBarang) {
                            if ($stockBarang->stock >= $detail->qty) {
                                $stockBarang->stock -= $detail->qty;
                                $stockBarang->save();
                            } else {
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk barang: ' . $stockBarang->nama_barang);
                            }
                        }
                    }

                    // **Tambahkan stok ke toko penerima**
                    $existingDetailToko = DetailToko::where('id_toko', $toko_penerima)
                        ->where('id_supplier', $detail->id_supplier)
                        ->where('id_barang', $detail->id_barang)
                        ->where('qrcode', $qrcode)
                        ->first();

                    if ($existingDetailToko) {
                        // Jika sudah ada, tambahkan qty
                        $existingDetailToko->qty += $detail->qty;
                        $existingDetailToko->save();
                    } else {
                        // Jika tidak ada, buat row baru
                        DetailToko::create([
                            'id_toko' => $toko_penerima,
                            'id_supplier' => $detail->id_supplier,
                            'id_barang' => $detail->id_barang,
                            'qty' => $detail->qty,
                            'harga' => $detail->harga,
                            'qrcode' => $qrcode,
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

            DB::commit();

            return redirect()->route('distribusi.pengirimanbarang.index')->with('success', 'Status Berhasil Diubah');
        } catch (\Exception $e) {

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }

    public function storetempPengiriman(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'id_supplier' => 'required',
            'id_pengiriman_barang' => 'required',
            'id_detail' => 'required',
            'qty' => 'required',
            'harga' => 'required',
        ]);

        if ($request->qty <= 0) {
            return response()->json([
                'error' => true,
                'message' => 'Qty tidak boleh kurang dari 0',
                'status_code' => 400,
            ], 400);
        }

        $totalharga = $request->qty * $request->harga;

        try {

            DB::table('temp_detail_pengiriman')->insert([
                'id_pengiriman_barang' => $request->id_pengiriman_barang,
                'id_detail_pembelian' => $request->id_detail,
                'id_barang' => $request->id_barang,
                'id_supplier' => $request->id_supplier,
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
            'status' => 'required',
        ]);

        try {

            if ($request->status == 'success' || $request->status == 'progress') {
                $data = DetailPengirimanBarang::where('id_pengiriman_barang', $request->id_pengiriman_barang)
                    ->join('barang', 'detail_pengiriman_barang.id_barang', '=', 'barang.id')
                    ->join('supplier', 'detail_pengiriman_barang.id_supplier', '=', 'supplier.id')
                    ->join('detail_pembelian_barang', 'detail_pengiriman_barang.id_detail_pembelian', '=', 'detail_pembelian_barang.id')
                    ->select('detail_pengiriman_barang.*', 'barang.nama_barang', 'supplier.nama_supplier', 'detail_pembelian_barang.qrcode')
                    ->get();

                return response()->json([
                    'error' => false,
                    'message' => 'Data berhasil diambil',
                    'status_code' => 200,
                    'data' => $data,
                ], 200);
            } elseif ($request->status == 'pending') {
                $data = DB::table('temp_detail_pengiriman')
                    ->join('pengiriman_barang', 'temp_detail_pengiriman.id_pengiriman_barang', '=', 'pengiriman_barang.id')
                    ->join('barang', 'temp_detail_pengiriman.id_barang', '=', 'barang.id')
                    ->join('supplier', 'temp_detail_pengiriman.id_supplier', '=', 'supplier.id')
                    ->join('stock_barang', 'temp_detail_pengiriman.id_barang', '=', 'stock_barang.id_barang')
                    ->join('detail_stock', 'temp_detail_pengiriman.id_detail_pembelian', '=', 'detail_stock.id_detail_pembelian')
                    ->join('detail_pembelian_barang', 'detail_stock.id_detail_pembelian', '=', 'detail_pembelian_barang.id')
                    ->select('temp_detail_pengiriman.*', 'barang.nama_barang', 'supplier.nama_supplier', 'detail_stock.qty_now as stock', 'detail_pembelian_barang.qrcode')
                    ->where('pengiriman_barang.status', $request->status)
                    ->where('temp_detail_pengiriman.id_pengiriman_barang', $request->id_pengiriman_barang)
                    ->get();

                return response()->json([
                    'error' => false,
                    'message' => 'Data berhasil diambil',
                    'status_code' => 200,
                    'data' => $data,
                ], 200);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Tidak ada data',
                    'status_code' => 400,
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }

    public function save(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|array',
            'qty' => 'required|array',
            'harga' => 'required|array',
            'id_supplier' => 'required|array',
            'id_pengiriman_barang' => 'required|string',
            'qrcode' => 'required|array',
        ]);

        $idBarangs = $request->input('id_barang', []);
        $qtys = $request->input('qty', []);
        $hargaBarangs = $request->input('harga', []);
        $qrcodes = $request->input('qrcode', []);
        $id_pengiriman_barang = $request->id_pengiriman_barang;

        try {
            DB::beginTransaction();

            $totalItem = 0;
            $totalNilai = 0;

            foreach ($idBarangs as $index => $id_barang) {
                $qty = $qtys[$index];
                $harga = $hargaBarangs[$index];
                $qrCode = $qrcodes[$index];
                $total_harga = $qty * $harga;

                // Find the detail_pembelian_barang by qrcode
                $detailPembelianBarang = DetailPembelianBarang::where('qrcode', $qrCode)->first();
                if (!$detailPembelianBarang) {
                    throw new \Exception('Detail Pembelian Barang not found for qrcode: ' . $qrCode);
                }

                // Find the detail_stock by id_detail_pembelian
                $detailStock = DetailStockBarang::where('id_detail_pembelian', $detailPembelianBarang->id)->first();
                if (!$detailStock) {
                    throw new \Exception('Detail Stock not found for id_detail_pembelian: ' . $detailPembelianBarang->id);
                }

                if (Auth::user()->id_toko == 1) {
                    $detailStock->qty_now -= $qty;
                    $detailStock->qty_out += $qty;
                    $detailStock->save();
                }

                DetailPengirimanBarang::create([
                    'id_pengiriman_barang' => $id_pengiriman_barang,
                    'id_barang' => $id_barang,
                    'id_detail_pembelian' => $detailPembelianBarang->id,
                    'qty' => $qty,
                    'harga' => $harga,
                    'total_harga' => $total_harga,
                    'id_supplier' => $request->id_supplier[$index],
                ]);

                $totalItem += $qty;
                $totalNilai += $total_harga;
            }

            // Mengabaikan id_toko = 1, menangani pengurangan stok untuk id_toko selain 1
            $pengiriman_barang = PengirimanBarang::findOrFail($id_pengiriman_barang);
            if ($pengiriman_barang->toko_pengirim != 1) {
                foreach ($idBarangs as $index => $id_barang) {
                    $qty = $qtys[$index];
                    $qrCode = $qrcodes[$index];

                    // Mengurangi stok di toko_pengirim selain id_toko = 1
                    $detailTokoPengirim = DetailToko::where('id_toko', $pengiriman_barang->toko_pengirim)
                        ->where('id_barang', $id_barang)
                        ->where('qrcode', $qrCode)
                        ->first();

                    if ($detailTokoPengirim) {
                        $detailTokoPengirim->qty -= $qty;
                        $detailTokoPengirim->save();
                    }
                }
            }

            DB::table('temp_detail_pengiriman')
                ->where('id_pengiriman_barang', $id_pengiriman_barang)
                ->delete();

            $pengiriman_barang->update([
                'total_item' => $totalItem,
                'total_nilai' => $totalNilai,
                'status' => 'progress',
            ]);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Data Pengiriman Barang berhasil Ditambahkan.',
                'status_code' => 200,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => 'Failed to update pengiriman barang. ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $pengiriman = PengirimanBarang::findOrFail($id);

            if ($pengiriman->status === 'pending') {
                DB::table('temp_detail_pengiriman')->where('id_pengiriman_barang', $id)->delete();
            } elseif ($pengiriman->status === 'progress') {
                $details = DetailPengirimanBarang::where('id_pengiriman_barang', $id)->get();

                foreach ($details as $detail) {
                    $detailTokoPengirim = DetailToko::where('id_toko', $pengiriman->toko_pengirim)
                        ->where('id_barang', $detail->id_barang)
                        ->where(function ($query) use ($detail) {
                            if (!empty($detail->qrcode)) {
                                $query->where('qrcode', $detail->qrcode);
                            }
                        })
                        ->first();


                    if ($detailTokoPengirim) {
                        $detailTokoPengirim->qty += $detail->qty;
                        $detailTokoPengirim->save();
                    }
                }

                DB::table('detail_pengiriman_barang')->where('id_pengiriman_barang', $id)->delete();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Penghapusan hanya dapat dilakukan jika status adalah pending atau progress.'
                ], 400);
            }

            $pengiriman->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengiriman Barang berhasil Dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pengiriman Barang. ' . $e->getMessage()
            ]);
        }
    }

    public function returePengiriman()
    {

        abort_if(!Auth::check() || !in_array(Auth::user()->id_level, [1, 2, 3]), 403, 'Unauthorized');

        $menu = [$this->title[0], $this->label[1], $this->title[4]];
        $toko = Toko::all();
        $detail_toko = DetailToko::all();
        $stock = StockBarang::all();

        $myToko = $toko->where('id', Auth::user()->id_toko)->first();

        return view('transaksi.pengirimanbarang.reture', compact('menu', 'toko', 'stock', 'detail_toko', 'myToko'));
    }
}
