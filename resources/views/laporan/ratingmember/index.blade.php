@extends('layouts.main')

@section('title')
    Rating Member
@endsection

@section('css')
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
                            <div class="d-flex gap-1">
                                <button class="btn-dynamic btn btn-outline-primary" type="button" data-toggle="collapse"
                                    data-target="#filter-collapse" aria-expanded="false" aria-controls="filter-collapse">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <select name="limitPage" id="limitPage" class="form-control mr-2 mb-2 mb-lg-0"
                                    style="width: 150px;">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                </select>
                                <input id="tb-search" class="tb-search form-control mb-2 mb-lg-0" type="search"
                                    name="search" placeholder="Cari Data" aria-label="search" style="width: 250px;">
                            </div>
                        </div>
                        <div class="content">
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
                                                <th class="text-wrap align-top">Nama Member</th>
                                                <th class="text-wrap align-top">Nama Toko</th>
                                                <th class="text-wrap align-top">Jml Trx</th>
                                                <th class="text-wrap align-top">Jml Item</th>
                                                <th class="text-wrap align-top">Jml Nominal</th>
                                                <th class="text-wrap align-top">Jml Laba</th>
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
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/daterange-picker.js') }}"></script>
    <script src="{{ asset('js/daterange-custom.js') }}"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Rating Member';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listData').html(loadingData());

            let filterParams = {};

            if (customFilter['startDate'] && customFilter['endDate']) {
                filterParams.startDate = customFilter['startDate'];
                filterParams.endDate = customFilter['endDate'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('dashboard.ratingmember') }}', {
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
            return {
                id: data?.id ?? '-',
                nama_member: data?.nama_member ?? '-',
                nama_toko: data?.nama_toko ?? '-',
                trx: data?.trx ?? 0,
                total_barang_dibeli: data?.total_barang_dibeli ?? 0,
                total_pembayaran: data?.total_pembayaran ?? 0,
                laba: data?.laba ?? 0,
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
                    <td class="${classCol}">${element.nama_member}</td>
                    <td class="${classCol}">${element.nama_toko}</td>
                    <td class="${classCol}">${element.trx}</td>
                    <td class="${classCol}">${element.total_barang_dibeli}</td>
                    <td class="${classCol}">${formatRupiah(element.total_pembayaran)}</td>
                    <td class="${classCol}">${element.laba}</td>
                </tr>`;
            });

            $('#listData').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);

            if (getFooterTable) {
                if (!$('#listData').closest('table').find('tfoot').length) {
                    $('#listData').closest('table').append('<tfoot></tfoot>');
                }
                $('#listData').closest('table').find('tfoot').html(getFooterTable);
            }

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
        }

        async function initPageLoad() {
            await setDynamicButton();
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await filterList();
        }
    </script>
@endsection
