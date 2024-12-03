<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Brand;
use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\Facades\DNS1DFacade;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('brand', 'jenis')
                        ->orderBy('id', 'desc')
                        ->get();
        return view('master.barang.index', compact('barang'));
    }

    public function create()
    {
        $jenis = JenisBarang::all();
        $brand = Brand::all();
        // Mengirim data ke view
        return view('master.barang.create', compact('brand', 'jenis'), [
            'brand' => Brand::all()->pluck('nama_brand','id'),
            'jenis' => JenisBarang::all()->pluck('nama_jenis_barang', 'id'),
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
        // Validasi input
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'id_jenis_barang' => 'required|exists:jenis_barang,id',
            'id_brand_barang' => 'required|exists:brand,id',
            'barcode' => 'nullable|string|max:255',
            'gambar_barang' => 'nullable|image|max:2048',
        ]);

        try {
            // Ambil nama jenis dan brand barang
            $jenisBarang = JenisBarang::findOrFail($request->id_jenis_barang)->nama_jenis_barang;
            $brandBarang = Brand::findOrFail($request->id_brand_barang)->nama_brand;

            // Buat kombinasi kode nama
            $initials = strtoupper(substr($jenisBarang, 0, 1) . substr($brandBarang, 0, 1));

            // Generate barcode value
            $barcodeValue = $request->barcode ?: $initials . '-' . random_int(100000, 999999);

            // Generate barcode as PNG file
            $barcodeFilename = "barcodes/{$barcodeValue}.jpg";
            if (!Storage::exists($barcodeFilename)) {
                $barcodeImage = DNS1DFacade::getBarcodePNG($barcodeValue, 'C128', 3, 100);

                if (!$barcodeImage) {
                    throw new \Exception('Failed to generate barcode PNG as Base64');
                }

                // Save to Storage
                if (!Storage::put($barcodeFilename, base64_decode($barcodeImage))) {
                    throw new \Exception('Failed to save barcode image to storage');
                }
            }

            // Simpan gambar barang jika diunggah
            $gambarPath = null; // Default null jika gambar tidak diunggah
            if ($request->hasFile('gambar_barang')) {
                $gambarFile = $request->file('gambar_barang');
                $gambarPath = $gambarFile->store('gambar_barang', 'public'); // Simpan ke storage/public/gambar_barang
            }

            // Simpan informasi barang ke database
            $barang = new Barang();
            $barang->nama_barang = $request->nama_barang;
            $barang->id_jenis_barang = $request->id_jenis_barang;
            $barang->id_brand_barang = $request->id_brand_barang;
            $barang->barcode = $barcodeValue;
            $barang->barcode_path = $barcodeFilename;
            $barang->gambar_path = $gambarPath;
            $barang->save();

            return redirect()->route('master.barang.index')->with('success', 'Data Barang berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function downloadQrCode(Barang $barang)
    {
        // Pastikan file barcode ada
        if (Storage::exists($barang->barcode_path)) {
            // Nama file download berdasarkan nama barang
            $filename = "{$barang->nama_barang}.png";
            return Storage::download($barang->barcode_path, $filename);
        }

        return redirect()->back()->with('error', 'QR Code tidak ditemukan.');
    }

    public function edit(string $id)
    {
        $barang = Barang::with('brand', 'jenis')->findOrFail($id);
        $brand = Brand::all();
        $jenis = JenisBarang::all();
        return view('master.barang.edit', compact('barang', 'brand', 'jenis'));
    }

    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);
        try {
            $barang->update([
                'id_jenis_barang' => $request->id_jenis_barang,
                'id_brand_barang' => $request->id_brand_barang,
                'nama_barang' => $request->nama_barang,
                'barcode' => $request->barcode,
            ]);
            return redirect()->route('master.barang.index')->with('success', 'Sukses Mengubah Data Barang');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        $barang = Barang::findOrFail($id);

        try {

            $barang->delete();

            DB::commit();

            return redirect()->route('master.barang.index')->with('success', 'Sukses menghapus Data Barang');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menghapus Data Barang: ' . $th->getMessage());
        }
    }
}
