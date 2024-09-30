<title>Data Stock Barang - Gecorp</title>
@extends('layouts.main')

@section('content')

<div class="pcoded-main-container">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                        <div class="page-header">
                            <div class="page-block">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <div class="page-header-title">
                                            <h4 class="m-b-10 ml-3">Data Stock Barang</h4>
                                        </div>
                                        <ul class="breadcrumb ">
                                            <li class="breadcrumb-item ml-3"><a href="{{ route('master.index')}}"><i class="feather icon-home"></i></a></li>
                                            <li class="breadcrumb-item"><a>Data Stock Barang</a></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- [ Main Content ] start -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <!-- Tombol Tambah -->
                                        <a href="{{ route('master.pembelianbarang.create')}}" class="btn btn-primary">
                                            <i class="ti-plus menu-icon"></i> Tambah
                                        </a>
                                        <!-- Input Search -->
                                        <form class="d-flex" method="GET" action="{{ route('master.stockbarang.index') }}">
                                            <input class="form-control me-2" id="search" type="search" name="search" placeholder="Cari Barang" aria-label="Search">
                                        </form>
                                    </div>
                                    <x-adminlte-alerts />
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="jsTable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nama Barang</th>
                                                        <th>Stock</th>
                                                        <th>Harga Satuan (Hpp Baru)</th>
                                                        <th>Detail</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; ?>
                                                    @foreach ($stock as $stk)
                                                        <tr data-toggle="modal" data-target="#mediumModal-{{$stk->id}}">
                                                            <td>{{$no++}}</td>
                                                            <td>{{$stk->nama_barang}}</td>
                                                            <td>{{$stk->stock}}</td>
                                                            <td>Rp. {{ number_format($stk->hpp_baru, 0, '.', '.') }}</td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-primary btn-sm"
                                                                    style="padding-top: 5px;"
                                                                    data-toggle="modal"
                                                                    data-target="#mediumModal-{{$stk->id}}"
                                                                    data-id_barang="{{$stk->id_barang}}"
                                                                    data-id="{{$stk->id}}">
                                                                    Cek Detail
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <form onsubmit="return confirm('Ingin menghapus Data ini ?');" action="#" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash menu-icon"></i></button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @foreach ($stock as $stk )
                                            <div class="modal fade" id="mediumModal-{{$stk->id}}" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel-{{$stk->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="mediumModalLabel-{{$stk->id}}">{{$stk->barang->nama_barang}}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <ul class="nav nav-tabs mb-3" id="myTab-{{$stk->id}}" role="tablist">
                                                                <li class="nav-item">
                                                                    <a class="nav-link active text-uppercase" id="home-tab-{{$stk->id}}" data-toggle="tab" href="#home-{{$stk->id}}" role="tab" aria-controls="home-{{$stk->id}}" aria-selected="true">Barang Di Toko</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link text-uppercase" id="profile-tab-{{$stk->id}}" data-toggle="tab" href="#profile-{{$stk->id}}" role="tab" aria-controls="profile-{{$stk->id}}" aria-selected="false">Profile</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link text-uppercase" id="contact-tab-{{$stk->id}}" data-toggle="tab" href="#contact-{{$stk->id}}" role="tab" aria-controls="contact-{{$stk->id}}" aria-selected="false">Contact</a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content" id="myTabContent-{{$stk->id}}">
                                                                <div class="tab-pane fade show active" id="home-{{$stk->id}}" role="tabpanel" aria-labelledby="home-tab-{{$stk->id}}">
                                                                    <div class="row">
                                                                        <div class="col-md-7 ml-3">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-striped" id="jsTable-{{$stk->id}}">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Nama Toko</th>
                                                                                            <th>Stock</th>
                                                                                            <th>Harga HPP Baru</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach ($toko as $tk)
                                                                                        <tr>
                                                                                            <td>{{ $tk->nama_toko }}</td>

                                                                                            @if ($tk->id == 1)
                                                                                                {{-- Tampilkan stok dari tabel stock_barang untuk toko dengan id = 1 --}}
                                                                                                @php
                                                                                                    // Ambil stok dari tabel stock_barang hanya untuk barang yang sedang diklik
                                                                                                    $stokBarangTokoUtama = $stock->where('id_barang', $stk->id_barang)->first();
                                                                                                @endphp

                                                                                                @if ($stokBarangTokoUtama)
                                                                                                    <td>{{ $stokBarangTokoUtama->stock }}</td>
                                                                                                    <td>Rp. {{ number_format($stokBarangTokoUtama->harga_satuan, 0, ',', '.') }}</td>
                                                                                                @else
                                                                                                    <td>0</td>
                                                                                                    <td>Rp. 0</td>
                                                                                                @endif
                                                                                            @else
                                                                                                {{-- Tampilkan stok dari tabel detail_toko untuk toko selain id = 1 --}}
                                                                                                @php
                                                                                                    // Ambil stok dari tabel detail_toko hanya untuk barang yang sedang diklik
                                                                                                    $stokBarangLain = $stokTokoLain->where('id_barang', $stk->id_barang)->where('id_toko', $tk->id)->first();
                                                                                                @endphp

                                                                                                @if ($stokBarangLain)
                                                                                                    <td>{{ $stokBarangLain->qty }}</td>
                                                                                                    <td>Rp. {{ number_format($stokBarangLain->harga, 0, ',', '.') }}</td>
                                                                                                @else
                                                                                                    <td>0</td>
                                                                                                    <td>Rp. 0</td>
                                                                                                @endif
                                                                                            @endif
                                                                                        </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>

                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <form>
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">Level 1</span>
                                                                                    </div>
                                                                                    <input type="text" class="form-control" placeholder="">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">0%</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">Level 2</span>
                                                                                    </div>
                                                                                    <input type="text" class="form-control" placeholder="">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">0%</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="input-group mb-3">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">Level 3</span>
                                                                                    </div>
                                                                                    <input type="text" class="form-control" placeholder="">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">0%</span>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade" id="profile-{{$stk->id}}" role="tabpanel" aria-labelledby="profile-tab-{{$stk->id}}">
                                                                    More Tab
                                                                </div>
                                                                <div class="tab-pane fade" id="contact-{{$stk->id}}" role="tabpanel" aria-labelledby="contact-tab-{{$stk->id}}">
                                                                    Another Tab
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Kembali</button>
                                                            <button type="button" class="btn btn-primary btn-sm">Simpan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    Menampilkan <span id="current-count">0</span> data dari <span id="total-count">0</span> total data.
                                                </div>
                                                <nav aria-label="Page navigation example">
                                                    <ul class="pagination justify-content-end" id="pagination">
                                                      {{-- isian paginate --}}
                                                    </ul>
                                                  </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Start Modal --}}

                        <!-- [ Main Content ] end -->
                    </div>
                </div>
            </div>
</div>

@endsection
