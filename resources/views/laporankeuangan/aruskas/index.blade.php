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
                                @if (Auth::user()->id_level == 1)
                                    <a href="{{ route('master.user.create') }}" class="mr-2 btn btn-primary"
                                        data-container="body" data-toggle="tooltip" data-placement="top"
                                        title="Tambah Data User">
                                        <i class="fa fa-circle-plus"></i> Tambah
                                    </a>
                                @endif
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
                            <x-adminlte-alerts />
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-nowrap align-middle" rowspan="5">No</th>
                                                <th class="text-nowrap align-middle" rowspan="5">Tgl</th>
                                                <th class="text-nowrap align-middle" rowspan="5">Subjek</th>
                                                <th class="text-nowrap align-middle" rowspan="5">Kategori</th>
                                                <th class="text-nowrap align-middle" rowspan="5">Item</th>
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
                '{{ asset('dummy/aruskas.json') }}', {
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
                await setListData(handleDataArray, getDataRest.data.pagination);
            } else {
                let errorMessage = getDataRest?.data?.message || 'Data gagal dimuat';
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
            return {
                id: data?.id ?? '-',
                tgl: data?.tgl ?? '-',
                subjek: data?.subjek ?? '-',
                kategori: data?.kategori ?? '-',
                item: data?.item ?? '-',
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

        async function setListData(dataList, pagination) {
            totalPage = pagination.total_pages;
            currentPage = pagination.current_page;
            let display_from = ((defaultLimitPage * (currentPage - 1)) + 1);
            let display_to = Math.min(display_from + dataList.length - 1, pagination.total);

            let getDataTable = '';
            let classCol = 'align-center text-dark text-wrap';
            dataList.forEach((element, index) => {
                getDataTable += `
                    <tr class="text-dark">
                        <td class="${classCol} text-center">${display_from + index}.</td>
                        <td class="${classCol}">${element.tgl}</td>
                        <td class="${classCol}">${element.subjek}</td>
                        <td class="${classCol}">${element.kategori}</td>
                        <td class="${classCol}">${element.item}</td>
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

            $('#listData').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
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
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await deleteData();
        }
    </script>
@endsection
