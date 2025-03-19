@extends('layouts.main')

@section('title')
    Data User
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <style>
        .th-data {
            width: 140px;
        }
        .td-data {
            width: 100px;
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
                            <div class="d-flex mb-2 mb-lg-0">
                                <button class="btn-dynamic btn btn-outline-primary ml-1" type="button"
                                    data-toggle="collapse" data-target="#filter-collapse" aria-expanded="false"
                                    aria-controls="filter-collapse">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
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
                            <div class="card-body p-0">
                                <div class="collapse mt-2" id="filter-collapse">
                                    <form id="custom-filter" class="row g-2 align-items-center mx-2">
                                        <div class="col-12 col-md-6 col-lg-2 mb-2">
                                            <select class="form-select select2" id="f_is_hutang" name="f_is_hutang">
                                                <option value="" selected disabled></option>
                                                <option value="1">Hutang</option>
                                                <option value="0">Tidak</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-2 mb-2">
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-2 mb-2 d-flex justify-content-end align-items-start">
                                            <button form="custom-filter" class="btn btn-info mr-2" id="tb-filter"
                                                type="submit">
                                                <i class="fa fa-magnifying-glass mr-2"></i>Cari
                                            </button>
                                            <button type="button" class="btn btn-secondary" id="tb-reset">
                                                <i class="fa fa-rotate mr-2"></i>Reset
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-nowrap align-middle" rowspan="5">No</th>
                                                <th class="text-nowrap align-middle" rowspan="5">Tgl</th>
                                                <th class="text-nowrap align-middle" rowspan="5">Subjek</th>
                                                <th class="text-nowrap align-middle" rowspan="5">Kategori</th>
                                                <th class="text-nowrap align-middle" rowspan="5">Item</th>
                                                <th class="text-nowrap align-middle" rowspan="5">Satuan</th>
                                                <th class="text-nowrap align-middle" rowspan="5">Jml</th>
                                                <th class="text-nowrap align-middle" rowspan="5">HST</th>
                                                <th class="text-nowrap align-middle" rowspan="5">Nilai Transaksi</th>
                                                <th class="text-nowrap th-data align-middle text-white bg-info">Saldo Akhir
                                                </th>
                                                <th class="text-nowrap th-data align-middle text-white bg-info text-right"
                                                    id="akhir_kas_kecil">-</th>
                                                <th class="text-nowrap th-data align-middle text-white bg-success">Saldo
                                                    Akhir</th>
                                                <th class="text-nowrap th-data align-middle text-white bg-success text-right"
                                                    id="akhir_kas_besar">-</th>
                                                <th class="text-nowrap th-data align-middle text-white bg-warning">Saldo
                                                    Akhir</th>
                                                <th class="text-nowrap th-data align-middle text-white bg-warning text-right"
                                                    id="akhir_piutang">-</th>
                                                <th class="text-nowrap th-data align-middle text-white bg-secondary">Saldo
                                                    Akhir
                                                </th>
                                                <th class="text-nowrap th-data align-middle text-white bg-secondary text-right"
                                                    id="akhir_hutang">-</th>
                                            </tr>
                                            <tr class="tb-head">
                                                <th class="text-nowrap align-middle text-white bg-info">Saldo Berjalan</th>
                                                <th class="text-nowrap align-middle text-white bg-info text-right"
                                                    id="berjalan_kas_kecil">-</th>
                                                <th class="text-nowrap align-middle text-white bg-success">Saldo Berjalan
                                                </th>
                                                <th class="text-nowrap align-middle text-white bg-success text-right"
                                                    id="berjalan_kas_besar">-</th>
                                                <th class="text-nowrap align-middle text-white bg-warning">Saldo Berjalan
                                                </th>
                                                <th class="text-nowrap align-middle text-white bg-warning text-right"
                                                    id="berjalan_piutang">-</th>
                                                <th class="text-nowrap align-middle text-white bg-secondary">Saldo Berjalan
                                                </th>
                                                <th class="text-nowrap align-middle text-white bg-secondary text-right"
                                                    id="berjalan_hutang">-</th>
                                            </tr>
                                            <tr class="tb-head">
                                                <th class="text-nowrap align-middle text-white bg-info">Saldo Awal</th>
                                                <th class="text-nowrap align-middle text-white bg-info text-right" id="awal_kas_kecil">-
                                                </th>
                                                <th class="text-nowrap align-middle text-white bg-success">Saldo Awal</th>
                                                <th class="text-nowrap align-middle text-white bg-success text-right"
                                                    id="awal_kas_besar">-</th>
                                                <th class="text-nowrap align-middle text-white bg-warning">Saldo Awal</th>
                                                <th class="text-nowrap align-middle text-white bg-warning text-right"
                                                    id="awal_piutang">-</th>
                                                <th class="text-nowrap align-middle text-white bg-secondary">Saldo Awal</th>
                                                <th class="text-nowrap align-middle text-white bg-secondary text-right"
                                                    id="awal_hutang">-</th>
                                            </tr>
                                            <tr class="tb-head">
                                                <th class="text-nowrap align-middle text-white bg-info">Kas Kecil In</th>
                                                <th class="text-nowrap align-middle text-white bg-info">Kas Kecil Out</th>
                                                <th class="text-nowrap align-middle text-white bg-success">Kas Besar In</th>
                                                <th class="text-nowrap align-middle text-white bg-success">Kas Besar Out
                                                </th>
                                                <th class="text-nowrap align-middle text-white bg-warning">Piutang In</th>
                                                <th class="text-nowrap align-middle text-white bg-warning">Piutang Out</th>
                                                <th class="text-nowrap align-middle text-white bg-secondary">Hutang In</th>
                                                <th class="text-nowrap align-middle text-white bg-secondary">Hutang Out
                                                </th>
                                            </tr>
                                            <tr class="tb-head">
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-info" id="total_kas_kecil_in">-</th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-info" id="total_kas_kecil_out">-</th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-success" id="total_kas_besar_in">-</th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-success" id="total_kas_besar_out">-
                                                </th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-warning" id="total_piutang_in">-</th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-warning" id="total_piutang_out">-</th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-secondary" id="total_hutang_in">-</th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-secondary" id="total_hutang_out">-
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="listData">
                                        </tbody>
                                    </table>
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
    <script src="{{ asset('js/pagination.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Data User';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listData').html(loadingData());

            let filterParams = {};

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.kasir.get') }}', {
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

            if (getDataRest && getDataRest.status == 200 && Array.isArray(getDataRest.data.data)) {
                let handleDataArray = await Promise.all(
                    getDataRest.data.data.map(async item => await handleData(item))
                );
                await setListData(handleDataArray, getDataRest.data.data_total);
            } else {
                let errorMessage = getDataRest?.data?.message || 'Data gagal dimuat';
                let errorRow = `
                    <tr class="text-dark">
                        <th class="text-center" colspan="${$('.tb-head th').length}"> ${errorMessage} </th>
                    </tr>`;
                $('#listData').html(errorRow);
            }
        }

        async function handleData(data) {
            return {
                id: data?.id ?? '-',
                tgl: data?.tgl ?? '-',
                subjek: data?.subjek ?? '-',
                kategori: data?.kategori ?? '-',
                item: data?.item ?? '-',
                sat: data?.sat ?? '-',
                jml: data?.jml ?? 0,
                hst: data?.hst ?? 0,
                nilai_transaksi: data?.nilai_transaksi ?? 0,
                kas_kecil_in: data?.kas_kecil_in ?? 0,
                kas_kecil_out: data?.kas_kecil_out ?? 0,
                kas_besar_in: data?.kas_besar_in ?? 0,
                kas_besar_out: data?.kas_besar_out ?? 0,
                piutang_in: data?.piutang_in ?? 0,
                piutang_out: data?.piutang_out ?? 0,
                hutang_in: data?.hutang_in ?? 0,
                hutang_out: data?.hutang_out ?? 0
            };
        }

        async function setListData(dataList, data) {
            let getDataTable = '';
            let kas_kecil = data?.kas_kecil ?? 0;
            let classCol = 'align-center text-dark text-wrap';
            dataList.forEach((element, index) => {
                getDataTable += `
                    <tr class="text-dark">
                        <td class="${classCol} text-center">${index + 1}.</td>
                        <td class="${classCol} td-data">${element.tgl}</td>
                        <td class="${classCol} td-data">${element.subjek}</td>
                        <td class="${classCol}">${element.kategori}</td>
                        <td class="${classCol} td-data">${element.item}</td>
                        <td class="${classCol} text-center">${element.sat}</td>
                        <td class="${classCol} text-center">${element.jml}</td>
                        <td class="${classCol} text-right">${element.hst.toLocaleString()}</td>
                        <td class="${classCol} text-right">${element.nilai_transaksi.toLocaleString()}</td>
                        <td class="${classCol} text-right">${element.kas_kecil_in.toLocaleString()}</td>
                        <td class="${classCol} text-right">${element.kas_kecil_out.toLocaleString()}</td>
                        <td class="${classCol} text-right">${element.kas_besar_in.toLocaleString()}</td>
                        <td class="${classCol} text-right">${element.kas_besar_out.toLocaleString()}</td>
                        <td class="${classCol} text-right">${element.piutang_in.toLocaleString()}</td>
                        <td class="${classCol} text-right">${element.piutang_out.toLocaleString()}</td>
                        <td class="${classCol} text-right">${element.hutang_in.toLocaleString()}</td>
                        <td class="${classCol} text-right">${element.hutang_out.toLocaleString()}</td>
                    </tr>`;
            });

            $('#akhir_kas_kecil').html(kas_kecil.saldo_awal ?? 0);
            $('#berjalan_kas_kecil').html(kas_kecil.saldo_berjalan ?? 0);
            $('#awal_kas_kecil').html(kas_kecil.saldo_akhir ?? 0);
            $('#total_kas_kecil_in').html(kas_kecil.kas_kecil_in ?? 0);
            $('#total_kas_kecil_out').html(kas_kecil.kas_kecil_out ?? 0);

            $('#listData').html(getDataTable);
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();
        }

        async function deleteData() {
            $(document).on("click", ".hapus-data", async function() {
                isActionForm = "destroy";
                let id = $(this).attr("data-id");
                let name = $(this).attr("data-name");

                swal({
                    title: `Hapus User ${name}`,
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
                        `user/delete/${id}`
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

        async function initPageLoad() {
            await setDynamicButton();
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await deleteData();
        }
    </script>
@endsection
