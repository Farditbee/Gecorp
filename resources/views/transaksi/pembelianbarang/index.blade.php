@extends('layouts.main')

@section('title')
    Pembelian Barang
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <style>
        #tgl_nota[readonly] {
            background-color: white !important;
            cursor: pointer !important;
            color: inherit !important;
        }

        .custom-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 10px;
        }

        .custom-left {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .custom-btn-tambah-wrap {
            flex: 1 1 auto;
        }

        .custom-form-import {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .custom-input-file {
            padding: 8px;
            border: 1px solid #ccc;
            background-color: #fff;
            border-radius: 4px;
            flex: 1 1 auto;
        }

        .custom-btn-import {
            flex: 0 0 auto;
            white-space: nowrap;
        }

        .custom-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 10px;
            flex: 0 0 auto;
        }

        .custom-limit-page {
            flex: 0 0 auto;
        }

        .custom-search {
            flex: 0 0 auto;
            width: 200px;
        }

        @media (max-width: 767.98px) {
            .custom-header {
                flex-direction: column;
                align-items: stretch;
            }

            .custom-left {
                flex-direction: column;
                align-items: stretch;
            }

            .custom-btn-tambah-wrap {
                width: 100%;
            }

            .custom-form-import {
                flex-direction: row;
                justify-content: space-between;
                width: 100%;
            }

            .custom-input-file {
                flex: 1 1 65%;
            }

            .custom-btn-import {
                flex: 1 1 30%;
            }

            .custom-right {
                flex-direction: row;
                justify-content: space-between;
                width: 100%;
                margin-top: 10px;
            }

            .custom-limit-page {
                flex: 1 1 25%;
            }

            .custom-search {
                flex: 1 1 70%;
            }

            .custom-btn-tambah {
                width: 100%;
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
                        <div class="card-header custom-header">
                            <div class="custom-left">
                                <button class="btn btn-primary mb-2 mb-lg-0 text-white add-data custom-btn-tambah"
                                    data-container="body" data-toggle="tooltip" data-placement="top"
                                    title="Tambah Data Pembelian Barang">
                                    <i class="fa fa-plus-circle"></i> Tambah
                                </button>
                                <button class="btn-dynamic btn btn-outline-primary custom-btn-tambah" type="button"
                                    data-toggle="collapse" data-target="#filter-collapse" aria-expanded="false"
                                    aria-controls="filter-collapse"data-container="body" data-toggle="tooltip"
                                    data-placement="top" title="Filter Pembelian Barang">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                                <form action="{{ route('master.pembelianbarang.import') }}" method="POST"
                                    enctype="multipart/form-data" class="custom-form-import">
                                    @csrf
                                    <input type="file" name="file" class="custom-input-file" accept=".xlsx" required>
                                    <button type="submit" class="btn btn-success custom-btn-import">
                                        <i class="fa fa-file-import"></i> Import
                                    </button>
                                </form>
                            </div>
                            <div class="custom-right">
                                <div class="custom-limit-page">
                                    <select name="limitPage" id="limitPage" class="form-control">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                    </select>
                                </div>
                                <div class="custom-search">
                                    <input id="tb-search" class="tb-search form-control" type="search" name="search"
                                        placeholder="Cari Data" aria-label="search">
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <x-adminlte-alerts />
                            <div class="collapse mt-2 pl-4" id="filter-collapse">
                                <form id="custom-filter" class="d-flex justify-content-start align-items-center">
                                    <input class="form-control w-25 mb-2" type="text" id="daterange" name="daterange"
                                        placeholder="Pilih rentang tanggal">
                                    <button class="btn btn-info mr-2 h-100 mb-2 mx-2" id="tb-filter" type="submit">
                                        <i class="fa fa-magnifying-glass mr-2"></i>Cari
                                    </button>
                                    <button type="button" class="btn btn-secondary mr-2 h-100 mb-2" id="tb-reset">
                                        <i class="fa fa-rotate mr-2"></i>Reset
                                    </button>
                                </form>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-striped m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">Status</th>
                                                <th class="text-wrap align-top">No. Nota</th>
                                                <th class="text-wrap align-top">Tanggal Nota</th>
                                                <th class="text-wrap align-top">Supplier</th>
                                                <th class="text-wrap align-top">Total Item</th>
                                                <th class="text-wrap align-top">Total Harga</th>
                                                <th class="text-wrap align-top">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listData">
                                        </tbody>
                                        <tfoot></tfoot>
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

                <div id="modal-form" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                    aria-labelledby="modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-lgs">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-title"></h5>
                                <button type="button" class="btn-close reset-all close" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="fa fa-xmark"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="custom-tab">
                                        <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="tambah-tab" data-toggle="tab"
                                                    href="#tambah" role="tab" aria-controls="tambah"
                                                    aria-selected="true">Tambah Pembelian</a>
                                                <a class="nav-item nav-link disabled" id="detail-tab" data-toggle="tab"
                                                    href="#detail" role="tab" aria-controls="detail"
                                                    aria-selected="true">Detail Pembelian</a>
                                            </div>
                                        </nav>
                                        <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="tambah" role="tabpanel"
                                                aria-labelledby="tambah-tab">
                                                <br>
                                                <form id="form-tambah-pembelian"
                                                    action="{{ route('transaksi.pembelianbarang.store') }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="id_supplier" class="form-control-label">Nama
                                                                    Supplier</label>
                                                                <select name="id_supplier" id="id_supplier">
                                                                    <option value="" selected>Pilih Supplier</option>
                                                                    @foreach ($suppliers as $supplier)
                                                                        <option value="{{ $supplier->id }}">
                                                                            {{ $supplier->nama_supplier }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <label for="id_supplier" class="form-control-label">Tanggal
                                                                Nota</label>
                                                            <input class="form-control tgl_nota" type="text"
                                                                name="tgl_nota" id="tgl_nota"
                                                                placeholder="Pilih tanggal" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="no_nota" class=" form-control-label">Nomor Nota<span
                                                                style="color: red">*</span></label>
                                                        <input type="number" id="no_nota" name="no_nota"
                                                            placeholder="Contoh : 001" class="form-control">
                                                    </div>
                                                    <button type="submit" style="float: right" id="save-btn"
                                                        class="btn btn-primary">
                                                        <span id="save-btn-text"><i class="fa fa-save"></i> Lanjut</span>
                                                        <span id="save-btn-spinner"
                                                            class="spinner-border spinner-border-sm" role="status"
                                                            style="display: none;"></span>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="detail" role="tabpanel"
                                                aria-labelledby="detail-tab">
                                                <br>
                                                <ul class="list-group list-group-flush my-4">
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <h5><i class="fa fa-home"></i> Nomor Nota</h5> <span
                                                            id="no-nota" class="badge badge-secondary"></span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <h5><i class="fa fa-globe"></i> Nama Supplier</h5> <span
                                                            id="nama-supplier" class="badge badge-secondary"></span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <h5><i class="fa fa-calendar-day"></i> &nbsp;Tanggal Nota</h5>
                                                        <span id="tgl-nota" class="badge badge-secondary"></span>
                                                    </li>
                                                </ul>
                                                <br>
                                                <form id="form-update-pembelian"
                                                    action="{{ route('transaksi.pembelianbarang.update', ':id') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div id="item-container">
                                                        <div class="item-group">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <label for="id_barang"
                                                                            class="form-control-label">Nama Barang<span
                                                                                style="color: red">*</span></label>
                                                                        <select name="id_barangs[]" id="id_barang"
                                                                            class="id-barang"
                                                                            data-placeholder="Pilih Barang...">
                                                                            <option value="" disabled selected
                                                                                required>Pilih Barang</option>
                                                                            @foreach ($barang as $brg)
                                                                                <option value="{{ $brg->id }}"
                                                                                    data-barcode="{{ $brg->barcode }}">
                                                                                    {{ $brg->nama_barang }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label for="jml_item"
                                                                            class="form-control-label">Jumlah Item<span
                                                                                style="color: red">*</span></label>
                                                                        <input type="number" id="jml_item"
                                                                            min="1" name="qty[]"
                                                                            placeholder="Contoh: 16"
                                                                            class="form-control jumlah-item">
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <label for="harga_barang"
                                                                            class="form-control-label">Harga Barang<span
                                                                                style="color: red">*</span></label>
                                                                        <input type="number" id="harga_barang"
                                                                            min="1" name="harga_barang[]"
                                                                            placeholder="Contoh: 16000"
                                                                            class="form-control harga-barang">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br><br>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="card border border-primary">
                                                                <div class="card-body">
                                                                    <p class="card-text">Detail Stock
                                                                        <strong>(GSS)</strong>
                                                                    </p>
                                                                    <p class="card-text">Stock :<strong
                                                                            class="stock">0</strong></p>
                                                                    <p class="card-text">Hpp Awal : <strong
                                                                            class="hpp-awal">Rp 0</strong></p>
                                                                    <p class="card-text">Hpp Baru : <strong
                                                                            class="hpp-baru">Rp 0</strong></p>
                                                                </div>
                                                                <button type="button" id="reset"
                                                                    style="float: right"
                                                                    class="btn btn-secondary">Reset</button>
                                                            </div>
                                                            <button type="button" id="add-item-detail"
                                                                style="float: right"
                                                                class="btn btn-secondary">Add</button>
                                                        </div>
                                                        <div class="col-6">
                                                            @foreach ($LevelHarga as $index => $level)
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span
                                                                            class="input-group-text">{{ $level->nama_level_harga }}</span>
                                                                    </div>
                                                                    <input type="hidden" name="level_nama[]"
                                                                        value="{{ $level->nama_level_harga }}">
                                                                    <div class="custom-file">
                                                                        <input type="text"
                                                                            class="form-control level-harga"
                                                                            name="level_harga[]"
                                                                            id="level_harga_{{ $index }}"
                                                                            data-index="{{ $index }}"
                                                                            data-hpp-baru="0">
                                                                        <label class="input-group-text"
                                                                            id="persen_{{ $index }}">0%</label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
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
                                                                <tbody id="tempData">
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th scope="col" colspan="5"
                                                                            style="text-align:right">SubTotal</th>
                                                                        <th scope="col" id="subtotal">Rp </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-primary pull-right"
                                                                    style="float: right">
                                                                    <i class="fa fa-dot-circle-o"></i> Simpan
                                                                </button>
                                                                <button type="button" id="cancel-button"
                                                                    class="btn btn-warning pull-right"
                                                                    style="float: right">
                                                                    <i class="fa fa-dot-circle-o"></i> Cancel
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
    </div>
@endsection

@section('asset_js')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/daterange-picker.js') }}"></script>
    <script src="{{ asset('js/daterange-custom.js') }}"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>
    <script src="{{ asset('js/flatpickr.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Pembeliang Barang';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};
        let id_pembelian_post = null;
        let idPembelianEdit = null;
        let rowGlobal = [];

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listData').html(loadingData());

            let filterParams = {};

            if (customFilter['startDate'] && customFilter['endDate']) {
                filterParams.startDate = customFilter['startDate'];
                filterParams.endDate = customFilter['endDate'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.pembelian.get') }}', {
                    page: page,
                    limit: limit,
                    ascending: ascending,
                    search: search,
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status == 200 && Array.isArray(getDataRest.data.data) && getDataRest.data
                .data.length > 0) {
                let handleDataArray = await Promise.all(
                    getDataRest.data.data.map(async item => await handleData(item))
                );
                await setListData(handleDataArray, getDataRest.data.pagination, getDataRest.data.total, getDataRest.data
                    .totals);
            } else {
                errorMessage = 'Tidak ada data';
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
            let elementData = encodeURIComponent(JSON.stringify(data));
            let status = '';
            let edit_button = '';
            let delete_button = '';
            let detail_button = '';

            if (data?.status === 'Sukses') {
                status =
                    `<span class="badge badge-success custom-badge"><i class="mx-1 fa fa-circle-check"></i>Sukses</span>`;
                detail_button = `
                    <a href="pembelianbarang/${data.id}/detail?r=${data.id}" class="p-1 btn detail-data action_button"
                        data-container="body" data-toggle="tooltip" data-placement="top"
                        title="Detail Data Nomor Nota: ${data.no_nota}"
                        data-id='${data.id}'>
                        <span class="text-dark">Detail</span>
                        <div class="icon text-info">
                            <i class="fa fa-eye"></i>
                        </div>
                    </a>`;
            } else if (data?.status === 'Gagal') {
                status =
                    `<span class="badge badge-info custom-badge"><i class="mx-1 fa fa-spinner"></i>Pending</span>`;
                edit_button = `
                <a button class="p-1 btn edit-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top" class="p-1 btn edit-data action_button"
                    title="Edit Data Nomor Nota: ${data.no_nota}"
                    data-id='${data.id}' data-name='${data.nama_supplier}' data-nota='${data.no_nota}' data-tanggal='${data.tgl_nota}'>
                    <span class="text-dark">Edit</span>
                    <div class="icon text-warning">
                        <i class="fa fa-edit"></i>
                    </div>
                </a>`;
                delete_button = `
                <a class="p-1 btn delete-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Hapus ${title} No.Nota: ${data.no_nota}" data="${elementData}">
                    <span class="text-dark">Hapus</span>
                    <div class="icon text-danger">
                        <i class="fa fa-trash"></i>
                    </div>
                </a>`;
            } else {
                status = `<span class="badge badge-info custom-badge"><i class="mx-1 fa fa-spinner"></i>Pending</span>`;
                edit_button = `
                <a button class="p-1 btn edit-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top" class="p-1 btn edit-data action_button"
                    title="Edit Data Nomor Nota: ${data.no_nota}"
                    data-id='${data.id}' data-name='${data.nama_supplier}' data-nota='${data.no_nota}' data-tanggal='${data.tgl_nota}'>
                    <span class="text-dark">Edit</span>
                    <div class="icon text-warning">
                        <i class="fa fa-edit"></i>
                    </div>
                </a>`;
                delete_button = `
                <a class="p-1 btn delete-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Hapus ${title} No.Nota: ${data.no_nota}" data="${elementData}">
                    <span class="text-dark">Hapus</span>
                    <div class="icon text-danger">
                        <i class="fa fa-trash"></i>
                    </div>
                </a>`;
            }

            let action_buttons = '';
            if (edit_button || detail_button || delete_button) {
                action_buttons = `
                <div class="d-flex justify-content-start">
                    ${detail_button ? `<div class="hovering p-1">${detail_button}</div>` : ''}
                    ${edit_button ? `<div class="hovering p-1">${edit_button}</div>` : ''}
                    ${delete_button ? `<div class="hovering p-1">${delete_button}</div>` : ''}
                </div>`;
            } else {
                action_buttons = `
                <span class="badge badge-secondary">Tidak Ada Aksi</span>`;
            }

            return {
                id: data?.id ?? '-',
                is_status: data?.status ?? '-',
                status,
                nama_supplier: data?.nama_supplier ?? '-',
                tgl_nota: data?.tgl_nota ?? '-',
                no_nota: data?.no_nota ?? '-',
                total_item: data?.total_item ?? '-',
                total_nilai: data?.total_nilai ?? '-',
                action_buttons,
            };
        }

        async function setListData(dataList, pagination, total, totals) {
            totalPage = pagination.total_pages;
            currentPage = pagination.current_page;
            let display_from = ((defaultLimitPage * (currentPage - 1)) + 1);
            let display_to = Math.min(display_from + dataList.length - 1, pagination.total);

            let getDataTable = '';
            let classCol = 'align-center text-dark text-wrap';

            dataList.forEach((element, index) => {
                let classStatus = element.is_status === 'Sukses' ? 'clickable-row' : '';
                getDataTable += `
                        <tr class="text-dark ${classStatus}" data-id="${element.id}">
                            <td class="${classCol} text-center">${display_from + index}.</td>
                            <td class="${classCol}">${element.status}</td>
                            <td class="${classCol}">${element.no_nota}</td>
                            <td class="${classCol}">${element.tgl_nota}</td>
                            <td class="${classCol}">${element.nama_supplier}</td>
                            <td class="${classCol}">${element.total_item}</td>
                            <td class="${classCol}">${element.total_nilai}</td>
                            <td class="${classCol}">${element.action_buttons}</td>
                        </tr>`;
            });

            let totalRow = `
            <tr class="bg-primary">
                <td class="${classCol}" colspan="4"></td>
                <td class="${classCol}" style="font-size: 1rem;"><strong class="text-white fw-bold">Total</strong></td>
                <td class="${classCol} text-left"><strong class="text-white" id="totalData">${totals}</strong></td>
                <td class="${classCol} text-left"><strong class="text-white" id="totalData">${total}</strong></td>
                <td class="${classCol}" colspan="3"></td>
            </tr>`;

            $('#listData').html(getDataTable);
            $('#listData').closest('table').find('tfoot').html(totalRow);

            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();

            $('.clickable-row').on('click', function(e) {
                if ($(e.target).closest('.edit-data, .detail-data, .delete-data').length) {
                    return;
                }

                let id = $(this).data('id');
                if (id) {
                    window.location.href = `pembelianbarang/${id}/detail?id=${data.id}`;
                }
            });
        }

        async function showData() {
            $(document).on("click", ".add-data", function() {
                $("#modal-title").html(`Form Pembelian Barang`);
                $("#modal-form").modal("show");

                $("form").find("input:not(#tgl_nota):not([type='hidden']), select, textarea")
                    .val("")
                    .prop("checked", false)
                    .trigger("change");

                $("#tempData").empty();

                $("#tambah-tab").removeClass("d-none").addClass("active").attr("aria-selected", "true");
                $("#tambah").addClass("show active");

                $("#detail-tab").addClass("disabled").removeClass("active").attr("aria-selected", "false").css({
                    "pointer-events": "none",
                    "opacity": "0.6"
                });
                $("#detail").removeClass("show active");
            });
        }

        async function editData() {
            $(document).on("click", ".edit-data", async function() {
                let id = $(this).attr("data-id");
                let nota = $(this).attr("data-nota");
                let tanggal = $(this).attr("data-tanggal");
                let nama = $(this).attr("data-name");
                idPembelianEdit = id;

                $("#modal-title").html(`Form Edit Pembelian No. Nota: ${nota}`);
                $("#modal-form").modal("show");

                $("form").find("input:not(#tgl_nota):not([type='hidden']), select, textarea")
                    .val("")
                    .prop("checked", false)
                    .trigger("change");
                var updateFormAction =
                    "{{ route('transaksi.pembelianbarang.update', ':id') }}";
                updateFormAction = updateFormAction.replace(':id', id);
                $('#form-update-pembelian').attr('action', updateFormAction);
                $("#tempData").empty();
                $('#subtotal').empty();
                $("#no-nota").html(nota);
                $("#tgl-nota").html(tanggal);
                $("#nama-supplier").html(nama);
                $("#tambah-tab").removeClass("active").addClass("d-none");
                $("#tambah").removeClass("show active");
                $("#detail-tab").removeClass("disabled").addClass("active").css({
                    "pointer-events": "auto",
                    "opacity": "1",
                });
                $("#detail").addClass("show active");
                $("#submit-reture").removeClass("d-none");

                try {
                    const response = await renderAPI('GET', '{{ route('master.temppembelian.get') }}', {
                        id_pembelian: id
                    });
                    if (response && response.status === 200) {
                        const dataItems = response.data.data;
                        rowGlobal = dataItems;
                        let totalHargaAll = 0;

                        dataItems.forEach(item => {
                            const totalHarga = item.qty * item.harga_barang;
                            totalHargaAll += totalHarga;

                            $("#tempData").append(`
                        <tr data-barang="${item.id_barang}">
                            <td><button onclick="removeRow({id_pembelian: '${idPembelianEdit}', id_barang: '${item.id_barang}' })" type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-circle-minus mr-1"></i>Remove</button></td>
                            <td class="numbered">${$("#tempData tr").length + 1}</td>
                            <td><input type="hidden" name="id_barang[]" value="${item.id_barang}">${item.nama_barang}</td>
                            <td><input type="hidden" name="qty[]" value="${item.qty}">${item.qty}</td>
                            <td><input type="hidden" name="harga_barang[]" value="${item.harga_barang}">Rp ${item.harga_barang.toLocaleString('id-ID')}</td>
                            <td>${formatRupiah(totalHarga)}</td>
                        </tr>
                    `);
                        });

                        $("#subtotal").html(formatRupiah(totalHargaAll));
                    } else {
                        notificationAlert('info', 'Pemberitahuan', 'Tidak ada data sementara ditemukan.');
                    }
                } catch (error) {
                    const errorMessage = error?.response?.data?.message ||
                        'Terjadi kesalahan saat memuat data sementara.';
                    notificationAlert('danger', 'Error', errorMessage);
                }
            });
        }

        async function deleteData() {
            $(document).on("click", ".delete-data", async function() {
                let rawData = $(this).attr("data");
                let data = JSON.parse(decodeURIComponent(rawData));

                swal({
                    title: `Hapus ${title} No Nota: ${data.no_nota}`,
                    text: "Apakah anda yakin?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Tidak, Batal!",
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    confirmButtonClass: "btn btn-danger",
                    cancelButtonClass: "btn btn-secondary",
                }).then(async (result) => {
                    let postDataRest = await renderAPI(
                        'DELETE',
                        `/admin/pembelianbarang/${data.id}/delete`, {}
                    ).then(function(response) {
                        return response;
                    }).catch(function(error) {
                        let resp = error.response;
                        return resp;
                    });

                    if (postDataRest.status == 200) {
                        setTimeout(function() {
                            getListData(defaultLimitPage, currentPage, defaultAscending,
                                defaultSearch, customFilter);
                        }, 500);
                        notificationAlert('success', 'Pemberitahuan', postDataRest.data
                            .message);
                    }
                }).catch(swal.noop);
            })
        }

        async function addTemporaryField() {
            try {
                let idBarang = document.getElementById('id_barang').value;
                let namaBarang = document.getElementById('id_barang').selectedOptions[0].text;
                let qty = parseInt(document.getElementById('jml_item').value);
                let hargaBarang = parseInt(document.getElementById('harga_barang').value);
                let levelHarga = Array.from(document.querySelectorAll('.level-harga')).map((input, index) => {
                    return `Level ${index + 1} : ${input.value}`;
                });

                if (!idBarang || !qty || !hargaBarang) {
                    notificationAlert('error', 'Pemberitahuan', 'Pastikan semua data telah diisi dengan benar.');
                    return;
                }

                let formData = {
                    id_pembelian: id_pembelian_post || idPembelianEdit,
                    id_barang: idBarang,
                    nama_barang: namaBarang,
                    qty: qty,
                    harga_barang: hargaBarang,
                    level_harga: levelHarga
                };

                const postData = await renderAPI('POST', '{{ route('transaksi.temp.pembelianbarang') }}', formData);

                if (postData.status >= 200 && postData.status < 300) {
                    const response = postData.data.data;
                    setTimeout(async function() {
                        await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                            customFilter);
                    }, 500);
                } else {
                    notificationAlert('info', 'Pemberitahuan', postData.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                loadingPage(false);
                const resp = error.response || {};
                notificationAlert('error', 'Kesalahan', resp.data?.message || 'Terjadi kesalahan saat menyimpan data.');
            }
        }

        function removeRow(rowData) {
            const {
                id_pembelian,
                id_barang
            } = rowData;

            deleteRowTable({
                id_pembelian,
                id_barang
            }).then(() => {
                rowGlobal = rowGlobal.filter(row => row.id_barang !== id_barang);
                updateSubtotalAfterRemoval();
            });
        }

        async function deleteRowTable(data) {
            try {
                const postDataRest = await renderAPI(
                    'DELETE',
                    '{{ route('master.temppembelian.hapus') }}',
                    data
                );
                if (postDataRest && postDataRest.status === 200) {
                    // Hapus baris di DOM setelah berhasil dihapus dari API
                    const row = document.querySelector(`tr[data-barang="${data.id_barang}"]`);
                    if (row) {
                        row.remove();
                    }
                }
            } catch (error) {
                const resp = error.response;
                const errorMessage = resp?.data?.message || 'Terjadi kesalahan saat menghapus data.';
                notificationAlert('error', 'Kesalahan', errorMessage);
            }
        }

        // Fungsi untuk menghitung ulang subtotal setelah baris dihapus
        function updateSubtotalAfterRemoval() {
            let subtotal = 0;
            document.querySelectorAll('.table-bordered tbody tr').forEach((row) => {
                // Menambah subtotal dengan harga total dari setiap baris
                let hargaPerItem = parseInt(row.children[5].textContent.replace(/\D/g, '')) ||
                    0; // Harga total per item
                subtotal += hargaPerItem; // Menambahkan harga ke subtotal
            });

            // Update total harga di footer tabel
            document.querySelector('.table-bordered tfoot tr th:last-child').textContent =
                `Rp ${subtotal.toLocaleString('id-ID')}`;
        }


        function updateSubTotal() {
            let subtotal = 0;
            $(".total-harga").each(function() {
                subtotal += parseInt($(this).data("harga"));
            });
            $("#subtotal").html(formatRupiah(subtotal));
        }

        async function addData() {
            let subtotal = 0;
            let addedItems = new Set();

            let initialHppBaru = 0;
            let initialStock = 0;
            let initialHppAwal = 0;

            let debounceTimer;
            const debounceDelay = 500;

            function toggleInputFields(disabled) {
                document.getElementById('jml_item').disabled = disabled;
                document.getElementById('harga_barang').disabled = disabled;
                if (disabled) {
                    document.getElementById('jml_item').value = '';
                    document.getElementById('harga_barang').value = '';
                }
            }

            function checkInputFields() {
                let idBarang = document.getElementById('id_barang').value;
                let isItemAdded = addedItems.has(idBarang);
                toggleInputFields(isItemAdded);
            }

            document.getElementById('add-item-detail').addEventListener('click', async function() {
                let btn = this;
                btn.disabled = true;
                let originalText = btn.innerHTML;
                btn.innerHTML = `<i class="fa fa-spinner fa-spin"></i> Proses...`;

                let idBarang = document.getElementById('id_barang').value;
                let namaBarang = document.getElementById('id_barang').selectedOptions[0]?.text || '';
                let qty = parseInt(document.getElementById('jml_item').value);
                let harga = parseInt(document.getElementById('harga_barang').value);
                let isBarangExist = rowGlobal.some(row => row.id_barang === idBarang);

                if (isBarangExist) {
                    notificationAlert('error', 'Pemberitahuan', 'Barang dengan ID yang sama sudah ada!');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    return;
                }
                if (!idBarang) {
                    notificationAlert('error', 'Pemberitahuan', 'Silakan pilih barang terlebih dahulu.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    return;
                }

                if (addedItems.has(idBarang)) {
                    notificationAlert('error', 'Pemberitahuan', 'Barang ini sudah ditambahkan sebelumnya.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    return;
                }

                if (!qty || !harga) {
                    notificationAlert('error', 'Pemberitahuan', 'Jumlah dan harga barang harus diisi.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    return;
                }

                let allLevelsFilled = true;
                document.querySelectorAll('.level-harga').forEach((input) => {
                    if (!input.value) {
                        allLevelsFilled = false;
                    }
                });

                if (!allLevelsFilled) {
                    notificationAlert('error', 'Pemberitahuan',
                        'Harap atur level harga ! jika tidak, silahkan isi dengan "0"');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    return;
                }

                await addTemporaryField(id_pembelian_post);
                addedItems.add(idBarang);
                document.querySelector(`#id_barang option[value="${idBarang}"]`).setAttribute('hidden',
                    true);

                // Hitung ulang subtotal sebelum menambahkan item baru
                let subtotal = 0;
                document.querySelectorAll('.table-bordered tbody tr').forEach((row) => {
                    let hargaPerItem = parseInt(row.children[5].textContent.replace(/\D/g, '')) ||
                        0; // Harga total per item
                    subtotal += hargaPerItem; // Menambahkan harga ke subtotal
                });

                let totalHarga = qty * harga; // Total harga untuk item baru
                subtotal += totalHarga; // Update subtotal dengan item baru

                // Generate input untuk level harga
                let levelHargaInputs = '';
                document.querySelectorAll('.level-harga').forEach((input) => {
                    levelHargaInputs +=
                        `<input type="hidden" name="level_harga[${idBarang}][]" value="${input.value}">`;
                });

                // Menambahkan baris baru ke tabel
                let row = `
                    <tr>
                        <td><button onclick="removeRow({id_pembelian: '${id_pembelian_post}', id_barang: '${idBarang}' })" type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-circle-minus mr-1"></i>Remove</button></td>
                        <td class="numbered">${document.querySelectorAll('.table-bordered tbody tr').length + 1}</td>
                        <td><input type="hidden" name="id_barang[]" value="${idBarang}">${namaBarang}</td>
                        <td><input type="hidden" name="qty[]" value="${qty}">${qty}</td>
                        <td><input type="hidden" name="harga_barang[]" value="${harga}">Rp ${harga.toLocaleString('id-ID')}</td>
                        <td>Rp ${totalHarga.toLocaleString('id-ID')}</td>
                        ${levelHargaInputs}
                    </tr>
                `;

                // Menyisipkan baris baru ke dalam tabel
                document.querySelector('.table-bordered tbody').insertAdjacentHTML('beforeend', row);

                // Update total harga di footer tabel
                document.querySelector('.table-bordered tfoot tr th:last-child').textContent =
                    `Rp ${subtotal.toLocaleString('id-ID')}`;

                // Menonaktifkan input dan reset formulir
                toggleInputFields(true);
                document.getElementById('id_barang').value = '';
                const tomSelect = document.getElementById('id_barang').tomselect;
                if (tomSelect) {
                    tomSelect.clear();
                }

                resetFields();
                updateNumbers();
                $('#id_barang').val(null).trigger('change');

                btn.innerHTML = originalText; // Kembalikan teks asli
                btn.disabled = false; // Aktifkan kembali tombol
            });

            $('#form-tambah-pembelian').on('submit', function(e) {
                e.preventDefault();

                $('#save-btn-text').hide();
                $('#save-btn-spinner').show(); // Tampilkan spinner
                $('#save-btn').prop('disabled', true); // Nonaktifkan tombol

                var formData = $(this).serialize(); // Mengambil data form

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    success: function(response) {
                        var id_pembelian = response.id_pembelian;
                        id_pembelian_post = response.id_pembelian;
                        var updateFormAction =
                            "{{ route('transaksi.pembelianbarang.update', ':id') }}";
                        updateFormAction = updateFormAction.replace(':id', id_pembelian);
                        $('#form-update-pembelian').attr('action', updateFormAction);

                        $('#no-nota').text(response.no_nota); // No Nota
                        $('#nama-supplier').text(response.nama_supplier); // Nama Supplier
                        $('#tgl-nota').text(response.tgl_nota); // Tanggal Nota

                        $('#save-btn-text').show(); // Tampilkan teks "Lanjut" lagi
                        $('#save-btn-spinner').hide(); // Sembunyikan spinner
                        $('#save-btn').prop('disabled', false); // Aktifkan kembali tombol submit

                        $('#tambah-tab').addClass('disabled');
                        $('#tambah-tab').removeClass('active');
                        $('#tambah').removeClass('show active');
                        $('#detail').addClass('show active');
                        $('#detail-tab').addClass('active');
                        $('#detail-tab').removeClass('disabled');

                        setTimeout(async function() {
                            await getListData(defaultLimitPage, currentPage,
                                defaultAscending, defaultSearch, customFilter);
                        }, 500);

                        // Kembalikan tombol ke keadaan awal
                        $('#save-btn-text').show();
                        $('#save-btn-spinner').hide();
                        $('#save-btn').prop('disabled', false); // Aktifkan kembali tombol
                    },
                    error: function(xhr) {
                        // Cek apakah ada pesan error dalam response
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                            xhr.responseJSON.message :
                            'Terjadi Kesalahan';
                        notificationAlert('error', 'Pemberitahuan', errorMessage);

                        // Kembalikan tombol ke keadaan awal jika terjadi error
                        $('#save-btn-text').show();
                        $('#save-btn-spinner').hide();
                        $('#save-btn').prop('disabled', false); // Aktifkan kembali tombol
                    }
                });
            });

            document.querySelector('.table-bordered tbody').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    let row = e.target.closest('tr');
                    let idBarang = row.querySelector('input[name="id_barang[]"]').value;
                    let qty = row.querySelector('input[name="qty[]"]').value;
                    let harga = row.querySelector('input[name="harga_barang[]"]').value;
                    let totalHarga = parseInt(row.querySelector('td:nth-child(6)').textContent.replace(
                        /\D/g, ''));

                    subtotal -= totalHarga;
                    row.remove();

                    addedItems.delete(idBarang);

                    let optionElement = document.querySelector(`#id_barang option[value="${idBarang}"]`);
                    if (optionElement) {
                        optionElement.removeAttribute('hidden');
                    } else {
                        console.log(`Opsi dengan id ${idBarang} tidak ditemukan di dropdown.`);
                    }

                    document.querySelector('.table-bordered tfoot tr th:last-child').textContent =
                        `Rp ${subtotal.toLocaleString('id-ID')}`;

                    updateNumbers();

                    if (!addedItems.size) {
                        toggleInputFields(false);
                    } else {
                        checkInputFields();
                    }
                }
            });

            document.getElementById('id_barang').addEventListener('change', function() {
                checkInputFields();
                document.getElementById('jml_item').value = '';
                document.getElementById('harga_barang').value = '';

                let idBarang = this.value;

                if (idBarang) {

                    fetch(`/admin/get-stock-details/${idBarang}`)
                        .then(response => response.json())
                        .then(data => {
                            initialHppBaru = data.hpp_baru || 0;
                            initialStock = data.stock || 0;
                            initialHppAwal = data.hpp_awal || 0;

                            document.querySelector('.card-text strong.stock').textContent = initialStock
                                .toLocaleString('id-ID');
                            document.querySelector('.card-text strong.hpp-awal').textContent =
                                `Rp ${initialHppAwal.toLocaleString('id-ID')}`;
                            document.querySelector('.card-text strong.hpp-baru').textContent =
                                `Rp ${initialHppBaru.toLocaleString('id-ID')}`;

                            document.querySelectorAll('.level-harga').forEach(function(input) {
                                input.setAttribute('data-hpp-baru', initialHppBaru);
                            });

                            originalLevelHarga = {
                                ...data.level_harga
                            };

                            document.querySelectorAll('input[name="level_nama[]"]').forEach(function(
                                namaLevelInput, index) {
                                const namaLevel = namaLevelInput.value;
                                const inputField = document.querySelectorAll(
                                    'input[name="level_harga[]"]')[index];
                                const persenElement = document.querySelector(
                                    `#persen_${index}`);

                                if (data.level_harga.hasOwnProperty(namaLevel)) {
                                    inputField.value = data.level_harga[namaLevel] || 0;
                                    let levelHarga = parseFloat(inputField.value) || 0;
                                    let persen = 0;
                                    if (initialHppAwal > 0) {
                                        persen = ((levelHarga - initialHppAwal) /
                                            initialHppAwal) * 100;
                                    }
                                    persenElement.textContent = `${persen.toFixed(2)}%`;
                                } else {
                                    inputField.value = 0;
                                    persenElement.textContent = '0%';
                                }
                            });

                            setupInputListeners(data.total_harga_success, data.total_qty_success);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    resetFields();
                }
            });

            document.querySelectorAll('.level-harga').forEach(function(input) {
                input.addEventListener('input', function() {
                    let hppAwal = initialHppAwal || 0;
                    let hppBaru = parseFloat(input.getAttribute('data-hpp-baru')) || 0;
                    let levelHarga = parseFloat(this.value) || 0;

                    let persen = 0;

                    if (hppBaru === 0 && hppAwal > 0) {
                        persen = ((levelHarga - hppAwal) / hppAwal) * 100;
                    } else if (hppBaru > 0) {
                        persen = ((levelHarga - hppBaru) / hppBaru) * 100;
                    }

                    const index = this.getAttribute('data-index');
                    const persenElement = document.getElementById(`persen_${index}`);
                    if (persenElement) {
                        persenElement.textContent = `${persen.toFixed(2)}%`;
                    }
                });
            });

            function setupInputListeners(totalHarga, totalQty) {
                document.querySelectorAll('.jumlah-item, .harga-barang').forEach(function(input) {
                    input.addEventListener('input', function() {
                        calculateHPP(totalHarga, totalQty);
                    });
                });
            }

            document.querySelectorAll('.jumlah-item, .harga-barang').forEach(function(input) {
                input.addEventListener('input', function() {
                    calculateHPP(0,
                        0
                    );
                });
            });

            async function calculateHPP(totalHarga, totalQty) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(async () => {
                    let id_barang = parseFloat(document.querySelector('.id-barang').value) || 0;
                    let jumlah = parseFloat(document.querySelector('.jumlah-item').value) || 0;
                    let harga = parseFloat(document.querySelector('.harga-barang').value) || 0;

                    let hppAwal = initialHppAwal || 0;

                    if (jumlah > 0 && harga > 0) {
                        try {
                            let getDataRest = await renderAPI('GET',
                                '{{ route('master.stock.hpp_barang') }}', {
                                    id_barang: id_barang,
                                    qty: jumlah,
                                    harga: harga,
                                });

                            if (getDataRest && getDataRest.status === 200) {
                                let finalHpp = getDataRest.data.hpp_baru;

                                document.querySelector('.card-text strong.hpp-baru').textContent =
                                    `Rp ${Math.round(finalHpp).toLocaleString('id-ID')}`;

                                document.querySelectorAll('.level-harga').forEach(function(input) {
                                    input.setAttribute('data-hpp-baru', finalHpp);
                                });

                                updatePercentages(finalHpp);
                                return;
                            }
                        } catch (error) {
                            let finalHpp = harga;

                            document.querySelector('.card-text strong.hpp-baru').textContent =
                                `Rp ${Math.round(finalHpp).toLocaleString('id-ID')}`;

                            document.querySelectorAll('.level-harga').forEach(function(input) {
                                input.setAttribute('data-hpp-baru', finalHpp);
                            });

                            updatePercentages(finalHpp);
                            return;
                        }
                    }

                    document.querySelector('.card-text strong.hpp-baru').textContent =
                        `Rp ${initialHppBaru.toLocaleString('id-ID')}`;

                    document.querySelectorAll('.level-harga').forEach(function(input) {
                        input.setAttribute('data-hpp-baru', hppAwal);
                    });

                    updatePercentages(hppAwal);
                }, debounceDelay);
            }

            function updatePercentages(hpp) {
                document.querySelectorAll('.level-harga').forEach(function(input) {
                    let levelHarga = parseFloat(input.value) || 0;
                    let persen = 0;
                    if (hpp > 0) {
                        persen = ((levelHarga - hpp) / hpp) * 100;
                    }

                    const persenElement = document.getElementById(
                        `persen_${input.getAttribute('data-index')}`);
                    if (persenElement) {
                        persenElement.textContent = `${persen.toFixed(2)}%`;
                    }
                });
            }

            function updateNumbers() {
                document.querySelectorAll('.table-bordered tbody tr .numbered').forEach((element, index) => {
                    element.textContent = index + 1;
                });
            }

            function resetFields() {
                document.querySelector('.card-text strong.stock').textContent = '0';
                document.querySelector('.card-text strong.hpp-awal').textContent = 'Rp 0';
                document.querySelector('.card-text strong.hpp-baru').textContent = 'Rp 0';

                document.querySelectorAll('.level-harga').forEach(function(input) {
                    input.value = '';
                    const persenElement = document.getElementById(
                        `persen_${input.getAttribute('data-index')}`);
                    if (persenElement) {
                        persenElement.textContent = '0%';
                    }
                });
            }

            function resetFieldsToOriginal() {
                let currentHppBaru = parseFloat(document.querySelector('.card-text strong.hpp-baru').textContent
                    .replace(/\D/g, ''));
                let hppUntukPerhitungan = initialHppAwal;
                let awal = 0;

                if (currentHppBaru && currentHppBaru > 0) {
                    hppUntukPerhitungan = currentHppBaru;
                }

                document.querySelector('.jumlah-item').value = '';
                document.querySelector('.harga-barang').value = '';

                document.querySelector('.card-text strong.stock').textContent = initialStock.toLocaleString(
                    'id-ID');
                document.querySelector('.card-text strong.hpp-awal').textContent =
                    `Rp ${initialHppAwal.toLocaleString('id-ID')}`;
                document.querySelector('.card-text strong.hpp-baru').textContent =
                    `Rp ${awal.toLocaleString('id-ID')}`;

                document.querySelectorAll('input[name="level_nama[]"]').forEach(function(namaLevelInput, index) {
                    const namaLevel = namaLevelInput.value;
                    const inputField = document.querySelectorAll('input[name="level_harga[]"]')[index];
                    const persenElement = document.querySelector(`#persen_${index}`);

                    if (originalLevelHarga.hasOwnProperty(namaLevel)) {
                        inputField.value = originalLevelHarga[namaLevel] ||
                            0;
                        let levelHarga = parseFloat(inputField.value) || 0;
                        let persen = 0;
                        if (hppUntukPerhitungan > 0) {
                            persen = ((levelHarga - hppUntukPerhitungan) / hppUntukPerhitungan) * 100;
                        }
                        persenElement.textContent = `${persen.toFixed(2)}%`;
                    } else {
                        inputField.value = 0;
                        persenElement.textContent = '0%';
                    }
                });
            }

            document.getElementById('reset').addEventListener('click', function() {
                let idBarang = document.getElementById('id_barang').value;
                if (idBarang) {
                    resetFieldsToOriginal();
                } else {
                    resetFields();
                }
            });

            document.getElementById('cancel-button').addEventListener('click', function(event) {
                event.preventDefault();
                location.reload();
            });

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

        async function setDatePicker() {
            flatpickr("#tgl_nota", {
                dateFormat: "Y-m-d",
                defaultDate: new Date(),
                minDate: "today",
                allowInput: true,
                appendTo: document.querySelector('.modal-body'),
                position: "above",
                onDayCreate: (dObj, dStr, fp, dayElem) => {
                    dayElem.addEventListener('click', () => {
                        fp.calendarContainer.querySelectorAll('.selected').forEach(el => {
                            el.style.backgroundColor = "#1abc9c";
                            el.style.color = "#fff";
                        });
                    });
                }
            });

            const inputField = document.querySelector("#tgl_nota");
            inputField.setAttribute("readonly", true);

            inputField.style.backgroundColor = "";
            inputField.style.cursor = "pointer";
        }

        async function initPageLoad() {
            await setDynamicButton();
            await setDatePicker();
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await filterList();
            await addData();
            await showData();
            await editData();
            await deleteData();
        }
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#id_supplier", {
                placeholder: "Pilih Supplier",
                allowClear: true,
                create: false
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#id_barang", {
                placeholder: "Pilih Barang",
                allowClear: true,
                create: false,
                valueField: "value",
                labelField: "text",
                searchField: ["text", "barcode"],
                plugins: ['clear_button'],
                onInitialize: function() {
                    const options = [];
                    document.querySelectorAll("#id_barang option").forEach(opt => {
                        options.push({
                            value: opt.value,
                            text: opt.textContent.trim(),
                            barcode: opt.getAttribute("data-barcode")
                        });
                    });
                    this.addOptions(options);
                }
            });
        });
    </script>
@endsection
