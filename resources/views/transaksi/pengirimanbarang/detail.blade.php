<title>Detail Pengiriman Barang - Gecorp</title>
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
                                            <h4 class="m-b-10 ml-3">Detail Pengiriman Barang</h4>
                                        </div>
                                        <ul class="breadcrumb ">
                                            <li class="breadcrumb-item ml-3"><a href="{{ route('master.index')}}"><i class="feather icon-home"></i></a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('master.pengirimanbarang.index')}}">Data Toko</a></li>
                                            <li class="breadcrumb-item"><a>Detail Pengiriman Barang</a></li>
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
                                                    <td><h4><span class="badge badge-danger">Failed</span></h4></td>
                                                @elseif ($dtpr->status == 'progress')
                                                <td><h4><span class="badge badge-warning">Progress</span></h4></td>
                                                @else
                                                <td><h4><span class="badge badge-success">Success</span></h4></td>
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
                        <!-- [ Main Content ] start -->
                        <!-- [ Main Content ] end -->
                    </div>
                </div>
            </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Fungsi Search
    $(document).ready(function() {
        $("#search").on("keyup", function() {
            console.log('kons');
            var value = $(this).val().toLowerCase();  // Ambil nilai input
            $("#jsTable tbody tr").filter(function() {
                // Show/hide baris berdasarkan pencarian pada kolom yang ada
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    // Fungsi Paginate
    $(document).ready(function(){
    var rowsPerPage = 10; // Jumlah baris per halaman
    var rows = $('#jsTable tbody tr'); // Mengambil semua baris dari tabel
    var rowsCount = rows.length; // Menghitung jumlah total baris
    var pageCount = Math.ceil(rowsCount / rowsPerPage); // Menghitung jumlah halaman
    var numbers = $('#pagination'); // Elemen untuk tombol pagination
    var currentPage = 1; // Halaman aktif saat ini

    // Fungsi untuk menampilkan baris sesuai halaman
    function showPage(page) {
        var start = (page - 1) * rowsPerPage;
        var end = start + rowsPerPage;
        rows.hide(); // Sembunyikan semua baris
        rows.slice(start, end).show(); // Tampilkan baris sesuai halaman yang dipilih
    }

    // Fungsi untuk membuat tombol pagination
    function createPaginationButtons() {
        numbers.empty(); // Hapus semua tombol pagination yang ada
        numbers.append('<button class="prev-btn btn btn-sm btn-outline-primary mx-1">Previous</button>'); // Tombol Previous

        for (var i = 1; i <= pageCount; i++) {
            numbers.append('<button class="page-btn btn btn-sm btn-primary mx-1" data-page="' + i + '">' + i + '</button>');
        }

        numbers.append('<button class="next-btn btn btn-sm btn-outline-primary mx-1">Next</button>'); // Tombol Next

        // Menonaktifkan tombol Previous jika berada di halaman pertama
        if (currentPage === 1) {
            $('.prev-btn').prop('disabled', true);
        } else {
            $('.prev-btn').prop('disabled', false);
        }

        // Menonaktifkan tombol Next jika berada di halaman terakhir
        if (currentPage === pageCount) {
            $('.next-btn').prop('disabled', true);
        } else {
            $('.next-btn').prop('disabled', false);
        }

        // Menyoroti tombol halaman yang aktif
        $('.page-btn').removeClass('active');
        $('.page-btn[data-page="' + currentPage + '"]').addClass('active');
    }

    // Inisialisasi tampilan halaman pertama
    showPage(currentPage);
    createPaginationButtons();

    // Ketika tombol halaman diklik
    numbers.on('click', '.page-btn', function(){
        currentPage = $(this).data('page');
        showPage(currentPage);
        createPaginationButtons();
    });

    // Ketika tombol Previous diklik
    numbers.on('click', '.prev-btn', function(){
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
            createPaginationButtons();
        }
    });

    // Ketika tombol Next diklik
    numbers.on('click', '.next-btn', function(){
        if (currentPage < pageCount) {
            currentPage++;
            showPage(currentPage);
            createPaginationButtons();
        }
    });
});

// Fungsi Show Data
$(document).ready(function(){
    // Mengambil semua baris dari tabel
    var rows = $('#jsTable tbody tr');
    var totalData = rows.length; // Total semua data di tabel

    // Tampilkan total data di awal
    $('#total-count').text(totalData);

    // Fungsi untuk memperbarui jumlah data yang terlihat
    function updateDataCount() {
        var visibleRows = rows.filter(':visible').length; // Hitung jumlah baris yang terlihat
        $('#current-count').text(visibleRows); // Perbarui jumlah data yang ditampilkan
        $('#total-count').text(totalData); // Menampilkan jumlah total data
    }

    // Inisialisasi jumlah data yang tampil di awal
    updateDataCount();

    // Fungsi pencarian
    $('#searchInput').on('keyup', function(){
        var value = $(this).val().toLowerCase();

        rows.filter(function(){
            // Tampilkan hanya baris yang sesuai dengan input pencarian
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });

        // Perbarui jumlah data yang tampil setelah pencarian
        updateDataCount();
    });

    // Panggil updateDataCount() ketika melakukan pagination
    $('#pagination').on('click', '.page-btn', function(){
        // Logika pagination (jika ada)
        updateDataCount(); // Perbarui jumlah data yang terlihat
    });
});

</script>

@endsection
