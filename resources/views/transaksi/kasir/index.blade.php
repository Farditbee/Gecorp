<title>Data Transaksi Kasir - Gecorp</title>
@extends('layouts.main')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">

    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Data Transaksi Kasir</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('master.index') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a>Data Transaksi Kasir</a></li>
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
                            <form class="d-flex" method="GET" action="{{ route('master.kasir.index') }}">
                                <input class="form-control me-2" id="search" type="search" name="search"
                                    placeholder="Cari Data Transaksi" aria-label="Search">
                            </form>
                        </div>
                        <x-adminlte-alerts />
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table table-striped" id="jsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>No Nota</th>
                                            <th>Tgl Transaksi</th>
                                            <th>Member</th>
                                            <th>Nama Toko</th>
                                            <th>Item</th>
                                            <th>Nilai</th>
                                            <th>Payment</th>
                                            <th>Kasir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        @forelse ($kasir as $ksr)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $ksr->no_nota }}</td>
                                                <td>{{ $ksr->tgl_transaksi }}</td>
                                                <td>{{ $ksr->id_member == 0 ? 'Guest' : $ksr->member->nama_member}}</td>
                                                <td>{{ $ksr->toko->nama_toko }}</td>
                                                <td>{{ $ksr->total_item }}</td>
                                                <td>{{ $ksr->total_nilai }}</td>
                                                <td>{{ $ksr->metode }}</td>
                                                <td>{{ $ksr->users->nama }}</td>
                                            </tr>
                                        @empty
                                            <td colspan="9" style="text-align: center">
                                                <h4><span class="badge badge-light-danger" style="margin:10px;">Tidak Ada
                                                        Data</span></h4>
                                            </td>
                                        @endforelse
                                    </tbody>
                                </table>
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

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lgs">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title h4" id="myLargeModalLabel">Data Transaksi</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-xl-12 d-flex justify-content-between">
                        <div class="d-flex col-6">
                            <div class="col-4">
                                <p class="mb-0">No Nota</p>
                            </div>
                            <div class="col-8">
                                <p id="noNota" name="no_nota"> </p> <!-- ID untuk mengupdate nomor nota -->
                            </div>
                        </div>
                        <div class="d-flex col-6 justify-content-end">
                            <div class="col-4 text-end">
                                <p class="mb-0">Nama Toko</p>
                            </div>
                            <div class="col-8">
                                @if (Auth::check())
                                    <h5>: <span class="badge badge-info">{{ Auth::user()->toko->nama_toko }}</span></h5>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12 d-flex justify-content-between">
                        <div class="d-flex col-6">
                            <div class="col-4">
                                <p class="mb-0">Tgl Transaksi</p>
                            </div>
                            <div class="col-8">
                                <p name="tgl_transaksi" id="tglTransaksi">: </p>
                                <!-- Anda bisa mengganti dengan tanggal yang sesuai -->
                            </div>
                        </div>
                        <div class="d-flex col-6 justify-content-end">
                            <div class="col-4 text-end">
                                <p class="mb-0">Kasir</p>
                            </div>
                            <div class="col-8">
                                @if (Auth::check())
                                    <h5>: <span class="badge badge-info">{{ Auth::user()->nama }}</span></h5>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12 d-flex justify-content-between">
                        <div class="d-flex col-6">
                            <div class="col-4"></div>
                            <div class="col-8"></div>
                        </div>
                        <div class="d-flex col-6 justify-content-end">
                            <div class="col-4 text-end">
                                <p class="mb-0">Member</p>
                            </div>
                            <div class="col-8">
                                <p>:
                                    <select name="id_member" id="id_member">
                                        <option value="" selected>~ Pilih Member ~</option>
                                        <option value="Guest">Guest</option>
                                        @foreach ($member as $mbr)
                                            <option value="{{ $mbr->id }}"
                                                data-level-info='@json($mbr->level_info)'>
                                                {{ $mbr->nama_member }}</option>
                                        @endforeach
                                    </select>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body table-border-style">
                                        <div class="form">
                                            <form action="{{ route('master.kasir.store') }}" method="post"
                                                class="">
                                                @csrf
                                                <input type="hidden" id="hiddenNoNota" name="no_nota">
                                                <input type="hidden" id="hiddenKembalian" name="kembalian">
                                                <input type="hidden" id="hiddenMember" name="id_member">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <!-- Nama Barang -->
                                                        <div class="form-group">
                                                            <label for="id_barang" class="form-control-label">Nama
                                                                Barang<span style="color: red">*</span></label>
                                                            <select name="id_barang[]" id="barang">
                                                                <option value="">~Silahkan Pilih Barang~</option>
                                                                @foreach ($barang as $brg)
                                                                    <option value="{{ $brg->id_barang }}"
                                                                        data-search-barang="{{ $brg->barang->nama_barang }}"
                                                                        data-stock="{{ Auth::user()->id_level == 1 ? $brg->stock : $brg->qty }}"
                                                                        data-jenis-barang="{{ $brg->barang->id_jenis_barang }}"
                                                                        data-level-harga='@json($brg->barang->level_harga)'>
                                                                        {{ $brg->barang->nama_barang }} (Stock:
                                                                        {{ Auth::user()->id_level == 1 ? $brg->stock : $brg->qty }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="harga" class="form-control-label">Harga<span
                                                                style="color: red">*</span></label>
                                                        <select class="form-control" name="harga[]" id="harga"
                                                            style="display: block;">
                                                            <option value="">~Pilih Member Dahulu~</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="qty" class=" form-control-label">Item<span
                                                                style="color: red">*</span></label>
                                                        <input type="number" id="qty" name="qty[]"
                                                            placeholder="Contoh : 1" class="form-control">
                                                        <br>
                                                        <button type="button" id="add-button"
                                                            class="btn btn-sm btn-secondary"
                                                            style="float: right;">Add</button>
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
                                                                    <th scope="col" colspan="5"
                                                                        style="text-align:right">SubTotal</th>
                                                                    <th scope="col" name="total_nilai">Rp </th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col" colspan="5"
                                                                        style="text-align:right">Payment</th>
                                                                    <th scope="col">
                                                                        <select name="metode" id="metode"
                                                                            style="width: 100%">
                                                                            <option value="">~Pilih Payment~</option>
                                                                            <option value="Tunai">Tunai</option>
                                                                            <option value="Non-Tunai">Non-Tunai</option>
                                                                        </select>
                                                                    </th>
                                                                </tr>
                                                                <tr id="uang-bayar-row">
                                                                    <th scope="col" colspan="5"
                                                                        style="text-align:right">Jml Bayar</th>
                                                                    <th scope="col"><input type="text"
                                                                            style="width: 100%" name="jml_bayar"
                                                                            id="uang-bayar-input">
                                                                        <input type="hidden" id="hiddenUangBayar" name="jml_bayar">
                                                                    </th>
                                                                </tr>
                                                                <tr id="kembalian-row">
                                                                    <th scope="col" colspan="5"
                                                                        style="text-align:right">Kembalian</th>
                                                                    <th scope="col" id="kembalian-amount"
                                                                        name="kembalian">Rp </th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        <!-- Submit Button -->
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fa fa-dot-circle-o"></i> Simpan
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        function getTodayDateWithDay() {
            const today = new Date();
            const day = String(today.getDate()).padStart(2, '0');
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Ditambah 1 karena getMonth() dimulai dari 0
            const year = today.getFullYear();

            // Array nama hari
            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const dayName = days[today.getDay()]; // Mendapatkan nama hari berdasarkan indeks

            return `${dayName}, ${day}-${month}-${year}`;
        }

        // Menampilkan tanggal dan hari di elemen dengan ID tglTransaksi
        document.getElementById('tglTransaksi').textContent += getTodayDateWithDay();
        // Fungsi untuk menghasilkan nomor berdasarkan format yang diinginkan
        function generateFormattedNumber() {
            const now = new Date();

            // Mendapatkan tanggal, bulan, tahun (2 digit), jam, menit, dan detik
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Ditambah 1 karena getMonth() dimulai dari 0
            const year = String(now.getFullYear()).slice(-2);
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            // Mendapatkan 3 digit angka acak
            const randomDigits = Math.floor(100 + Math.random() * 900);

            // Menggabungkan semuanya
            return `${day}${month}${year}${hours}${minutes}${seconds}${randomDigits}`;
        }

        // Event listener untuk menampilkan nomor nota saat modal dibuka
        $('.bd-example-modal-lg').on('show.bs.modal', function() {
            const noNota = generateFormattedNumber();
            const noNotaElement = document.getElementById('noNota');
            const hiddenNoNotaInput = document.getElementById('hiddenNoNota');

            // Menampilkan nomor nota di elemen tampilan
            noNotaElement.textContent = ': ' + noNota;

            // Mengatur nilai nomor nota ke input hidden
            hiddenNoNotaInput.value = noNota;
        });
    </script>

    <script>
         document.addEventListener('DOMContentLoaded', function() {
            const selectBarang = new TomSelect("#barang", {
                // Menyimpan data value dan text untuk setiap opsi
                valueField: 'value',
                labelField: 'text',
                searchField: ['text', 'data-search-barang'], // Memungkinkan pencarian di 'text' dan 'data-search-barang'
                render: {}
            });

            const memberSelect = document.getElementById('id_member');
            const barangSelect = document.getElementById('barang');
            const hargaSelect = document.getElementById('harga');
            const qtyInput = document.getElementById('qty');
            const addButton = document.getElementById('add-button');
            const tableBody = document.querySelector('.modal-body table tbody');
            const subtotalFooter = document.querySelector('.modal-body tfoot th[colspan="5"] + th');

            const metodeSelect = document.getElementById('metode');
            const uangBayarInput = document.getElementById('uang-bayar-input');
            const kembalianAmount = document.getElementById('kembalian-amount');

            let subtotal = 0;

            function updateRowNumbers() {
                const rows = tableBody.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    row.children[1].textContent = index + 1; // Mengupdate nomor baris
                });
            }

            memberSelect.addEventListener('change', function() {
                barangSelect.disabled = !this.value;
                hargaSelect.disabled = true;
                hargaSelect.innerHTML = '<option value="">~Pilih Barang Dahulu~</option>';
            });

            barangSelect.addEventListener('change', function() {
                const selectedBarang = this.options[this.selectedIndex];
                const memberId = memberSelect.value;
                const barangId = selectedBarang.value;

                if (memberId && barangId) {

                    hargaSelect.innerHTML = '<option value="">Loading...</option>';
                    hargaSelect.disabled = true;

                    fetch(`/admin/kasir/get-filtered-harga?id_member=${memberId}&id_barang=${barangId}`)
                        .then(response => response.json())
                        .then(data => {
                            hargaSelect.innerHTML = '<option value="">~Masukkan Harga~</option>';
                            if (Array.isArray(data.filteredHarga)) {
                                data.filteredHarga.forEach(harga => {
                                    if (harga) {
                                        hargaSelect.innerHTML +=
                                            `<option value="${harga}">${harga}</option>`;
                                    }
                                });
                            } else if (data.filteredHarga) {
                                hargaSelect.innerHTML +=
                                    `<option value="${data.filteredHarga}">${data.filteredHarga}</option>`;
                            }
                            hargaSelect.disabled = !hargaSelect.querySelectorAll('option[value]')
                                .length;
                        })
                        .catch(error => console.error('Error fetching filtered harga:', error));
                }
            });

            addButton.addEventListener('click', function() {
                let idBarang = document.getElementById('barang').value;
                const selectedBarang = barangSelect.options[barangSelect.selectedIndex];
                const selectedHarga = hargaSelect.value;
                const qty = parseInt(qtyInput.value);
                const stock = parseInt(selectedBarang.getAttribute('data-stock'));
                let harga = parseInt(document.getElementById('harga').value);

                // Cek apakah barang sudah ada di tabel
                const existingRow = Array.from(tableBody.children).find(row => {
                    const barangNameCell = row.children[2];
                    return barangNameCell && barangNameCell.textContent.trim() === selectedBarang
                        .textContent.trim();
                });

                if (existingRow) {
                    alert("Barang sudah ditambahkan");
                    return; // Menghentikan proses jika barang sudah ada
                }

                // Validasi apakah qty melebihi stok
                if (qty > stock) {
                    alert("Stock barang tidak cukup");
                    return;
                }

                if (!selectedBarang.value || !selectedHarga || !qty) {
                    alert("Silakan lengkapi semua data sebelum menambahkan.");
                    return;
                }

                const totalHarga = parseFloat(selectedHarga) * qty;
                subtotal += totalHarga;
                subtotalFooter.textContent = `Rp ${subtotal.toLocaleString()}`;

                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><button type="button" class="btn btn-danger btn-sm remove-btn"><i class="fa fa-trash"></i></button></td>
                    <td></td> <!-- Ini akan diisi oleh fungsi updateRowNumbers -->
                    <td><input type="hidden" name="id_barang[]" value="${idBarang}">${selectedBarang.textContent}</td>
                    <td><input type="hidden" name="qty[]" value="${qty}">${qty}</td>
                    <td><input type="hidden" name="harga[]" value="${harga}">Rp ${parseFloat(selectedHarga).toLocaleString()}</td>
                    <td>Rp ${totalHarga.toLocaleString()}</td>
                `;
                tableBody.appendChild(newRow);

                qtyInput.value = '';
                document.getElementById('harga').value = '';
                document.getElementById('barang').value = '';

                // Menambah event listener untuk tombol hapus
                newRow.querySelector('.remove-btn').addEventListener('click', function() {
                    subtotal -= totalHarga;
                    subtotalFooter.textContent = `Rp ${subtotal.toLocaleString()}`;
                    newRow.remove();
                    updateRowNumbers(); // Memperbarui nomor baris setelah menghapus
                });

                updateRowNumbers(); // Memperbarui nomor baris setelah menambahkan
            });

            document.getElementById('id_member').addEventListener('change', function() {
                const selectedMember = this.value;
                document.getElementById('hiddenMember').value = selectedMember;
            });

            // Set hidden inputs before form submission
            document.querySelector('form').addEventListener('submit', function(event) {
                document.getElementById('hiddenNoNota').value = document.getElementById('noNota').textContent;
                document.getElementById('hiddenKembalian').value = document.getElementById('kembalian-amount').textContent;
                document.getElementById('hiddenMember').value = document.getElementById('id_member').value;
            });

            // Fungsi untuk update kembalian berdasarkan subtotal dan input Uang Bayar
            function updateKembalian() {
                const uangBayar = parseFloat(uangBayarInput.value.replace(/,/g, '')) || 0; // Menghapus koma
                const kembalian = uangBayar - subtotal;
                kembalianAmount.textContent = `Rp ${kembalian >= 0 ? kembalian.toLocaleString() : 0}`;

                // Menyimpan nilai kembalian ke input hidden
                document.getElementById('hiddenKembalian').value = kembalian >= 0 ? kembalian : 0;
            }

            // Format input uang bayar dengan pemisah ribuan
            uangBayarInput.addEventListener('input', function() {
                // Menghapus semua karakter non-digit (kecuali koma)
                let value = this.value.replace(/[^0-9]/g, '');

                // Menyimpan nilai asli (tanpa koma) di hidden input untuk database
                document.getElementById('hiddenUangBayar').value = value;
                
                // Menggunakan format pemisah ribuan
                if (value) {
                    this.value = parseInt(value).toLocaleString();
                }
                updateKembalian(); // Update kembalian
            });

            // Event listener untuk mengupdate kembalian secara real-time
            uangBayarInput.addEventListener('input', updateKembalian);

            // Menyembunyikan dan menampilkan baris Uang Bayar dan Kembalian sesuai metode pembayaran
            metodeSelect.addEventListener('change', function() {
                const isTunai = metodeSelect.value === "Tunai";
                document.getElementById('uang-bayar-row').style.display = isTunai ? '' : 'none';
                document.getElementById('kembalian-row').style.display = isTunai ? '' : 'none';
                uangBayarInput.value = ''; // Reset input dan kembalian jika metode diubah
                kembalianAmount.textContent = 'Rp 0';
            });

            // Menetapkan tampilan awal berdasarkan pilihan metode pembayaran
            metodeSelect.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
