@extends('layouts.main')

@section('title')
    Detail Pengiriman Barang
@endsection

@section('content')

<div class="pcoded-main-container">
    <div class="pcoded-content">
        @include('components.breadcrumbs')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <!-- Tombol Tambah -->
                        <a href="{{ route('master.pengirimanbarang.index')}}" class="btn btn-danger">
                            <i class="ti-plus menu-icon"></i> Kembali
                        </a>
                        <!-- Input Search -->
                        <form class="d-flex" method="GET" action="{{ route('master.pengirimanbarang.detail', $pengiriman_barang->id) }}">
                            <input class="form-control me-2" id="search" type="search" name="search" placeholder="Cari Detail" aria-label="Search">
                        </form>
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        @if ($pengiriman_barang)
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-2">
                                        <h5 class="mb-0"><i class="fa fa-barcode"></i> Nomor Resi</h5>
                                    </div>
                                    <div class="col">
                                        <span class="badge badge-pill badge-secondary">{{ $pengiriman_barang->no_resi }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-2">
                                        <h5 class="mb-0"><i class="fa fa-home"></i> Toko Pengirim</h5>
                                    </div>
                                    <div class="col">
                                        <span class="badge badge-pill badge-secondary">{{ $pengiriman_barang->toko->nama_toko }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-2">
                                        <h5 class="mb-0"><i class="fa fa-tag"></i> Nama Pengirim</h5>
                                    </div>
                                    <div class="col">
                                        <span class="badge badge-pill badge-secondary">{{ $pengiriman_barang->user->nama }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-2">
                                        <h5 class="mb-0"><i class="fa fa-truck"></i> Ekspedisi</h5>
                                    </div>
                                    <div class="col">
                                        <span class="badge badge-pill badge-secondary">{{ $pengiriman_barang->ekspedisi }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-2">
                                        <h5 class="mb-0"><i class="fa fa-home"></i> Toko Penerima</h5>
                                    </div>
                                    <div class="col">
                                        <span class="badge badge-pill badge-secondary">{{ $pengiriman_barang->tokos->nama_toko }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-2">
                                        <h5 class="mb-0"><i class="fa fa-calendar"></i> Tanggal Kirim</h5>
                                    </div>
                                    <div class="col">
                                        <span class="badge badge-pill badge-secondary">{{ $pengiriman_barang->tgl_kirim }}</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-striped" id="jsTable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th>Status</th>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Qty</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                            @forelse ($detail_pengiriman as $dtpr)
                            <tr>
                                <td>{{ $loop->iteration}}</td>
                                @if ($dtpr->status == 'failed')
                                    <td><span class="badge badge-danger fixed-badge">Failed</span></td>
                                @elseif ($dtpr->status == 'progress')
                                <td><span class="badge badge-warning fixed-badge">Progress</span></td>
                                @else
                                <td><span class="badge badge-success fixed-badge">Success</span></td>
                                @endif
                                <td>{{ $dtpr->nama_barang}}</td>
                                <td>{{ $dtpr->qty}}</td>
                                <td>Rp. {{ number_format($dtpr->harga, 0, '.', '.') }}</td>
                                <td>Rp. {{ number_format($dtpr->total_harga, 0, '.', '.')}}</td>
                            </tr>
                            @empty

                        @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col" colspan="5" style="text-align:right">SubTotal</th>
                                    <th scope="col">Rp. {{ number_format($pengiriman_barang->total_nilai, 0, '.', '.')}}</th>
                                </tr>

                            </tfoot>
                        </table>
                        <hr>
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
                            @else
                    <div class="alert alert-warning">
                        <strong>Perhatian!</strong> Anda perlu menambahkan data pengiriman di tab "Tambah Pengiriman" terlebih dahulu.
                    </div>
                    @endif
                    <div class="tab-pane fade" id="custom-nav-contact" role="tabpanel" aria-labelledby="custom-nav-contact-tab">
                        <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, irure terry richardson ex sd. Alip placeat salvia cillum iphone. Seitan alip s cardigan american apparel, butcher voluptate nisi .</p>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>


@endsection
