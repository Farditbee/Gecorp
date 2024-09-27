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
                                                    @forelse ($stock as $stk)
                                                    <tr>
                                                        <td>{{$no++}}</td>
                                                        <td type="button" style="padding-top: 10px;" data-toggle="modal" data-target="#mediumModal">{{$stk->nama_barang}}</td>
                                                        <td>{{$stk->stock}}</td>
                                                        <td>Rp. {{ number_format($stk->hpp_baru, 0, '.', '.') }}</td>
                                                        <td type="button" style="padding-top: 10px;" data-toggle="modal" data-target="#mediumModal" data-nama-barang="{{$stk->nama_barang}}">{{$stk->nama_barang}}</td>
                                                        <td>
                                                            <form onsubmit="return confirm('Ingin menghapus Data ini ? ?');" action="#" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash menu-icon"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <div class="alert alert-danger">
                                                        Data Stock belum Tersedia.
                                                    </div>
                                                    @endforelse
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
                        {{-- Start Modal --}}
                        <div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="mediumModalLabel">{{$stk->nama_barang}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active text-uppercase" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Barang Di Toko</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link text-uppercase" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link text-uppercase" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="myTabContent">
                                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                                    <div class="row">
                                                    <div class="col-md-7">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped" id="jsTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Nama Barang</th>
                                                                            <th>Stock</th>
                                                                            <th>Harga Satuan (Hpp Baru)</th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td type="button" style="padding-top: 10px;" data-toggle="modal" data-target="#mediumModal">Nama Barang</td>
                                                                            <td>20</td>
                                                                            <td>Rp. 100.000</td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                    </div>
                                                    <div class="col-md-5 .ml-0">
                                                        <form>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Level 1</span>
                                                                </div>
                                                                <div class="custom-file">
                                                                    <input type="text" class="form-control" placeholder="">
                                                                    <label class="input-group-text">0%</label>
                                                                </div>
                                                            </div>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Level 2</span>
                                                                </div>
                                                                <div class="custom-file">
                                                                    <input type="text" class="form-control" placeholder="">
                                                                    <label class="input-group-text">0%</label>
                                                                </div>
                                                            </div>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Level 3</span>
                                                                </div>
                                                                <div class="custom-file">
                                                                    <input type="text" class="form-control" placeholder="">
                                                                    <label class="input-group-text">0%</label>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                    More Tab
                                                </div>
                                                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
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
                        <!-- [ Main Content ] end -->
                    </div>
                </div>
            </div>
</div>

<script>
    // Saat modal akan ditampilkan
    $('#mediumModal').on('show.bs.modal', function (event) {
        // Dapatkan tombol yang diklik
        var button = $(event.relatedTarget);

        // Ambil data-nama-barang dari tombol tersebut
        var namaBarang = button.data('nama=barang');

        // Update judul modal dengan nama barang
        var modal = $(this);
        modal.find('.modal-title').text(namaBarang);
    });
</script>


@endsection
