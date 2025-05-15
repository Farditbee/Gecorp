@extends('layouts.main')

@section('title')
    Laba Rugi
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/month-select.css') }}">
    <style>
        #bulan_tahun[readonly] {
            background-color: white !important;
            cursor: pointer !important;
            color: inherit !important;
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
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-xl-6 col-lg-4 mb-2">
                                    <span id="time-report" class="font-weight-bold"></span>
                                </div>
                                <div class="col-12 col-xl-6 col-lg-8 mb-2">
                                    <form id="custom-filter" class="row justify-content-end">
                                        <div class="col-12 col-xl-6 col-lg-6 mb-2">
                                            <input type="text" id="bulan_tahun" class="form-control"
                                                placeholder="Pilih Bulan & Tahun" readonly>
                                        </div>
                                        <div class="col-6 col-xl-3 col-lg-3">
                                            <button form="custom-filter" class="btn btn-info w-100" id="tb-filter"
                                                type="submit">
                                                <i class="fa fa-magnifying-glass mr-2"></i>Cari
                                            </button>
                                        </div>
                                        <div class="col-6 col-xl-3 col-lg-3">
                                            <button type="button" class="btn btn-secondary w-100" id="tb-reset">
                                                <i class="fa fa-rotate mr-2"></i>Reset
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <div class="d-flex justify-content-center">
                                <div class="card w-50">
                                    <div class="card-body p-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped m-0">
                                                <tbody id="listData" class="container-fluid">
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
        </div>
    </div>
@endsection

@section('asset_js')
    <script src="{{ asset('js/pagination.js') }}"></script>
    <script src="{{ asset('js/flatpickr.js') }}"></script>
    <script src="{{ asset('js/month-select.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Laba Rugi';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};

        function setInputFilter() {
            const now = new Date();
            const year = now.getFullYear();
            const monthText = now.toLocaleString('id-ID', {
                month: 'long'
            });

            $('#time-report').html(
                `<i class="fa fa-calendar mr-1"></i><b>${title}</b> (Bulan <b class="text-primary">${monthText}</b> Tahun <b class="text-primary">${year}</b>)`
            );

            const bulanID = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            flatpickr("#bulan_tahun", {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: false,
                        dateFormat: "F Y",
                        theme: "light"
                    })
                ],
                disableMobile: true,
                locale: {
                    firstDayOfWeek: 1,
                    months: {
                        shorthand: bulanID,
                        longhand: bulanID
                    }
                },
                onReady: function(selectedDates, dateStr, instance) {
                    setTimeout(() => translateMonthPicker(), 10);
                    if (selectedDates.length > 0) {
                        instance.setDate(
                            `${bulanID[selectedDates[0].getMonth()]} ${selectedDates[0].getFullYear()}`,
                            false);
                    }
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        instance.input.value =
                            `${bulanID[selectedDates[0].getMonth()]} ${selectedDates[0].getFullYear()}`;
                    }
                },
                onOpen: function() {
                    setTimeout(() => translateMonthPicker(), 10);
                }
            });
        }

        function translateMonthPicker() {
            const bulanID = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            setTimeout(() => {
                $(".flatpickr-monthSelect-month").each(function(index) {
                    $(this).text(bulanID[index]);
                });
            }, 50);
        }

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listData').html(loadingData());

            let filterParams = {};

            if (customFilter['month'] && customFilter['year']) {
                filterParams.month = customFilter['month'];
                filterParams.year = customFilter['year'];
            }

            let getDataRest = await renderAPI(
                    'GET',
                    '{{ route('master.labarugi.get') }}', {
                        page: page,
                        limit: limit,
                        ascending: ascending,
                        search: search,
                        ...filterParams
                    }
                ).then(response => response)
                .catch(error => error.response || {});

            if (getDataRest && getDataRest.status == 200 && Array.isArray(getDataRest.data.data)) {
                let handleDataArray = getDataRest.data.data.map(item => {
                    let kategori = item[0];
                    let items = item[1].map(subItem => [subItem[0], subItem[1]]);
                    return [kategori, items];
                });

                let handleDataTotalArray = (getDataRest.data.data_total ?? []).map(subItem => [subItem[0], subItem[1]]);

                await setListData(handleDataArray, handleDataTotalArray);
            } else {
                let errorMessage = getDataRest?.data?.message || 'Data gagal dimuat';
                let errorRow = `
                <tr class="text-dark">
                    <th class="text-center" colspan="${$('.tb-head th').length}"> ${errorMessage} </th>
                </tr>`;
                $('#listData').html(errorRow);
            }
        }

        async function setListData(dataList, dataTotalList) {
            let getDataTable = '';

            dataList.forEach(([kategori, items]) => {
                getDataTable += `<tr class="font-weight-bold"><td colspan="3">${kategori}</td></tr>`;

                items.forEach(([nama, nilai], index) => {
                    let classBadge = parseFloat(nilai) < 0 ? 'text-danger' : '';
                    let isLastItem = index === items.length - 1 ? 'font-weight-bold' : '';

                    getDataTable += `
                    <tr class="${isLastItem}">
                        <td class="space-blank"></td>
                        <td>${nama}</td>
                        <td class="text-right ${classBadge}">${nilai.toLocaleString()}</td>
                    </tr>`;
                });
            });

            getDataTable += `<tr><td colspan="3" class="py-2"></td></tr>`;

            dataTotalList.forEach(([nama, nilai]) => {
                let classBadge = parseFloat(nilai) < 0 ? 'text-danger' : '';

                getDataTable += `
                <tr class="font-weight-bold">
                    <td></td>
                    <td>${nama}</td>
                    <td class="text-right ${classBadge}">${nilai.toLocaleString()}</td>
                </tr>`;
            });

            $('#listData').html(getDataTable);
            $('[data-toggle="tooltip"]').tooltip();
        }

        async function filterList() {
            function defaultTime(monthText, year) {
                const now = new Date();
                const yearDefault = now.getFullYear();
                const monthTextDefault = now.toLocaleString('id-ID', {
                    month: 'long'
                });
                $('#time-report').html(
                    `<i class="fa fa-calendar mr-1"></i><b>${title}</b> (Bulan <b class="text-primary">${monthText || monthTextDefault}</b> Tahun <b class="text-primary">${year || yearDefault}</b>)`
                );
            }

            document.getElementById('custom-filter').addEventListener('submit', async function(e) {
                e.preventDefault();
                let bulanTahun = document.getElementById("bulan_tahun").value.trim();

                let monthText = '',
                    year = '',
                    month = '';

                if (bulanTahun) {
                    let parts = bulanTahun.split(" ");
                    if (parts.length === 2) {
                        monthText = parts[0];
                        year = parts[1];
                        month = getMonthNumber(monthText);
                    }
                }

                customFilter = {
                    year: year || '',
                    month: month || '',
                };

                currentPage = 1;

                await defaultTime(monthText, year);
                await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                    customFilter);
            });

            document.getElementById('tb-reset').addEventListener('click', async function() {
                $('#bulan_tahun').val('').trigger('change');
                $('#custom-filter select').val(null).trigger('change');
                customFilter = {};
                defaultSearch = $('.tb-search').val();
                defaultLimitPage = $("#limitPage").val();
                currentPage = 1;

                await defaultTime(null, null);
                await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                    customFilter);
            });
        }

        function getMonthNumber(monthName) {
            const monthNames = {
                "Januari": "1",
                "Februari": "2",
                "Maret": "3",
                "April": "4",
                "Mei": "5",
                "Juni": "6",
                "Juli": "7",
                "Agustus": "8",
                "September": "9",
                "Oktober": "10",
                "November": "11",
                "Desember": "12"
            };
            return monthNames[monthName] || '';
        }

        async function initPageLoad() {
            await setInputFilter();
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await filterList();
        }
    </script>
@endsection
