<title>Data Promo - Gecorp</title>
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
                            <h5 class="m-b-10">Data Promo</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('master.index')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a>Data Promo</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <!-- Tombol Tambah -->
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target=".bd-example-modal-lg">
                                Tambah
                        </button>
                        <!-- Input Search -->
                        <form class="d-flex" method="GET" action="{{ route('master.levelharga.index') }}">
                            <input class="form-control me-2" id="search" type="search" name="search" placeholder="Cari Level Harga" aria-label="Search">
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
                                        <th>Diskon</th>
                                        <th>Jumlah</th>
                                        <th>dari</th>
                                        <th>sampai</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Oppo</td>
                                        <td>10%</td>
                                        <td>90</td>
                                        <td>10 Agustus</td>
                                        <td>25 Agustus</td>
                                        <td>Done</td>
                                    </tr>
                                </tbody>
                            </table>
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

        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lgs">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title h4" id="myLargeModalLabel">Data Transaksi</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-tambah-pembelian" action="{{ route('master.promo.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-3">
                                <!-- Nama Supplier -->
                                <div class="form-group">
                                    <label for="barang" class="form-control-label">Nama Barang</label>
                                    <select name="barang" id="barang" class="form-control">
                                        <option value="" selected>Pilih Barang</option>
                                        @foreach ($barang as $brg)
                                        <option value="{{ $brg->id }}">{{ $brg->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <label for="minimal" class="form-control-label">Minimal</label>
                                <input class="form-control" type="number" min='0' name="minimal" id="minimal">
                            </div>

                            <div class="col-3">
                                <label for="id_supplier" class="form-control-label">Jumlah</label>
                                <input class="form-control" type="number" min='0' name="jumlah" id="jumlah">
                            </div>

                            <div class="col-3">
                                <label for="id_supplier" class="form-control-label">diskon</label>
                                <input class="form-control" type="number" min='0' max='100' name="diskon" id="diskon">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label for="id_supplier" class="form-control-label">Dari</label>
                                <input class="form-control" type="date" name="dari" id="dari">
                            </div>
                            <div class="col-6">
                                <label for="id_supplier" class="form-control-label">Sampai</label>
                                <input class="form-control" type="date" name="sampai" id="sampai">
                            </div>
                        </div><br>
                        <button type="submit" style="float: right" id="save-btn" class="btn btn-primary">
                            <span id="save-btn-text"><i class="fa fa-save"></i> Simpan</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <!-- [ Main Content ] end -->
    </div>
</div>

@endsection
