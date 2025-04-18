<?php

namespace App\Services;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pengeluaran;
use App\Models\Kasir;
use App\Models\PembelianBarang;
use App\Models\Pemasukan;
use App\Models\DetailPemasukan;
use App\Models\Kasbon;
use App\Models\Mutasi;
use App\Models\Toko;

class ArusKasService
{
    public function getArusKasData(Request $request)
    {
        // dd($request->all());
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        // Get month and year from request if provided, otherwise use current month and year
        $month = $request->has('month') ? $request->month : Carbon::now()->month;
        $year = $request->has('year') ? $request->year : Carbon::now()->year;

        // Get data from Pengeluaran model with its details
        $pengeluaranQuery = Pengeluaran::with(['toko', 'jenis_pengeluaran', 'detail_pengeluaran'])
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year);

        // Filter by id_toko if provided
        if ($request->has('id_toko') && is_array($request->id_toko)) {
            $pengeluaranQuery->whereIn('id_toko', $request->id_toko);
        }

        $pengeluaranQuery->orderBy('id', $meta['orderBy']);

        // Get data from Kasir model
        $kasirQuery = Kasir::with('toko', 'users')
            ->whereMonth('tgl_transaksi', $month)
            ->whereYear('tgl_transaksi', $year);

        if ($request->has('id_toko') && is_array($request->id_toko)) {
            $kasirQuery->whereIn('id_toko', $request->id_toko);
        }

        $kasirQuery->orderBy('id', $meta['orderBy']);

        // Get data from PembelianBarang model
        $pembelianQuery = PembelianBarang::with('supplier')
            ->whereMonth('tgl_nota', $month)
            ->whereYear('tgl_nota', $year)
            ->orderBy('id', $meta['orderBy']);

        // Get data from Pemasukan model
        $pemasukanQuery = Pemasukan::with('jenis_pemasukan')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year);

        if ($request->has('id_toko') && is_array($request->id_toko)) {
            $pemasukanQuery->whereIn('id_toko', $request->id_toko);
        }

        $pemasukanQuery->orderBy('id', $meta['orderBy']);

        // Get data from Mutasi model
        $mutasiQuery = Mutasi::with(['toko', 'tokoPengirim'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        if ($request->has('id_toko') && is_array($request->id_toko)) {
            $mutasiQuery->where(function ($query) use ($request) {
                $query->whereIn('id_toko_penerima', $request->id_toko)
                    ->orWhereIn('id_toko_pengirim', $request->id_toko);
            });
        }

        $mutasiQuery->orderBy('id', $meta['orderBy']);

        // Get data from Kasbon model
        $kasbonQuery = Kasbon::with('detailKasbon', 'kasir.toko')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        if ($request->has('id_toko') && is_array($request->id_toko)) {
            $kasbonQuery->whereHas('kasir', function ($query) use ($request) {
                $query->whereIn('id_toko', $request->id_toko);
            });
        }

        $kasbonQuery->orderBy('id', $meta['orderBy']);

        // Get filtered data
        $pengeluaranList = $pengeluaranQuery->get();
        $kasirList = $kasirQuery->get();
        $pembelianList = $pembelianQuery->get();
        $pemasukanList = $pemasukanQuery->get();
        $mutasiList = $mutasiQuery->get();
        $kasbonList = $kasbonQuery->get();

        if ($pengeluaranList->isEmpty() && $kasirList->isEmpty() && $pembelianList->isEmpty() && $pemasukanList->isEmpty()) {
            return response()->json([
                'status_code' => 404,
                'errors' => true,
                'message' => 'Data tidak ditemukan',
                'data' => [],
                'data_total' => null,
            ], 404);
        }

        // Format pengeluaran data without grouping
        $pengeluaranData = $pengeluaranList->map(function ($pengeluaran) {
            $rows = [];

            // Baris utama (piutang_in jika ada)
            $mainRow = [
                'id' => $pengeluaran->id,
                'tgl' => Carbon::parse($pengeluaran->tanggal)->format('d-m-Y H:i:s'),
                'subjek' => "Toko {$pengeluaran->toko->singkatan}",
                'kategori' => 'Pengeluaran ' . ($pengeluaran->jenis_pengeluaran ? $pengeluaran->jenis_pengeluaran->nama_jenis : ($pengeluaran->ket_hutang ?? 'Tidak Terkategori')),
                'item' => $pengeluaran->nama_pengeluaran,
                'jml' => 1,
                'sat' => "Ls",
                'hst' => (int)$pengeluaran->nilai,
                'nilai_transaksi' => (int)$pengeluaran->nilai,
                'kas_kecil_in' => 0,
                'kas_kecil_out' => $pengeluaran->is_hutang ? 0 : ($pengeluaran->toko->id != 1 ? (int)$pengeluaran->nilai : 0),
                'kas_besar_in' => 0,
                'kas_besar_out' => $pengeluaran->is_hutang ? 0 : ($pengeluaran->toko->id == 1 ? (int)$pengeluaran->nilai : 0),
                'piutang_out' => 0,
                'piutang_in' => 0,
                'hutang_in' => $pengeluaran->is_hutang ? (int)$pengeluaran->nilai : 0,
                'hutang_out' => 0,
                'urutan' => 1, // Changed from 0 to 1 to appear after piutang_out
            ];
            $rows[] = $mainRow;

            // Detail pembayaran (hutang_out), urutan dimulai dari 1 ke atas
            if ($pengeluaran->detail_pengeluaran->isNotEmpty()) {
                $detailPengeluaran = $pengeluaran->detail_pengeluaran
                    ->sortBy('created_at'); // pastikan terurut tanggalnya

                foreach ($detailPengeluaran as $detail) {
                    $rows[] = [
                        'id' => $pengeluaran->id,
                        'tgl' => Carbon::parse($detail->created_at)->format('d-m-Y H:i:s'),
                        'subjek' => "Toko {$pengeluaran->toko->singkatan}",
                        'kategori' => 'Pembayaran Hutang',
                        'item' => 'Pembayaran ' . $pengeluaran->nama_pengeluaran,
                        'jml' => 1,
                        'sat' => "Ls",
                        'hst' => (int)$detail->nilai,
                        'nilai_transaksi' => (int)$detail->nilai,
                        'kas_kecil_in' => 0,
                        'kas_kecil_out' => $pengeluaran->toko->id != 1 ? (int)$detail->nilai : 0,
                        'kas_besar_in' => 0,
                        'kas_besar_out' => $pengeluaran->toko->id == 1 ? (int)$detail->nilai : 0,
                        'piutang_out' => 0,
                        'piutang_in' => 0,
                        'hutang_in' => 0,
                        'hutang_out' => (int)$detail->nilai,
                        'urutan' => 0, // Changed to 0 to appear before piutang_in
                    ];
                }
            }

            // Urutkan berdasarkan kolom 'urutan' agar piutang_in duluan
            return collect($rows)->sortBy('urutan')->values();
        })->flatten(1)->values();

        // Format pembelian data
        $pembeliansup = Toko::where('id', 1)->first();
        $idTokoRequest = request()->input('id_toko');

        $pembelianData = $pembelianList->map(function ($pembelian) use ($pembeliansup, $idTokoRequest) {
            if (is_array($idTokoRequest) && !in_array(1, $idTokoRequest)) {
                return null;
            }

            return [
                'id' => $pembelian->id,
                'tgl' => Carbon::parse($pembelian->tgl_nota)->format('d-m-Y H:i:s'),
                'subjek' => 'Toko ' . ($pembeliansup ? $pembeliansup->nama_toko : 'Tidak Diketahui'),
                'kategori' => 'Transaksi Supplier',
                'item' => 'Pembelian Barang di ' . ($pembelian->supplier ? $pembelian->supplier->nama_supplier : 'Supplier Tidak Diketahui'),
                'jml' => 1,
                'sat' => 'Ls',
                'hst' => (int)$pembelian->total_nilai,
                'nilai_transaksi' => (int)$pembelian->total_nilai,
                'kas_kecil_in' => 0,
                'kas_kecil_out' => 0,
                'kas_besar_in' => 0,
                'kas_besar_out' => (int)$pembelian->total_nilai,
                'piutang_in' => 0,
                'piutang_out' => 0,
                'hutang_in' => 0,
                'hutang_out' => 0,
            ];
        })->filter();

        // Format kasir data
        $kasirData = $kasirList
            ->groupBy(function ($kasir) {
                return $kasir->toko->singkatan . '_' . Carbon::parse($kasir->created_at)->format('d-m-Y H:i:s');
            })
            ->map(function ($groupedKasir) {
                $firstKasir = $groupedKasir->first();
                return [
                    'id' => $firstKasir->id,
                    'tgl' => Carbon::parse($firstKasir->created_at)->format('d-m-Y H:i:s'),
                    'subjek' => "Toko {$firstKasir->toko->singkatan}",
                    'kategori' => "Pendapatan Umum",
                    'item' => "Pendapatan Harian",
                    'jml' => 1,
                    'sat' => "Ls",
                    'hst' => (int)$groupedKasir->sum('total_nilai'),
                    'nilai_transaksi' => (int)$groupedKasir->sum('total_nilai'),
                    'kas_kecil_in' => (int)$groupedKasir->sum('total_nilai'),
                    'kas_kecil_out' => 0,
                    'kas_besar_in' => 0,
                    'kas_besar_out' => 0,
                    'piutang_in' => 0,
                    'piutang_out' => $groupedKasir->sum(function ($kasir) {
                        return $kasir->detail_pengeluaran ? (int)$kasir->detail_pengeluaran->sum('nilai') : 0;
                    }),
                    'hutang_in' => 0,
                    'hutang_out' => 0,
                ];
            })->values();

        $kasbonData = $kasbonList->flatMap(function ($kasbon) {
            // Entry utama dari kasbon
            $kasbonEntry = [
                'id' => $kasbon->id,
                'tgl' => Carbon::parse($kasbon->created_at)->format('d-m-Y H:i:s'),
                'subjek' => "Toko {$kasbon->kasir->toko->nama_toko}",
                'kategori' => "Piutang Member",
                'item' => "Kasbon {$kasbon->member->nama_member}",
                'jml' => 1,
                'sat' => "Ls",
                'hst' => (int)$kasbon->utang,
                'nilai_transaksi' => (int)$kasbon->utang,
                'kas_kecil_in' => 0,
                'kas_kecil_out' => 0,
                'kas_besar_in' => 0,
                'kas_besar_out' => 0,
                'piutang_in' => (int)$kasbon->utang,
                'piutang_out' => 0,
                'hutang_in' => 0,
                'hutang_out' => 0,
            ];
        
            // Detail kasbon sebagai array tambahan
            $detailEntries = $kasbon->detailKasbon->map(function ($detail) use ($kasbon) {
                return [
                    'id' => $detail->id,
                    'tgl' => Carbon::parse($detail->created_at)->format('d-m-Y H:i:s'),
                    'subjek' => "Toko {$kasbon->kasir->toko->nama_toko}",
                    'kategori' => "Piutang Member",
                    'item' => "Kasbon {$kasbon->member->nama_member}",
                    'jml' => 1,
                    'sat' => "Ls",
                    'hst' => (int)$detail->bayar,
                    'nilai_transaksi' => (int)$detail->bayar,
                    'kas_kecil_in' => (int)$detail->bayar,
                    'kas_kecil_out' => 0,
                    'kas_besar_in' => 0,
                    'kas_besar_out' => 0,
                    'piutang_in' => 0,
                    'piutang_out' => (int)$detail->bayar,
                    'hutang_in' => 0,
                    'hutang_out' => 0,
                ];
            });            
        
            // Gabungkan kasbon + detail menjadi satu array
            return collect([$kasbonEntry])->merge($detailEntries);
        })->values();
            
        // Format pemasukan data
        $pemasukanData = $pemasukanList->map(function ($pemasukan) {
            $rows = [];

            // Main row for pemasukan (hutang_in)
            $rows[] = [
                'id' => $pemasukan->id,
                'tgl' => Carbon::parse($pemasukan->tanggal)->format('d-m-Y H:i:s'),
                'subjek' => "Toko {$pemasukan->toko->singkatan}",
                'kategori' => 'Pemasukan',
                'item' => $pemasukan->nama_pemasukan,
                'jml' => 1,
                'sat' => 'Ls',
                'hst' => (int)$pemasukan->nilai,
                'nilai_transaksi' => (int)$pemasukan->nilai,
                'kas_kecil_in' => $pemasukan->id_toko == 1 ? 0 : (int)$pemasukan->nilai,
                'kas_kecil_out' => 0,
                'kas_besar_in' => $pemasukan->id_toko == 1 ? (int)$pemasukan->nilai : 0,
                'kas_besar_out' => 0,
                'piutang_in' => 0,
                'piutang_out' => 0,
                'hutang_in' => $pemasukan->is_pinjam ? (int)$pemasukan->nilai : 0,
                'hutang_out' => 0,
            ];

            // Add separate row for hutang_out if it exists
            if ($pemasukan->is_pinjam) {
                $detailPemasukan = DetailPemasukan::where('id_pemasukan', $pemasukan->id)
                    ->get()
                    ->groupBy(function ($detail) {
                        return Carbon::parse($detail->created_at)->format('Y-m-d H:i:s');
                    });

                foreach ($detailPemasukan as $date => $details) {
                    $totalNilai = $details->sum('nilai');
                    $rows[] = [
                        'id' => $pemasukan->id,
                        'tgl' => Carbon::parse($date)->format('d-m-Y H:i:s'),
                        'subjek' => "Toko {$pemasukan->toko->singkatan}",
                        'kategori' => 'Pembayaran Hutang',
                        'item' => 'Pembayaran ' . $pemasukan->nama_pemasukan,
                        'jml' => 1,
                        'sat' => 'Ls',
                        'hst' => (int)$totalNilai,
                        'nilai_transaksi' => (int)$totalNilai,
                        'kas_kecil_in' => 0,
                        'kas_kecil_out' => $pemasukan->id_toko == 1 ? 0 : (int)$totalNilai,
                        'kas_besar_in' => 0,
                        'kas_besar_out' => $pemasukan->id_toko == 1 ? (int)$totalNilai : 0,
                        'piutang_out' => 0,
                        'piutang_in' => 0,
                        'hutang_in' => 0,
                        'hutang_out' => (int)$totalNilai,
                    ];
                }
            }

            return $rows;
        })->flatten(1);

        // Format mutasi data
        $mutasiData = $mutasiList->flatMap(function ($mutasi) {
            $date = Carbon::parse($mutasi->created_at)->format('d-m-Y H:i:s');
            $rows = [];

            // Get toko names with null checks
            $pengirimName = $mutasi->tokoPengirim ? "Toko {$mutasi->tokoPengirim->singkatan}" : 'Toko Tidak Diketahui';
            $penerimaName = $mutasi->tokoPenerima ? "Toko {$mutasi->tokoPenerima->singkatan}" : 'Toko Tidak Diketahui';

            // Sender's row (outgoing transaction)
            $rows[] = [
                'id' => $mutasi->id,
                'tgl' => $date,
                'subjek' => $pengirimName,
                'kategori' => 'Mutasi Keluar',
                'item' => 'Mutasi Kas Keluar',
                'jml' => 1,
                'sat' => 'Ls',
                'hst' => (int)$mutasi->nilai,
                'nilai_transaksi' => (int)$mutasi->nilai,
                'kas_kecil_in' => 0,
                'kas_kecil_out' => $mutasi->id_toko_pengirim != 1 ? (int)$mutasi->nilai : 0,
                'kas_besar_in' => 0,
                'kas_besar_out' => $mutasi->id_toko_pengirim == 1 ? (int)$mutasi->nilai : 0,
                'piutang_out' => 0,
                'piutang_in' => 0,
                'hutang_in' => 0,
                'hutang_out' => 0,
            ];

            // Receiver's row (incoming transaction)
            $rows[] = [
                'id' => $mutasi->id,
                'tgl' => $date,
                'subjek' => $penerimaName,
                'kategori' => 'Mutasi Masuk',
                'item' => 'Mutasi Kas Masuk',
                'jml' => 1,
                'sat' => 'Ls',
                'hst' => (int)$mutasi->nilai,
                'nilai_transaksi' => (int)$mutasi->nilai,
                'kas_kecil_in' => $mutasi->id_toko_pengirim == 1 ? (int)$mutasi->nilai : 0,
                'kas_kecil_out' => 0,
                'kas_besar_in' => $mutasi->id_toko == 1 ? (int)$mutasi->nilai : 0,
                'kas_besar_out' => 0,
                'piutang_in' => 0,
                'piutang_out' => 0,
                'hutang_in' => 0,
                'hutang_out' => 0,
            ];

            return $rows;
        });

        // Merge and sort data
        $data = $pengeluaranData
        ->concat($kasirData)
        ->concat($pembelianData)
        ->concat($pemasukanData)
        ->concat($mutasiData)
        ->concat($kasbonData)
        ->sortByDesc('tgl')->values();

        $totalBulanLalu = $this->calculateBulanLalu($year, $month);
        $KB_saldoAwal = $totalBulanLalu['kas_besar']['saldo_awal'];

        // dd($KB_saldoAwal);
        
        // Calculate totals
        $kas_kecil_in = $data->sum('kas_kecil_in');
        $kas_kecil_out = $data->sum('kas_kecil_out');
        $saldo_berjalan = $kas_kecil_in - $kas_kecil_out;
        $saldo_awal = 0;
        $saldo_akhir = $saldo_berjalan - $saldo_awal;

        $kas_besar_in = $data->sum('kas_besar_in');
        $kas_besar_out = $data->sum('kas_besar_out');
        $kas_besar_saldo_berjalan = $kas_besar_in - $kas_besar_out;
        $kas_besar_saldo_awal = $KB_saldoAwal;
        $kas_besar_saldo_akhir = abs($kas_besar_saldo_berjalan - $kas_besar_saldo_awal);

        $piutang_out = $data->sum('piutang_out');
        $piutang_in = $data->sum('piutang_in');
        $piutang_saldo_berjalan = $piutang_in - $piutang_out;
        $piutang_saldo_awal = 0;
        $piutang_saldo_akhir = $piutang_saldo_berjalan - $piutang_saldo_awal;

        $hutang_in = $data->sum('hutang_in');
        $hutang_out = $data->sum('hutang_out');
        $hutang_saldo_berjalan = $hutang_in - $hutang_out;
        $hutang_saldo_awal = 0;
        $hutang_saldo_akhir = $hutang_saldo_berjalan - $hutang_saldo_awal;

        $asetPeralatanBesar = $pengeluaranList->where('is_asset', 'Asset Peralatan Besar')->sum('nilai');
        $asetPeralatanKecil = $pengeluaranList->where('is_asset', 'Asset Peralatan Kecil')->sum('nilai');

        $modal = $pemasukanList->where('id_jenis_pemasukan', 1)->sum('nilai');
        $modalLainnya = $pemasukanList->where('id_jenis_pemasukan', 2)->sum('nilai');

        $total_modal = $modal + $modalLainnya;

        $hutangPendek = $pemasukanList->where('is_pinjam', 1);
        $hutangPanjang = $pemasukanList->where('is_pinjam', 2);

        // Mapping data hutang menjadi format item
        $hutangPendekItems = $hutangPendek->map(function ($item, $index) {
            return [
                "kode" => "III.1." . ($index + 1),
                "nama" => $item->nama_pemasukan,
                "nilai" => $item->nilai,
            ];
        })->toArray();

        $hutangPanjangItems = $hutangPanjang->map(function ($item, $index) {
            return [
                "kode" => "III.2." . ($index + 1),
                "nama" => $item->nama_pemasukan,
                "nilai" => $item->nilai,
            ];
        })->toArray();

        $data_total = [
            'kas_kecil' => [
                'saldo_awal' => $saldo_awal,
                'saldo_akhir' => $saldo_akhir,
                'saldo_berjalan' => $saldo_berjalan,
                'kas_kecil_in' => $kas_kecil_in,
                'kas_kecil_out' => $kas_kecil_out,
            ],
            'kas_besar' => [
                'saldo_awal' => $kas_besar_saldo_awal,
                'saldo_akhir' => $kas_besar_saldo_akhir,
                'saldo_berjalan' => $kas_besar_saldo_berjalan,
                'kas_besar_in' => $kas_besar_in,
                'kas_besar_out' => $kas_besar_out,
            ],
            'piutang' => [
                'saldo_awal' => $piutang_saldo_awal,
                'saldo_akhir' => $piutang_saldo_akhir,
                'saldo_berjalan' => $piutang_saldo_berjalan,
                'piutang_in' => $piutang_in,
                'piutang_out' => $piutang_out,
            ],
            'hutang' => [
                'saldo_awal' => $hutang_saldo_awal,
                'saldo_akhir' => $hutang_saldo_akhir,
                'saldo_berjalan' => $hutang_saldo_berjalan,
                'hutang_in' => $hutang_in,
                'hutang_out' => $hutang_out,
            ],
            'aset_besar' => [
                'aset_peralatan_besar' => $asetPeralatanBesar,
            ],
            'aset_kecil' => [
                'aset_peralatan_kecil' => $asetPeralatanKecil,
            ],
            'modal' => [
                'total_modal' => $modal,
            ]
        ];

        return [
            'data' => $data,
            'data_total' => $data_total,
            'hutang' => [
                'pendek' => $hutangPendekItems,
                'panjang' => $hutangPanjangItems,
            ],
        ];
    }

    protected function calculateBulanLalu($year, $month)
    {
        // Hitung bulan dan tahun sebelumnya
        $prevMonth = $month - 1;
        $prevYear = $year;
    
        if ($month == 1) {
            $prevMonth = 12;
            $prevYear = $year;
        }
    
        // Buat request baru untuk data bulan sebelumnya
        $newRequest = new Request([
            'year' => $prevYear,
            'month' => $prevMonth,
            'page' => 1,
            'limit' => 10,
            'ascending' => 0,
            'search' => "",
        ]);
    
        // Ambil data bulan sebelumnya
        $dataBulanSebelumnyaResponse = $this->getArusKasData($newRequest);
    
        // Pastikan respons adalah JSON dan ubah menjadi array
        if ($dataBulanSebelumnyaResponse instanceof \Illuminate\Http\JsonResponse) {
            $dataBulanSebelumnya = $dataBulanSebelumnyaResponse->getData(true); // Konversi ke array
        } else {
            $dataBulanSebelumnya = $dataBulanSebelumnyaResponse; // Jika sudah array
        }
        
        // Hitung saldo awal
        $KB_saldoAwal = $dataBulanSebelumnya['data_total']['kas_besar']['saldo_akhir'] ?? 0;

        $data = [
            'kas_besar' => [
                'saldo_awal' => $KB_saldoAwal,
            ],
        ];
    
        return $data;
    }
}
