<title>Data Transaksi Kasir - Gecorp</title>
@extends('layouts.main')
@section('content')

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">

    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Data Transaksi Kasir</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('master.index') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a>Data Transaksi Kasir</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <!-- Tombol Tambah -->
                            <div class="d-flex">
                                @if (Auth::user()->id_level == 1)

                                @else
                                <a href="" class="btn btn-primary mr-2" id="btn-tambah" data-toggle="modal" data-target=".bd-example-modal-lg">
                                    <i class="ti-plus menu-icon"></i> Tambah
                                </a>
                                @endif
                                <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#filterModal">
                                    <i class="ti-plus menu-icon"></i> Filter
                                </a>
                            </div>
                            <!-- Input Search -->
                            <form class="d-flex" method="GET" action="{{ route('master.kasir.index') }}">
                                <input class="form-control me-2" id="search" type="search" name="search"
                                    placeholder="Cari Data Transaksi" aria-label="Search">
                            </form>
                        </div>
                        <x-adminlte-alerts />
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table table-striped" id="jsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>No Nota</th>
                                            <th>Tgl Transaksi</th>
                                            <th>Member</th>
                                            <th>Nama Toko</th>
                                            <th>Item</th>
                                            <th>Nilai</th>
                                            <th>Payment</th>
                                            <th>Kasir</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        @forelse ($kasir as $ksr)
                                            <tr data-toggle="modal" class="atur-harga-btn"
                                                data-target="#mediumModal-{{ $ksr->id }}"
                                                data-id_barang="{{ $ksr->id_barang }}" data-id="{{ $ksr->id }}">
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $ksr->no_nota }}</td>
                                                <td>
                                                    {{ $ksr->tgl_transaksi ? \DateTime::createFromFormat('Y-m-d', $ksr->tgl_transaksi)->format('d-m-Y') : '' }}
                                                </td>
                                                <td>{{ $ksr->id_member == 0 ? 'Guest' : $ksr->member->nama_member }}</td>
                                                <td>{{ $ksr->toko->nama_toko }}</td>
                                                <td>{{ $ksr->total_item }}</td>
                                                <td>Rp. {{ number_format($ksr->total_nilai, 0, '.', '.') }}</td>
                                                <td>{{ $ksr->metode }}</td>
                                                <td>{{ $ksr->users->nama }}</td>
                                                <form onsubmit="return confirm('Ingin menghapus Data ini ? ?');"
                                                    action="#" method="post">
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm atur-harga-btn"
                                                            data-toggle="modal"
                                                            data-target="#mediumModal-{{ $ksr->id }}"
                                                            data-id_barang="{{ $ksr->id_barang }}"
                                                            data-id="{{ $ksr->id }}" style="font-size: 12px;">
                                                            <i class="fa fa-book menu-icon"></i>
                                                        </button>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i
                                                                class="fa fa-trash menu-icon"></i></button>
                                                    </td>
                                                </form>
                                            </tr>
                                        @empty
                                            <td colspan="9" style="text-align: center">
                                                <h4><span class="badge badge-light-danger" style="margin:10px;">Tidak Ada
                                                        Data</span></h4>
                                            </td>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        Menampilkan <span id="current-count">0</span> data dari <span
                                            id="total-count">0</span> total data.
                                    </div>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-end" id="pagination">
                                            {{-- isian paginate --}}
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Tanggal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="GET" action="{{ route('master.kasir.index') }}">
                        <div class="form-group">
                            <label for="startDate">Dari Tanggak</label>
                            <input type="date" class="form-control" id="startDate" name="start_date" placeholder="Start Date">
                        </div>
                        <div class="form-group">
                            <label for="endDate">Hingga Tanggal</label>
                            <input type="date" class="form-control" id="endDate" name="end_date" placeholder="End Date">
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lgs">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title h4" id="myLargeModalLabel">Data Transaksi</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-xl-12 d-flex justify-content-between">
                        <div class="d-flex col-6">
                            <div class="col-4">
                                <p class="mb-0">No Nota</p>
                            </div>
                            <div class="col-8">
                                <p id="noNota" name="no_nota"> </p> <!-- ID untuk mengupdate nomor nota -->
                            </div>
                        </div>
                        <div class="d-flex col-6 justify-content-end">
                            <div class="col-4 text-end">
                                <p class="mb-0">Nama Toko</p>
                            </div>
                            <div class="col-8">
                                @if (Auth::check())
                                    <h5>: <span class="badge badge-info">{{ Auth::user()->toko->nama_toko }}</span></h5>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12 d-flex justify-content-between">
                        <div class="d-flex col-6">
                            <div class="col-4">
                                <p class="mb-0">Tgl Transaksi</p>
                            </div>
                            <div class="col-8">
                                <p name="tgl_transaksi" id="tglTransaksi">: </p>
                                <!-- Anda bisa mengganti dengan tanggal yang sesuai -->
                            </div>
                        </div>
                        <div class="d-flex col-6 justify-content-end">
                            <div class="col-4 text-end">
                                <p class="mb-0">Kasir</p>
                            </div>
                            <div class="col-8">
                                @if (Auth::check())
                                    <h5>: <span class="badge badge-info">{{ Auth::user()->nama }}</span></h5>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12 d-flex justify-content-between">
                        <div class="d-flex col-6">
                            <div class="col-4"></div>
                            <div class="col-8"></div>
                        </div>
                        <div class="d-flex col-6 justify-content-end">
                            <div class="col-4 text-end">
                                <p class="mb-0">Member</p>
                            </div>
                            <div class="col-8">
                                <p>:
                                    <select name="id_member" id="id_member">
                                        <option value="" selected>~ Pilih Member ~</option>
                                        <option value="Guest">Guest</option>
                                        @foreach ($member as $mbr)
                                            <option value="{{ $mbr->id }}"
                                                data-level-info='@json($mbr->level_info)'>
                                                {{ $mbr->nama_member }}</option>
                                        @endforeach
                                    </select>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body table-border-style">
                                        <div class="form">
                                            <form action="{{ route('master.kasir.store') }}" method="post"
                                                class="">
                                                @csrf
                                                <input type="hidden" id="hiddenNoNota" name="no_nota">
                                                <input type="hidden" id="hiddenKembalian" name="kembalian">
                                                <input type="hidden" id="hiddenMember" name="id_member">

                                                <div class="row">
                                                    <div class="col-12">
                                                        <label for="id_barang" class="form-control-label">Ketik Nama / Scan Barang<span style="color: red">*</span></label>
                                                        <input type="text" autocomplete="off" id="search-barang" placeholder="" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4">
                                                        <!-- Nama Barang -->
                                                        <div class="form-group">
                                                            <label for="id_barang" class="form-control-label">Nama
                                                                Barang<span style="color: red">*</span></label>
                                                            <select name="id_barang[]" id="barang" class="form-control">
                                                                <option value="">~Silahkan Pilih Barang~</option>
                                                                @foreach ($barang as $brg)
                                                                    <option value="{{ $brg->barang->id }}"
                                                                        data-barcode-barang="{{ $brg->barang->barcode }}"
                                                                        data-nama-barang="{{ $brg->barang->nama_barang }}"
                                                                        data-stock="{{ Auth::user()->id_level == 1 ? $brg->stock : $brg->qty }}"
                                                                        data-barcode="{{ $brg->barang->barcode }}"
                                                                        data-jenis-barang="{{ $brg->barang->id_jenis_barang }}"
                                                                        data-level-harga='@json($brg->barang->level_harga)'>
                                                                        {{ $brg->barang->nama_barang }} (Stock:
                                                                        {{ Auth::user()->id_level == 1 ? $brg->stock : $brg->qty }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="harga" class="form-control-label">Harga<span
                                                                style="color: red">*</span></label>
                                                        <select class="form-control" name="harga[]" id="harga"
                                                            style="display: block;">
                                                            <option value="">~Pilih Member Dahulu~</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="qty" class=" form-control-label">Item<span
                                                                style="color: red">*</span></label>
                                                        <input type="number" id="qty" name="qty[]"
                                                            placeholder="Contoh : 1" class="form-control">
                                                        <br>
                                                        <button type="button" id="add-button"
                                                            class="btn btn-sm btn-secondary"
                                                            style="float: right;">Add</button>
                                                    </div>
                                                </div>

                                                <br>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Action</th>
                                                                    <th scope="col">No</th>
                                                                    <th scope="col">Nama Barang</th>
                                                                    <th scope="col">Qty</th>
                                                                    <th scope="col">Harga</th>
                                                                    <th scope="col">Total Harga</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Rows akan ditambahkan di sini oleh JavaScript -->
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th scope="col" colspan="5"
                                                                        style="text-align:right">SubTotal</th>
                                                                    <th scope="col" name="total_nilai">Rp </th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col" colspan="5"
                                                                        style="text-align:right">Payment</th>
                                                                    <th scope="col">
                                                                        <select name="metode" id="metode"
                                                                            style="width: 100%">
                                                                            <option value="">~Pilih Payment~</option>
                                                                            <option value="Tunai">Tunai</option>
                                                                            <option value="Non-Tunai">Non-Tunai</option>
                                                                        </select>
                                                                    </th>
                                                                </tr>
                                                                <tr id="uang-bayar-row">
                                                                    <th scope="col" colspan="5"
                                                                        style="text-align:right">Jml Bayar</th>
                                                                    <th scope="col"><input type="text"
                                                                            style="width: 100%" name="jml_bayar"
                                                                            id="uang-bayar-input">
                                                                        <input type="hidden" id="hiddenUangBayar"
                                                                            name="jml_bayar">
                                                                    </th>
                                                                </tr>
                                                                <tr id="kembalian-row">
                                                                    <th scope="col" colspan="5"
                                                                        style="text-align:right">Kembalian</th>
                                                                    <th scope="col" id="kembalian-amount"
                                                                        name="kembalian">Rp </th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        <!-- Submit Button -->
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fa fa-dot-circle-o"></i> Simpan
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($kasir as $ksr)
        <div class="modal fade" id="mediumModal-{{ $ksr->id }}" tabindex="-1" role="dialog"
            aria-labelledby="mediumModalLabel-{{ $ksr->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lgs" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mediumModalLabel-{{ $ksr->id }}">Detail Transaksi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Detail Kasir -->
                        <div class="tab-content" id="myTabContent-{{ $ksr->id }}">
                            <div class="tab-pane fade show active" id="home-{{ $ksr->id }}" role="tabpanel"
                                aria-labelledby="home-tab-{{ $ksr->id }}">
                                <div class="row">
                                    <!-- Informasi Transaksi -->
                                    <div class="col-md-7">
                                        <div class="info-wrapper">
                                            <div class="info-wrapper">
                                                <div class="info-row">
                                                    <p class="label">No Nota</p>
                                                    <p class="value" id="notaS">: @php
                                                        // Mendapatkan nilai no_nota dari database
                                                        $noNotaFormatted = substr($ksr->no_nota, 0, 6) . '-' . substr($ksr->no_nota, 6, 6) . '-' . substr($ksr->no_nota, 12);
                                                    @endphp
                                                    {{ $noNotaFormatted }}</p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Tgl Transaksi</p>
                                                    <p class="value">:
                                                        {{ $ksr->created_at->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s') }}

                                                    </p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Jml Item</p>
                                                    <p class="value">: {{ $ksr->total_item }} Item</p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Nilai Transaksi</p>
                                                    <p class="value">: Rp.
                                                        {{ number_format($ksr->total_nilai, 0, '.', '.') }}</p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Total Potongan</p>
                                                    <p class="value">: Rp.
                                                        {{ number_format($ksr->total_diskon, 0, '.', '.') }}
                                                    </p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Jumlah Bayar</p>
                                                    <p class="value">: Rp.
                                                        {{ number_format($ksr->jml_bayar, 0, '.', '.') }}</p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Kembalian</p>
                                                    <p class="value">: Rp.
                                                        {{ number_format($ksr->kembalian, 0, '.', '.') }}</p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Kasir</p>
                                                    <p class="value">: {{ $ksr->users->nama }}</p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Item Retur</p>
                                                    <p class="value">: 0</p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Nilai Retur</p>
                                                    <p class="value">: 0</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tabel Data Barang -->
                                        <div class="table-responsive-js">
                                            <table class="table table-striped" id="jsTable-{{ $ksr->id }}">
                                                <thead>
                                                    <tr>
                                                        <th>Id trx</th>
                                                        <th>Nama Barang</th>
                                                        <th>Item</th>
                                                        <th>Harga</th>
                                                        <th>N.retur</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Filter hanya data detail yang sesuai dengan kasir -->
                                                    @foreach ($detail_kasir->where('id_kasir', $ksr->id) as $dtks)
                                                        <tr>
                                                            <td>{{ $dtks->id_kasir }}</td>
                                                            <td>{{ $dtks->barang->nama_barang }}</td>
                                                            <td>{{ $dtks->qty }}</td>
                                                            <td>{{ number_format($dtks->harga, 0, '.', '.') }}</td>
                                                            <td>0</td>
                                                            <td></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Tabel kedua di sebelah kanan -->
                                    <div class="col-md-5" style="background-color: rgb(250, 250, 250)">
                                        <div class="card text-center" style="background-color: rgb(250, 250, 250)">
                                            <div class="card-body">
                                                {{-- <h5 class="card-title">{{ $ksr->toko->nama_toko }}</h5> --}}
                                                <h5 class="card-subtitle">{{ $ksr->toko->nama_toko }}</h5>
                                                <p class="card-text">{{ $ksr->toko->alamat }}</p>
                                            </div>
                                        </div>
                                        <div class="info-wrapper">
                                            <div class="info-wrapper">
                                                <div class="info-row">
                                                    <p class="label">No Nota</p>
                                                    <p class="value">: @php
                                                        // Mendapatkan nilai no_nota dari database
                                                        $noNotaFormatted = substr($ksr->no_nota, 0, 6) . '-' . substr($ksr->no_nota, 6, 6) . '-' . substr($ksr->no_nota, 12);
                                                    @endphp
                                                    {{ $noNotaFormatted }}</p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Tgl Transaksi</p>
                                                    <p class="value">:
                                                        {{ $ksr->created_at->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s') }}
                                                    </p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Member</p>
                                                    <p class="value">: {{ $ksr->id_member == 0 ? 'Guest' : $ksr->member->nama_member }}</p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Kasir</p>
                                                    <p class="value">: {{ $ksr->users->nama }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive-js">
                                            <table class="table-borderless" id="jsTable-{{ $ksr->id }}">
                                                <tbody>
                                                    <!-- Filter hanya data detail yang sesuai dengan kasir -->
                                                    @foreach ($detail_kasir->where('id_kasir', $ksr->id) as $dtks)
                                                        <tr>
                                                            <td class="narrow-column">{{ $loop->iteration }}.</td>
                                                            <td class="wide-column">({{ $dtks->barang->nama_barang }})
                                                                {{ $dtks->qty }}pcs
                                                                @*{{ number_format($dtks->harga, 0, '.', '.') }}</td>
                                                            <td class="price-column">
                                                                -{{ number_format((float) $dtks->diskon, 0, '.', '.') }}
                                                            </td>
                                                            <td class="price-column">
                                                                {{ number_format($dtks->total_harga, 0, '.', '.') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="text-align:left">Total Harga</td>
                                                        <td class="price-column">
                                                            {{ number_format($ksr->total_nilai, 0, '.', '.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="text-align:left">Total Potongan</td>
                                                        <td class="price-column">
                                                            {{ number_format($ksr->total_diskon, 0, '.', '.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" colspan="3" style="text-align:left">Total
                                                        </th>
                                                        <th scope="col" class="price-column">
                                                            {{ number_format(($ksr->total_nilai - $ksr->total_diskon), 0, '.', '.') }}
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="text-align:left">Dibayar</td>
                                                        <td class="price-column">
                                                            {{ number_format($ksr->jml_bayar, 0, '.', '.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="text-align:left">Kembalian</td>
                                                        <td class="price-column">
                                                            {{ number_format($ksr->kembalian, 0, '.', '.') }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <p class="card-text" style="text-align: center">Terima Kasih</p>
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onclick="cetakStruk({{ $ksr->id }})">Cetak Struk</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contact-{{ $ksr->id }}" role="tabpanel"
                                aria-labelledby="contact-tab-{{ $ksr->id }}">
                                Another Tab
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- Tambahkan script di bawah ini setelah form di view Anda -->
<script>
    // Menambahkan event listener untuk membuka picker tanggal saat input fokus
    document.getElementById('startDate').addEventListener('focus', function() {
        this.showPicker();
    });
    document.getElementById('endDate').addEventListener('focus', function() {
        this.showPicker();
    });

    // Validasi form filter agar tanggal dipilih sebelum submit
    document.getElementById('filterForm').addEventListener('submit', function(event) {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (startDate && endDate) {
            return true; // Kirim form jika tanggal dipilih
        } else {
            alert('Pilih rentang tanggal untuk filter.');
            event.preventDefault(); // Cegah submit jika tanggal belum dipilih
        }
    });

    // Cek jika filter tanggal ada di URL, hapus parameter saat halaman di-refresh
    $(document).ready(function() {
        if (window.location.search.includes('start_date') || window.location.search.includes('end_date')) {
            const url = new URL(window.location);
            url.searchParams.delete('start_date'); // Hapus start_date
            url.searchParams.delete('end_date');   // Hapus end_date
            window.history.replaceState({}, document.title, url.toString()); // Update URL tanpa parameter
        }
    });
</script>

    <script>
        function cetakStruk(id_kasir) {
        const url = `{{ route('cetak.struk', ':id_kasir') }}`.replace(':id_kasir', id_kasir);
        const newWindow = window.open(url, '_blank');
        newWindow.onload = function() {
            newWindow.print();
        };
    }

    </script>
    <script>
        function getTodayDateWithDay() {
            const today = new Date();
            const day = String(today.getDate()).padStart(2, '0');
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Ditambah 1 karena getMonth() dimulai dari 0
            const year = today.getFullYear();

            // Array nama hari
            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const dayName = days[today.getDay()]; // Mendapatkan nama hari berdasarkan indeks

            return `${dayName}, ${day}-${month}-${year}`;
        }

        // Menampilkan tanggal dan hari di elemen dengan ID tglTransaksi
        document.getElementById('tglTransaksi').textContent += getTodayDateWithDay();
        // Fungsi untuk menghasilkan nomor berdasarkan format yang diinginkan
        function generateFormattedNumber() {
            const now = new Date();

            // Mendapatkan tanggal, bulan, tahun (2 digit), jam, menit, dan detik
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Ditambah 1 karena getMonth() dimulai dari 0
            const year = String(now.getFullYear()).slice(-2);
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            // Mendapatkan 3 digit angka acak
            const randomDigits = Math.floor(100 + Math.random() * 900);

            const noNota = `${day}${month}${year}${hours}${minutes}${seconds}${randomDigits}`;

            // Menyisipkan separator '-' setelah 6 digit pertama dan 6 digit kedua
            return `${noNota.slice(0, 6)}-${noNota.slice(6, 12)}-${noNota.slice(12)}`;
        }

        // Event listener untuk menampilkan nomor nota saat modal dibuka
        $('.bd-example-modal-lg').on('show.bs.modal', function() {
            const formattedNoNota = generateFormattedNumber();
            const noNotaElement = document.getElementById('noNota');
            const hiddenNoNotaInput = document.getElementById('hiddenNoNota');

            // Menampilkan nomor nota di elemen tampilan
            noNotaElement.textContent = ': ' + formattedNoNota;

            // Menghilangkan separator untuk penyimpanan
            const noNotaWithoutSeparator = formattedNoNota.replace(/-/g, '');
            hiddenNoNotaInput.value = noNotaWithoutSeparator;
        });
    </script>

    <script>
        const searchInput = document.getElementById("search-barang");
        const select = document.getElementById("barang");

        // Event listener untuk memfilter opsi dropdown saat pengguna mengetik
        searchInput.addEventListener("input", function(event) {
            const searchValue = event.target.value.toLowerCase();
            let matchCount = 0; // Hitung opsi yang cocok

            // Loop melalui opsi dan tampilkan/sembunyikan berdasarkan kecocokan searchValue
            for (const option of select.options) {
                const namaBarang = option.getAttribute("data-nama-barang")?.toLowerCase();

                // Tampilkan opsi yang mengandung searchValue, sembunyikan yang lain
                if (namaBarang && namaBarang.includes(searchValue)) {
                    option.style.display = ""; // Tampilkan opsi yang cocok
                    matchCount++; // Tambahkan hitungan untuk opsi yang cocok
                } else {
                    option.style.display = "none"; // Sembunyikan opsi yang tidak cocok
                }
            }

            // Atur ukuran dropdown agar terlihat sejumlah opsi yang cocok
            select.size = matchCount > 0 ? matchCount : 1; // Minimal ukuran dropdown tetap 1
        });

        // Event listener untuk memilih opsi jika Enter ditekan
        searchInput.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault(); // Mencegah form submit jika ada
                const searchValue = event.target.value.toLowerCase();
                let found = false;

                for (const option of select.options) {
                    const namaBarang = option.getAttribute("data-nama-barang")?.toLowerCase();
                    const barcodeBarang = option.getAttribute("data-barcode-barang")?.toLowerCase();

                    if (namaBarang === searchValue || barcodeBarang === searchValue) {
                        option.selected = true;
                        found = true;
                        select.dispatchEvent(new Event('change'));
                        break;
                    }
                }

                if (!found && searchValue) {
                    alert("Barang tidak ditemukan. Pastikan nama barang sesuai.");
                }

                // Reset ukuran dropdown dan bersihkan input
                select.size = 1; // Kembali ke ukuran normal setelah pemilihan
                this.value = ''; // Bersihkan input setelah pencarian
            }
        });

        // Event listener untuk menangkap pilihan barang secara langsung
        select.addEventListener("change", function() {
            select.size = 1; // Kembali ke ukuran normal setelah memilih opsi
            searchInput.value = ''; // Bersihkan input setelah pilihan
        });

        // Tutup dropdown ketika pengguna mengklik di luar input atau dropdown
        document.addEventListener("click", function(event) {
            if (!select.contains(event.target) && !searchInput.contains(event.target)) {
                select.size = 1; // Tutup dropdown ketika klik di luar
            }
        });
    </script>

    <script>
    document.getElementById('btn-tambah').addEventListener('click', function () {
        // Tunggu modal tampil
        setTimeout(function () {
            document.getElementById('search-barang').focus();
        }, 1000); // Penyesuaian waktu, sesuai animasi modal
    });

    $('.bd-example-modal-lg').on('shown.bs.modal', function () {
        const searchBarangInput = document.getElementById('search-barang');

        if (searchBarangInput) {
            // Fokus awal ke input search-barang
            searchBarangInput.focus();

            // Event listener untuk klik di modal
            const modalContent = document.querySelector('.bd-example-modal-lg .modal-content');

            modalContent.addEventListener('click', function (event) {
                // Daftar elemen interaktif yang tidak akan memicu fokus ulang
                const nonInteractiveElements = ['input', 'select', 'textarea', 'button', 'a'];

                // Jika area yang diklik bukan elemen interaktif, fokuskan kembali ke input search-barang
                if (!nonInteractiveElements.includes(event.target.tagName.toLowerCase())) {
                    searchBarangInput.focus();
                }
            });
        }
    });
    </script>

    <style>
        #search-barang:focus {
            border-color: #007bff; /* Warna border biru */
            background-color: rgb(31, 248, 2);
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Efek glow */
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
            outline: none; /* Menghilangkan outline default */
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // const selectBarang = new TomSelect("#barang", {
            //     // Menyimpan data value dan text untuk setiap opsi
            //     valueField: 'value',
            //     labelField: 'text',
            //     searchField: ['text',
            //         'data-search-barang'
            //     ], // Memungkinkan pencarian di 'text' dan 'data-search-barang'
            //     render: {}
            // });

            const memberSelect = document.getElementById('id_member');
            const barangSelect = document.getElementById('barang');
            const hargaSelect = document.getElementById('harga');
            const qtyInput = document.getElementById('qty');
            const addButton = document.getElementById('add-button');
            const tableBody = document.querySelector('.modal-body table tbody');
            const subtotalFooter = document.querySelector('.modal-body tfoot th[colspan="5"] + th');

            const metodeSelect = document.getElementById('metode');
            const uangBayarInput = document.getElementById('uang-bayar-input');
            const kembalianAmount = document.getElementById('kembalian-amount');

            let subtotal = 0;

            function updateRowNumbers() {
                const rows = tableBody.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    row.children[1].textContent = index + 1; // Mengupdate nomor baris
                });
            }

            memberSelect.addEventListener('change', function() {
                barangSelect.disabled = !this.value;
                hargaSelect.disabled = true;
                hargaSelect.innerHTML = '<option value="">~Pilih Barang Dahulu~</option>';
            });

            barangSelect.addEventListener('change', function() {
                const selectedBarang = this.options[this.selectedIndex];
                const memberId = memberSelect.value;
                const barangId = selectedBarang.value;

                if (memberId && barangId) {
                    hargaSelect.innerHTML = '<option value="">Loading...</option>';
                    hargaSelect.disabled = true;

                    fetch(`/admin/kasir/get-filtered-harga?id_member=${memberId}&id_barang=${barangId}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log(data.filteredHarga);
                            hargaSelect.innerHTML = ''; // Kosongkan dropdown sebelum menambahkan opsi baru

                            if (Array.isArray(data.filteredHarga)) {
                                data.filteredHarga.forEach(harga => {
                                    if (harga) {
                                        hargaSelect.innerHTML +=
                                            `<option value="${harga}">${harga}</option>`;
                                    }
                                });
                            } else if (data.filteredHarga) {
                                hargaSelect.innerHTML +=
                                    `<option value="${data.filteredHarga}">${data.filteredHarga}</option>`;
                            }

                            const options = hargaSelect.querySelectorAll('option[value]');
                            if (options.length === 1) {
                                // Jika hanya ada satu opsi, langsung pilih opsi tersebut
                                hargaSelect.value = options[0].value; // Pilih nilai secara langsung
                            } else {
                                // Jika lebih dari satu opsi, tambahkan opsi default
                                hargaSelect.insertAdjacentHTML('afterbegin', '<option value="">~Masukkan Harga~</option>');
                            }

                            // Aktifkan kembali dropdown jika ada opsi
                            hargaSelect.disabled = options.length === 0;
                        })
                        .catch(error => console.error('Error fetching filtered harga:', error));
                }
            });

            function toggleMemberSelectDisabled() {
                const hasRows = tableBody.children.length > 0;
                memberSelect.disabled = hasRows;
            }

            addButton.addEventListener('click', function() {
                let idBarang = document.getElementById('barang').value;
                const selectedBarang = barangSelect.options[barangSelect.selectedIndex];
                const selectedHarga = hargaSelect.value;
                const qty = parseInt(qtyInput.value);
                const stock = parseInt(selectedBarang.getAttribute('data-stock'));
                let harga = parseInt(document.getElementById('harga').value);

                // Cek apakah barang sudah ada di tabel
                const existingRow = Array.from(tableBody.children).find(row => {
                    const barangNameCell = row.children[2];
                    return barangNameCell && barangNameCell.textContent.trim() === selectedBarang
                        .textContent.trim();
                });

                if (existingRow) {
                    alert("Barang sudah ditambahkan");
                    return; // Menghentikan proses jika barang sudah ada
                }

                // Validasi apakah qty melebihi stok
                if (qty > stock) {
                    alert("Stock barang tidak cukup");
                    return;
                }

                if (!selectedBarang.value || !selectedHarga || !qty) {
                    alert("Silakan lengkapi semua data sebelum menambahkan.");
                    return;
                }

                const totalHarga = parseFloat(selectedHarga) * qty;
                subtotal += totalHarga;
                subtotalFooter.textContent = `Rp ${subtotal.toLocaleString()}`;

                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><button type="button" class="btn btn-danger btn-sm remove-btn"><i class="fa fa-trash"></i></button></td>
                    <td></td> <!-- Ini akan diisi oleh fungsi updateRowNumbers -->
                    <td><input type="hidden" name="id_barang[]" value="${idBarang}">${selectedBarang.textContent}</td>
                    <td><input type="hidden" name="qty[]" value="${qty}">${qty}</td>
                    <td><input type="hidden" name="harga[]" value="${harga}">Rp ${parseFloat(selectedHarga).toLocaleString()}</td>
                    <td>Rp ${totalHarga.toLocaleString()}</td>
                `;
                tableBody.appendChild(newRow);

                qtyInput.value = '';
                document.getElementById('harga').value = '';
                document.getElementById('barang').value = '';

                // Menambah event listener untuk tombol hapus
                newRow.querySelector('.remove-btn').addEventListener('click', function() {
                    subtotal -= totalHarga;
                    subtotalFooter.textContent = `Rp ${subtotal.toLocaleString()}`;
                    newRow.remove();
                    updateRowNumbers(); // Memperbarui nomor baris setelah menghapus
                    toggleMemberSelectDisabled();
                });

                updateRowNumbers(); // Memperbarui nomor baris setelah menambahkan
                toggleMemberSelectDisabled();

                const searchBarangInput = document.getElementById('search-barang');
                searchBarangInput.focus();
            });

            document.getElementById('id_member').addEventListener('change', function() {
                const selectedMember = this.value;
                document.getElementById('hiddenMember').value = selectedMember;
            });

            // Set hidden inputs before form submission
            document.querySelector('form').addEventListener('submit', function(event) {
                document.getElementById('hiddenNoNota').value = document.getElementById('noNota')
                    .textContent;
                document.getElementById('hiddenKembalian').value = document.getElementById(
                    'kembalian-amount').textContent;
                document.getElementById('hiddenMember').value = document.getElementById('id_member').value;
            });

            // Fungsi untuk update kembalian berdasarkan subtotal dan input Uang Bayar
            function updateKembalian() {
                const uangBayar = parseFloat(uangBayarInput.value.replace(/,/g, '')) || 0; // Menghapus koma
                const kembalian = uangBayar - subtotal;
                kembalianAmount.textContent = `Rp ${kembalian >= 0 ? kembalian.toLocaleString() : 0}`;

                // Menyimpan nilai kembalian ke input hidden
                document.getElementById('hiddenKembalian').value = kembalian >= 0 ? kembalian : 0;
            }

            // Format input uang bayar dengan pemisah ribuan
            uangBayarInput.addEventListener('input', function() {
                // Menghapus semua karakter non-digit (kecuali koma)
                let value = this.value.replace(/[^0-9]/g, '');

                // Menyimpan nilai asli (tanpa koma) di hidden input untuk database
                document.getElementById('hiddenUangBayar').value = value;

                // Menggunakan format pemisah ribuan
                if (value) {
                    this.value = parseInt(value).toLocaleString();
                }
                updateKembalian(); // Update kembalian
            });

            // Event listener untuk mengupdate kembalian secara real-time
            uangBayarInput.addEventListener('input', updateKembalian);

            // Menyembunyikan dan menampilkan baris Uang Bayar dan Kembalian sesuai metode pembayaran
            metodeSelect.addEventListener('change', function() {
                const isTunai = metodeSelect.value === "Tunai";
                document.getElementById('uang-bayar-row').style.display = isTunai ? '' : 'none';
                document.getElementById('kembalian-row').style.display = isTunai ? '' : 'none';
                uangBayarInput.value = ''; // Reset input dan kembalian jika metode diubah
                kembalianAmount.textContent = 'Rp 0';
            });

            // Menetapkan tampilan awal berdasarkan pilihan metode pembayaran
            metodeSelect.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
