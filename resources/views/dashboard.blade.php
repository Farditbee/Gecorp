<title>Dashboard - Gecorp</title>
@extends('layouts.main')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Dashboard</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('master.index') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a>Dashboard</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
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
                                            <p class="font-weight-bold mb-0">Total Pendapatan</p>
                                            <div class="d-flex align-items-end">
                                                <h2 class="mb-0" id="total-pendapatan">
                                                    Rp. 0
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="card table-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Top 5 Penjualan</h5>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 200px;">
                                            <select id="f-barang-toko" class="form-select form-select-sm w-auto">
                                                <option value="all">Semua Toko</option>
                                                @foreach ($toko as $tokoData)
                                                    <option value="{{ $tokoData->id }}">{{ $tokoData->nama_toko }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="performance-scroll simplebar-scrollable-y"
                                    style="height: 350px; position: relative" data-simplebar="init">
                                    <div class="simplebar-wrapper" style="margin: 0px;">
                                        <div class="simplebar-height-auto-observer-wrapper">
                                            <div class="simplebar-height-auto-observer"></div>
                                        </div>
                                        <div class="simplebar-mask">
                                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                                <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                                    aria-label="scrollable content"
                                                    style="height: 100%; overflow: hidden scroll;">
                                                    <div class="simplebar-content" style="padding: 0px;">
                                                        <div class="card-body p-0">
                                                            <div class="table-responsive">
                                                                <table class="table table-hover m-b-0 without-header">
                                                                    <tbody id="listData">
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="simplebar-placeholder" style="width: 471px; height: 443px;"></div>
                                    </div>
                                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                    </div>
                                    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                        <div class="simplebar-scrollbar"
                                            style="height: 325px; transform: translate3d(0px, 0px, 0px); display: block;">
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
                            <h5>Laporan Penjualan</h5>
                            <div class="d-flex flex-column flex-md-row align-items-md-center gap-2">
                                <div style="width: 200px;">
                                    <select id="f-penjualan-toko" class="form-select form-select-sm w-100">
                                        <option value="all">Semua Toko</option>
                                        @foreach ($toko as $tokoData)
                                            <option value="{{ $tokoData->id }}">{{ $tokoData->nama_toko }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="width: 200px;">
                                    <select id="filter-period" class="form-select form-select-sm w-100">
                                        <option value="daily">Harian</option>
                                        <option value="monthly" selected>Bulanan</option>
                                        <option value="yearly">Tahunan</option>
                                    </select>
                                </div>
                                <div style="width: 200px; display: none;" id="filter-month-container">
                                    <select id="filter-month" class="form-select form-select-sm w-100">
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
                                <div style="width: 200px;">
                                    <select id="filter-year" class="form-select form-select-sm w-100"></select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row pb-2 align-items-center">
                                <div class="col-auto m-b-10">
                                    <h3 class="mb-1" id="total-penjualan">Rp. 0</h3>
                                    <span>Data Penjualan</span>
                                </div>
                            </div>
                            <div class="row pb-2 align-items-center">
                                <div class="col-auto ms-auto">
                                    <span class="text-muted me-1">
                                        <i class="fa fa-filter me-1"></i>Pengaturan Grafik :
                                    </span>
                                    <button class="btn btn-outline-primary btn-sm" id="chart-area" title="Area Chart">
                                        <i class="fa fa-chart-area"></i>
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm" id="chart-bar" title="Bar Chart">
                                        <i class="fa fa-chart-bar"></i>
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm" id="chart-line" title="Line Chart">
                                        <i class="fa fa-chart-line"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="laporan-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        let customFilter = {};
        let customFilter2 = {};

        async function getTotalPendapatan() {
            let getDataRest = await renderAPI(
                'GET',
                '{{ asset('dummy/pendapatan.json') }}'
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status === 200) {
                let data = getDataRest.data.data;
                await $('#total-pendapatan').html(formatRupiah(data));
            } else {
                console.error(getDataRest?.data?.message || "Error retrieving data.");
            }
        }

        async function getLaporanPenjualan() {
            let filterParams = {};

            if (customFilter['nama_toko']) {
                filterParams.nama_toko = customFilter['nama_toko'];
            }
            if (customFilter['period']) {
                filterParams.period = customFilter['period'];
            }
            if (customFilter['month']) {
                filterParams.month = customFilter['month'];
            }
            if (customFilter['year']) {
                filterParams.year = customFilter['year'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ asset('dummy/laporan.json') }}', {
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status === 200) {
                await setLaporanPenjualan(getDataRest.data.data);
            } else {
                console.error(getDataRest?.data?.message || "Error retrieving data.");
            }
        }

        async function setLaporanPenjualan(data) {
            const filterToko = document.getElementById('f-penjualan-toko');
            const filterPeriod = document.getElementById('filter-period');
            const filterMonthContainer = document.getElementById('filter-month-container');
            const filterMonth = document.getElementById('filter-month');
            const filterYear = document.getElementById('filter-year');
            const total = document.getElementById('total-penjualan');
            const chartContainer = document.getElementById('laporan-chart');
            let currentChartType = 'bar';

            customFilter = {
                nama_toko: filterToko.value,
                period: filterPeriod.value,
                month: filterMonth.value,
                year: filterYear.value,
            };

            const getDaysInMonth = (year, month) => new Date(year, month, 0).getDate();

            const updateChart = (period, year, chartType) => {
                let penjualan = [];
                const month = parseInt(filterMonth.value);

                if (period === 'daily') {
                    const daysInMonth = getDaysInMonth(year, month);
                    penjualan = data.daily?.[year]?.[month] || Array.from({
                        length: daysInMonth
                    }, () => 0);
                } else if (period === 'monthly') {
                    penjualan = data.monthly?.[year] || [];
                } else if (period === 'yearly') {
                    penjualan = data.yearly?.[year] || [];
                }

                total.textContent = formatRupiah(penjualan.reduce((a, b) => a + b, 0));

                const categories = {
                    daily: Array.from({
                        length: penjualan.length
                    }, (_, i) => `Day ${i + 1}`),
                    monthly: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                        'Dec'
                    ],
                    yearly: [year],
                };

                const chartOptions = {
                    series: [{
                        name: 'penjualan',
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
                        enabled: false
                    },
                    stroke: {
                        curve: chartType === 'line' ? 'smooth' : 'straight',
                        width: 3,
                        colors: ['#90EE90'],
                    },
                    xaxis: {
                        categories: categories[period]
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
                        strokeWidth: 2
                    },
                };

                chartContainer.innerHTML = '';
                const chart = new ApexCharts(chartContainer, chartOptions);
                chart.render();
            };

            const populateYearOptions = () => {
                const currentYear = new Date().getFullYear();
                for (let year = currentYear; year > currentYear - 10; year--) {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    filterYear.appendChild(option);
                }
                filterYear.value = currentYear;
            };

            populateYearOptions();
            updateChart(filterPeriod.value, filterYear.value, currentChartType);

            filterPeriod.addEventListener('change', () => {
                filterMonthContainer.style.display = filterPeriod.value === 'daily' ? 'block' : 'none';
                updateChart(filterPeriod.value, filterYear.value, currentChartType);
            });

            filterMonth.addEventListener('change', () => {
                if (filterPeriod.value === 'daily') {
                    updateChart(filterPeriod.value, filterYear.value, currentChartType);
                }
            });

            filterYear.addEventListener('change', () => {
                updateChart(filterPeriod.value, filterYear.value, currentChartType);
            });

            function setActiveChartButton(activeId, chartMapping) {
                Object.keys(chartMapping).forEach((id) => {
                    const button = document.getElementById(id);
                    if (id === activeId) {
                        button.classList.add('btn-primary');
                        button.classList.remove('btn-outline-primary');
                    } else {
                        button.classList.add('btn-outline-primary');
                        button.classList.remove('btn-primary');
                    }
                });
            }

            function initializeChartTypeListeners(chartMapping) {
                Object.keys(chartMapping).forEach((id) => {
                    document.getElementById(id).addEventListener('click', () => {
                        const currentChartType = chartMapping[id];
                        updateChart(filterPeriod.value, filterYear.value, currentChartType);
                        setActiveChartButton(id, chartMapping);
                    });
                });
            }

            const chartTypeMapping = {
                'chart-area': 'area',
                'chart-bar': 'bar',
                'chart-line': 'line'
            };

            initializeChartTypeListeners(chartTypeMapping);

            setActiveChartButton('chart-bar', chartTypeMapping);
        }

        async function getTopPenjualan(customFilter2 = {}) {
            let filterParams = {};

            if (customFilter2['id_toko']) {
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
                        <div class="d-inline-block">
                            <h5 class="m-b-0 font-weight-bold">${element.nama_barang}</h5>
                            <p class="m-b-0"><i class="fa fa-shopping-cart"></i> <span style="font-size: 1rem;">Terjual :</span> ${element.jumlah}</p>
                        </div>
                    </td>
                </tr>`;
            }
            $('#listData tr').remove();
            $('#listData').html(getDataTable);
        }

        async function filterSelect() {
            const selectElement = document.getElementById('f-barang-toko');

            selectElement.addEventListener('change', async function() {
                const id_toko = selectElement.value;

                customFilter2 = {
                    'id_toko': id_toko,
                };

                await getTopPenjualan(customFilter2);
            });
        }

        document.addEventListener('DOMContentLoaded', async function() {
            await getTotalPendapatan();
            await getLaporanPenjualan();
            await selectList(['f-penjualan-toko', 'f-barang-toko', 'filter-period', 'filter-month',
                'filter-year'
            ]);
            await getTopPenjualan();
            await filterSelect();
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
