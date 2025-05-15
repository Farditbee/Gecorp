@extends('layouts.main')

@section('title')
    Rekapitulasi Pembelian
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
    <style>
        #jsTables thead th {
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px;
            vertical-align: middle;
            line-height: 3;
            font-size: 15px;
        }

        #jsTables tbody td {
            padding: 5px;
            line-height: 1;
            vertical-align: middle;
            font-size: 14px;
        }

        #daterange[readonly] {
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
                                <div class="col-12 col-md-4 align-items-center mb-2 mb-md-0">
                                    <form id="custom-filter" class="row align-items-center">
                                        <div class="col-12 col-md-6 mb-2">
                                            <input class="form-control" type="text" id="daterange" name="daterange"
                                                placeholder="Pilih rentang tanggal">
                                        </div>
                                        <div class="col-6 col-md-3 mb-2">
                                            <button class="btn btn-warning w-100" id="tb-filter" type="submit"
                                                data-container="body" data-toggle="tooltip" data-placement="top"
                                                title="Filter Pembelian Barang">
                                                <i class="fa fa-filter mr-1"></i>Filter
                                            </button>
                                        </div>
                                        <div class="col-6 col-md-3 mb-2">
                                            <a href="{{ route('laporan.pembelian.index') }}" class="btn btn-secondary w-100"
                                                onclick="resetFilter()">
                                                <i class="fa fa-rotate mr-1"></i>Reset
                                            </a>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12 col-md-8 text-md-right">
                                    @if (request('startDate') && request('endDate'))
                                        <p class="text-muted mb-0 font-weight-bold">
                                            Data dimuat dalam periode dari tanggal
                                            <span class="text-primary">
                                                {{ \Carbon\Carbon::parse(request('startDate'))->locale('id')->translatedFormat('d F Y') }}
                                                s/d
                                                {{ \Carbon\Carbon::parse(request('endDate'))->locale('id')->translatedFormat('d F Y') }}.
                                            </span>
                                        </p>
                                    @else
                                        <p class="text-muted mb-0 font-weight-bold">
                                            Data dimuat default pada Bulan ini, silahkan filter untuk kustomisasi periode
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <x-adminlte-alerts />
                            <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="jsTables">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Supplier</th>
                                                <th>Jml Trx</th>
                                                <th>Jml Item</th>
                                                <th>Total Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($pembelian_dt->isNotEmpty())
                                                @foreach ($suppliers as $spl)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $spl->nama_supplier }}</td>
                                                        <td>{{ $pembelian_dt->where('id_supplier', $spl->id)->count() }}
                                                        </td>
                                                        <td>{{ $pembelian_dt->where('id_supplier', $spl->id)->sum('total_item') }}
                                                        </td>
                                                        <td>Rp.
                                                            {{ number_format($pembelian_dt->where('id_supplier', $spl->id)->sum('total_nilai')) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        {{ $message ?? 'Silahkan Filter periode untuk menampilkan data.' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            Menampilkan <span id="current-count">0</span> data dari <span
                                                id="total-count">0</span> total data.
                                        </div>
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-end" id="pagination">
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
    </div>
@endsection

@section('asset_js')
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/daterange-picker.js') }}"></script>
    <script src="{{ asset('js/daterange-custom.js') }}"></script>
@endsection

@section('js')
    <script>
        function resetFilter() {
            const url = new URL(window.location.href);
            url.searchParams.delete('startDate');
            url.searchParams.delete('endDate');
            window.location.href = url.toString();
        }

        async function filterList() {
            let dateRangePickerList = initializeDateRangePicker();

            const form = document.getElementById('custom-filter');
            form.action = "{{ route('laporan.pembelian.index') }}";
            form.method = "GET";

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                let startDate = dateRangePickerList.data('daterangepicker').startDate;
                let endDate = dateRangePickerList.data('daterangepicker').endDate;

                if (!startDate || !endDate) {
                    startDate = null;
                    endDate = null;
                } else {
                    // Format tanggal menjadi 'YYYY-MM-DD' tanpa waktu
                    startDate = startDate.format('YYYY-MM-DD');
                    endDate = endDate.format('YYYY-MM-DD');
                }

                const params = new URLSearchParams({
                    startDate: $("#daterange").val() !== '' ? startDate : '',
                    endDate: $("#daterange").val() !== '' ? endDate : ''
                });

                window.location.href = `${form.action}?${params.toString()}`;
            });
        }

        async function initPageLoad() {
            await filterList();
        }
    </script>
@endsection
