<title>Laporan Pengiriman - Gecorp</title>
@extends('layouts.main')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">
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
            line-height: 1,4;
            /* Sesuaikan tinggi baris */
            vertical-align: middle;
            font-size: 14px;
        }
    </style>

    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <!-- Tombol Tambah, Filter, dan Reset Filter -->
                            <div>
                                <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#filterModal">
                                    <i class="ti-plus menu-icon"></i> Filter
                                </a>
                                <a href="{{ route('laporan.pengiriman.index') }}" class="btn btn-secondary ml-2"
                                    onclick="resetFilter()">
                                    Reset
                                </a>
                                <!-- Keterangan Periode Tanggal di Bawah Tombol Filter -->
                                @if (request('startDate') && request('endDate'))
                                    <p class="text-muted mt-2 mb-0">
                                        Data dimuat dalam periode dari tanggal
                                        {{ \Carbon\Carbon::parse(request('startDate'))->format('d M Y') }} s/d
                                        {{ \Carbon\Carbon::parse(request('endDate'))->format('d M Y') }}.
                                    </p>
                                @endif
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
                                                    <td colspan="5" class="text-center">Silahkan Filter periode untuk menampilkan data.</td>
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
                                                            <td><strong>{{ $tk->pengirimanSebagaiPengirim->sum('total_item') }}</strong></td>
                                                            <td><strong>{{ number_format($tk->pengirimanSebagaiPengirim->sum('total_nilai'), 0, '.', '.') }}</strong></td>
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

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

        <script>
            $(document).ready(function() {
                // Buka modal ketika tombol filter diklik
                $('#filterButton').on('click', function() {
                    $('#filterModal').modal('show');
                });

                // Saat modal ditutup, bersihkan tanggal jika diperlukan
                $('#filterModal').on('hidden.bs.modal', function() {
                    $('#startDate').val('');
                    $('#endDate').val('');
                });
            });
        </script>

        <script>
            function resetFilter() {
                const url = new URL(window.location.href);
                url.searchParams.delete('startDate');
                url.searchParams.delete('endDate');
                window.location.href = url.toString();
            }

            document.addEventListener('DOMContentLoaded', function() {
                const startDateInput = document.getElementById('startDate');
                const endDateInput = document.getElementById('endDate');

                if (startDateInput) {
                    startDateInput.addEventListener('focus', function() {
                        this.showPicker?.(); // Modern browsers
                    });
                }

                if (endDateInput) {
                    endDateInput.addEventListener('focus', function() {
                        this.showPicker?.(); // Modern browsers
                    });
                }
            });
        </script>
    @endsection
