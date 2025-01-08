@extends('layouts.main')

@section('title')
    Dashboard
@endsection

@section('css')
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xxl-6 col-md-3">
                    <div class="row">
                        <div class="col-xxl-12 col-md-12">
                            <div class="card statistics-card-1" style="position: relative;">
                                <img src="{{ asset('images/dash-1.svg') }}" alt="img" class="img-fluid"
                                    style="position: absolute; top: 0; right: 0; width: 125px; height: auto; z-index: 1;">
                                <div class="card-body position-relative">
                                    <div class="d-flex align-items-center">
                                        <div class="avtar bg-brand-color-1 text-white me-3">
                                            <i class="ph-duotone ph-currency-dollar f-26"></i>
                                        </div>
                                        <div>
                                            <p class="font-weight-bold mb-0">Total Omset</p>
                                            <div class="d-flex align-items-end">
                                                <h2 class="mb-0" id="total-pendapatan">
                                                    {{-- Rp. {{ number_format($totalSemuaNilai, 0, '.', '.') }} --}}
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="omset-chart" style="margin-top: 20px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="card table-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Top 5 Penjualan</h5>
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
                                <div class="performance-scroll overflow-auto" style="height: 350px; position: relative;">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped m-b-0 without-header">
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
                <div class="col-xxl-6 col-md-9">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-2">Rekapitulasi Penjualan</h5>
                                <div class="row align-items-center">
                                    <div class="col-auto ms-auto">
                                        <span class="text-muted me-1">
                                            <i class="fa fa-cogs mr-1"></i>Atur Grafik :
                                        </span>
                                        <button class="btn btn-outline-primary btn-sm" id="chart-area" title="Area Grafik">
                                            <i class="fa fa-chart-area"></i>
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" id="chart-bar" title="Bar Grafik">
                                            <i class="fa fa-chart-bar"></i>
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" id="chart-line" title="Line Grafik">
                                            <i class="fa fa-chart-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button class="btn-dynamic btn btn-outline-primary" type="button" data-toggle="collapse"
                                data-target="#filter-collapse" aria-expanded="false" aria-controls="filter-collapse">
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
                                                            <option value="{{ $tokoData->id }}">{{ $tokoData->nama_toko }}
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
@endsection

@section('asset_js')
    <script src="{{ asset('js/apexcharts.js') }}"></script>
@endsection

@section('js')
    <script>
        let customFilter = {};
        let customFilter2 = {};

        async function getOmset() {
            let getDataRest = await renderAPI(
                'GET',
                '{{ asset('dummy/pendapatan.json') }}', {
                    id_toko: '{{ auth()->user()->id_toko }}',
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
            const total = data?.total ? formatRupiah(data.total) : 0;
            const laba_bersih = data?.laba_bersih || 0;
            const laba_kotor = data?.laba_kotor || 0;

            await $('#total-pendapatan').html(total);

            var options = {
                series: [laba_bersih, laba_kotor],
                chart: {
                    type: 'donut',
                    height: 250
                },
                labels: ['Laba Bersih', 'Laba Kotor'],
                colors: ['#1abc9c', '#FF9800'],
                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1) + "%";
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#omset-chart"), options);
            chart.render();
        }

        async function getLaporanPenjualan() {
            let filterParams = {};

            if ('{{ auth()->user()->id_toko != 1 }}') {
                filterParams.nama_toko = '{{ auth()->user()->id_toko }}';
            } else if (customFilter['nama_toko']) {
                filterParams.nama_toko = customFilter['nama_toko'];
            }

            if (customFilter['period']) {
                filterParams.period = customFilter['period'];
            }
            if (customFilter['month'] && customFilter['period'] === 'daily') {
                filterParams.month = customFilter['month'];
            }
            if (customFilter['year']) {
                filterParams.year = customFilter['year'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.index.kasir') }}', {
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

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
        }

        async function setLaporanPenjualan(apiResponse, period) {
            const filterPeriod = document.getElementById('filter-period');
            const filterMonthContainer = document.getElementById('filter-month-container');
            const filterMonth = document.getElementById('filter-month');
            const filterYear = document.getElementById('filter-year');
            const total = document.getElementById('total-penjualan');
            const chartContainer = document.getElementById('laporan-chart');

            let currentChartType = 'bar';

            const getDaysInMonth = (year, month) => new Date(year, month, 0).getDate();

            const updateChart = (selectedPeriod, year, month, chartType) => {
                let penjualan = [];

                if (selectedPeriod === 'daily') {
                    const daysInMonth = getDaysInMonth(year, month);
                    const dailyData = apiResponse.daily?.[year]?.[month] || Array(daysInMonth).fill(0);
                    penjualan = dailyData;
                } else if (selectedPeriod === 'monthly') {
                    penjualan = apiResponse.monthly?.[year] || Array(12).fill(0);
                } else if (selectedPeriod === 'yearly') {
                    penjualan = Object.values(apiResponse.yearly || {});
                }

                total.textContent = formatRupiah(apiResponse.totals || 0);

                const categories = {
                    daily: Array.from({
                        length: penjualan.length
                    }, (_, i) => `${i + 1}`),
                    monthly: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September', 'Oktober', 'November', 'Desember'
                    ],
                    yearly: Object.keys(apiResponse.yearly || {}).map(year => year),
                };

                const chartOptions = {
                    series: [{
                        name: 'Penjualan',
                        data: penjualan
                    }],
                    chart: {
                        height: 350,
                        type: chartType,
                        toolbar: {
                            show: true,
                            tools: {
                                download: true
                            },
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: chartType === 'line' ? 'smooth' : 'straight',
                        width: 2,
                        colors: ['#90EE90'],
                    },
                    xaxis: {
                        categories: categories[selectedPeriod]
                    },
                    colors: ['#90EE90'],
                    legend: {
                        position: 'top'
                    },
                    fill: {
                        type: 'solid',
                        colors: ['#90EE90']
                    },
                    markers: {
                        size: 5,
                        colors: ['#90EE90'],
                        strokeWidth: 2,
                    },
                };

                chartContainer.innerHTML = '';
                const chart = new ApexCharts(chartContainer, chartOptions);
                chart.render();
            };

            const setDefaultMonth = () => {
                const currentMonth = new Date().getMonth() + 1;
                for (let option of filterMonth.options) {
                    if (parseInt(option.value) === currentMonth) {
                        option.selected = true;
                        break;
                    }
                }
            };

            const populateYearOptions = () => {
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

            populateYearOptions();
            setDefaultMonth();

            updateChart(period, filterYear.value, parseInt(filterMonth.value, 10), currentChartType);

            filterPeriod.addEventListener('change', () => {
                const selectedPeriod = filterPeriod.value;
                filterMonthContainer.style.display = selectedPeriod === 'daily' ? 'block' : 'none';

                if (selectedPeriod === 'daily') {
                    setDefaultMonth();
                }

                updateChart(selectedPeriod, filterYear.value, parseInt(filterMonth.value, 10),
                    currentChartType);
            });

            filterMonth.addEventListener('change', () => {
                if (filterPeriod.value === 'daily') {
                    updateChart(filterPeriod.value, filterYear.value, parseInt(filterMonth.value, 10),
                        currentChartType);
                }
            });

            filterYear.addEventListener('change', () => {
                const selectedYear = filterYear.value;
                const selectedMonth = parseInt(filterMonth.value, 10);

                updateChart(filterPeriod.value, selectedYear, selectedMonth, currentChartType);
            });
        }

        function setDefaultMonth() {
            const filterMonth = document.getElementById('filter-month');
            const currentMonth = new Date().getMonth() + 1;

            for (let option of filterMonth.options) {
                if (parseInt(option.value) === currentMonth) {
                    option.selected = true;
                    break;
                }
            }
        }

        function populateYearOptions() {
            const filterYear = document.getElementById('filter-year');
            const currentYear = new Date().getFullYear();
            const startYear = 2000;

            let selectedYear = filterYear.value;

            if (!selectedYear) {
                selectedYear = currentYear;
            }

            for (let year = currentYear; year >= startYear; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;

                if (year == selectedYear) {
                    option.selected = true;
                }

                filterYear.appendChild(option);
            }
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

            if (getDataRest && getDataRest.status == 200 && Array.isArray(getDataRest.data.data)) {
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
                total_nilai: total_nilai === '' ? '-' : formatRupiah(total_nilai),
            };

            return handleData;
        }

        async function setTopPenjualan(dataList) {
            let getDataTable = '';
            for (let index = 0; index < dataList.length; index++) {
                let element = dataList[index];

                getDataTable += `
                <tr>
                    <td>
                        <div class="d-inline-block w-100">
                            <h5 class="m-b-0 font-weight-bold">${element.nama_barang}</h5>
                            <div class="d-flex justify-content-between align-items-start">
                                <p class="m-b-0" style="font-size: 0.9rem;">
                                    <i class="fa fa-shopping-cart"></i> <span>Terjual :</span> ${element.jumlah}
                                </p>
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

        async function filterSelect() {
            const filterElements = document.querySelectorAll('.filter-option');

            async function updateFilters() {
                let allSelected = true;
                filterElements.forEach((select) => {
                    const value = select.value.trim();
                    if (!value) {
                        allSelected = false;
                    }
                    customFilter[select.name] = value;
                });

                if (allSelected) {
                    await getLaporanPenjualan(customFilter);
                }
            }

            filterElements.forEach((select) => {
                select.addEventListener('change', async () => {
                    await updateFilters();

                    if (select.id === 'f-barang-toko' && select.value.trim()) {
                        customFilter2 = {
                            id_toko: select.value.trim()
                        };
                        await getTopPenjualan(customFilter2);
                    }
                });
            });
        }

        async function initPageLoad() {
            await getOmset();
            await setDynamicButton();
            await getLaporanPenjualan();
            await getTopPenjualan();
            if ('{{ auth()->user()->id_toko == 1 }}') {
                await selectList(['f-penjualan-toko', 'f-barang-toko', 'filter-period', 'filter-month', 'filter-year']);
            } else {
                await selectList(['filter-period', 'filter-month', 'filter-year']);
            }
            await filterSelect();
        }
    </script>
@endsection
