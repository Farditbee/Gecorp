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
                            <input class="form-control me-2" id="search" type="search" name="search" placeholder="Cari Promo" aria-label="Search">
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
                                        <th>Terjual</th>
                                        <th>dari</th>
                                        <th>sampai</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $promo as $prm )
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$prm->barang->nama_barang}}</td>
                                        <td>{{$prm->diskon}}%</td>
                                        <td>{{$prm->minimal}} Item</td>
                                        <td>{{$prm->terjual}} Item</td>
                                        <td>{{ \Carbon\Carbon::parse($prm->dari)->format('d-m-Y / H:i:s') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($prm->sampai)->format('d-m-Y / H:i:s') }}</td>
                                            @if ($prm->status == "done")
                                            <td><span class="badge badge-success badge-sm">{{$prm->status}}</span></td>
                                            @else
                                            <td><span class="badge badge-warning badge-sm">{{$prm->status}}</span></td>
                                            @endif
                                    <td>
                                        @if ($prm->status == "ongoing")
                                        <button type="button" class="btn btn-warning btn-sm atur-harga-btn" data-toggle="modal" data-target="#mediumModal-{{ $prm->id }}" data-id_barang="{{ $prm->id_barang }}" data-id="{{ $prm->id }}" style="font-size: 12px;">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-success btn-sm atur-harga-btn" disabled data-toggle="modal" data-target="#mediumModal-{{ $prm->id }}" data-id_barang="{{ $prm->id_barang }}" data-id="{{ $prm->id }}" style="font-size: 12px;">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        @endif
                                    </td>
                                    </tr>
                                    @endforeach

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

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lgs">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title h4" id="myLargeModalLabel">Data Promo</h6>
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
                                <div class="form-group">
                                    <label for="toko" class="form-control-label">Nama toko</label>
                                    <select name="toko" id="toko" class="form-control">
                                        <option value="" selected>Pilih toko</option>
                                        @foreach ($toko as $tk)
                                        <option value="{{ $tk->id }}">{{ $tk->nama_toko }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <label for="minimal" class="form-control-label">Minimal</label>
                                <input class="form-control" type="number" min='1' name="minimal" id="minimal">
                            </div>

                            <div class="col-3">
                                <label for="id_supplier" class="form-control-label">Jumlah</label>
                                <input class="form-control" type="number" min='0' name="jumlah" id="jumlah">
                                <small id="jumlah-error" style="color: red; display: none;">Jumlah harus kelipatan dari minimal</small>
                            </div>

                            <div class="col-3">
                                <label for="id_supplier" class="form-control-label">diskon</label>
                                <input class="form-control" type="number" min='0' max='100' name="diskon" id="diskon">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label for="id_supplier" class="form-control-label">Dari</label>
                                <input class="form-control" type="datetime-local" name="dari" id="dari">
                            </div>
                            <div class="col-6">
                                <label for="id_supplier" class="form-control-label">Sampai</label>
                                <input class="form-control" type="datetime-local" name="sampai" id="sampai">
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

    @foreach ($promo as $prm)
        <div class="modal fade" id="mediumModal-{{ $prm ->id }}" tabindex="-1" role="dialog"
            aria-labelledby="mediumModalLabel-{{ $prm ->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lgs" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title h4" id="myLargeModalLabel">Edit Promo</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-tambah-pembelian" action="{{ route('master.promo.store') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-3">
                                    <!-- Nama Supplier -->
                                    <div class="form-group">
                                        <label for="barang" class="form-control-label">Nama Barang</label>
                                        <select name="barang" id="barang" class="form-control">
                                            <option value="" selected>Pilih Barang</option>
                                            @foreach ($barang as $brg)
                                            <option value="{{ $brg->id }}" {{ old('id_barang', $prm->id_barang) == $brg->id ? 'selected' : '' }}>
                                                {{ $brg->nama_barang }}
                                            </option>
                                            <option value="{{ $brg->id }}">{{ $brg->nama_barang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <label for="minimal" class="form-control-label">Minimal</label>
                                    <input type="number" id="minimal" min='1' name="minimal" value="{{ old('minimal', $prm->minimal) }}" class="form-control">
                                </div>

                                <div class="col-3">
                                    <label for="jumlah" class="form-control-label">Jumlah</label>
                                    <input type="number" id="jumlah" min='0' name="jumlah" value="{{ old('jumlah', $prm->jumlah) }}" class="form-control">
                                    <small id="jumlah-error" style="color: red; display: none;">Jumlah harus kelipatan dari minimal</small>
                                </div>

                                <div class="col-3">
                                    <label for="diskon" class="form-control-label">diskon</label>
                                    <input type="number" id="diskon" min='0' name="diskon" value="{{ old('diskon', $prm->diskon) }}" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <label for="dari" class="form-control-label">Dari</label>
                                    <input type="datetime-local" id="dari" min='0' name="dari" value="{{ old('dari', $prm->dari) }}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label for="sampai" class="form-control-label">Sampai</label>
                                    <input type="datetime-local" id="sampai" min='0' name="sampai" value="{{ old('sampai', $prm->sampai) }}" class="form-control">
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
    @endforeach
        <!-- [ Main Content ] end -->
    </div>
</div>

<script>
    let typingTimer;
    const doneTypingInterval = 500; // Waktu jeda setelah selesai mengetik (ms)

    document.getElementById("jumlah").addEventListener("input", function() {
        clearTimeout(typingTimer);
        const jumlahInput = this;

        typingTimer = setTimeout(() => {
            const minimal = parseInt(document.getElementById("minimal").value);
            const jumlah = parseInt(jumlahInput.value);
            const errorMessage = document.getElementById("jumlah-error");

            // Pastikan minimal ada nilainya dan jumlah bukan kelipatan dari minimal
            if (minimal && jumlah % minimal !== 0) {
                errorMessage.style.display = "block";
                errorMessage.textContent = `Jumlah harus kelipatan dari ${minimal}`;
                jumlahInput.value = ""; // Mengosongkan input jumlah jika tidak valid
            } else {
                errorMessage.style.display = "none";
            }
        }, doneTypingInterval);
    });
</script>

@endsection
