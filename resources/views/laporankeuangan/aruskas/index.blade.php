@extends('layouts.main')

@section('title')
    Arus Kas
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/month-select.css') }}">
    <style>
        .th-data {
            width: 160px;
        }

        .td-data {
            width: 100px;
        }

        .data-total {
            min-width: 140px;
        }

        .table-responsive {
            overflow-x: auto;
        }

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
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex mb-2 mb-lg-0 align-items-center">
                                <button class="btn-dynamic btn btn-outline-primary ml-1" type="button"
                                    data-toggle="collapse" data-target="#filter-collapse" aria-expanded="false"
                                    aria-controls="filter-collapse">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                                <span id="time-report" class="font-weight-bold ml-2"></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <input id="tb-search" class="tb-search form-control mb-2 mb-lg-0" type="search"
                                    name="search" placeholder="Cari Data" aria-label="search" style="width: 200px;">
                            </div>
                        </div>
                        <div class="content">
                            <div class="card-body p-0">
                                <div class="collapse mt-2" id="filter-collapse">
                                    <form id="custom-filter"
                                        class="d-flex flex-wrap justify-content-between align-items-center mx-2">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mx-3">
                                            <input type="text" id="bulan_tahun" class="form-control mr-1 mb-3"
                                                style="width: 300px;" placeholder="Pilih Bulan & Tahun" readonly>
                                            <select name="f_toko" id="f_toko" class="form-control select2 ml-1 mt-1"
                                                style="width: 300px;"></select>
                                        </div>
                                        <div class="d-flex gap-2 mt-2 mt-md-0 mx-3">
                                            <button form="custom-filter" class="btn btn-info mr-1" id="tb-filter"
                                                type="submit">
                                                <i class="fa fa-magnifying-glass mr-2"></i>Cari
                                            </button>
                                            <button type="button" class="btn btn-secondary ml-1" id="tb-reset">
                                                <i class="fa fa-rotate mr-2"></i>Reset
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped m-0">
                                        <thead>
                                            <tr class="tb-head" id="head-table">
                                                <th class="text-center text-nowrap align-middle" rowspan="5">No</th>
                                                <th class="text-nowrap align-middle" rowspan="5">Tanggal</th>
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
                                                <th class="text-nowrap align-middle text-white bg-info text-right"
                                                    id="awal_kas_kecil">-
                                                </th>
                                                <th class="text-nowrap align-middle text-white bg-success">Saldo Awal</th>
                                                <th class="text-nowrap align-middle text-white bg-success text-right"
                                                    id="awal_kas_besar">-</th>
                                                <th class="text-nowrap align-middle text-white bg-warning">Saldo Awal</th>
                                                <th class="text-nowrap align-middle text-white bg-warning text-right"
                                                    id="awal_piutang">-</th>
                                                <th class="text-nowrap align-middle text-white bg-secondary">Saldo Awal
                                                </th>
                                                <th class="text-nowrap align-middle text-white bg-secondary text-right"
                                                    id="awal_hutang">-</th>
                                            </tr>
                                            <tr class="tb-head">
                                                <th class="text-nowrap align-middle text-white bg-info">Kas Kecil In</th>
                                                <th class="text-nowrap align-middle text-white bg-info data-total">Kas
                                                    Kecil Out</th>
                                                <th class="text-nowrap align-middle text-white bg-success">Kas Besar In
                                                </th>
                                                <th class="text-nowrap align-middle text-white bg-success data-total">Kas
                                                    Besar Out
                                                </th>
                                                <th class="text-nowrap align-middle text-white bg-warning">Piutang In</th>
                                                <th class="text-nowrap align-middle text-white bg-warning data-total">
                                                    Piutang Out</th>
                                                <th class="text-nowrap align-middle text-white bg-secondary">Hutang In</th>
                                                <th class="text-nowrap align-middle text-white bg-secondary data-total">
                                                    Hutang Out
                                                </th>
                                            </tr>
                                            <tr class="tb-head">
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-info"
                                                    id="total_kas_kecil_in">-</th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-info"
                                                    id="total_kas_kecil_out">-</th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-success"
                                                    id="total_kas_besar_in">-</th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-success"
                                                    id="total_kas_besar_out">-
                                                </th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-warning"
                                                    id="total_piutang_in">-</th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-warning"
                                                    id="total_piutang_out">-</th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-secondary"
                                                    id="total_hutang_in">-</th>
                                                <th class="text-nowrap th-data align-middle text-white text-right bg-secondary"
                                                    id="total_hutang_out">-
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
    <script src="{{ asset('js/flatpickr.js') }}"></script>
    <script src="{{ asset('js/month-select.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Arus Kas';
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

            if (customFilter['id_toko']) {
                filterParams.id_toko = customFilter['id_toko'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.aruskas.get') }}', {
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
                await totalListData(getDataRest.data.data_total);
                await setListData(handleDataArray);
            } else {
                await totalListData(null);
                let errorMessage = getDataRest?.data?.message || 'Data gagal dimuat';
                let errorRow = `
                    <tr class="text-dark">
                        <th class="text-center" colspan="${$('#head-table th').length}"> ${errorMessage} </th>
                    </tr>`;
                $('#listData').html(errorRow);
            }
        }

        async function handleData(data) {
            const formatDate = (input) => {
                if (!input) return '-';
                const dateOnly = input.split(' ')[0] || input;
                const [day, month, year] = dateOnly.split('-');
                if (!day || !month || !year) return '-';
                return `${day}-${month}-${year}`;
            };

            return {
                id: data?.id ?? '-',
                tgl: formatDate(data?.tgl),
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

        async function setListData(dataList) {
            let getDataTable = '';
            let classCol = 'align-top text-dark text-wrap';
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

            $('#listData').html(getDataTable);
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();
        }

        async function totalListData(data) {
            let kas_kecil, kas_besar, piutang, hutang;

            if (data == null) {
                kas_kecil = {};
                kas_besar = {};
                piutang = {};
                hutang = {};
            } else {
                kas_kecil = data?.kas_kecil ?? {};
                kas_besar = data?.kas_besar ?? {};
                piutang = data?.piutang ?? {};
                hutang = data?.hutang ?? {};
            }

            function formatNumber(value) {
                return (value ?? 0).toLocaleString('id-ID');
            }

            $('#akhir_kas_kecil').html(formatNumber(kas_kecil.saldo_akhir));
            $('#berjalan_kas_kecil').html(formatNumber(kas_kecil.saldo_berjalan));
            $('#awal_kas_kecil').html(formatNumber(kas_kecil.saldo_awal));
            $('#total_kas_kecil_in').html(formatNumber(kas_kecil.kas_kecil_in));
            $('#total_kas_kecil_out').html(formatNumber(kas_kecil.kas_kecil_out));

            $('#akhir_kas_besar').html(formatNumber(kas_besar.saldo_akhir));
            $('#berjalan_kas_besar').html(formatNumber(kas_besar.saldo_berjalan));
            $('#awal_kas_besar').html(formatNumber(kas_besar.saldo_awal));
            $('#total_kas_besar_in').html(formatNumber(kas_besar.kas_besar_in));
            $('#total_kas_besar_out').html(formatNumber(kas_besar.kas_besar_out));

            $('#akhir_piutang').html(formatNumber(piutang.saldo_akhir));
            $('#berjalan_piutang').html(formatNumber(piutang.saldo_berjalan));
            $('#awal_piutang').html(formatNumber(piutang.saldo_awal));
            $('#total_piutang_in').html(formatNumber(piutang.piutang_in));
            $('#total_piutang_out').html(formatNumber(piutang.piutang_out));

            $('#akhir_hutang').html(formatNumber(hutang.saldo_akhir));
            $('#berjalan_hutang').html(formatNumber(hutang.saldo_berjalan));
            $('#awal_hutang').html(formatNumber(hutang.saldo_awal));
            $('#total_hutang_in').html(formatNumber(hutang.hutang_in));
            $('#total_hutang_out').html(formatNumber(hutang.hutang_out));
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
                const now = new Date();
                const yearDefault = now.getFullYear();
                const monthTextDefault = now.toLocaleString('id-ID', {
                    month: 'long'
                });

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

                let selectedTokoIds = $('#f_toko').val();

                if (selectedTokoIds && selectedTokoIds.length > 0) {
                    customFilter['id_toko'] = selectedTokoIds;
                }

                defaultSearch = $('.tb-search').val();
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
            await setDynamicButton();
            await selectMulti(selectOptions);
            await setInputFilter();
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await filterList();
        }
    </script>
@endsection
