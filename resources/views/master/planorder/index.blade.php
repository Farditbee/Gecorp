@extends('layouts.main')

@section('title')
    Plan Order All Toko
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <style>
        .header-wrapper {
            justify-content: center;
            position: relative;
        }

        .header-wrapper span {
            margin: 0 auto;
        }

        .header-wrapper i {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
        }

        @media (max-width: 768px) {
            .header-wrapper {
                justify-content: space-between;
                position: static;
            }

            .header-wrapper i {
                position: static;
                transform: none;
                margin-left: auto;
            }
        }

        .toggle-header {
            transition: background-color 0.3s ease, cursor 0.3s ease;
        }

        .toggle-header:hover {
            background-color: rgba(0, 0, 0, 0.1);
            cursor: pointer;
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
                            <button class="btn-dynamic btn btn-outline-primary" type="button" data-toggle="collapse"
                                data-target="#filter-collapse" aria-expanded="false" aria-controls="filter-collapse">
                                <i class="fa fa-filter"></i> Filter
                            </button>
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
                                    <button class="btn btn-info mr-2 h-100 mb-2" id="tb-filter" type="submit">
                                        <i class="fa fa-magnifying-glass mr-2"></i>Cari
                                    </button>
                                    <select name="f_toko" id="f_toko" class="form-select select2 ml-2 mb-lg-0"
                                        style="width: 200px;"></select>
                                </form>
                            </div>
                            <x-adminlte-alerts />
                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-hover m-0">
                                        <thead id="dynamicHeaders">
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
        let title = 'Plan Order';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};
        let selectOptions = [{
            id: '#f_toko',
            isUrl: '{{ route('master.toko') }}',
            placeholder: 'Pilih Toko'
        }];

        function renderData() {
            let dynamicHeadersCount = $('.tb-head th').length - 2;
            let subHeadersCount = dynamicHeadersCount * 3;
            let totalColumns = 2 + dynamicHeadersCount + subHeadersCount;

            let html = `
            <tr class="text-dark loading-row">
                <td class="text-center" colspan="${totalColumns}">
                    <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="162px" height="24px"
                        viewBox="0 0 128 19" xml:space="preserve">
                        <rect x="0" y="0" width="100%" height="100%" fill="#FFFFFF" />
                        <path fill="#1abc9c" d="M0.8,2.375H15.2v14.25H0.8V2.375Zm16,0H31.2v14.25H16.8V2.375Zm16,0H47.2v14.25H32.8V2.375Zm16,0H63.2v14.25H48.8V2.375Zm16,0H79.2v14.25H64.8V2.375Zm16,0H95.2v14.25H80.8V2.375Zm16,0h14.4v14.25H96.8V2.375Zm16,0h14.4v14.25H112.8V2.375Z"/>
                        <g>
                            <path fill="#c7efe7" d="M128.8,2.375h14.4v14.25H128.8V2.375Z"/>
                            <path fill="#c7efe7" d="M144.8,2.375h14.4v14.25H144.8V2.375Z"/>
                            <path fill="#9fe3d5" d="M160.8,2.375h14.4v14.25H160.8V2.375Z"/>
                            <path fill="#72d6c2" d="M176.8,2.375h14.4v14.25H176.8V2.375Z"/>
                            <animateTransform attributeName="transform" type="translate" values="0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;-16 0;-32 0;-48 0;-64 0;-80 0;-96 0;-112 0;-128 0;-144 0;-160 0;-176 0" calcMode="discrete" dur="2160ms" repeatCount="indefinite"/>
                        </g>
                        <g>
                            <path fill="#c7efe7" d="M-15.2,2.375H-0.8v14.25H-15.2V2.375Z"/>
                            <path fill="#c7efe7" d="M-31.2,2.375h14.4v14.25H-31.2V2.375Z"/>
                            <path fill="#9fe3d5" d="M-47.2,2.375h14.4v14.25H-47.2V2.375Z"/>
                            <path fill="#72d6c2" d="M-63.2,2.375h14.4v14.25H-63.2V2.375Z"/>
                            <animateTransform attributeName="transform" type="translate" values="16 0;32 0;48 0;64 0;80 0;96 0;112 0;128 0;144 0;160 0;176 0;192 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0;0 0" calcMode="discrete" dur="2160ms" repeatCount="indefinite"/>
                        </g>
                    </svg>
                </td>
            </tr>`;

            return html;
        }

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listData').html(renderData());

            let filterParams = {};

            if (customFilter['id_toko']) {
                filterParams.id_toko = customFilter['id_toko'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.getplanorder') }}', {
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
                let allKeys = new Set();
                getDataRest.data.data.forEach(item => {
                    if (item.stok_per_toko) {
                        Object.keys(item.stok_per_toko).forEach(key => allKeys.add(key));
                    }
                });

                const dynamicKeys = Array.from(allKeys);
                await setListData(getDataRest.data.data, getDataRest.data.pagination, dynamicKeys);
            } else {
                let errorMessage = getDataRest?.data?.message;
                let errorRow = `
                <tr class="text-dark">
                    <th class="text-center" colspan="${$('.tb-head th').length}"> ${errorMessage} </th>
                </tr>`;
                $('#listData').html(errorRow);
                $('#countPage').text("0 - 0");
                $('#totalPage').text("0");
            }
        }


        async function setListData(dataList, pagination, dynamicKeys = []) {
            totalPage = pagination.total_pages;
            currentPage = pagination.current_page;
            let display_from = ((defaultLimitPage * (currentPage - 1)) + 1);
            let display_to = Math.min(display_from + dataList.length - 1, pagination.total);

            let dynamicHeaders = dynamicKeys.map((key, index) => `
                <th class="text-wrap align-top text-center toggle-header" colspan="3" data-key="header-${index}" id="header-${index}">
                    <div class="d-flex align-items-center header-wrapper">
                        <span>${key}</span>
                        <i class="fa fa-caret-left"></i>
                    </div>
                </th>
            `).join('');

            let subHeaders = dynamicKeys.map((key, index) => `
                <th class="text-wrap align-top text-center header-${index}-stock"
                    style="background: linear-gradient(to bottom, #a8e6a1, #66ff66);">
                    Stock
                </th>
                <th class="text-wrap align-top text-center header-${index}-otw"
                    style="background: linear-gradient(to bottom, #fff9a1, #ffff33);">
                    OTW
                </th>
                <th class="text-wrap align-top text-center header-${index}-lo"
                    style="background: linear-gradient(to bottom, #a1e9ff, #00ccff);">
                    LO (Hari)
                </th>
            `).join('');

            let tableHeaders = `
                <tr class="tb-head">
                    <th class="text-center text-wrap align-top">No</th>
                    <th class="text-wrap align-top">Nama Barang</th>
                    ${dynamicHeaders}
                </tr>
                <tr class="tb-subhead">
                    <th colspan="2"></th>
                    ${subHeaders}
                </tr>`;
            $('thead').html(tableHeaders);

            let getDataTable = '';
            let classCol = 'align-center text-dark text-wrap';
            dataList.forEach((element, index) => {
                let stokColumns = dynamicKeys.map((key, i) => {
                    let tokoData = element.stok_per_toko[key] || 0;
                    return `
                        <td class="${classCol} text-center header-${i}-stock"><b>${tokoData ?? '-'}</b></td>
                        <td class="${classCol} text-center header-${i}-otw"><b>${tokoData.otw ?? '-'}</b></td>
                        <td class="${classCol} text-center header-${i}-lo"><b>${tokoData.tt ?? '-'}</b></td>
                    `;
                }).join('');

                getDataTable += `
                <tr class="text-dark">
                    <td class="${classCol} text-center">${display_from + index}.</td>
                    <td class="${classCol}">${element.nama_barang}</td>
                    ${stokColumns}
                </tr>`;
            });

            $('#listData').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            renderPagination();
        }

        function setViewData() {
            $(document).on('click', '.toggle-header', function() {
                let targetKey = $(this).data('key');
                let targetColumnClassStock = `.header-${targetKey.split('-')[1]}-stock`;
                let targetColumnClassOtw = `.header-${targetKey.split('-')[1]}-otw`;
                let targetColumnClassLo = `.header-${targetKey.split('-')[1]}-lo`;
                let header = $(`#${targetKey}`);
                let headerColumn = header.attr('colspan');

                $(targetColumnClassStock).css('transition', 'opacity 0.5s ease, transform 0.5s ease');
                $(targetColumnClassOtw).css('transition', 'opacity 0.5s ease, transform 0.5s ease');
                $(targetColumnClassLo).css('transition', 'opacity 0.5s ease, transform 0.5s ease');

                if (headerColumn === '3') {
                    header.attr('colspan', '1');

                    $(targetColumnClassStock).css('opacity', '1').css('visibility', 'visible').css('transform',
                        'translateX(0)').show();
                    $(targetColumnClassOtw).css('opacity', '0').css('visibility', 'hidden').css('transform',
                        'translateX(100%)').hide();
                    $(targetColumnClassLo).css('opacity', '0').css('visibility', 'hidden').css('transform',
                        'translateX(100%)').hide();
                    $(this).find('i').removeClass('fa-caret-left').addClass('fa-caret-right');
                    $(this).addClass('bg-secondary text-white');
                } else {
                    header.attr('colspan', '3');

                    $(targetColumnClassStock).css('opacity', '1').css('visibility', 'visible').css('transform',
                        'translateX(0)').show();
                    $(targetColumnClassOtw).css('opacity', '1').css('visibility', 'visible').css('transform',
                        'translateX(0)').show();
                    $(targetColumnClassLo).css('opacity', '1').css('visibility', 'visible').css('transform',
                        'translateX(0)').show();

                    $(this).find('i').removeClass('fa-caret-right').addClass('fa-caret-left');
                    $(this).removeClass('bg-secondary text-white');
                }
            });
        }

        async function filterList() {
            document.getElementById('custom-filter').addEventListener('submit', async function(e) {
                e.preventDefault();

                let selectedTokoIds = $('#f_toko').val();

                let customFilter = {};
                if (selectedTokoIds && selectedTokoIds.length > 0) {
                    customFilter['id_toko'] = selectedTokoIds;
                }

                defaultSearch = $('.tb-search').val();
                defaultLimitPage = $('#limitPage').val();
                currentPage = 1;

                await getListData(
                    defaultLimitPage,
                    currentPage,
                    defaultAscending,
                    defaultSearch,
                    customFilter
                );
            });
        }

        async function initPageLoad() {
            await setDynamicButton();
            await selectMulti(selectOptions);
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await setViewData();
            await filterList();
        }
    </script>
@endsection
