<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailKasir;
use App\Models\DetailPembelianBarang;
use App\Models\DetailPengirimanBarang;
use App\Models\DetailToko;
use App\Models\Kasir;
use App\Models\LevelHarga;
use App\Models\Member;
use App\Models\Promo;
use App\Models\StockBarang;
use App\Models\Toko;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\NotoSans;

class KasirController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Transaksi Kasir',
        ];
    }

    public function getkasirs(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Kasir::query();

        $query->with(['member', 'toko', 'users'])->orderBy('id', $meta['orderBy']);

        // Filter berdasarkan id_toko
        if ($request->has('id_toko')) {
            $idToko = $request->input('id_toko');
            if ($idToko != 1) {
                $query->where('id_toko', $idToko);
            } else {
                // Secara default, jangan tampilkan transaksi dengan id_toko = 1
                $query->where('id_toko', '!=', 1);
            }

            // Pencarian
            if (!empty($request['search'])) {
                $searchTerm = trim(strtolower($request['search']));

                $query->where(function ($query) use ($searchTerm) {
                    if ($searchTerm === 'guest') {
                        // Jika pencarian adalah "Guest", cari data dengan id_member = 0
                        $query->where('id_member', 0);
                    } else {
                        // Logika pencarian normal
                        $query->orWhereRaw("LOWER(no_nota) LIKE ?", ["%$searchTerm%"]);
                        $query->orWhereRaw("LOWER(metode) LIKE ?", ["%$searchTerm%"]);

                        $query->orWhereHas('member', function ($subquery) use ($searchTerm) {
                            $subquery->whereRaw("LOWER(nama_member) LIKE ?", ["%$searchTerm%"]);
                        });
                        $query->orWhereHas('toko', function ($subquery) use ($searchTerm) {
                            $subquery->whereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                        });
                        $query->orWhereHas('users', function ($subquery) use ($searchTerm) {
                            $subquery->whereRaw("LOWER(nama) LIKE ?", ["%$searchTerm%"]);
                        });
                    }
                });
            }

            // Filter berdasarkan tanggal
            if ($request->has('startDate') && $request->has('endDate')) {
                $startDate = $request->input('startDate');
                $endDate = $request->input('endDate');

                $query->whereBetween('tgl_transaksi', [$startDate, $endDate]);
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
                    'nama_member' => $item['member']->nama_member ?? "Guest",
                    'status' => match ($item->status) {
                        'success' => 'Sukses',
                        'failed' => 'Gagal',
                        default => $item->status,
                    },
                    'nama_toko' => $item['toko']->nama_toko ?? null,
                    'nama' => $item['users']->nama ?? null,
                    'tgl_transaksi' => \Carbon\Carbon::parse($item->tgl_transaksi)->format('d-m-Y'),
                    'no_nota' => $item->no_nota,
                    'total_item' => $item->total_item,
                    'metode' => $item->metode,
                    'total_nilai' => 'Rp. ' . number_format($item->total_nilai - $item->total_diskon, 0, '.', '.'),
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
    }

    public function cetakStruk($id_kasir)
    {
        $kasir = Kasir::with('toko', 'member', 'users')->findOrFail($id_kasir); // Pastikan relasi 'toko', 'member', dan 'users' termuat
        $detail_kasir = DetailKasir::where('id_kasir', $id_kasir)->get(); // Hanya ambil detail kasir yang sesuai

        return view('transaksi.kasir.cetak_struk', compact('kasir', 'detail_kasir'));
    }

    public function index(Request $request)
    {
        if (!in_array(Auth::user()->id_level, [1, 2, 3])) {
            abort(403, 'Unauthorized');
        }
        $menu = [$this->title[0], $this->label[1]];
        $user = Auth::user();
        $users = User::all();
        $detail_kasir = DetailKasir::all();
        $toko = Toko::all();

        // Mengambil data berdasarkan level user
        if ($user->id_level == 1) {
            $kasirQuery = Kasir::orderBy('id', 'desc');
        } else {
            $kasirQuery = Kasir::where('id_toko', $user->id_toko)
                ->orderBy('id', 'desc');
        }

        // Filter berdasarkan tgl_transaksi
        if ($request->has(['start_date', 'end_date'])) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $kasirQuery->whereBetween('tgl_transaksi', [$startDate, $endDate]);
        }

        $kasir = $kasirQuery->get();

        // Ambil data barang dan member berdasarkan level user
        if ($user->id_level == 1) {
            $barang = StockBarang::all();
            $member = Member::all();
        } else {
            $barang = DetailToko::where('id_toko', $user->id_toko)->get();
            $member = Member::where('id_toko', $user->id_toko)->get();
        }

        return view('transaksi.kasir.index', compact('menu', 'barang', 'kasir', 'member', 'detail_kasir', 'users', 'toko'));
    }

    //     public function getFilteredHarga(Request $request)
    // {
    //     $memberId = $request->input('id_member');
    //     $barangId = $request->input('id_barang');

    //     // Ambil data barang
    //     $barang = Barang::find($barangId);
    //     if (!$barang) {
    //         return response()->json(['error' => 'Barang tidak ditemukan.'], 404);
    //     }

    //     // Parsing level harga barang jika dalam bentuk JSON string
    //     $levelHarga = is_string($barang->level_harga) ? json_decode($barang->level_harga, true) : $barang->level_harga;

    //     // Cek apakah member adalah Guest
    //     if ($memberId === 'Guest') {
    //         // Urutkan semua level harga barang dari tertinggi ke terendah
    //         $filteredHarga = collect($levelHarga)
    //             ->sortByDesc(function ($harga) {
    //                 // Ekstrak nilai harga dari string untuk pengurutan numerik
    //                 return (int)explode(' : ', $harga)[1];
    //             })
    //             ->values()
    //             ->map(function ($harga) {
    //                 return intval(explode(' : ', $harga)[1]); // Hanya ambil nilai harga
    //             });

    //         return response()->json(['filteredHarga' => $filteredHarga]);
    //     }

    //     // Lanjutkan dengan logika normal jika bukan Guest
    //     $member = Member::find($memberId);
    //     if (!$member) {
    //         return response()->json(['error' => 'Member tidak ditemukan.'], 404);
    //     }

    //     // Parsing level_info jika dalam bentuk JSON string
    //     $levelInfo = is_string($member->level_info) ? json_decode($member->level_info, true) : $member->level_info;
    //     $jenisBarangId = $barang->id_jenis_barang;

    //     // Ambil ID level yang cocok dengan jenis barang dari level_info
    //     $levelIds = collect($levelInfo)->map(function ($info) use ($jenisBarangId) {
    //         list($infoJenisBarangId, $infoLevelId) = explode(' : ', $info);
    //         return intval($infoJenisBarangId) === intval($jenisBarangId) ? intval($infoLevelId) : null;
    //     })->filter();

    //     // Ambil nama level harga yang sesuai dari tabel LevelHarga
    //     $levelNames = LevelHarga::whereIn('id', $levelIds)->pluck('nama_level_harga');

    //     // Filter level harga barang sesuai dengan levelNames
    //     $filteredHarga = collect($levelHarga)->filter(function ($harga) use ($levelNames) {
    //         return $levelNames->contains(function ($levelName) use ($harga) {
    //             return str_contains($harga, $levelName);
    //         });
    //     })->map(function ($harga) {
    //         return intval(explode(' : ', $harga)[1]); // Ambil hanya angka dari harga
    //     })->values();

    //     Log::info('Filtered Harga:', ['filteredHarga' => $filteredHarga->toArray()]);

    //     // Mengatur respons untuk mengembalikan angka jika hanya satu elemen
    //     $response = count($filteredHarga) === 1 ? $filteredHarga->first() : $filteredHarga;

    //     return response()->json(['filteredHarga' => $response]);
    // }

    public function getFilteredHarga(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|string', // QR Code/id_detail dari frontend
            'id_member' => 'required|string',
        ]);

        $id_barang_input = $request->input('id_barang'); // Contoh: "10022025SP2ID6-1/"
        $memberId = $request->input('id_member');

        // Pastikan format id_barang benar (harus mengandung "/")
        if (!str_contains($id_barang_input, '/')) {
            return response()->json(['error' => 'Format id_barang tidak valid. Gunakan format qrcode/id_detail.'], 400);
        }

        // Pisahkan QR Code dan id_detail
        list($qrCode, $id_detail) = explode('/', $id_barang_input) + [null, null];

        try {
            // 1. Cari barang berdasarkan QR Code di tabel DetailPembelianBarang
            $barangDetail = DetailPembelianBarang::where('qrcode', $qrCode)->first();

            if (!$barangDetail) {
                return response()->json(['error' => 'Barang tidak ditemukan berdasarkan QR Code.'], 404);
            }

            $barangId = $barangDetail->id_barang;

            // 2. Cari barang di tabel Barang berdasarkan id_barang
            $barang = Barang::find($barangId);
            if (!$barang) {
                return response()->json(['error' => 'Barang tidak ditemukan.'], 404);
            }

            // 3. Parsing level harga barang (format JSON jika string)
            $levelHarga = is_string($barang->level_harga) ? json_decode($barang->level_harga, true) : $barang->level_harga;

            // 4. Jika member adalah "Guest", tampilkan semua harga yang tersedia
            if ($memberId === 'Guest') {
                $filteredHarga = collect($levelHarga)
                    ->sortByDesc(fn($harga) => (int)explode(' : ', $harga)[1]) // Urutkan harga dari tertinggi
                    ->values()
                    ->map(fn($harga) => intval(explode(' : ', $harga)[1])); // Ambil hanya angka harga

                return response()->json([
                    'filteredHarga' => $filteredHarga,
                    'id_barang' => $barangId,
                    'nama_barang' => $barang->nama_barang
                ]);
            }

            // 5. Jika member bukan Guest, cari informasi level harga berdasarkan tabel Member
            $member = Member::find($memberId);
            if (!$member) {
                return response()->json(['error' => 'Member tidak ditemukan.'], 404);
            }

            // 6. Parsing level_info dari tabel Member (format JSON jika string)
            $levelInfo = is_string($member->level_info) ? json_decode($member->level_info, true) : $member->level_info;
            $jenisBarangId = $barang->id_jenis_barang;

            // 7. Ambil ID level yang cocok dengan jenis barang dari level_info
            $levelIds = collect($levelInfo)->map(function ($info) use ($jenisBarangId) {
                list($infoJenisBarangId, $infoLevelId) = explode(' : ', $info);
                return intval($infoJenisBarangId) === intval($jenisBarangId) ? intval($infoLevelId) : null;
            })->filter();

            // 8. Ambil nama level harga yang sesuai dari tabel LevelHarga
            $levelNames = LevelHarga::whereIn('id', $levelIds)->pluck('nama_level_harga');

            // 9. Filter level harga barang sesuai dengan levelNames
            $filteredHarga = collect($levelHarga)->filter(function ($harga) use ($levelNames) {
                return $levelNames->contains(fn($levelName) => str_contains($harga, $levelName));
            })->map(fn($harga) => intval(explode(' : ', $harga)[1]))->values();

            Log::info('Filtered Harga:', ['filteredHarga' => $filteredHarga->toArray()]);

            // 10. Jika hanya ada satu harga, kembalikan dalam bentuk angka, jika lebih dari satu, kembalikan array
            $response = count($filteredHarga) === 1 ? $filteredHarga->first() : $filteredHarga;

            return response()->json([
                'filteredHarga' => $response,
                'id_barang' => $barangId,
                'nama_barang' => $barang->nama_barang
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching filtered harga by QR Code: ' . $e->getMessage());

            return response()->json([
                'error' => 'Terjadi kesalahan pada server: ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Ambil data dari request
            $idBarangs = $request->input('id_barang', []);
            $qtys = $request->input('qty', []);
            $hargaBarangs = $request->input('harga', []);

            // Bersihkan elemen kosong dari array
            $idBarangs = array_values(array_filter($idBarangs, function ($value) {
                return $value !== null && $value !== '';
            }));
            $qtys = array_values(array_filter($qtys, function ($value) {
                return $value !== null && $value !== '';
            }));
            $hargaBarangs = array_values(array_filter($hargaBarangs, function ($value) {
                return $value !== null && $value !== '';
            }));

            // Sinkronisasi array berdasarkan jumlah elemen
            $maxCount = max(count($idBarangs), count($qtys), count($hargaBarangs));
            $idBarangs = $this->fillArrayToMatchCount($idBarangs, $maxCount);
            $qtys = $this->fillArrayToMatchCount($qtys, $maxCount);
            $hargaBarangs = $this->fillArrayToMatchCount($hargaBarangs, $maxCount);

            // Validasi kesesuaian jumlah elemen setelah sinkronisasi
            if (count($idBarangs) !== count($qtys) || count($idBarangs) !== count($hargaBarangs)) {
                return redirect()->back()->with('error', 'Data tidak sinkron. Silakan periksa kembali input Anda.');
            }

            $user = Auth::user();
            $tglTransaksi = now();

            // Inisialisasi transaksi kasir
            $kasir = new Kasir();
            $kasir->id_member = $request->id_member == 'Guest' ? 0 : $request->id_member;
            $kasir->id_users = $user->id;
            $kasir->tgl_transaksi = $tglTransaksi;
            $kasir->id_toko = $user->id_toko;
            $kasir->total_item = 0;
            $kasir->total_nilai = 0;
            $kasir->no_nota = $request->no_nota;
            $kasir->metode = $request->metode;
            $kasir->jml_bayar = (float)$request->jml_bayar;
            $kasir->kembalian = (float)$request->kembalian;
            $kasir->save();

            $totalItem = 0;
            $totalNilai = 0;
            $totalDiskon = 0;
            $counter = 1;

            foreach ($idBarangs as $index => $id_barang) {
                $qty = isset($qtys[$index]) ? (float)$qtys[$index] : null;
                $harga_barang = isset($hargaBarangs[$index]) ? (float)$hargaBarangs[$index] : null;

                if (is_null($qty) || is_null($harga_barang)) {
                    continue;
                }

                // Ambil id_detail_pembelian berdasarkan qrcode
                $detailPembelian = DetailPembelianBarang::where('qrcode', 'LIKE', "%{$id_barang}%")->first();
                $id_detail_pembelian = $detailPembelian ? $detailPembelian->id : null;

                // Ambil id_supplier dari detail_toko berdasarkan id_barang dan id_toko
                $detailToko = DetailToko::where('id_barang', $id_barang)
                    ->where('id_toko', $user->id_toko)
                    ->first();
                $id_supplier = $detailToko ? $detailToko->id_supplier : null;

                if (is_null($id_supplier)) {
                    return redirect()->back()->with('error', "Supplier tidak ditemukan untuk barang ID: $id_barang.");
                }

                // Cek promo yang berlaku
                $promo = Promo::where('id_barang', $id_barang)
                    ->where('status', 'ongoing')
                    ->where('dari', '<=', $tglTransaksi)
                    ->where('sampai', '>=', $tglTransaksi)
                    ->where('id_toko', $user->id_toko)
                    ->first();

                $potongan = 0;
                if ($promo && $qty >= $promo->minimal) {
                    $diskon = $promo->diskon;
                    $qtyDiskon = $promo->jumlah ? min($qty, $promo->jumlah - $promo->terjual) : $qty;
                    $potongan = ($harga_barang * $diskon / 100) * $qtyDiskon;
                    $totalDiskon += $potongan;

                    if ($promo->jumlah) {
                        $promo->terjual += $qtyDiskon;
                        if ($promo->terjual >= $promo->jumlah) {
                            $promo->status = 'done';
                        }
                    } else {
                        $promo->terjual += $qty;
                    }
                    $promo->save();
                }

                // Generate QR Code
                $tglTransaksiFormat = $tglTransaksi->format('dmY');
                $qrCodeValue = "{$tglTransaksiFormat}TK{$user->id_toko}MM{$kasir->id_member}ID{$kasir->id}-{$counter}";
                $qrCodePath = "qrcodes/trx_kasir/{$kasir->id}-{$counter}.png";
                $fullPath = storage_path('app/public/' . $qrCodePath);

                if (!file_exists(dirname($fullPath))) {
                    mkdir(dirname($fullPath), 0755, true);
                }

                $qrCode = QrCode::create($qrCodeValue)
                    ->setEncoding(new Encoding('UTF-8'))
                    ->setSize(200)
                    ->setMargin(10);
                $writer = new PngWriter();
                $result = $writer->write($qrCode, null, Label::create("{$qrCodeValue}")->setFont(new NotoSans(12)));
                $result->saveToFile($fullPath);

                // Ambil hpp_baru dari tabel stock_barang
                $stock = StockBarang::where('id_barang', $id_barang)->first();
                $hpp_jual = $stock ? $stock->hpp_baru : 0;

                // Simpan detail kasir
                DetailKasir::create([
                    'id_kasir' => $kasir->id,
                    'id_barang' => $id_barang,
                    'id_supplier' => $id_supplier,
                    'id_detail_pembelian' => $id_detail_pembelian, // Tambahkan kolom ini
                    'qty' => $qty,
                    'harga' => $harga_barang,
                    'diskon' => $potongan,
                    'total_harga' => $qty * $harga_barang,
                    'qrcode' => $qrCodeValue,
                    'qrcode_path' => $qrCodePath,
                    'hpp_jual' => $hpp_jual,
                ]);

                // Update stok
                if ($user->id_toko == 1) {
                    $stock?->decrement('stock', $qty);
                } else {
                    $detailToko?->decrement('qty', $qty);
                }

                $totalItem += $qty;
                $totalNilai += $qty * $harga_barang;
                $counter++;
            }

            // Update total transaksi di kasir
            $kasir->update([
                'total_item' => $totalItem,
                'total_nilai' => $totalNilai,
                'total_diskon' => $totalDiskon,
                'kembalian' => $kasir->jml_bayar - ($totalNilai - $totalDiskon),
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => $kasir
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Error saat menyimpan transaksi:', ['error' => $th->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save transaction.',
                'error' => $th->getMessage()
            ], 500);

        }
    }


    // Fungsi untuk mengisi array agar memiliki jumlah elemen yang sama
    private function fillArrayToMatchCount(array $array, int $count)
    {
        while (count($array) < $count) {
            // Tambahkan elemen terakhir jika array lebih pendek
            $array[] = end($array) ?: 0;
        }
        return $array;
    }
}
