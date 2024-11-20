<title>Data Toko - Gecorp</title>
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
                            <h5 class="m-b-10">Data Toko</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('master.index')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a>Data Toko</a></li>
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
                        @if (Auth::user()->id_level == 1)
                        <a href="{{ route('master.toko.create')}}" class="btn btn-primary">
                            <i class="ti-plus menu-icon"></i> Tambah
                        </a>
                        @else
                        <a href="{{ route('master.toko.create')}}" class="disabled btn btn-secondary">
                            <i class="ti-plus menu-icon"></i> Tambah
                        </a>
                        @endif
                        <!-- Input Search -->
                        <form class="d-flex" method="GET" action="{{ route('master.toko.index') }}">
                            <input class="form-control me-2" id="search" type="search" name="search" placeholder="Cari Toko" aria-label="Search">
                        </form>
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm" id="jsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Toko</th>
                                        <th>Singkatan</th>
                                        <th>Level Harga</th>
                                        <th>Wilayah</th>
                                        <th>Alamat</th>
                                        <th>List Barang</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($toko as $tk)
                                    <tr data-id="{{ $tk->id }}">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$tk->nama_toko}}</td>
                                        <td>{{$tk->singkatan}}</td>
                                        <td>
                                            @php
                                                $levelHargaArray = json_decode($tk->id_level_harga, true) ?? [];
                                                if (is_int($levelHargaArray)) {
                                                    $levelHargaArray = [$levelHargaArray];
                                                }
                                            @endphp
                                            @if(!empty($levelHargaArray) && is_array($levelHargaArray))
                                                @foreach($levelHargaArray as $levelHargaId)
                                                    @php
                                                        $levelHarga = \App\Models\LevelHarga::find($levelHargaId);
                                                    @endphp
                                                    {{ $levelHarga ? $levelHarga->nama_level_harga : 'N/A' }}
                                                    @if (!$loop->last), @endif
                                                @endforeach
                                            @else
                                                Tidak Ada Level
                                            @endif
                                        </td>
                                        <td>{{$tk->wilayah}}</td>
                                        <td>{{$tk->alamat}}</td>
                                        <td><a href="{{ route('master.toko.detail', $tk->id)}}" class="btn btn-primary btn-sm" style="font-size: 12px"><strong>Cek Detail</strong></a></td>
                                        <form onsubmit="return confirm('Ingin menghapus Data ini ?');" action="{{ route('master.toko.delete', $tk->id)}}" method="post">
                                        <td style="">
                                            <a href="{{ route('master.toko.edit', $tk->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                @if (Auth::user()->id_level == 1)
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash menu-icon"></i></button>
                                                @endif
                                            </td>
                                        </form>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6">Tidak ada data.</td>
                                    </tr>
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
