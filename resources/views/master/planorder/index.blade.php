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
            right: 2px;
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
                            <div class="d-flex gap-1">
                                <button class="btn-dynamic btn btn-outline-primary" type="button" data-toggle="collapse"
                                    data-target="#filter-collapse" aria-expanded="false" aria-controls="filter-collapse">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                                <button class="btn-dynamic btn btn-outline-primary mx-2" type="button"
                                    data-toggle="collapse" data-target="#info-collapse" aria-expanded="false"
                                    aria-controls="info-collapse">
                                    <i class="fa fa-circle-info"></i> Informasi
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
                            <div class="collapse px-4 pt-3" id="info-collapse">
                                <div class="alert alert-custom alert-dismissible fade show" role="alert">
                                    <h4 class="alert-heading">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                            fill="currentColor" class="bi bi-info-circle" viewBox="0 0 20 20">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                            <path
                                                d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                        </svg>
                                        Informasi
                                    </h4>
                                    <div>
                                        <div class="text-bold d-flex align-items-center mb-2">
                                            <em class="fa fa-circle mx-1"></em>
                                            <span>
                                                Untuk Filter Data, silahkan klik tombol
                                                <strong><em class="fa fa-filter mx-1"></em>Filter</strong> lalu isi inputan
                                                dan klik tombol
                                                <strong><em class="fa fa-magnifying-glass mx-1"></em>Cari</strong> untuk
                                                submit
                                            </span>
                                        </div>
                                        <div class="text-bold d-flex align-items-center mb-2">
                                            <em class="fa fa-circle mx-1"></em>
                                            <div
                                                style="display: inline-block; width: 15px; height: 15px; background: linear-gradient(to bottom, #a8e6a1, #66ff66); border-radius: 20%; border: 3px solid #ffffff; margin-right: 5px;">
                                            </div>
                                            <span><strong class="fw-bold"><i class="fa fa-box"></i></strong> Jumlah stock
                                                barang yang
                                                tersisa</span>
                                        </div>
                                        <div class="text-bold d-flex align-items-center mb-2">
                                            <em class="fa fa-circle mx-1"></em>
                                            <div
                                                style="display: inline-block; width: 15px; height: 15px; background: linear-gradient(to bottom, #fff9a1, #ffff33); border-radius: 20%; border: 3px solid #ffffff; margin-right: 5px;">
                                            </div>
                                            <span><strong class="fw-bold"><i class="fa fa-truck-fast"></i></strong> Jumlah
                                                barang yang sedang
                                                dikirimkan</span>
                                        </div>
                                        <div class="text-bold d-flex align-items-center">
                                            <em class="fa fa-circle mx-1"></em>
                                            <div
                                                style="display: inline-block; width: 15px; height: 15px; background: linear-gradient(to bottom, #a1e9ff, #00ccff); border-radius: 20%; border: 3px solid #ffffff; margin-right: 5px;">
                                            </div>
                                            <span><strong class="fw-bold"><i class="fa fa-clock"></i></strong> Penjualan
                                                terakhir dari stock
                                                barang yang
                                                tersisa dalam satuan hari</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapse mt-2 pl-4" id="filter-collapse">
                                <form id="custom-filter" class="d-flex justify-content-start align-items-center">
                                    <select name="f_toko" id="f_toko" class="form-select select2 mb-lg-0"
                                        style="width: 200px;"></select>
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
                const tokoMap = {};
                if (getDataRest.data.data_toko) {
                    getDataRest.data.data_toko.forEach(toko => {
                        tokoMap[toko.singkatan] = toko.nama_toko;
                    });
                }

                await setListData(getDataRest.data.data, getDataRest.data.pagination, dynamicKeys, tokoMap);
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

        async function setListData(dataList, pagination, dynamicKeys = [], tokoMap = {}) {
            totalPage = pagination.total_pages;
            currentPage = pagination.current_page;
            let display_from = ((defaultLimitPage * (currentPage - 1)) + 1);
            let display_to = Math.min(display_from + dataList.length - 1, pagination.total);

            let dynamicHeaders = dynamicKeys.map((key, index) => {
                let title = tokoMap[key] || key;
                return `
                    <th class="text-wrap align-top text-center toggle-header" colspan="3" data-key="header-${index}" id="header-${index}" title="${title}" data-toggle="tooltip" data-placement="top">
                        <div class="d-flex align-items-center header-wrapper">
                            <span>${key}</span>
                            <i class="fa fa-caret-left"></i>
                        </div>
                    </th>
                `;
            }).join('');

            let subHeaders = dynamicKeys.map((key, index) => `
                <th class="text-wrap align-top text-center header-${index}-stock"
                    style="background: linear-gradient(to bottom, #a8e6a1, #66ff66); width: 80px;">
                    <i class="fa fa-box"></i>
                </th>
                <th class="text-wrap align-top text-center header-${index}-otw"
                    style="background: linear-gradient(to bottom, #fff9a1, #ffff33); width: 80px;">
                    <i class="fa fa-truck-fast"></i>
                </th>
                <th class="text-wrap align-top text-center header-${index}-lo"
                    style="background: linear-gradient(to bottom, #a1e9ff, #00ccff); width: 80px;">
                    <i class="fa fa-clock"></i>
                </th>
            `).join('');

            let tableHeaders = `
            <tr class="tb-head">
                <th class="text-center text-wrap align-top" style="width: 10px;">No</th>
                <th class="text-wrap align-top" style="width: 150px;">Nama Barang</th>
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
                <td class="${classCol} text-center header-${i}-stock" style="background-color: #CCFFCC"><b>${tokoData.stock ?? '-'}</b></td>
                <td class="${classCol} text-center header-${i}-otw" style="background-color: #FFFFCC"><b>${tokoData.otw ?? '-'}</b></td>
                <td class="${classCol} text-center header-${i}-lo" style="background-color: #99CCFF"><b>${tokoData.lo ?? '-'}</b></td>
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
            $('[data-toggle="tooltip"]').tooltip();
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

            document.getElementById('tb-reset').addEventListener('click', async function() {
                $('.select2').val('').trigger('change');
                $('.tb-search').val('');

                customFilter = {};
                defaultSearch = '';
                defaultLimitPage = 10;
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
