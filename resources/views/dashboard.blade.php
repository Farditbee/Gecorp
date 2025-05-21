@extends('layouts.main')

@section('title')
    Dashboard
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
    <style>
        .performance-scroll {
            max-height: 350px;
            overflow-y: auto;
            position: relative;
        }

        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            transition: box-shadow 0.3s ease;
        }

        .avatar:hover {
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.4);
        }

        .avatar i {
            margin-top: 2px;
        }

        .glass {
            background: rgb(255, 251, 251);
            border-radius: 1rem;
            padding: 1.5rem;
            backdrop-filter: blur(8px);
            flex: 1 1 auto;
            word-wrap: break-word;
        }

        @media (max-width: 576px) {
            .avatar {
                width: 50px;
                height: 50px;
            }

            .avatar i {
                font-size: 1.5rem;
            }

            #total-pendapatan {
                font-size: 1.25rem;
            }
        }

        @media (min-width: 577px) and (max-width: 992px) {
            .avatar {
                width: 55px;
                height: 55px;
            }

            .avatar i {
                font-size: 1.8rem;
            }

            #total-pendapatan {
                font-size: 1.5rem;
            }
        }

        @media (min-width: 993px) {
            .avatar {
                width: 60px;
                height: 60px;
            }

            .avatar i {
                font-size: 2rem;
            }

            #total-pendapatan {
                font-size: 1.75rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card statistics-card-1 position-relative">
                                <img src="{{ asset('images/dash-1.svg') }}" alt="img" class="img-fluid"
                                    style="position: absolute; top: 0; right: 0; width: 125px; height: auto; z-index: 1;">
                                <div class="card-body position-relative">
                                    <div class="row p-3">
                                        <div class="col-12 glass bg-primary text-white">
                                            <div class="row justify-content-between align-items-center">
                                                <div class="col-8 col-xl-9 col-lg-12">
                                                    <h5 class="mb-2 text-light font-weight-bold">Total Omset</h5>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="avatar bg-primary text-white mx-2">
                                                            <i class="fa fa-dollar-sign fa-2x"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="text-light mb-0" id="total-pendapatan">Rp 0
                                                            </h3>
                                                            <hr class="p-0 m-1">
                                                            <small><i class="fa fa-circle-info mr-1"></i><b
                                                                    id="info-omset">Omset per hari
                                                                    ini</b></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4 col-xl-3 col-lg-12 text-right">
                                                    <button class="btn-dynamic btn btn-outline-light w-100" type="button"
                                                        data-toggle="collapse" data-target="#filter-collapse3" title="Filter"
                                                        aria-expanded="false" aria-controls="filter-collapse3">
                                                        <i class="fa fa-filter"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="collapse" id="filter-collapse3">
                                                <hr>
                                                <form id="custom-filter-omset">
                                                    <div class="row">
                                                        <div class="col-12 col-xl-12 col-lg-12 mb-2">
                                                            <input class="form-control" type="text" id="daterange-omset"
                                                                name="daterange" placeholder="Pilih rentang tanggal">
                                                        </div>
                                                        <div class="col-12 col-xl-12 col-lg-12">
                                                            <div class="row justify-content-end text-right">
                                                                <div class="col-6 col-xl-6 col-lg-12 mb-2 text-right">
                                                                    <button class="btn btn-light w-100 h-100 d-flex align-items-center justify-content-center" id="tb-filter"
                                                                        type="submit">
                                                                        <i class="fa fa-magnifying-glass mr-1"></i>Submit
                                                                    </button>
                                                                </div>
                                                                <div class="col-6 col-xl-6 col-lg-12 mb-2 text-right">
                                                                    <button type="button"
                                                                        class="btn btn-secondary w-100 h-100 d-flex align-items-center justify-content-center"
                                                                        id="reset-omset">
                                                                        <i class="fa fa-rotate mr-1"></i>Reset
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            @if (Auth::user()->id_level == 1 || Auth::user()->id_level == 5)
                                                <hr>
                                                <div class="row">
                                                    <div class="col-12 col-xl-6 col-lg-12 px-3 pb-2">
                                                        <div class="glass flex-fill text-dark">
                                                            <i class="fa fa-shopping-cart fa-lg mb-2 text-primary"></i>
                                                            <div class="font-weight-bold">Jumlah Transaksi</div>
                                                            <div id="total-transaksi" class="fs-4 font-weight-bold">0</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-xl-6 col-lg-12 px-3">
                                                        <div class="glass flex-fill text-dark">
                                                            <i class="fa fa-wallet fa-lg mb-2 text-primary"></i>
                                                            <div class="font-weight-bold">Laba Kotor</div>
                                                            <div id="laba-kotor" class="fs-4 font-weight-bold">Rp 0</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card table-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Top 10 Penjualan</h5>
                                    @if (auth()->user()->id_toko == 1)
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 200px;">
                                                <select id="f-barang-toko"
                                                    class="filter-option form-select form-select-sm w-auto">
                                                    <option value="all">Semua Toko</option>
                                                    @foreach ($toko as $tokoData)
                                                        <option value="{{ $tokoData->id }}">{{ $tokoData->nama_toko }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="performance-scroll overflow-auto" style="position: relative;">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped m-b-0 without-header">
                                                <tbody id="listData"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card table-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Top 10 Member</h5>
                                    @if (auth()->user()->id_toko == 1)
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 200px;">
                                                <select id="f-member-toko"
                                                    class="filter-option form-select form-select-sm w-auto">
                                                    <option value="all">Semua Toko</option>
                                                    @foreach ($toko as $tokoData)
                                                        <option value="{{ $tokoData->id }}">{{ $tokoData->nama_toko }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="performance-scroll overflow-auto" style="position: relative;">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped m-b-0 without-header">
                                                <tbody id="listData2"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-8">
                    <div class="row">
                        @if (Auth::user()->id_level == 1 || Auth::user()->id_level == 2)
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-2">Komparasi Penjualan Antar Toko</h5>
                                            <div class="row align-items-center">
                                                <div class="col-auto ms-auto">
                                                    <small class="me-1">
                                                        <i class="fa fa-circle-info mr-1"></i> <b id="info-komparasi">Data
                                                            per
                                                            hari ini</b>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn-dynamic btn btn-outline-primary" type="button"
                                            data-toggle="collapse" data-target="#filter-collapse2" aria-expanded="false"
                                            aria-controls="filter-collapse2">
                                            <i class="fa fa-filter"></i> Filter
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="row pb-2 align-items-center justify-content-between">
                                            <div class="mb-2 col-12 col-md-auto">
                                                <h4 class="mb-1" id="total-penjualan2">Rp. 0</h4>
                                                <span>Data Penjualan</span>
                                            </div>
                                            <div class="mb-2 col-12 col-md-auto ms-auto justify-content-end text-end">
                                                <div class="collapse" id="filter-collapse2">
                                                    <div class="d-flex flex-column flex-md-row align-items-md-start gap-2">
                                                        <form id="custom-filter"
                                                            class="d-flex justify-content-between align-items-center w-100">
                                                            <i class="fa fa-filter"></i>
                                                            <input class="form-control w-75 mx-2 mb-lg-0" type="text"
                                                                id="daterange" name="daterange"
                                                                placeholder="Pilih rentang tanggal">
                                                            <button
                                                                class="btn btn-success w-25 h-100 d-flex align-items-center justify-content-center mr-2"
                                                                id="tb-filter" type="submit">
                                                                <i class="fa fa-magnifying-glass mr-2"></i>Submit
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-secondary w-25 h-100 d-flex align-items-center justify-content-center"
                                                                id="reset-komparasi">
                                                                <i class="fa fa-rotate mr-2"></i>Reset
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="komparasi-chart"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-2">Rekapitulasi Penjualan</h5>
                                        <div class="row align-items-center">
                                            <div class="col-auto ms-auto">
                                                <span class="text-muted me-1">
                                                    <i class="fa fa-cogs mr-1"></i>Atur Grafik :
                                                </span>
                                                <button class="btn btn-outline-primary btn-sm" id="chart-area"
                                                    title="Area Grafik">
                                                    <i class="fa fa-chart-area"></i>
                                                </button>
                                                <button class="btn btn-outline-primary btn-sm" id="chart-bar"
                                                    title="Bar Grafik">
                                                    <i class="fa fa-chart-bar"></i>
                                                </button>
                                                <button class="btn btn-outline-primary btn-sm" id="chart-line"
                                                    title="Line Grafik">
                                                    <i class="fa fa-chart-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn-dynamic btn btn-outline-primary" type="button"
                                        data-toggle="collapse" data-target="#filter-collapse" aria-expanded="false"
                                        aria-controls="filter-collapse">
                                        <i class="fa fa-filter"></i> Filter
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row pb-2 align-items-center justify-content-between">
                                        <div class="mb-2 col-12 col-md-auto">
                                            <h4 class="mb-1" id="total-penjualan">Rp. 0</h4>
                                            <span>Data Penjualan</span>
                                        </div>
                                        <div class="mb-2 col-12 col-md-auto ms-auto justify-content-end text-end">
                                            <div class="collapse" id="filter-collapse">
                                                <div class="d-flex flex-column flex-md-row align-items-md-start gap-2">
                                                    <div style="width: 200px; display: none;" id="filter-month-container">
                                                        <select id="filter-month" name="month"
                                                            class="filter-option form-select form-select-sm w-100">
                                                            <option value="1">Januari</option>
                                                            <option value="2">Februari</option>
                                                            <option value="3">Maret</option>
                                                            <option value="4">April</option>
                                                            <option value="5">Mei</option>
                                                            <option value="6">Juni</option>
                                                            <option value="7">Juli</option>
                                                            <option value="8">Agustus</option>
                                                            <option value="9">September</option>
                                                            <option value="10">Oktober</option>
                                                            <option value="11">November</option>
                                                            <option value="12">Desember</option>
                                                        </select>
                                                    </div>
                                                    <div style="width: 200px;" id="filter-year-container">
                                                        <select id="filter-year" name="year"
                                                            class="filter-option form-select form-select-sm w-100"></select>
                                                    </div>
                                                    <div style="width: 200px;">
                                                        <select id="filter-period" name="period"
                                                            class="filter-option form-select form-select-sm w-100">
                                                            <option value="daily">Harian</option>
                                                            <option value="monthly" selected>Bulanan</option>
                                                            <option value="yearly">Tahunan</option>
                                                        </select>
                                                    </div>
                                                    @if (auth()->user()->id_toko == 1)
                                                        <div style="width: 200px;">
                                                            <select id="f-penjualan-toko" name="nama_toko"
                                                                class="filter-option form-select form-select-sm w-100">
                                                                <option value="all">Semua Toko</option>
                                                                @foreach ($toko as $tokoData)
                                                                    <option value="{{ $tokoData->id }}">
                                                                        {{ $tokoData->nama_toko }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="laporan-chart"></div>
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
    <script src="{{ asset('js/apexcharts.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/daterange-picker.js') }}"></script>
    <script src="{{ asset('js/daterange-custom.js') }}"></script>
@endsection

@section('js')
    <script>
        let customFilter = {};
        let customFilter2 = {};
        let customFilter3 = {};
        let customFilter4 = {};
        let customFilter5 = {};

        async function getOmset(customFilter4) {
            let filterParams = {};

            if (customFilter4['startDate'] && customFilter4['endDate']) {
                filterParams.startDate = customFilter4['startDate'];
                filterParams.endDate = customFilter4['endDate'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('dashboard.omset') }}', {
                    id_toko: '{{ auth()->user()->id_toko }}',
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status === 200) {
                let data = getDataRest.data.data;
                await setOmsetChart(data);
            } else {
                console.error(getDataRest?.data?.message || "Error retrieving data.");
            }
        }

        async function setOmsetChart(data) {
            const total = data?.total ? data?.total : 0;
            const jumlah_transaksi = data?.jumlah_trx ? data?.jumlah_trx : 0;
            const laba_kotor = data?.laba_kotor ? data?.laba_kotor : 0;

            await $('#total-pendapatan').html(formatRupiah(total));
            await $('#total-transaksi').html(jumlah_transaksi);
            await $('#laba-kotor').html(formatRupiah(laba_kotor));
        }

        async function filterOmset() {
            let dateRangePickerList = initializeDateRangePicker('#daterange-omset');

            document.getElementById('custom-filter-omset').addEventListener('submit', async function(e) {
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

                customFilter4 = {
                    'startDate': $("#daterange-omset").val() != '' ? startDate : '',
                    'endDate': $("#daterange-omset").val() != '' ? endDate : ''
                };

                let startDateFormatted = startDate ? moment(startDate).format('DD-MM-YYYY') : '';
                let endDateFormatted = endDate ? moment(endDate).format('DD-MM-YYYY') : '';

                if (startDateFormatted && endDateFormatted) {
                    $('#info-omset').html(
                        `Omset dari <span class="text-dark font-weight-bold" style="padding: 2px 5px;">${startDateFormatted}</span> s/d <span class="text-dark font-weight-bold" style="padding: 2px 5px;">${endDateFormatted}</span>`
                    );
                } else {
                    $('#info-omset').html('Terjadi Kesalahan, Silahkan pilih filter dengan benar');
                }

                await getOmset(customFilter4);
            });

            document.getElementById('reset-omset').addEventListener('click', async function() {
                $('#daterange-omset').val('');
                customFilter4 = {};
                $('#info-omset').html('Omset per hari ini');
                await getOmset(customFilter4);
            });
        }

        function populateYearOptions() {
            const filterYear = document.getElementById('filter-year');
            const currentYear = new Date().getFullYear();
            const startYear = 2000;
            const selectedYear = customFilter.year || currentYear;

            filterYear.innerHTML = '';

            for (let year = currentYear; year >= startYear; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                if (parseInt(year) === parseInt(selectedYear)) {
                    option.selected = true;
                }
                filterYear.appendChild(option);
            }
        };

        async function getLaporanPenjualan(customFilter5 = {}) {
            let filterParams = {};

            if ('{{ auth()->user()->id_toko != 1 }}') {
                filterParams.nama_toko = '{{ auth()->user()->id_toko }}';
            } else if (customFilter5['nama_toko']) {
                filterParams.nama_toko = customFilter5['nama_toko'];
            }

            if (customFilter5['period']) {
                filterParams.period = customFilter5['period'];
            }
            if (customFilter5['month'] && customFilter5['period'] === 'daily') {
                filterParams.month = customFilter5['month'];
            }
            if (customFilter5['year']) {
                filterParams.year = customFilter5['year'];
            }

            try {
                const getDataRest = await renderAPI(
                    'GET',
                    '{{ route('master.index.kasir') }}',
                    filterParams
                );

                if (getDataRest && getDataRest.status === 200) {
                    const responseData = getDataRest.data?.data?.[0] || {
                        nama_toko: "All",
                        daily: {},
                        monthly: {},
                        yearly: {},
                        totals: 0
                    };
                    await setLaporanPenjualan(responseData, filterParams.period || 'monthly');
                } else {
                    console.error(getDataRest?.data?.message || "Error retrieving data.");
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        }

        async function setLaporanPenjualan(apiResponse, period) {
            const filterMonthContainer = document.getElementById('filter-month-container');
            const filterMonth = document.getElementById('filter-month');
            const filterYearContainer = document.getElementById('filter-year-container');
            const filterYear = document.getElementById('filter-year');
            const total = document.getElementById('total-penjualan');
            const chartContainer = document.getElementById('laporan-chart');

            filterMonthContainer.style.display = (period === 'daily') ? 'block' : 'none';
            filterYearContainer.style.display = (period === 'daily' || period === 'monthly' || period === 'yearly') ?
                'block' : 'none';

            const currentYear = new Date().getFullYear();
            const currentMonth = new Date().getMonth() + 1;

            if (!filterYear.value) filterYear.value = currentYear;
            if (!filterMonth.value) filterMonth.value = currentMonth;

            const activeYear = parseInt(filterYear.value, 10);
            const activeMonth = parseInt(filterMonth.value, 10);

            await new Promise((resolve) => setTimeout(resolve, 300));
            updateChart(apiResponse, period, activeYear, activeMonth, 'bar');

            const chartTypeMapping = {
                'chart-area': 'area',
                'chart-bar': 'bar',
                'chart-line': 'line',
            };

            const setActiveChartButton = (activeId) => {
                Object.keys(chartTypeMapping).forEach((id) => {
                    const button = document.getElementById(id);
                    button.classList.toggle('btn-primary', id === activeId);
                    button.classList.toggle('btn-outline-primary', id !== activeId);
                });
            };

            Object.keys(chartTypeMapping).forEach((id) => {
                document.getElementById(id).addEventListener('click', () => {
                    const chartType = chartTypeMapping[id];
                    updateChart(apiResponse, period, activeYear, activeMonth, chartType);
                    setActiveChartButton(id);
                });
            });

            setActiveChartButton('chart-bar');
        }


        const getDaysInMonth = (year, month) => new Date(year, month, 0).getDate();

        function updateChart(apiResponse, period, year, month, chartType) {
            let penjualan = [];
            const total = document.getElementById('total-penjualan');
            const chartContainer = document.getElementById('laporan-chart');

            let categories = [];

            if (period === 'daily') {
                const daysInMonth = getDaysInMonth(year, month);
                const dailyData = apiResponse.daily?.[year]?.[month] || Array(daysInMonth).fill(0);
                penjualan = dailyData;
                categories = Array.from({
                    length: daysInMonth
                }, (_, i) => `${i + 1}`);
            } else if (period === 'monthly') {
                penjualan = apiResponse.monthly?.[year] || Array(12).fill(0);
                categories = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                    'Oktober', 'November', 'Desember'
                ];
            } else if (period === 'yearly') {
                penjualan = Object.values(apiResponse.yearly || {});
                categories = Object.keys(apiResponse.yearly || {}).length > 0 ?
                    Object.keys(apiResponse.yearly) : [year.toString()];
            }

            total.textContent = formatRupiah(apiResponse.totals || 0);

            const chartOptions = {
                series: [{
                    name: 'Penjualan',
                    data: penjualan,
                }],
                chart: {
                    height: 350,
                    type: chartType,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                        },
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: chartType === 'line' ? 'smooth' : 'straight',
                    width: 2,
                    colors: ['#1abc9c'],
                },
                xaxis: {
                    categories: categories,
                },
                colors: ['#1abc9c'],
                legend: {
                    position: 'top',
                },
                fill: {
                    type: 'solid',
                    colors: ['#1abc9c'],
                },
                markers: {
                    size: 5,
                    colors: ['#1abc9c'],
                    strokeWidth: 2,
                },
            };

            chartContainer.innerHTML = '';
            const chart = new ApexCharts(chartContainer, chartOptions);
            chart.render();
        }

        function filterLaporanPenjualan() {
            const filterPeriod = document.getElementById('filter-period');
            const filterMonth = document.getElementById('filter-month');
            const filterYear = document.getElementById('filter-year');

            const updateFilterAndFetch = () => {
                customFilter5['period'] = filterPeriod.value;
                customFilter5['month'] = filterMonth.value;
                customFilter5['year'] = filterYear.value;
                getLaporanPenjualan(customFilter5);
            };

            let debounceTimeout;
            const debounce = (callback, delay = 500) => {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(callback, delay);
            };

            [filterPeriod, filterMonth, filterYear].forEach((filterElement) => {
                filterElement.addEventListener('change', () => {
                    debounce(updateFilterAndFetch);
                });
            });
        }

        async function getKomparasiToko(customFilter) {
            let filterParams = {};

            if (customFilter['startDate'] && customFilter['endDate']) {
                filterParams.startDate = customFilter['startDate'];
                filterParams.endDate = customFilter['endDate'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('dashboard.komparasi') }}', {
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status === 200) {
                const responseData = getDataRest.data?.data;
                await setKomparasiToko(responseData);
            } else {
                console.error(getDataRest?.data?.message || "Error retrieving data.");
            }
        }

        async function setKomparasiToko(apiResponse) {
            const chartContainer = document.getElementById('komparasi-chart');
            const totalPenjualan = document.getElementById('total-penjualan2');

            const tokoData = apiResponse?.singkatan || [];

            const categories = tokoData.map(item => Object.keys(item)[0]);
            const jumlahTransaksi = tokoData.map(item => Object.values(item)[0].jumlah_transaksi || 0);
            const totalTransaksi = tokoData.map(item => Object.values(item)[0].total_transaksi || 0);

            totalPenjualan.textContent = formatRupiah(apiResponse?.total || 0);

            const chartOptions = {
                series: [{
                        name: 'Jumlah Transaksi',
                        type: 'line',
                        data: jumlahTransaksi,
                    },
                    {
                        name: 'Total Transaksi',
                        type: 'area',
                        data: totalTransaksi,
                    },
                ],
                chart: {
                    height: 400,
                    type: 'line',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                        },
                    },
                },
                stroke: {
                    curve: 'smooth',
                    width: [3, 2],
                },
                dataLabels: {
                    enabled: true,
                    formatter: (value, {
                        seriesIndex
                    }) => {
                        if (seriesIndex === 1) {
                            if (value === 0) {
                                return
                            } else {
                                return formatRupiah(value);
                            }
                        }
                        return value;
                    },
                },
                tooltip: {
                    y: {
                        formatter: (value, {
                            seriesIndex
                        }) => {
                            if (seriesIndex === 1) {
                                return formatRupiah(value);
                            }
                            return value;
                        },
                    },
                },
                xaxis: {
                    categories: categories,
                    title: {
                        text: 'Nama Toko',
                    },
                },
                yaxis: [{
                        title: {
                            text: 'Jumlah Transaksi',
                        },
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Total Transaksi',
                        },
                    },
                ],
                colors: ['#1E90FF', '#1abc9c'],
                fill: {
                    type: ['solid', 'gradient'],
                    gradient: {
                        shade: 'light',
                        type: 'vertical',
                        gradientToColors: ['#32CD32'],
                        stops: [0, 100],
                    },
                },
                legend: {
                    position: 'top',
                },
            };

            chartContainer.innerHTML = '';
            const chart = new ApexCharts(chartContainer, chartOptions);
            chart.render();
        }

        async function filterKomparasiToko() {
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

                let startDateFormatted = startDate ? moment(startDate).format('DD-MM-YYYY') : '';
                let endDateFormatted = endDate ? moment(endDate).format('DD-MM-YYYY') : '';

                if (startDateFormatted && endDateFormatted) {
                    $('#info-komparasi').html(
                        `Data dari <span style="color: #1abc9c; padding: 2px 5px;">${startDateFormatted}</span> s/d <span style="color: #1abc9c; padding: 2px 5px;">${endDateFormatted}</span>`
                    );
                } else {
                    $('#info-komparasi').html('Terjadi Kesalahan, Silahkan pilih filter dengan benar');
                }

                await getKomparasiToko(customFilter);
            });

            document.getElementById('reset-komparasi').addEventListener('click', async function() {
                $('#daterange').val('');
                customFilter = {};
                $('#info-komparasi').html('Data per hari ini');
                await getKomparasiToko(customFilter);
            });
        }

        async function getTopPenjualan(customFilter2 = {}) {
            let filterParams = {};

            if ('{{ auth()->user()->id_toko != 1 }}') {
                filterParams.id_toko = '{{ auth()->user()->id_toko }}';
            } else if (customFilter2['id_toko']) {
                filterParams.id_toko = customFilter2['id_toko'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('dashboard.rating') }}', {
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
                    getDataRest.data.data.map(async item => await handleTopPenjualan(item))
                );
                await setTopPenjualan(handleDataArray, getDataRest.data.pagination);
            } else {
                let errorMessage = getDataRest?.data?.message;
                let errorRow = `
                <tr>
                    <td colspan="${$('.nk-tb-head th').length}"> ${errorMessage} </td>
                </tr>`;
                $('#listData').html(errorRow);
            }
        }

        async function handleTopPenjualan(data) {
            let nama_barang = data?.nama_barang ?? '-';
            let dataJumlah = data?.jumlah ?? '-';
            let total_nilai = data?.total_nilai ?? 0;
            let total_retur = data?.total_retur ?? 0;

            let fontSize = dataJumlah.toString().length > 3 ?
                '0.50rem' :
                dataJumlah.toString().length > 2 ?
                '0.70rem' :
                '0.80rem';

            let jumlah = `
            <span class="badge-success" style="
                display: inline-block;
                width: 2rem;
                height: 2rem;
                border-radius: 100%;
                line-height: 2rem;
                text-align: center;
                font-size: ${fontSize};
                font-weight: bold;">
                ${dataJumlah}
            </span>`;

            let handleData = {
                nama_barang: nama_barang === '' ? '-' : nama_barang,
                jumlah: dataJumlah === '' ? '-' : jumlah,
                total_retur: total_retur === '' ? '-' : total_retur,
                total_nilai: total_nilai === '' ? '-' : formatRupiah(total_nilai),
            };

            return handleData;
        }

        async function setTopPenjualan(dataList) {
            let getDataTable = '';

            for (let index = 0; index < dataList.length; index++) {
                let element = dataList[index];
                let retur = '';

                if (element.total_retur && element.total_retur != 0) {
                    retur = `<div class="col-12 col-xl-12 col-lg-12">
                        <small class="text-danger">
                            <i class="fa fa-rotate mx-1"></i> Total Retur : <b>${element.total_retur}</b>
                        </small>
                    </div>`;
                }

                getDataTable += `
                <tr>
                    <td>
                        <div class="d-inline-block w-100">
                            <h5 class="m-b-0 font-weight-bold">${element.nama_barang}</h5>
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="row">
                                    <div class="col-12 col-xl-12 col-lg-12">
                                        <p class="m-b-0" style="font-size: 0.9rem;">
                                            <i class="fa fa-shopping-cart"></i> <span>Terjual :</span> ${element.jumlah}
                                        </p>
                                    </div>
                                    ${retur}
                                </div>
                                <div class="text-right">
                                    <p class="m-b-0 font-weight-bold">Total</p>
                                    <p class="m-b-0"><span>${element.total_nilai}</span></p>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>`;
            }

            $('#listData tr').remove();
            $('#listData').html(getDataTable);
        }

        async function getTopMember(customFilter3 = {}) {
            let filterParams = {};

            if ('{{ auth()->user()->id_toko != 1 }}') {
                filterParams.id_toko = '{{ auth()->user()->id_toko }}';
            } else if (customFilter3['id_toko']) {
                filterParams.id_toko = customFilter3['id_toko'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('dashboard.member') }}', {
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
                    getDataRest.data.data.map(async item => await handleTopMember(item))
                );
                await setTopMember(handleDataArray, getDataRest.data.pagination);
            } else {
                let errorMessage = getDataRest?.data?.message;
                let errorRow = `
                <tr>
                    <td colspan="${$('.nk-tb-head th').length}"> ${errorMessage} </td>
                </tr>`;
                $('#listData2').html(errorRow);
            }
        }

        async function handleTopMember(data) {
            let nama_member = data?.nama_member ?? '-';
            let nama_toko = data?.nama_toko ?? '-';
            let dataJumlah = data?.total_barang_dibeli ?? '-';
            let total_pembayaran = data?.total_pembayaran ?? 0;

            let fontSize = dataJumlah.toString().length > 3 ?
                '0.50rem' :
                dataJumlah.toString().length > 2 ?
                '0.70rem' :
                '0.80rem';

            let jumlah = `
            <span class="badge-success" style="
                display: inline-block;
                width: 2rem;
                height: 2rem;
                border-radius: 100%;
                line-height: 2rem;
                text-align: center;
                font-size: ${fontSize};
                font-weight: bold;">
                ${dataJumlah}
            </span>`;

            let toko = '';
            if ('{{ auth()->user()->id_toko }}' == 1) {
                toko = `<span class="badge badge-success text-white">${nama_toko}</span>`;
            }

            let handleData = {
                nama_member: nama_member === '' ? '-' : nama_member,
                toko: toko === '' ? '-' : toko,
                jumlah: dataJumlah === '' ? '-' : jumlah,
                total_pembayaran: total_pembayaran === '' ? '-' : formatRupiah(total_pembayaran),
            };

            return handleData;
        }

        async function setTopMember(dataList) {
            let getDataTable = '';
            for (let index = 0; index < dataList.length; index++) {
                let element = dataList[index];

                getDataTable += `
                <tr>
                    <td>
                        <div class="d-inline-block w-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="m-b-0 font-weight-bold">${element.nama_member}</h5>
                                ${element.toko}
                            </div>
                            <div class="d-flex justify-content-between align-items-start">
                                <p class="m-b-0" style="font-size: 0.9rem;">
                                    <i class="fa fa-shopping-cart"></i> <span>Transaksi :</span> ${element.jumlah}
                                </p>
                                <div class="text-right">
                                    <p class="m-b-0 font-weight-bold">Total</p>
                                    <p class="m-b-0"><span>${element.total_pembayaran}</span></p>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>`;
            }
            $('#listData2 tr').remove();
            $('#listData2').html(getDataTable);
        }

        async function filterSelect() {
            const filterElements = document.querySelectorAll('.filter-option');

            async function updateFilters() {
                let allSelected = true;
                filterElements.forEach((select) => {
                    const value = select.value.trim();
                    if (!value) {
                        allSelected = false;
                    }
                    customFilter5[select.name] = value;
                });

                if (allSelected) {
                    await getLaporanPenjualan(customFilter5);
                }
            }

            filterElements.forEach((select) => {
                select.addEventListener('change', async () => {
                    if (select.id === 'f-penjualan-toko' && select.value.trim()) {
                        await updateFilters();
                    }

                    if (select.id === 'f-barang-toko' && select.value.trim()) {
                        customFilter2 = {
                            id_toko: select.value.trim()
                        };
                        await getTopPenjualan(customFilter2);
                    }
                    if (select.id === 'f-member-toko' && select.value.trim()) {
                        customFilter3 = {
                            id_toko: select.value.trim()
                        };
                        await getTopMember(customFilter3);
                    }
                });
            });
        }

        async function initPageLoad() {
            await populateYearOptions();
            await getOmset(customFilter4);
            await filterOmset();
            await setDynamicButton();
            if ('{{ auth()->user()->id_level == 1 || auth()->user()->id_level == 2 }}') {
                await getKomparasiToko(customFilter);
                await filterKomparasiToko();
            }
            await getLaporanPenjualan();
            await filterLaporanPenjualan();
            await getTopPenjualan();
            await getTopMember();
            if ('{{ auth()->user()->id_toko == 1 }}') {
                await selectList(['f-penjualan-toko', 'f-barang-toko', 'f-member-toko', 'filter-period', 'filter-month',
                    'filter-year'
                ]);
            } else {
                await selectList(['filter-period', 'filter-month', 'filter-year']);
            }
            await filterSelect();
        }
    </script>
@endsection
