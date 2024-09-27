<title>Pembelian Barang - Gecorp</title>
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
                                            <h4 class="m-b-10 ml-3">Pembelian Barang</h4>
                                        </div>
                                        <ul class="breadcrumb ">
                                            <li class="breadcrumb-item ml-3"><a href="{{ route('master.index')}}"><i class="feather icon-home"></i></a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('master.pembelianbarang.index')}}">Pembelian Barang</a></li>
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
                                        <form class="d-flex" method="GET" action="{{ route('master.pembelianbarang.index') }}">
                                            <input class="form-control me-2" id="search" type="search" name="search" placeholder="Cari Pembelian" aria-label="Search">
                                        </form>
                                    </div>
                                    <div class="content">
                                        @if (session('success'))
                                        <div class="alerts">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-success" role="alert" id="success-alert">
                                                        {{ session('success') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="jsTable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Status</th>
                                                        <th>No. Nota</th>
                                                        <th>Tgl Nota</th>
                                                        <th>Supplier</th>
                                                        <th>Total Item</th>
                                                        <th>Total Harga</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($pembelian as $beli)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        @if ($beli->status == 'progress')
                                                            <td><h5><span class="badge badge-warning fixed-badge">Progress</span></h5></td>
                                                        @elseif ($beli->status == 'success')
                                                            <td><h5><span class="badge badge-success fixed-badge">Success</span></h5></td>
                                                        @elseif ($beli->status == 'failed')
                                                            <td><h5><span class="badge badge-danger fixed-badge">Failed</span></h5></td>
                                                        @else
                                                            <td><h5><span class="badge badge-primary fixed-badge">Mixed</span></h5></td>
                                                        @endif
                                                        <td>{{ $beli->no_nota }}</td>
                                                        <td>{{ \DateTime::createFromFormat('Y-m-d H:i:s', $beli->tgl_nota)->format('d-m-Y') }}</td>
                                                        <td>{{ $beli->supplier->nama_supplier }}</td>
                                                        <td>{{ $beli->total_item }}</td>
                                                        <td>Rp. {{ number_format($beli->total_nilai, 0, '.', '.') }} </td>
                                                        <td>
                                                            <form onsubmit="return confirm('Ingin menghapus Kostum ini ? ?');" action="{{ route('master.pembelianbarang.delete', $beli->id) }}" method="POST">
                                                                @if ($beli->status == 'progress')
                                                                <a href="{{ route('master.pembelianbarang.edit', $beli->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-book menu-icon"></i></a>
                                                                @else
                                                                <a href="{{ route('master.pembelianbarang.edit', $beli->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit menu-icon"></i></a>
                                                                @endif
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash menu-icon"></i></button>
                                                            </form>
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
