<title>Data User - Gecorp</title>
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
                                            <h4 class="m-b-10 ml-3">Data User</h4>
                                        </div>
                                        <ul class="breadcrumb ">
                                            <li class="breadcrumb-item ml-3"><a href="{{ route('master.index')}}"><i class="feather icon-home"></i></a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('master.user.index')}}">Data User</a></li>
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
                                        <a href="{{ route('master.user.create')}}" class="btn btn-primary">
                                            <i class="ti-plus menu-icon"></i> Tambah
                                        </a>
                                        <!-- Input Search -->
                                        <form class="d-flex" method="GET" action="{{ route('master.user.index') }}">
                                            <input class="form-control me-2" id="search" type="search" name="search" placeholder="Cari User" aria-label="Search">
                                        </form>
                                    </div>
                                    <x-adminlte-alerts />
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="jsTable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nama User</th>
                                                        <th>Level</th>
                                                        <th>Toko</th>
                                                        <th>Username</th>
                                                        <th>Email</th>
                                                        <th>No. HP</th>
                                                        <th>Alamat</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; ?>
                                                    @forelse ($user as $usr)
                                                    <tr>
                                                        <td>{{$no++}}</td>
                                                        <td>{{$usr->nama}}</td>
                                                        <td>{{$usr->leveluser->nama_level}}</td>
                                                        <td>{{$usr->toko->nama_toko}}</td>
                                                        <td>{{$usr->username}}</td>
                                                        <td>{{$usr->email}}</td>
                                                        <td>{{$usr->no_hp}}</td>
                                                        <td>{{$usr->alamat}}</td>
                                                        <td>
                                                            <form onsubmit="return confirm('Ingin menghapus Data ini ? ?');" action="{{ route('master.user.delete', $usr->id)}}" method="post">
                                                                <a href="{{ route('master.user.edit', $usr->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit menu-icon"></i></a>
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash menu-icon"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <td colspan="6">Tidak ada data.</td>
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
                    </div>
                </div>
            </div>
</div>

@endsection
