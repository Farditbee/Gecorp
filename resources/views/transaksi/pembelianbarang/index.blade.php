<title>Pembelian Barang - Gecorp</title>
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
                            <h5 class="m-b-10">Data Pembelian Barang</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('master.index')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a>Data Pembelian Barang</a></li>
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
                        {{-- <a href="{{ route('master.pembelianbarang.create')}}" class="btn btn-primary">
                            <i class="ti-plus menu-icon"></i> Tambah
                        </a> --}}
                        <a href="" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">
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
                                @foreach ($pembelian_dt as $beli)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        @if ($beli->status == 'progress')
                                            <td><span class="badge badge-warning fixed-badge">Progress</span></td>
                                        @elseif ($beli->status == 'success')
                                            <td><span class="badge badge-success fixed-badge">Success</span></td>
                                        @elseif ($beli->status == 'failed')
                                            <td><span class="badge badge-danger fixed-badge">Failed</span></td>
                                        @else
                                            <td><span class="badge badge-primary fixed-badge">Mixed</span></td>
                                        @endif
                                        <td>{{ $beli->no_nota }}</td>
                                        <td>{{ \DateTime::createFromFormat('Y-m-d H:i:s', $beli->tgl_nota)->format('d-m-Y') }}</td>
                                        <td>{{ $beli->supplier->nama_supplier }}</td>
                                        <td>{{ $beli->total_item }}</td>
                                        <td>Rp. {{ number_format($beli->total_nilai, 0, '.', '.') }} </td>
                                        <form onsubmit="return confirm('Ingin menghapus Kostum ini ? ?');" action="{{ route('master.pembelianbarang.delete', $beli->id) }}" method="POST">
                                        <td>
                                                @if ($beli->status == 'progress')
                                                <a href="{{ route('master.pembelianbarang.edit', $beli->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-book menu-icon"></i></a>
                                                @else
                                                <a href="{{ route('master.pembelianbarang.edit', $beli->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit menu-icon"></i></a>
                                                @endif
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash menu-icon"></i></button>
                                            </td>
                                        </form>
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
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title h4" id="myLargeModalLabel">Pembelian Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="custom-tab">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <a class="nav-item nav-link {{ session('tab') == 'detail' ? '' : 'active' }}" id="tambah-tab" data-toggle="tab" href="#tambah" role="tab" aria-controls="tambah" aria-selected="true" {{ session('tab') == 'detail' ? 'style=pointer-events:none;opacity:0.6;' : '' }}>Tambah Pembelian</a>
                                        <a class="nav-item nav-link {{ session('tab') == 'detail' ? 'active' : '' }}" id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="false" {{ session('tab') == '' ? 'style=pointer-events:none;opacity:0.6;' : '' }}>Detail Pembelian</a>
                                    </div>
                                </nav>
                                <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                    <div class="tab-pane fade show {{ session('tab') == 'detail' ? '' : 'active' }}" id="tambah" role="tabpanel" aria-labelledby="tambah-tab">
                                        <br>
                                        <form id="form-tambah-pembelian" action="{{ route('master.pembelianbarang.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-6">
                                                    <!-- Nama Supplier -->
                                                    <div class="form-group">
                                                        <label for="id_supplier" class="form-control-label">Nama Supplier</label>
                                                        <select name="id_supplier" id="id_supplier" class="form-control">
                                                            <option value="" selected>Pilih Supplier</option>
                                                            @foreach($suppliers as $supplier)
                                                                <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <label for="id_supplier" class="form-control-label">Tanggal Nota</label>
                                                    <input class="form-control" type="date" name="tgl_nota" id="tgl_nota">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="no_nota" class=" form-control-label">Nomor Nota<span style="color: red">*</span></label>
                                                <input type="number" id="no_nota" name="no_nota" placeholder="Contoh : 001" class="form-control">
                                            </div>
                                            <button type="submit" style="float: right" id="save-btn" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade {{ session('tab') == 'detail' ? 'show active' : '' }}" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                                    <br>
                                    @php
                                        $pembelian = session('pembelian', $pembelian ?? null);
                                    @endphp

                                    @if ($pembelian)
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <h5><i class="fa fa-home"></i> Nomor Nota <span class="badge badge-secondary pull-right">{{ $pembelian->no_nota }}</span></h5>
                                            </li>
                                            <li class="list-group-item">
                                                <h5><i class="fa fa-globe"></i> Nama Supplier <span class="badge badge-secondary pull-right">{{ $pembelian->supplier->nama_supplier }}</span></h5>
                                            </li>
                                            <li class="list-group-item">
                                                <h5><i class="fa fa-map-marker"></i> &nbsp;Tanggal Nota <span class="badge badge-secondary pull-right">{{ $pembelian->tgl_nota }}</span></h5>
                                            </li>
                                        </ul>
                                    <br>
                                    <form action="{{ route('master.pembelianbarang.update', $pembelian->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <!-- Item Container -->
                                    <div id="item-container">
                                        <div class="item-group">
                                            <div class="row">
                                                <div class="col-12">
                                                    <!-- Jenis Barang -->
                                                    <div class="form-group">
                                                        <label for="id_barang" class="form-control-label">Nama Barang<span style="color: red">*</span></label>
                                                        <select name="id_barang[]" id="id_barang"  data-placeholder="Pilih Barang..." class="form-control">
                                                            <option value="" disabled selected required>Pilih Barang</option>
                                                            @foreach($barang as $brg)
                                                                <option value="{{ $brg->id }}">{{ $brg->nama_barang }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <!-- Jumlah Item -->
                                                    <div class="form-group">
                                                        <label for="jml_item" class="form-control-label">Jumlah Item<span style="color: red">*</span></label>
                                                        <input type="number" id="jml_item" min="1" name="qty[]" placeholder="Contoh: 16" class="form-control jumlah-item">
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <!-- Harga Barang -->
                                                    <div class="form-group">
                                                        <label for="harga_barang" class="form-control-label">Harga Barang<span style="color: red">*</span></label>
                                                        <input type="number" id="harga_barang" min="1" name="harga_barang[]" placeholder="Contoh: 16000" class="form-control harga-barang">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <button type="button" id="add-item-detail" style="float: right" class="btn btn-secondary">Add</button>
                                <br><br>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="card border border-primary">
                                            <div class="card-body">
                                                <p class="card-text">Detail Stock <strong>(GSS)</strong></p>
                                                <p class="card-text">Stock :<strong class="stock">0</strong></p>
                                                <p class="card-text">Hpp Awal : <strong class="hpp-awal">Rp 0</strong></p>
                                                <p class="card-text">Hpp Baru : <strong class="hpp-baru">Rp 0</strong></p>
                                            </div>
                                            <button type="button" id="reset" style="float: right" class="btn btn-secondary">Reset</button>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        @foreach ($LevelHarga as $index => $level)
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ $level->nama_level_harga }}</span>
                                            </div>
                                            <input type="hidden" name="level_nama[]" value="{{ $level->nama_level_harga }}">
                                            <div class="custom-file">
                                                <input type="text" class="form-control level-harga" name="level_harga[]" id="level_harga_{{ $index }}" data-index="{{ $index }}" data-hpp-baru="0">
                                                <label class="input-group-text" id="persen_{{ $index }}">0%</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th scope="col">No</th>
                                                        <th scope="col">Nama Barang</th>
                                                        <th scope="col">Qty</th>
                                                        <th scope="col">Harga</th>
                                                        <th scope="col">Total Harga</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Rows akan ditambahkan di sini oleh JavaScript -->
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th scope="col" colspan="5" style="text-align:right">SubTotal</th>
                                                        <th scope="col">Rp </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <!-- Submit Button -->
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary pull-right">
                                                    <i class="fa fa-dot-circle-o"></i> Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                    @else
                                    <div class="alert alert-warning">
                                        <strong>Perhatian!</strong> Anda perlu menambahkan data pembelian di tab "Tambah Pembelian" terlebih dahulu.
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
            </div>
        </div>
        <!-- [ Main Content ] end -->
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let subtotal = 0;
        let addedItems = new Set();

        // Variabel untuk menyimpan nilai awal dari server
        let initialHppBaru = 0;
        let initialStock = 0;
        let initialHppAwal = 0;

        function toggleInputFields(disabled) {
            document.getElementById('jml_item').disabled = disabled;
            document.getElementById('harga_barang').disabled = disabled;
            if (disabled) {
                document.getElementById('jml_item').value = '';
                document.getElementById('harga_barang').value = '';
            }
        }

        function checkInputFields() {
            let idBarang = document.getElementById('id_barang').value;
            let isItemAdded = addedItems.has(idBarang);
            toggleInputFields(isItemAdded);
        }

        document.getElementById('add-item-detail').addEventListener('click', function () {
            let idBarang = document.getElementById('id_barang').value;
            let namaBarang = document.getElementById('id_barang').selectedOptions[0].text;
            let qty = parseInt(document.getElementById('jml_item').value);
            let harga = parseInt(document.getElementById('harga_barang').value);

            if (!idBarang) {
                alert('Silakan pilih barang terlebih dahulu.');
                return;
            }

            if (addedItems.has(idBarang)) {
                alert('Barang ini sudah ditambahkan sebelumnya.');
                return;
            }

            if (!qty || !harga) {
                alert('Jumlah dan harga barang harus diisi.');
                return;
            }

            addedItems.add(idBarang);

            // Menyembunyikan pilihan barang yang sudah ditambahkan
            document.querySelector(`#id_barang option[value="${idBarang}"]`).setAttribute('hidden', true);

            let totalHarga = qty * harga;
            subtotal += totalHarga;

            // Generate hidden input fields for level prices
            let levelHargaInputs = '';
            document.querySelectorAll('.level-harga').forEach((input, index) => {
                const levelHarga = input.value;
                levelHargaInputs += `<input type="hidden" name="level_harga[${idBarang}][]" value="${levelHarga}">`;
            });

            let row = `
                <tr>
                    <td><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
                    <td class="numbered">${document.querySelectorAll('.table-bordered tbody tr').length + 1}</td>
                    <td><input type="hidden" name="id_barang[]" value="${idBarang}">${namaBarang}</td>
                    <td><input type="hidden" name="qty[]" value="${qty}">${qty}</td>
                    <td><input type="hidden" name="harga_barang[]" value="${harga}">Rp ${harga.toLocaleString('id-ID')}</td>
                    <td>Rp ${totalHarga.toLocaleString('id-ID')}</td>
                    ${levelHargaInputs}
                </tr>
            `;

            document.querySelector('.table-bordered tbody').insertAdjacentHTML('beforeend', row);

            document.querySelector('.table-bordered tfoot tr th:last-child').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;

            // Disable input fields after adding
            toggleInputFields(true);

            document.getElementById('id_barang').value = '';

            resetFields();

            updateNumbers();
        });

        document.querySelector('.table-bordered tbody').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-item')) {
                let row = e.target.closest('tr');
                let idBarang = row.querySelector('input[name="id_barang[]"]').value;
                let qty = row.querySelector('input[name="qty[]"]').value;
                let harga = row.querySelector('input[name="harga_barang[]"]').value;
                let totalHarga = parseInt(row.querySelector('td:nth-child(6)').textContent.replace(/\D/g, ''));

                // Update subtotal by subtracting the total price of the removed item
                subtotal -= totalHarga;
                row.remove();

                addedItems.delete(idBarang);

                let optionElement = document.querySelector(`#id_barang option[value="${idBarang}"]`);
                if (optionElement) {
                    optionElement.removeAttribute('hidden');
                } else {
                    console.log(`Opsi dengan id ${idBarang} tidak ditemukan di dropdown.`);
                }

                // Update subtotal display
                document.querySelector('.table-bordered tfoot tr th:last-child').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;

                updateNumbers();

                // Enable input fields if no items are added
                if (!addedItems.size) {
                    toggleInputFields(false);
                } else {
                    // Recheck if the currently selected item is in the added items
                    checkInputFields();
                }
            }
        });

        document.getElementById('id_barang').addEventListener('change', function () {
            checkInputFields();
            document.getElementById('jml_item').value = '';
            document.getElementById('harga_barang').value = '';

            let idBarang = this.value;

            if (idBarang) {

                fetch(`/admin/get-stock-details/${idBarang}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        // Simpan nilai awal yang diterima dari server
                        initialHppBaru = data.hpp_baru || 0;
                        initialStock = data.stock || 0;
                        initialHppAwal = data.hpp_awal || 0;

                        // Tampilkan nilai dari server
                        document.querySelector('.card-text strong.stock').textContent = initialStock.toLocaleString('id-ID');
                        document.querySelector('.card-text strong.hpp-awal').textContent = `Rp ${initialHppAwal.toLocaleString('id-ID')}`;
                        document.querySelector('.card-text strong.hpp-baru').textContent = `Rp ${initialHppBaru.toLocaleString('id-ID')}`;

                        // Set data-hpp-baru di input level harga
                        document.querySelectorAll('.level-harga').forEach(function(input) {
                            input.setAttribute('data-hpp-baru', initialHppBaru);
                        });

                        // Simpan nilai level harga asli dari server
                        originalLevelHarga = { ...data.level_harga };  // Simpan salinan level harga asli

                        // Mengisi nilai level harga dari server dan menghitung persentase
                        document.querySelectorAll('input[name="level_nama[]"]').forEach(function(namaLevelInput, index) {
                            const namaLevel = namaLevelInput.value;
                            const inputField = document.querySelectorAll('input[name="level_harga[]"]')[index];
                            const persenElement = document.querySelector(`#persen_${index}`);

                            // Jika level ada di data server, tampilkan, jika tidak biarkan kosong
                            if (data.level_harga.hasOwnProperty(namaLevel)) {
                                inputField.value = data.level_harga[namaLevel];
                                let levelHarga = parseFloat(inputField.value) || 0;
                                let persen = 0;
                                if (initialHppAwal > 0) {
                                    persen = ((levelHarga - initialHppAwal) / initialHppAwal) * 100;
                                }
                                persenElement.textContent = `${persen.toFixed(2)}%`;
                            } else {
                                inputField.value = '';  // Biarkan kosong jika tidak ada data
                                persenElement.textContent = '0%';
                            }
                        });

                        setupInputListeners(data.total_harga_success, data.total_qty_success);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                // Reset tampilan jika tidak ada barang yang dipilih
                resetFields();
            }
        });

        document.querySelectorAll('.level-harga').forEach(function(input) {
            input.addEventListener('input', function() {
                let hppAwal = initialHppAwal || 0;
                let hppBaru = parseFloat(input.getAttribute('data-hpp-baru')) || 0;
                let levelHarga = parseFloat(this.value) || 0;

                let persen = 0;

                // Jika harga barang belum diisi, gunakan hpp_awal
                if (hppBaru === 0 && hppAwal > 0) {
                    persen = ((levelHarga - hppAwal) / hppAwal) * 100;
                } else if (hppBaru > 0) {
                    // Jika harga barang sudah diisi, gunakan hpp_baru
                    persen = ((levelHarga - hppBaru) / hppBaru) * 100;
                }

                const index = this.getAttribute('data-index');
                const persenElement = document.getElementById(`persen_${index}`);
                if (persenElement) {
                    persenElement.textContent = `${persen.toFixed(2)}%`;
                }
            });
        });

        // Fungsi untuk mendengarkan perubahan input jumlah dan harga
        function setupInputListeners(totalHarga, totalQty) {
            document.querySelectorAll('.jumlah-item, .harga-barang').forEach(function (input) {
                input.addEventListener('input', function () {
                    calculateHPP(totalHarga, totalQty);
                });
            });
        }

        document.querySelectorAll('.jumlah-item, .harga-barang').forEach(function (input) {
            input.addEventListener('input', function () {
                calculateHPP(0, 0);  // Asumsikan barang baru jika tidak ada total harga atau qty dari database
            });
        });

        function calculateHPP(totalHarga, totalQty) {
            let jumlah = parseFloat(document.querySelector('.jumlah-item').value) || 0;
            let harga = parseFloat(document.querySelector('.harga-barang').value) || 0;

            let hppAwal = initialHppAwal || 0;  // Ambil HPP awal dari server

            if (jumlah > 0 && harga > 0) {
                let totalHargaBaru = jumlah * harga;

                // Hitung total keseluruhan harga dan total qty
                let totalKeseluruhanHarga = totalHargaBaru + totalHarga;
                let totalKeseluruhanQty = jumlah + totalQty;

                // Hitung HPP baru
                let finalHpp = totalKeseluruhanHarga / totalKeseluruhanQty;

                // Tampilkan hasil HPP baru
                document.querySelector('.card-text strong.hpp-baru').textContent = `Rp ${Math.round(finalHpp).toLocaleString('id-ID')}`;

                // Set nilai HPP baru di setiap input level harga
                document.querySelectorAll('.level-harga').forEach(function(input) {
                    input.setAttribute('data-hpp-baru', finalHpp);
                });

                // Hitung ulang persentase menggunakan HPP baru
                updatePercentages(finalHpp);

            } else {
                // Jika input jumlah atau harga dikosongkan, gunakan HPP awal dari server
                document.querySelector('.card-text strong.hpp-baru').textContent = `Rp ${initialHppBaru.toLocaleString('id-ID')}`;

                // Set HPP awal dari server di setiap input level harga
                document.querySelectorAll('.level-harga').forEach(function(input) {
                    input.setAttribute('data-hpp-baru', initialHppAwal);
                });

                // Hitung ulang persentase menggunakan HPP awal
                updatePercentages(initialHppAwal);
            }
        }

        // Fungsi untuk memperbarui persentase
        function updatePercentages(hpp) {
            document.querySelectorAll('.level-harga').forEach(function(input) {
                let levelHarga = parseFloat(input.value) || 0;
                let persen = 0;
                if (hpp > 0) {
                    persen = ((levelHarga - hpp) / hpp) * 100;
                }

                const persenElement = document.getElementById(`persen_${input.getAttribute('data-index')}`);
                if (persenElement) {
                    persenElement.textContent = `${persen.toFixed(2)}%`;
                }
            });
        }

        function updateNumbers() {
            document.querySelectorAll('.table-bordered tbody tr .numbered').forEach((element, index) => {
                element.textContent = index + 1;
            });
        }

        document.querySelector('#detail-tab').addEventListener('click', function (e) {
            e.preventDefault();
            // Logika untuk berpindah ke tab detail
            let tabDetail = new bootstrap.Tab(document.querySelector('#detail-tab'));
            tabDetail.show();
        });

        // // Event listener untuk menghitung ulang persentase saat input level harga berubah
        // document.querySelectorAll('.level-harga').forEach(function(input) {
        //     input.addEventListener('input', function() {
        //         let hppBaru = parseFloat(input.getAttribute('data-hpp-baru')) || 0;
        //         let levelHarga = parseFloat(this.value) || 0;

        //         // Hitung persentase jika HPP baru lebih dari 0
        //         let persen = 0;
        //         if (hppBaru > 0) {
        //             persen = ((levelHarga - hppBaru) / hppBaru) * 100;
        //         }

        //         const index = this.getAttribute('data-index');
        //         const persenElement = document.getElementById(`persen_${index}`);
        //         if (persenElement) {
        //             persenElement.textContent = `${persen.toFixed(2)}%`;
        //         }
        //     });
        // });

        function resetFields() {
            // Kosongkan tampilan HPP, stock, dan level harga
            document.querySelector('.card-text strong.stock').textContent = '0';
            document.querySelector('.card-text strong.hpp-awal').textContent = 'Rp 0';
            document.querySelector('.card-text strong.hpp-baru').textContent = 'Rp 0';

            // Kosongkan nilai level harga
            document.querySelectorAll('.level-harga').forEach(function(input) {
                input.value = '';
                const persenElement = document.getElementById(`persen_${input.getAttribute('data-index')}`);
                if (persenElement) {
                    persenElement.textContent = '0%';
                }
            });
        }

        // Fungsi untuk mereset nilai ke nilai asli dari server
        function resetFieldsToOriginal() {
            // Kembalikan nilai HPP dan stock dari server
            document.querySelector('.card-text strong.stock').textContent = initialStock.toLocaleString('id-ID');
            document.querySelector('.card-text strong.hpp-awal').textContent = `Rp ${initialHppAwal.toLocaleString('id-ID')}`;

            // Kembalikan nilai level harga ke nilai asli dari server
            document.querySelectorAll('input[name="level_nama[]"]').forEach(function(namaLevelInput, index) {
                const namaLevel = namaLevelInput.value;
                const inputField = document.querySelectorAll('input[name="level_harga[]"]')[index];
                const persenElement = document.querySelector(`#persen_${index}`);

                // Jika level ada di data server, tampilkan, jika tidak biarkan kosong
                if (originalLevelHarga.hasOwnProperty(namaLevel)) {
                    inputField.value = originalLevelHarga[namaLevel] || '';  // Kembalikan nilai asli jika ada
                    let levelHarga = parseFloat(inputField.value) || 0;
                    let persen = 0;
                    if (initialHppBaru > 0) {
                        persen = ((levelHarga - initialHppBaru) / initialHppBaru) * 100;
                    }
                    persenElement.textContent = `${persen.toFixed(2)}%`;
                } else {
                    inputField.value = '';  // Kosongkan jika tidak ada data
                    persenElement.textContent = '0%';
                }
            });
        }

        // Tambahkan event listener pada tombol reset
        document.getElementById('reset').addEventListener('click', function () {
            let idBarang = document.getElementById('id_barang').value;
            if (idBarang) {
                // Jika ada barang yang dipilih, kembalikan nilai asli dari server
                resetFieldsToOriginal();
            } else {
                // Jika tidak ada barang yang dipilih, reset semua field menjadi kosong
                resetFields();
            }
        });

    });

</script>

@endsection
