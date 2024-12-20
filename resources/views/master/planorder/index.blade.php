@extends('layouts.main')

@section('title')
    Plan Order All Toko
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex mb-2 mb-lg-0">
                                @if (Auth::user()->id_level == 1)
                                    <a href="{{ route('master.barang.create') }}" class="mr-2 btn btn-primary">
                                        <i class="fa fa-circle-plus"></i> Tambah
                                    </a>
                                @else
                                    <a href="{{ route('master.barang.create') }}" class="mr-2 btn btn-secondary disabled">
                                        <i class="fa fa-circle-plus"></i> Tambah
                                    </a>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <select name="limitPage" id="limitPage" class="form-control mr-2 mb-2 mb-lg-0"
                                    style="width: 100px;">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                </select>
                                <input id="tb-search" class="tb-search form-control mb-2 mb-lg-0" type="search"
                                    name="search" placeholder="Cari Data" aria-label="search" style="width: 200px;">
                            </div>
                        </div>
                        <div class="content">
                            <x-adminlte-alerts />
                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-hover m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">Nama Barang</th>
                                                @foreach ($toko as $tk)
                                                    <th class="text-wrap align-top">{{ $tk->singkatan }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($barang as $brg)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $brg->nama_barang }}</td>
                                                    @foreach ($toko as $tk)
                                                        <td>
                                                            @if ($tk->id == 1)
                                                                {{-- Ambil stok dari StockBarang jika id_toko == 1 --}}
                                                                {{ $stock->where('id_barang', $brg->id)->first()?->stock ?? 0 }}
                                                            @else
                                                                {{-- Ambil qty dari DetailToko jika id_toko != 1 --}}
                                                                {{ $stokTokoLain->where('id_barang', $brg->id)->where('id_toko', $tk->id)->first()?->qty ?? 0 }}
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        {{-- <tbody id="listData">
                                    </tbody> --}}
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
</body>

</html>
