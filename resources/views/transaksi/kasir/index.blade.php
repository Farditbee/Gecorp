@extends('layouts.main')

@section('title')
    Data Transaksi Kasir
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <style>
        @media (max-width: 768px) {
            .modal-dialog {
                max-width: 100%;
                margin: 0;
            }
        }

        @media (min-width: 769px) {
            .modal-dialog {
                max-width: 90%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                                @if (Auth::user()->id_level == 3 || Auth::user()->id_level == 4)
                                    <a id="btn-tambah" class="btn btn-primary mb-2 mr-2 text-white" data-toggle="modal"
                                        data-target=".bd-example-modal-lg">
                                        <i class="fa fa-plus-circle"></i> Tambah
                                    </a>
                                @endif
                                <form id="custom-filter" class="d-flex justify-content-start align-items-center">
                                    <input class="form-control w-50 mb-2" type="text" id="daterange" name="daterange"
                                        placeholder="Pilih rentang tanggal">
                                    <button class="btn btn-info h-100 mb-2 mx-2" id="tb-filter" type="submit">
                                        <i class="fa fa-magnifying-glass mr-2"></i>Cari
                                    </button>
                                    <button type="button" class="btn btn-secondary h-100 mb-2" id="tb-reset">
                                        <i class="fa fa-rotate mr-2"></i>Reset
                                    </button>
                                </form>
                            </div>
                            <div class="d-flex justify-content-between align-items-lg-start flex-wrap">
                                <select name="limitPage" id="limitPage" class="form-control mr-2 mb-2 mb-lg-0"
                                    style="width: 100px;">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                </select>
                                <input id="tb-search" class="tb-search form-control mb-2 mb-lg-0" type="search"
                                    name="search" placeholder="Cari Data" aria-label="search" style="width: 200px;">
                            </div>
                        </div>
                        <div class="content">
                            <x-adminlte-alerts />
                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-striped m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">No. Nota</th>
                                                <th class="text-wrap align-top">Tanggal Transaksi</th>
                                                <th class="text-wrap align-top">Member</th>
                                                <th class="text-wrap align-top">Nama Toko</th>
                                                <th class="text-wrap align-top">Item</th>
                                                <th class="text-wrap align-top">Nilai</th>
                                                <th class="text-wrap align-top">Payment</th>
                                                <th class="text-wrap align-top">Kasir</th>
                                                <th class="text-wrap align-top">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listData">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3">
                                    <div class="text-center text-md-start mb-2 mb-md-0">
                                        <div class="pagination">
                                            <div>Menampilkan <span id="countPage">0</span> dari <span
                                                    id="totalPage">0</span> data</div>
                                        </div>
                                    </div>
                                    <nav class="text-center text-md-end">
                                        <ul class="pagination justify-content-center justify-content-md-end"
                                            id="pagination-js">
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-form" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lgs">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title h4" id="myLargeModalLabel">Data Transaksi</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-12 d-flex justify-content-between">
                                <div class="d-flex col-6">
                                    <div class="col-4">
                                        <p class="mb-0"><i class="mr-1 fa fa-file-text"></i>No Nota</p>
                                    </div>
                                    <div class="col-8">
                                        <h5 id="noNota" name="no_nota"></h5>
                                    </div>
                                </div>
                                <div class="d-flex col-6 justify-content-end">
                                    <div class="col-4 text-end">
                                        <p class="mb-0"><i class="mr-1 fa fa-store"></i>Nama Toko</p>
                                    </div>
                                    <div class="col-8">
                                        @if (Auth::check())
                                            <h5>: <span class="badge badge-info">{{ Auth::user()->toko->nama_toko }}</span>
                                            </h5>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 d-flex justify-content-between">
                                <div class="d-flex col-6">
                                    <div class="col-4">
                                        <p class="mb-0"><i class="mr-1 fa fa-calendar-day"></i>Tanggal Transaksi</p>
                                    </div>
                                    <div class="col-8">
                                        <h5 name="tgl_transaksi" id="tglTransaksi"></h5>
                                    </div>
                                </div>
                                <div class="d-flex col-6 justify-content-end">
                                    <div class="col-4 text-end">
                                        <p class="mb-0"><i class="mr-1 fa fa-user"></i>Kasir</p>
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
                                        <p class="mb-0"><i class="mr-1 fa fa-id-card"></i>Member</p>
                                    </div>
                                    <div class="col-8 d-flex align-items-center">
                                        <p class="mr-1">:</p>
                                        <select id="id_member" class="form-select select2">
                                            <option value="" selected>~ Pilih Member ~</option>
                                            <option value="Guest">Guest</option>
                                            @foreach ($member as $mbr)
                                                <option value="{{ $mbr->id }}"
                                                    data-level-info='@json($mbr->level_info)'>
                                                    {{ $mbr->nama_member }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form">
                                    @csrf
                                    <input type="hidden" id="hiddenNoNota" name="no_nota">
                                    <input type="hidden" id="hiddenKembalian" name="kembalian">
                                    <input type="hidden" id="hiddenMember" name="id_member">
                                    <input type="hidden" id="hiddenMinus" name="minus">
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-5">
                                            <div class="form-group">
                                                <label for="id_barang" class="form-control-label">Nama Barang<sup
                                                        style="color: red">*</sup></label>
                                                <select id="barang" class="form-control select2"></select>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="form-group">
                                                <label for="harga" class="form-control-label">Harga<sup
                                                        style="color: red">*</sup></label>
                                                <select class="form-control select2" id="harga">
                                                    <option value="">~Pilih Member Dahulu~</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2 d-flex align-items-end">
                                            <button type="button" id="add-button"
                                                class="btn btn-sm btn-secondary w-100">
                                                <i class="mr-2 fa fa-circle-plus"></i>Add
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
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
                                                <tbody id="dataStore">
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th scope="col" colspan="5" style="text-align:right">
                                                            SubTotal</th>
                                                        <th scope="col" name="total_nilai">Rp </th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" colspan="5" style="text-align:right">
                                                            Payment</th>
                                                        <th scope="col">
                                                            <select name="metode" id="metode" style="width: 100%">
                                                                <option value="">~Pilih Payment~</option>
                                                                <option value="Tunai">Tunai</option>
                                                                <option value="Non-Tunai">Non-Tunai</option>
                                                            </select>
                                                        </th>
                                                    </tr>
                                                    <tr id="uang-bayar-row">
                                                        <th scope="col" colspan="5" style="text-align:right">Jml
                                                            Bayar</th>
                                                        <th scope="col">
                                                            <input type="text" style="width: 100%" name="jml_bayar"
                                                                id="uang-bayar-input">
                                                            <input type="hidden" id="hiddenUangBayar" name="jml_bayar">
                                                        </th>
                                                    </tr>
                                                    <tr id="kembalian-row">
                                                        <th scope="col" colspan="5" id="kembalian-text"
                                                            style="text-align:right">
                                                            Kembalian
                                                        </th>
                                                        <th scope="col" id="kembalian-amount" name="kembalian">Rp
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="save-data" class="btn btn-success w-100"><i
                                class="mr-2 fa fa-save"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($kasir as $ksr)
        <div class="modal fade" id="mediumModal-{{ $ksr->id }}" tabindex="-1" role="dialog"
            aria-labelledby="mediumModalLabel-{{ $ksr->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-tile" id="mediumModalLabel-{{ $ksr->id }}">Detail Transaksi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="tab-content" id="myTabContent-{{ $ksr->id }}">
                            <div class="tab-pane fade show active" id="home-{{ $ksr->id }}" role="tabpanel"
                                aria-labelledby="home-tab-{{ $ksr->id }}">
                                <div class="row">
                                    <div class="col-md-7 mb-4">
                                        <div class="info-wrapper">
                                            <div class="info-wrapper">
                                                <div class="info-row">
                                                    <p class="label">No Nota</p>
                                                    <p class="value" id="notaS">: @php
                                                        $noNotaFormatted =
                                                            substr($ksr->no_nota, 0, 6) .
                                                            '-' .
                                                            substr($ksr->no_nota, 6, 6) .
                                                            '-' .
                                                            substr($ksr->no_nota, 12);
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
                                                    <p class="value">: {{ $ksr->users->nama ?? null }}</p>
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
                                        <div class="table-responsive table-scroll-wrapper">
                                            <table class="table table-striped m-0" id="jsTable-{{ $ksr->id }}">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center text-wrap align-top">Id trx</th>
                                                        <th class="text-wrap align-top">Nama Barang</th>
                                                        <th class="text-wrap align-top">Item</th>
                                                        <th class="text-wrap align-top">Harga</th>
                                                        <th class="text-wrap align-top">N.retur</th>
                                                        <th class="text-wrap align-top">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($detail_kasir->where('id_kasir', $ksr->id) as $dtks)
                                                        <tr>
                                                            <td class="text-center text-wrap align-top">
                                                                {{ $dtks->id_kasir }}</td>
                                                            <td class="text-wrap align-top">
                                                                {{ $dtks->barang->nama_barang }}</td>
                                                            <td class="text-wrap align-top">{{ $dtks->qty }}</td>
                                                            <td class="text-wrap align-top">
                                                                {{ number_format($dtks->harga, 0, '.', '.') }}</td>
                                                            <td class="text-wrap align-top">0</td>
                                                            <td class="text-wrap align-top">
                                                                <a href="{{ asset('storage/' . $dtks->qrcode_path) }}"
                                                                    download class="btn btn-success">
                                                                    <span><i
                                                                            class="fa fa-download mr-2"></i>Download</span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-5" style="background-color: rgb(250, 250, 250)">
                                        <div class="card text-center p-0" style="background-color: rgb(250, 250, 250)">
                                            <div class="card-body">
                                                <h5 class="card-subtitle">{{ $ksr->toko->nama_toko }}</h5>
                                                <p class="card-text">{{ $ksr->toko->alamat }}</p>
                                            </div>
                                        </div>
                                        <div class="info-wrapper">
                                            <div class="info-wrapper">
                                                <div class="info-row">
                                                    <p class="label">No Nota</p>
                                                    <p class="value">: @php
                                                        $noNotaFormatted =
                                                            substr($ksr->no_nota, 0, 6) .
                                                            '-' .
                                                            substr($ksr->no_nota, 6, 6) .
                                                            '-' .
                                                            substr($ksr->no_nota, 12);
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
                                                    <p class="value">:
                                                        {{ $ksr->id_member == 0 ? 'Guest' : $ksr->member->nama_member }}
                                                    </p>
                                                </div>
                                                <div class="info-row">
                                                    <p class="label">Kasir</p>
                                                    <p class="value">: {{ $ksr->users->nama ?? null }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive-js">
                                            <table class="table-borderless" id="jsTable-{{ $ksr->id }}">
                                                <tbody>
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
                                                    <tr style="background-color: rgba(145, 145, 145, 0.289);">
                                                        <th scope="col" colspan="3" style="text-align:left">Total
                                                        </th>
                                                        <th scope="col" class="price-column">
                                                            {{ number_format($ksr->total_nilai - $ksr->total_diskon, 0, '.', '.') }}
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="text-align:left">Dibayar</td>
                                                        <td class="price-column">
                                                            {{ number_format($ksr->jml_bayar, 0, '.', '.') }}</td>
                                                    </tr>
                                                    @if ($ksr->kembalian != 0)
                                                        <tr>
                                                            <td colspan="3" style="text-align:left">Kembalian</td>
                                                            <td class="price-column">
                                                                {{ number_format($ksr->kembalian, 0, '.', '.') }}</td>
                                                        </tr>
                                                    @endif
                                                    @if ($ksr->kasbon != null)
                                                        <tr>
                                                            <td colspan="3" style="text-align:left">Sisa Pembayaran
                                                            </td>
                                                            <td class="price-column">
                                                                {{ number_format($ksr->kasbon->utang, 0, '.', '.') }}
                                                            </td>
                                                        </tr>
                                                    @elseif ($ksr->kasbon == null)
                                                    <tr>
                                                        <td colspan="3" style="text-align:left">Sisa Pembayaran
                                                        </td>
                                                        <td class="price-column">
                                                           woyy
                                                        </td>
                                                    </tr>
                                                    @endif
                                                </tfoot>
                                            </table>
                                        </div>
                                        <p class="card-text" style="text-align: center">Terima Kasih</p>
                                        <hr>
                                        <button type="button" class="btn btn-primary btn-sm mb-3 btn-block"
                                            onclick="cetakStruk({{ $ksr->id }})">
                                            <i class="fa fa-print mr-2"></i>Cetak Struk
                                        </button>
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
@endsection

@section('asset_js')
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/daterange-picker.js') }}"></script>
    <script src="{{ asset('js/daterange-custom.js') }}"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Transaksi Kasir';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};
        let selectOptions = [{
            id: '#barang',
            isFilter: {
                id_toko: '{{ auth()->user()->id_toko }}',
            },
            isUrl: '{{ route('master.barangKasir') }}',
            placeholder: 'Pilih Member terlebih dahulu',
            isMinimum: 3,
            isModal: '#modal-form',
            isDisabled: true,
        }];

        function selectFormat(isParameter, isPlaceholder, isDisabled = true) {
            if (!$(isParameter).find('option[value=""]').length) {
                $(isParameter).prepend('<option value=""></option>');
            }

            $(isParameter).select2({
                dropdownParent: $('#modal-form'),
                disabled: isDisabled,
                dropdownAutoWidth: true,
                width: '100%',
                placeholder: isPlaceholder,
                allowClear: true,
            });
        }

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listData').html(loadingData());

            let filterParams = {};

            if (customFilter['startDate'] && customFilter['endDate']) {
                filterParams.startDate = customFilter['startDate'];
                filterParams.endDate = customFilter['endDate'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.transaksi.get') }}', {
                    page: page,
                    limit: limit,
                    ascending: ascending,
                    search: search,
                    id_toko: '{{ auth()->user()->id_toko }}',
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status == 200 && Array.isArray(getDataRest.data.data)) {
                let handleDataArray = await Promise.all(
                    getDataRest.data.data.map(async item => await handleData(item))
                );
                await setListData(handleDataArray, getDataRest.data.pagination);
            } else {
                errorMessage = getDataRest?.data?.message;
                let errorRow = `
                            <tr class="text-dark">
                                <th class="text-center" colspan="${$('.tb-head th').length}"> ${errorMessage} </th>
                            </tr>`;
                $('#listData').html(errorRow);
                $('#countPage').text("0 - 0");
                $('#totalPage').text("0");
            }
        }

        async function handleData(data) {
            let detail_button = `
                <a class="p-1 btn detail-data action_button" data-toggle="modal" data-target="#mediumModal-${data.id}"
                    data-id='${data.id}'>
                    <span class="text-dark" data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Detail ${title}: ${data.no_nota}">Detail</span>
                    <div class="icon text-info" data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Detail ${title}: ${data.no_nota}">
                        <i class="fa fa-book"></i>
                    </div>
                </a>`;

            let delete_button = `
                <a class="p-1 btn hapus-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Hapus ${title}: ${data.no_nota}"
                    data-id='${data.id}'
                    data-name='${data.no_nota}'>
                    <span class="text-dark">Hapus</span>
                    <div class="icon text-danger">
                        <i class="fa fa-trash"></i>
                    </div>
                </a>`;

            let action_buttons = '';
            if (detail_button || delete_button) {
                action_buttons = `
                <div class="d-flex justify-content-start">
                    ${detail_button ? `<div class="hovering p-1">${detail_button}</div>` : ''}
\                </div>`;
            } else {
                action_buttons = `
                <span class="badge badge-danger">Tidak Ada Aksi</span>`;
            }

            return {
                id: data?.id ?? '-',
                no_nota: data?.no_nota ?? '-',
                tgl_transaksi: data?.tgl_transaksi ?? '-',
                nama_member: data?.nama_member ?? '-',
                nama_toko: data?.nama_toko ?? '-',
                total_item: data?.total_item ?? '-',
                total_nilai: data?.total_nilai ?? '-',
                metode: data?.metode ?? '-',
                nama: data?.nama ?? '-',
                action_buttons,
            };
        }

        async function setListData(dataList, pagination) {
            totalPage = pagination.total_pages;
            currentPage = pagination.current_page;
            let display_from = ((defaultLimitPage * (currentPage - 1)) + 1);
            let display_to = Math.min(display_from + dataList.length - 1, pagination.total);

            let getDataTable = '';
            let classCol = 'align-center text-dark text-wrap';
            dataList.forEach((element, index) => {
                getDataTable += `
                            <tr class="text-dark clickable-row" data-toggle="modal" data-target="#mediumModal-${element.id}">
                                <td class="${classCol} text-center">${display_from + index}.</td>
                                <td class="${classCol}">${element.no_nota}</td>
                                <td class="${classCol}">${element.tgl_transaksi}</td>
                                <td class="${classCol}">${element.nama_member}</td>
                                <td class="${classCol}">${element.nama_toko}</td>
                                <td class="${classCol}">${element.total_item}</td>
                                <td class="${classCol}">${element.total_nilai}</td>
                                <td class="${classCol}">${element.metode}</td>
                                <td class="${classCol}">${element.nama}</td>
                                <td class="${classCol}">${element.action_buttons}</td>
                            </tr>`;
            });

            $('#listData').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();
        }

        async function filterList() {
            let dateRangePickerList = initializeDateRangePicker();

            document.getElementById('custom-filter').addEventListener('submit', async function(e) {
                e.preventDefault();
                let startDate = dateRangePickerList.data('daterangepicker').startDate;
                let endDate = dateRangePickerList.data('daterangepicker').endDate;

                if (!startDate || !endDate) {
                    startDate = null;
                    endDate = null;
                } else {
                    startDate = startDate.startOf('day').toISOString();
                    endDate = endDate.endOf('day').toISOString();
                }

                customFilter = {
                    'startDate': $("#daterange").val() != '' ? startDate : '',
                    'endDate': $("#daterange").val() != '' ? endDate : ''
                };

                defaultSearch = $('.tb-search').val();
                defaultLimitPage = $("#limitPage").val();
                currentPage = 1;

                await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                    customFilter);
            });

            document.getElementById('tb-reset').addEventListener('click', async function() {
                $('#daterange').val('');
                customFilter = {};
                defaultSearch = $('.tb-search').val();
                defaultLimitPage = $("#limitPage").val();
                currentPage = 1;
                await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                    customFilter);
            });
        }

        function cetakStruk(id_kasir) {
            const url = `{{ route('cetak.struk', ':id_kasir') }}`.replace(':id_kasir', id_kasir);
            const newWindow = window.open(url, '_blank');
            newWindow.onload = function() {
                newWindow.print();
            };
        }

        function getTodayDateWithDay() {
            const today = new Date();
            const day = String(today.getDate()).padStart(2, '0');
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const year = today.getFullYear();
            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const dayName = days[today.getDay()];

            return `${dayName}, ${day}-${month}-${year}`;
        }

        function generateFormattedNumber() {
            const now = new Date();
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const year = String(now.getFullYear()).slice(-2);
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const randomDigits = Math.floor(100 + Math.random() * 900);
            const noNota = `${day}${month}${year}${hours}${minutes}${seconds}${randomDigits}`;

            return `${noNota.slice(0, 6)}-${noNota.slice(6, 12)}-${noNota.slice(12)}`;
        }

        const select = document.getElementById("barang");

        select.addEventListener("change", function() {
            select.size = 1;
        });

        document.addEventListener("click", function(event) {
            if (!select.contains(event.target)) {
                select.size = 1;
            }
        });

        function add() {
            if ('{{ Auth::user()->id_level }}' == 3) {
                document.getElementById('btn-tambah').addEventListener('click', function() {
                    const formattedNoNota = generateFormattedNumber();
                    const hiddenNoNotaInput = document.getElementById('hiddenNoNota');
                    const noNotaWithoutSeparator = formattedNoNota.replace(/-/g, '');
                    hiddenNoNotaInput.value = noNotaWithoutSeparator;

                    $('#noNota').html(`: <span class="badge badge-primary">${formattedNoNota}</span>`);
                    $('#tglTransaksi').html(`: <span class="badge badge-primary">${getTodayDateWithDay()}</span>`);
                });
            }
        }

        function setCreate() {
            const memberSelect = $('#id_member');
            const barangSelect = $('#barang');
            const hargaSelect = $('#harga');
            const addButton = document.getElementById('add-button');
            const tableBody = document.querySelector('.modal-body table tbody');
            const subtotalFooter = document.querySelector('.modal-body tfoot th[colspan="5"] + th');
            const metodeSelect = document.getElementById('metode');
            const uangBayarInput = document.getElementById('uang-bayar-input');
            const kembalianText = document.getElementById('kembalian-text');
            const kembalianAmount = document.getElementById('kembalian-amount');
            let subtotal = 0;
            let hiddenUangBayar = document.getElementById('hiddenUangBayar');
            let getStock = 0;

            function updateRowNumbers() {
                const rows = tableBody.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    row.children[1].textContent = index + 1;
                });
            }

            memberSelect.select2().on('change', function() {
                barangSelect.prop('disabled', !this.value).val(null).trigger('change');
                barangSelect.data('select2').$container.find('.select2-selection__placeholder').text(
                    'Pilih Barang');

                hargaSelect.prop('disabled', true).val(null).trigger('change');
                hargaSelect.data('select2').$container.find('.select2-selection__placeholder').text(
                    'Pilih Barang terlebih dahulu');

                document.getElementById('hiddenMember').value = memberSelect.val();
            });

            barangSelect.select2().on('change', function() {
                const selectedBarang = $(this).find(':selected');
                const memberId = memberSelect.val();
                const barangId = selectedBarang.val();

                if (memberId && barangId) {
                    hargaSelect.html('<option value="">Loading...</option>').prop('disabled', true).trigger(
                        'change');
                    fetch(`/admin/kasir/get-filtered-harga?id_member=${memberId}&id_barang=${barangId}`)
                        .then(response => response.json())
                        .then(data => {
                            hargaSelect.empty();
                            if (Array.isArray(data.filteredHarga)) {
                                data.filteredHarga.forEach(harga => {
                                    if (harga) {
                                        hargaSelect.append(new Option(harga, harga));
                                    }
                                });
                            } else if (data.filteredHarga) {
                                hargaSelect.append(new Option(data.filteredHarga, data.filteredHarga));
                            }

                            if (hargaSelect.children().length === 1) {
                                hargaSelect.val(hargaSelect.children().first().val());
                            } else {
                                hargaSelect.prepend(new Option('~Masukkan Harga~', ''));
                            }

                            hargaSelect.data('select2').$container.find('.select2-selection__placeholder').text(
                                'Pilih Harga');
                            hargaSelect.prop('disabled', hargaSelect.children().length === 0).trigger(
                                'change');
                            getStock = data.stock || 0;
                        })
                        .catch(error => console.error('Error fetching filtered harga:', error));
                }
            });

            hargaSelect.select2();

            function toggleMemberSelectDisabled() {
                memberSelect.prop('disabled', tableBody.children.length > 0);
            }

            let hargaBarangTerpilih = {};

            addButton.addEventListener('click', function() {
                const idBarang = barangSelect.val();
                const selectedBarang = barangSelect.find(':selected');
                const selectedHarga = hargaSelect.val();
                const stock = parseInt(selectedBarang.data('stock'));
                const harga = parseInt(selectedHarga);
                const qty = 1;

                if (!idBarang || !selectedHarga) {
                    notificationAlert('error', 'Error', 'Silakan lengkapi semua data sebelum menambahkan.');
                    return;
                }

                let existingRow = null;
                tableBody.querySelectorAll('tr').forEach(row => {
                    const rowIdBarang = row.querySelector('input[name="id_barang[]"]').value;
                    if (rowIdBarang === idBarang) {
                        existingRow = row;
                    }
                });

                if (existingRow) {
                    if (hargaBarangTerpilih[idBarang] !== undefined && hargaBarangTerpilih[idBarang] !== harga) {
                        notificationAlert('error', 'Error',
                            'Harga barang harus sama dengan harga pertama yang dipilih.');
                        return;
                    }

                    const qtyInput = existingRow.querySelector('.qty-input');
                    let currentQty = parseInt(qtyInput.value);
                    let newQty = currentQty + 1;

                    if (newQty > getStock) {
                        notificationAlert('error', 'Error', 'Stock barang tidak cukup');
                        return;
                    }

                    qtyInput.value = newQty;
                    let totalHarga = harga * newQty;

                    subtotal += harga;
                    existingRow.querySelector('.total-harga').textContent = `Rp ${totalHarga.toLocaleString()}`;
                    subtotalFooter.textContent = `Rp ${subtotal.toLocaleString()}`;

                } else {
                    if (hargaBarangTerpilih[idBarang] === undefined) {
                        hargaBarangTerpilih[idBarang] = harga;
                    } else if (hargaBarangTerpilih[idBarang] !== harga) {
                        notificationAlert('error', 'Error',
                            'Harga barang harus sama dengan harga pertama yang dipilih.');
                        return;
                    }

                    let totalHarga = harga * qty;
                    subtotal += totalHarga;
                    subtotalFooter.textContent = `Rp ${subtotal.toLocaleString()}`;

                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-btn"><i class="fa fa-trash-alt"></i></button></td>
                        <td class="text-center"></td>
                        <td><input type="hidden" name="id_barang[]" value="${idBarang}">${selectedBarang.text()}</td>
                        <td>
                            <input type="number" class="form-control qty-input" name="qty[]" value="${qty}" min="1" max="${getStock}">
                            <small class="text-danger">Max: ${getStock}</small>
                        </td>
                        <td><input type="hidden" name="harga[]" value="${harga}">Rp ${harga.toLocaleString()}</td>
                        <td class="total-harga">Rp ${totalHarga.toLocaleString()}</td>
                    `;
                    tableBody.appendChild(newRow);

                    const qtyInput = newRow.querySelector('.qty-input');

                    qtyInput.addEventListener('input', function() {
                        let newQty = parseInt(this.value) || 1;
                        newQty = Math.min(Math.max(1, newQty), getStock);
                        this.value = newQty;

                        let newTotalHarga = harga * newQty;
                        subtotal += newTotalHarga - totalHarga;
                        totalHarga = newTotalHarga;

                        newRow.querySelector('.total-harga').textContent =
                            `Rp ${totalHarga.toLocaleString()}`;
                        subtotalFooter.textContent = `Rp ${subtotal.toLocaleString()}`;
                        updateKembalian();
                    });

                    qtyInput.addEventListener('blur', function() {
                        if (parseInt(this.value) < 1 || isNaN(this.value)) {
                            this.value = 1;
                        }
                    });

                    newRow.querySelector('.remove-btn').addEventListener('click', function() {
                        subtotal -= totalHarga;
                        subtotalFooter.textContent = `Rp ${subtotal.toLocaleString()}`;
                        newRow.remove();

                        // Cek apakah semua baris dengan idBarang ini sudah dihapus
                        const remainingRows = Array.from(tableBody.querySelectorAll('tr')).filter(row =>
                            row.querySelector('input[name="id_barang[]"]')?.value === idBarang
                        );

                        if (remainingRows.length === 0) {
                            delete hargaBarangTerpilih[
                                idBarang]; // Hapus batasan harga jika semua item dihapus
                        }

                        updateRowNumbers();
                        toggleMemberSelectDisabled();
                        updateKembalian();
                    });

                    updateRowNumbers();
                    toggleMemberSelectDisabled();
                }

                barangSelect.val(null).trigger('change');
                hargaSelect.val(null).trigger('change');
            });

            document.querySelector('form').addEventListener('submit', function() {
                document.getElementById('hiddenNoNota').value = document.getElementById('noNota')
                    .textContent;
                document.getElementById('hiddenKembalian').value = kembalianAmount.textContent;
                document.getElementById('hiddenMember').value = memberSelect.val();
            });

            function updateKembalian() {
                const uangBayar = hiddenUangBayar.value || 0;
                const kembalian = uangBayar - subtotal;

                if (kembalian >= 0) {
                    kembalianText.textContent = 'Kembalian';
                    kembalianAmount.textContent = `Rp ${kembalian >= 0 ? kembalian.toLocaleString() : 0}`;
                    document.getElementById('hiddenKembalian').value = kembalian;
                    document.getElementById('hiddenMinus').value = '';
                } else {
                    let math = Math.abs(kembalian)
                    kembalianText.textContent = 'Sisa Pembayaran';
                    kembalianAmount.textContent = `Rp ${math.toLocaleString()}`;
                    document.getElementById('hiddenMinus').value = Math.abs(kembalian);
                    document.getElementById('hiddenKembalian').value = '';
                }
            }

            uangBayarInput.addEventListener('input', function() {
                let value = this.value.replace(/[^0-9]/g, '');
                hiddenUangBayar.value = value;
                this.value = value ? parseInt(value).toLocaleString() : '';
                updateKembalian();
            });

            metodeSelect.addEventListener('change', function() {
                const isTunai = metodeSelect.value === "Tunai";
                document.getElementById('uang-bayar-row').style.display = isTunai ? '' : 'none';
                document.getElementById('kembalian-row').style.display = isTunai ? '' : 'none';
                uangBayarInput.value = '';
                kembalianAmount.textContent = 'Rp 0';
            });

            metodeSelect.dispatchEvent(new Event('change'));
        }

        async function saveData() {
            $(document).on("click", "#save-data", async function(e) {
                e.preventDefault();
                const saveButton = document.getElementById('save-data');
                const form = $(this).closest("form")[0];
                const formData = new FormData(form);

                if (saveButton.disabled) return;

                swal({
                    title: "Konfirmasi",
                    text: "Apakah Anda yakin ingin menyimpan data ini?",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonColor: '#2ecc71',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then(async (willSave) => {
                    if (!willSave) return;

                    saveButton.disabled = true;
                    const originalContent = saveButton.innerHTML;
                    saveButton.innerHTML =
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan`;

                    loadingPage(true);

                    try {
                        const response = await renderAPI('POST',
                            '{{ route('transaksi.kasir.store') }}', formData);

                        loadingPage(false);

                        if (response.status === 200) {
                            swal("Berhasil!", "Data berhasil disimpan.", "success");
                            setTimeout(() => {
                                window.location.href =
                                    '{{ route('transaksi.kasir.index') }}';
                            }, 1000);
                        } else {
                            swal("Pemberitahuan", response.data.message || "Terjadi kesalahan",
                                "info");
                        }
                    } catch (error) {
                        loadingPage(false);
                        swal("Kesalahan", response.data.message ||
                            "Terjadi kesalahan saat menyimpan data.", "error");
                    } finally {
                        saveButton.disabled = false;
                        saveButton.innerHTML = originalContent;
                    }
                }).catch(function(error) {
                    let resp = error.response;
                    swal("Kesalahan", resp ||
                        "Terjadi kesalahan saat menyimpan data.", "error");
                    return resp;
                });
            });
        }

        async function initPageLoad() {
            await add();
            await setCreate();
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await filterList();
            await selectFormat('#id_member', 'Pilih Member', false);
            await selectData(selectOptions);
            await selectFormat('#harga', 'Pilih Member terlebih dahulu', true);
            await saveData();
        }
    </script>
@endsection
