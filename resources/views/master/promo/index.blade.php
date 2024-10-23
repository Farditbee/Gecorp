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
                        <a href="{{ route('master.supplier.create') }}" class="btn btn-primary">
                            <i class="ti-plus menu-icon"></i> Tambah
                        </a>
                        <!-- Input Search -->
                        <form class="d-flex" method="GET" action="{{ route('master.stockbarang.index') }}">
                            <input class="form-control me-2" id="search" type="search" name="search"
                                placeholder="Cari Barang" aria-label="Search">
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
                                        <th>Jenis Barang</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr data-toggle="modal" class="atur-harga-btn" data-target="#mediumModal-{{ $stk->id }}" data-id_barang="{{ $stk->id_barang }}" data-id="{{ $stk->id }}">
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $stk->nama_barang }}</td>
                                            <td>{{ $stk->barang->jenis->nama_jenis_barang }}</td>
                                                <button type="button" class="btn btn-primary btn-sm atur-harga-btn"
                                                    data-toggle="modal" data-target="#mediumModal-{{ $stk->id }}"
                                                    data-id_barang="{{ $stk->id_barang }}" data-id="{{ $stk->id }}" style="font-size: 12px;">
                                                    Cek Detail
                                                </button>
                                            </td>
                                            <form onsubmit="return confirm('Ingin menghapus Data ini ?');"
                                            action="#" method="POST">
                                            <td>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                                            class="fa fa-trash menu-icon"></i></button>
                                                        </td>
                                                    </form>
                                        </tr>
                                </tbody>
                            </table>
                            @foreach ($stock as $stk)
                                <div class="modal fade" id="mediumModal-{{ $stk->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="mediumModalLabel-{{ $stk->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="mediumModalLabel-{{ $stk->id }}">
                                                    {{ $stk->barang->nama_barang }} : @php
                                                    $stokBarang = $stock->where('id_barang', $stk->id_barang)->first();
                                                @endphp
                                                Rp. {{ number_format($stokBarang->hpp_baru, 0, ',', '.') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="nav nav-tabs mb-3" id="myTab-{{ $stk->id }}"
                                                    role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active text-uppercase"
                                                            id="home-tab-{{ $stk->id }}" data-toggle="tab"
                                                            href="#home-{{ $stk->id }}" role="tab"
                                                            aria-controls="home-{{ $stk->id }}"
                                                            aria-selected="true">Barang Di Toko</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link text-uppercase"
                                                            id="atur-harga-tab-{{ $stk->id }}"
                                                            data-toggle="tab"
                                                            href="#atur-harga-{{ $stk->id }}"
                                                            role="tab"
                                                            aria-controls="atur-harga-{{ $stk->id }}"
                                                            aria-selected="false">Atur Harga</a>
                                                    </li>
                                                    {{-- <li class="nav-item">
                                                        <a class="nav-link text-uppercase"
                                                            id="contact-tab-{{ $stk->id }}"
                                                            data-toggle="tab"
                                                            href="#contact-{{ $stk->id }}" role="tab"
                                                            aria-controls="contact-{{ $stk->id }}"
                                                            aria-selected="false">Contact</a>
                                                    </li> --}}
                                                </ul>
                                                <div class="tab-content" id="myTabContent-{{ $stk->id }}">
                                                    <div class="tab-pane fade show active"
                                                        id="home-{{ $stk->id }}" role="tabpanel"
                                                        aria-labelledby="home-tab-{{ $stk->id }}">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped"
                                                                        id="jsTable-{{ $stk->id }}">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Nama Toko</th>
                                                                                <th>Stock</th>
                                                                                <th>Level Harga</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($toko as $tk)
                                                                                <tr>
                                                                                    <td>{{ $tk->nama_toko }}
                                                                                    </td>

                                                                                    @if ($tk->id == 1)
                                                                                        {{-- Tampilkan stok dari tabel stock_barang untuk toko dengan id = 1 --}}
                                                                                        @php
                                                                                            // Ambil stok dari tabel stock_barang hanya untuk barang yang sedang diklik
                                                                                            $stokBarangTokoUtama = $stock
                                                                                                ->where(
                                                                                                    'id_barang',
                                                                                                    $stk->id_barang,
                                                                                                )
                                                                                                ->first();
                                                                                        @endphp

                                                                                        @if ($stokBarangTokoUtama)
                                                                                            <td>{{ $stokBarangTokoUtama->stock }}
                                                                                            </td>
                                                                                        @else
                                                                                            <td>0</td>
                                                                                        @endif
                                                                                    @else
                                                                                        {{-- Tampilkan stok dari tabel detail_toko untuk toko selain id = 1 --}}
                                                                                        @php
                                                                                            // Ambil stok dari tabel detail_toko hanya untuk barang yang sedang diklik
                                                                                            $stokBarangLain = $stokTokoLain
                                                                                                ->where(
                                                                                                    'id_barang',
                                                                                                    $stk->id_barang,
                                                                                                )
                                                                                                ->where(
                                                                                                    'id_toko',
                                                                                                    $tk->id,
                                                                                                )
                                                                                                ->first();
                                                                                        @endphp
                                                                                        @if ($stokBarangLain)
                                                                                            <td>{{ $stokBarangLain->qty }}
                                                                                            </td>
                                                                                        @else
                                                                                            <td>0</td>
                                                                                        @endif
                                                                                    @endif
                                                                                    <td>
                                                                                        @php
                                                                                            $levelHargaArray =
                                                                                                json_decode(
                                                                                                    $tk->id_level_harga,
                                                                                                    true,
                                                                                                ) ?? [];
                                                                                            if (
                                                                                                is_int(
                                                                                                    $levelHargaArray,
                                                                                                )
                                                                                            ) {
                                                                                                $levelHargaArray = [
                                                                                                    $levelHargaArray,
                                                                                                ];
                                                                                            }
                                                                                        @endphp
                                                                                        @if (!empty($levelHargaArray) && is_array($levelHargaArray))
                                                                                            @foreach ($levelHargaArray as $levelHargaId)
                                                                                                @php
                                                                                                    $levelHarga = \App\Models\LevelHarga::find(
                                                                                                        $levelHargaId,
                                                                                                    );
                                                                                                @endphp
                                                                                                {{ $levelHarga ? $levelHarga->nama_level_harga : 'N/A' }}
                                                                                                @if (!$loop->last)
                                                                                                    ,
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @else
                                                                                            Tidak Ada Level
                                                                                            Harga
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="atur-harga-{{ $stk->id }}" role="tabpanel" aria-labelledby="atur-harga-tab-{{ $stk->id }}">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="harga-form" id="harga-form-{{ $stk->id_barang }}">
                                                                    <form>
                                                                        @php
                                                                            $stokBarang = $stock->where('id_barang', $stk->id_barang)->first();
                                                                        @endphp
                                                                        @foreach ($levelharga as $lh)
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">{{ $lh->nama_level_harga }}</span>
                                                                            </div>
                                                                            <input type="text" id="harga-{{ $stk->id_barang }}-{{ str_replace(' ', '-', $lh->nama_level_harga) }}"
                                                                                   class="form-control level-harga"
                                                                                   placeholder="Atur harga baru"
                                                                                   data-raw-value="">
                                                                            <input type="hidden" id="harga-{{ $stk->id_barang }}-{{ str_replace(' ', '-', $lh->nama_level_harga) }}-hidden"
                                                                                   name="harga_level_{{ $lh->id }}_barang_{{ $stk->id_barang }}" value="">
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text" id="persen-{{ $stk->id_barang }}-{{ str_replace(' ', '-', $lh->nama_level_harga) }}">0%</span>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                    <input type="hidden" id="hpp-baru-{{ $stk->id_barang }}" value="{{ $stokBarang->hpp_baru }}">
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade"
                                                        id="contact-{{ $stk->id }}" role="tabpanel"
                                                        aria-labelledby="contact-tab-{{ $stk->id }}">
                                                        Another Tab
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-dismiss="modal">Kembali</button>
                                                <button type="button"
                                                    class="btn btn-primary btn-sm">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    Menampilkan <span id="current-count">0</span> data dari <span
                                        id="total-count">0</span> total data.
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
        <!-- /.content -->

        <!-- Footer -->
@endsection
