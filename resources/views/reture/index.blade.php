@extends('layouts.main')

@section('title')
    Barang Reture
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
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
                                <a class="btn btn-primary mb-2 mb-lg-0 text-white" data-toggle="modal"
                                    data-target=".bd-example-modal-lg">
                                    <i class="fa fa-plus-circle"></i> Tambah
                                </a>

                                <form id="custom-filter" class="d-flex justify-content-between align-items-center mx-2">
                                    <input class="form-control w-75 mx-1 mb-lg-0" type="text" id="daterange"
                                        name="daterange" placeholder="Pilih rentang tanggal">
                                    <button class="btn btn-warning ml-1 w-50" id="tb-filter" type="submit">
                                        <i class="fa fa-filter"></i> Filter
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
                                    <table class="table table-hover m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">Status</th>
                                                <th class="text-wrap align-top">No. Nota</th>
                                                <th class="text-wrap align-top">Tanggal Nota</th>
                                                <th class="text-center text-wrap align-top">Action</th>
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

                <!-- Modal untuk Filter Tanggal -->
                <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterModalLabel">Filter Tanggal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('transaksi.pembelianbarang.index') }}" method="GET">
                                    <div class="form-group">
                                        <label for="startDate">Tanggal Mulai</label>
                                        <input type="date" name="startDate" id="startDate" class="form-control"
                                            value="{{ request('startDate') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="endDate">Tanggal Selesai</label>
                                        <input type="date" name="endDate" id="endDate" class="form-control"
                                            value="{{ request('endDate') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lgs">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="myLargeModalLabel">Data Barang Reture</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="custom-tab">
                                        <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="tambah-tab" data-toggle="tab"
                                                    href="#tambah" role="tab" aria-controls="tambah"
                                                    aria-selected="true">Tambah Pembelian</a>
                                                <a class="nav-item nav-link" id="detail-tab" data-toggle="tab"
                                                    href="#detail" role="tab" aria-controls="detail"
                                                    aria-selected="false">Detail Pembelian</a>
                                            </div>
                                        </nav>
                                        <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="tambah" role="tabpanel"
                                                aria-labelledby="tambah-tab">
                                                <br>
                                                <form id="form-tambah-pembelian" action="{{ route('reture.storeNota') }}" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label for="id_supplier" class="form-control-label">Tanggal
                                                                Nota</label>
                                                            <input class="form-control" type="date" name="tgl_retur"
                                                                id="tgl_retur">
                                                        </div>
                                                        <div class="col-6">
                                                            <label for="no_nota" class=" form-control-label">Nomor
                                                                Nota<span style="color: red">*</span></label>
                                                            <input type="number" id="no_nota" name="no_nota"
                                                                placeholder="Contoh : 001" class="form-control">
                                                        </div>
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
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        <h5><i class="fa fa-home"></i> Nomor Nota <span id="no-nota"
                                                                class="badge badge-secondary pull-right"></span></h5>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <h5><i class="fa fa-map-marker"></i> &nbsp;Tanggal Nota <span
                                                                id="tgl-nota"
                                                                class="badge badge-secondary pull-right"></span></h5>
                                                    </li>
                                                </ul>
                                                <br>
                                                <form id="form-update-pembelian"
                                                    action="{{ route('reture.updateStore') }}" method="POST">
                                                    @csrf
                                                    <!-- Item Container -->
                                                    <div id="item-container">
                                                        <div class="item-group">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <!-- Jumlah Item -->
                                                                    <div class="form-group">
                                                                        <label for="jml_item"
                                                                            class="form-control-label">Search Qr Code
                                                                            Barang<span style="color: red">*</span></label>
                                                                        <input type="text" placeholder="Contoh: 16"
                                                                            class="form-control jumlah-item">
                                                                    </div>
                                                                    <button type="button" id="add-item-detail"
                                                                        style="float: right"
                                                                        class="btn btn-secondary">Add</button>
                                                                </div>
                                                            </div><br>
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
                                                    <br><br><br>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ Main Content ] end -->
                </div>
            </div>
        </div>
    </div>
@endsection
