@extends('layouts.main')

@section('title')
    Neraca
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

        .space-blank {
            width: 30px;
        }

        #listData {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        #listData td,
        #listData th {
            padding: 8px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }

        #listData .kategori-data td:nth-child(1),
        #listData .kategori-data td:nth-child(5){
            width: 4%;
        }
        #listData .kategori-data td:nth-child(2),
        #listData .kategori-data td:nth-child(6) {
            width: 34%;
        }

        #listData .kategori-data td:nth-child(3),
        #listData .kategori-data td:nth-child(7) {
            width: 10%;
        }

        #listData .kategori-data td:nth-child(4) {
            width: 5%;
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
                            <div class="d-flex mb-2 mb-lg-0 align-items-center flex-grow-1">
                                <span id="time-report" class="font-weight-bold ml-2"></span>
                            </div>
                            <form id="custom-filter" class="row g-2 align-items-center ms-auto">
                                <div class="col-12 col-md-6 col-lg-6 mb-2">
                                    <input type="text" id="bulan_tahun" class="form-control"
                                        placeholder="Pilih Bulan & Tahun" readonly>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6 mb-2 d-flex justify-content-end align-items-start">
                                    <button form="custom-filter" class="btn btn-info mr-2" id="tb-filter" type="submit">
                                        <i class="fa fa-magnifying-glass mr-2"></i>Cari
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="tb-reset">
                                        <i class="fa fa-rotate mr-2"></i>Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="content">
                            <div class="d-flex justify-content-center">
                                <div class="card w-75">
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
        let title = 'Neraca';
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
                    '{{ asset('dummy/neraca.json') }}', {
                        page: page,
                        limit: limit,
                        ascending: ascending,
                        search: search,
                        ...filterParams
                    }
                ).then(response => response)
                .catch(error => error.response || {});

            if (getDataRest && getDataRest.status == 200 && Array.isArray(getDataRest.data.data)) {
                let handleDataArray = getDataRest.data.data;
                await setListData(handleDataArray);
            } else {
                let errorMessage = getDataRest?.data?.message || 'Data gagal dimuat';
                let errorRow = `
                <tr class="text-dark">
                    <th class="text-center" colspan="${$('.tb-head th').length}"> ${errorMessage} </th>
                </tr>`;
                $('#listData').html(errorRow);
            }
        }

        async function setListData(dataList) {
            let getDataTable = '';

            const aktiva = dataList.find(k => k.kategori === 'AKTIVA');
            const pasiva = dataList.find(k => k.kategori === 'PASIVA');

            const subAktiva = aktiva?.subkategori || [];
            const subPasiva = pasiva?.subkategori || [];

            const maxSub = Math.max(subAktiva.length, subPasiva.length);

            getDataTable += `
                <tr class="font-weight-bold bg-light">
                    <td colspan="2">AKTIVA</td>
                    <td colspan="1" class="text-right">${aktiva.total.toLocaleString()}</td>
                    <td colspan="1"></td>
                    <td colspan="2">PASIVA</td>
                    <td colspan="1" class="text-right">${pasiva.total.toLocaleString()}</td>
                </tr>
            `;

            for (let i = 0; i < maxSub; i++) {
                const subA = subAktiva[i];
                const subP = subPasiva[i];

                const aItems = subA ? subA.item : [];
                const pItems = subP ? subP.item : [];

                const aRows = aItems.map(item => ({
                    kode: item.kode,
                    nama: item.nama,
                    nilai: item.nilai
                }));

                const pRows = pItems.map(item => ({
                    kode: item.kode,
                    nama: item.nama,
                    nilai: item.nilai
                }));

                const maxRow = Math.max(aRows.length, pRows.length);

                getDataTable += `
                    <tr class="font-weight-bold bg-secondary text-white">
                        <td colspan="2">${subA ? subA.judul : ''}</td>
                        <td colspan="1" class="text-right">${subA ? subA.total.toLocaleString() : ''}</td>
                        <td colspan="1"></td>
                        <td colspan="2">${subP ? subP.judul : ''}</td>
                        <td colspan="1" class="text-right">${subP ? subP.total.toLocaleString() : ''}</td>
                    </tr>
                `;

                for (let j = 0; j < maxRow; j++) {
                    const aData = aRows[j] || {
                        kode: '',
                        nama: '',
                        nilai: ''
                    };
                    const pData = pRows[j] || {
                        kode: '',
                        nama: '',
                        nilai: ''
                    };

                    const aBadge = parseFloat(aData.nilai) < 0 ? 'text-danger' : '';
                    const pBadge = parseFloat(pData.nilai) < 0 ? 'text-danger' : '';

                    getDataTable += `
                        <tr class="kategori-data">
                            <td class="text-center">${aData.kode}</td>
                            <td>${aData.nama}</td>
                            <td class="text-right ${aBadge}">${aData.nilai !== '' ? `${parseFloat(aData.nilai).toLocaleString()}` : ''}</td>
                            <td colspan="1"></td>
                            <td class="text-center">${pData.kode}</td>
                            <td>${pData.nama}</td>
                            <td class="text-right ${pBadge}">${pData.nilai !== '' ? `${parseFloat(pData.nilai).toLocaleString()}` : ''}</td>
                        </tr>
                    `;
                }
            }

            $('#listData').html(getDataTable);
            $('[data-toggle="tooltip"]').tooltip();
        }

        async function filterList() {
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

                $('#time-report').html(
                    `<i class="fa fa-calendar mr-1"></i><b>${title}</b> (Bulan <b class="text-primary">${monthText}</b> Tahun <b class="text-primary">${year}</b>)`
                );

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
