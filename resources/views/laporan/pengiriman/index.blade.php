@extends('layouts.main')

@section('title')
    Rekapitulasi Pengiriman
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
    <style>
        #jsTables thead th {
            font-weight: bold;
            /* Font tebal untuk penekanan */
            text-transform: uppercase;
            /* (Opsional) Semua huruf kapital */
            padding: 5px;
            /* Sedikit padding untuk thead */
            vertical-align: middle;
            line-height: 3;
            font-size: 15px;
        }

        #jsTables tbody td {
            padding: 5px;
            /* Sesuaikan padding untuk jarak antar sel */
            line-height: 1, 4;
            /* Sesuaikan tinggi baris */
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
                                                <th>Pengirim</th>
                                                <th>Penerima</th>
                                                <th>Jml Barang</th>
                                                <th>Total Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($toko->isEmpty())
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        {{ $message ?? 'Silahkan Filter periode untuk menampilkan data.' }}
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($toko as $tk)
                                                    {{-- Filter hanya untuk user dengan id_toko selain 1 --}}
                                                    @if (auth()->user()->id_toko == 1 || $tk->id == auth()->user()->id_toko)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $tk->nama_toko }}</td>
                                                            <td>
                                                                @if ($tk->pengirimanSebagaiPengirim->isNotEmpty())
                                                                    @foreach ($tk->pengirimanSebagaiPengirim->unique('toko_penerima') as $pengiriman)
                                                                        @if (auth()->user()->id_toko == 1 || $pengiriman->toko_penerima == auth()->user()->id_toko)
                                                                            <div style="margin-bottom: 10px;">
                                                                                {{ $pengiriman->tokos->nama_toko ?? '-' }}
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div>-</div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($tk->pengirimanSebagaiPengirim->isNotEmpty())
                                                                    @foreach ($tk->pengirimanSebagaiPengirim->groupBy('toko_penerima') as $tokoPenerimaId => $pengirimanGroup)
                                                                        @if (auth()->user()->id_toko == 1 || $tokoPenerimaId == auth()->user()->id_toko)
                                                                            <div style="margin-bottom: 10px;">
                                                                                {{ $pengirimanGroup->sum('total_item') }}
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div>0</div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($tk->pengirimanSebagaiPengirim->isNotEmpty())
                                                                    @foreach ($tk->pengirimanSebagaiPengirim->groupBy('toko_penerima') as $tokoPenerimaId => $pengirimanGroup)
                                                                        @if (auth()->user()->id_toko == 1 || $tokoPenerimaId == auth()->user()->id_toko)
                                                                            <div style="margin-bottom: 10px;">
                                                                                {{ number_format($pengirimanGroup->sum('total_nilai'), 0, '.', '.') }}
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div>0</div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr class="table-success">
                                                            <td></td>
                                                            <td><strong>Total</strong></td>
                                                            <td></td>
                                                            <td><strong>{{ $tk->pengirimanSebagaiPengirim->sum('total_item') }}</strong>
                                                            </td>
                                                            <td><strong>{{ number_format($tk->pengirimanSebagaiPengirim->sum('total_nilai'), 0, '.', '.') }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>


                                    <div class="d-flex justify-content-between align-items-center mb-3">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal untuk Filter Tanggal -->
                <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterModalLabel">Filter Tanggal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('laporan.pengiriman.index') }}" method="GET">
                                    <div class="form-group">
                                        <label for="startDate">Tanggal Mulai</label>
                                        <input type="date" name="startDate" id="startDate" class="form-control"
                                            value="{{ request('startDate') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="endDate">Tanggal Selesai</label>
                                        <input type="date" name="endDate" id="endDate" class="form-control"
                                            value="{{ request('endDate') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- [ Main Content ] end -->
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
                form.action = "{{ route('laporan.pengiriman.index') }}";
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
