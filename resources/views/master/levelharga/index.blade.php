<title>Data Level Harga - Gecorp</title>
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
                            <h5 class="m-b-10">Data Level Harga</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('master.index')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a>Data Level Harga</a></li>
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
                        <a href="{{ route('master.levelharga.create')}}" class="btn btn-primary">
                            <i class="ti-plus menu-icon"></i> Tambah
                        </a>
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
                                        <th>Nama Level Harga</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @forelse ($levelharga as $lh)
                                    <tr>
                                        <td>{{$no++}}</td>
                                        <td>{{$lh->nama_level_harga}}</td>
                                        <form onsubmit="return confirm('Ingin menghapus Kostum ini ? ?');" action="{{ route('master.levelharga.delete', $lh->id)}}" method="post">
                                        <td>
                                            <a href="{{ route('master.levelharga.edit', $lh->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit menu-icon"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash menu-icon"></i></button>
                                            </td>
                                        </form>
                                    </tr>
                                    @empty
                                    <div class="alert alert-danger">
                                        Data Level Harga belum Tersedia.
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
        <!-- [ Main Content ] end -->
    </div>
</div>

@endsection
