@extends('layouts.main')

@section('title')
    Tambah Pembelian Barang
@endsection

@section('content')

<div class="pcoded-main-container">
            <div class="pcoded-inner-content pt-1 mt-1">
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
                                            <li class="breadcrumb-item ml-3"><a href="{{ route('dashboard.index')}}"><i class="feather icon-home"></i></a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('transaksi.pembelianbarang.index')}}">Pembelian Barang</a></li>
                                            <li class="breadcrumb-item"><a>Tambah Pembelian</a></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- [ Main Content ] start -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <a href="{{ url()->previous() }}" class="btn btn-danger">Kembali</a>
                                    </div>
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
                                                    <form action="{{ route('transaksi.pembelianbarang.store') }}" method="POST">
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
                                                            <input type="text" id="no_nota" name="no_nota" placeholder="Contoh : 001" class="form-control">
                                                        </div>
                                                        <button type="submit" id="add-item" style="float: right" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
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
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <h5 class="mb-0"><i class="fa fa-barcode"></i> Nomor Nota
                                                                </h5>
                                                            </div>
                                                            <div class="col">
                                                                <span
                                                                    class="badge badge-pill badge-secondary">{{ $pembelian->no_nota }}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <h5 class="mb-0"><i class="fa fa-home"></i> Nama Supplier
                                                                </h5>
                                                            </div>
                                                            <div class="col">
                                                                <span
                                                                    class="badge badge-pill badge-secondary">{{ $pembelian->supplier->nama_supplier }}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <h5 class="mb-0"><i class="fa fa-calendar"></i> Tanggal Nota
                                                                </h5>
                                                            </div>
                                                            <div class="col">
                                                                <span
                                                                    class="badge badge-pill badge-secondary">{{ $pembelian->tgl_nota }}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <br>
                                                <form action="{{ route('transaksi.pembelianbarang.update', $pembelian->id) }}" method="POST">
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
                                                                        <option value="" disabled selected>Pilih Barang</option>
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
                                                                    <input type="text" id="harga_barang" min="1" name="harga_barang[]" placeholder="Contoh: 16000" class="form-control harga-barang">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <button type="button" id="add-item-detail" style="float: right" class="btn btn-secondary">Add</button>
                                            <br><br>

                                            <div class="row">
                                                <div class="col-12">
                                                    <!-- Jumlah Item -->
                                                    <div class="card border border-primary">
                                                        <div class="card-body">
                                                            <p class="card-text">Detail Stock <strong>(GSS)</strong></p>
                                                            <p class="card-text">Stock :<strong class="stock">0</strong></p>
                                                            <p class="card-text">Hpp Awal : <strong class="hpp-awal">Rp 0</strong></p>
                                                            <p class="card-text">Hpp Baru : <strong class="hpp-baru">Rp 0</strong></p>
                                                        </div>
                                                    </div>
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
                                                            <button type="submit" class="btn btn-primary">
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
                                                    <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth transaksi cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, irure terry richardson ex sd. Alip placeat salvia cillum iphone. Seitan alip s cardigan american apparel, butcher voluptate nisi .</p>
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
            </div>
</div>



    <script>

    document.addEventListener('DOMContentLoaded', function () {
        let subtotal = 0;
        let addedItems = new Set();

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

            if (addedItems.has(idBarang)) {
                alert('Barang ini sudah ditambahkan sebelumnya.');
                return;
            }

            addedItems.add(idBarang);

            let totalHarga = qty * harga;
            subtotal += totalHarga;

            let row = `
                <tr>
                    <td><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
                    <td class="numbered">${document.querySelectorAll('tbody tr').length + 1}</td>
                    <td><input type="hidden" name="id_barang[]" value="${idBarang}">${namaBarang}</td>
                    <td><input type="hidden" name="qty[]" value="${qty}">${qty}</td>
                    <td><input type="hidden" name="harga_barang[]" value="${harga}">Rp ${harga.toLocaleString('id-ID')}</td>
                    <td>Rp ${totalHarga.toLocaleString('id-ID')}</td>
                </tr>
            `;

            document.querySelector('tbody').insertAdjacentHTML('beforeend', row);

            document.querySelector('tfoot tr th:last-child').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;

            // Disable input fields after adding
            toggleInputFields(true);

            updateNumbers();
        });

        document.querySelector('tbody').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-item')) {
                let row = e.target.closest('tr');
                let idBarang = row.querySelector('input[name="id_barang[]"]').value;
                let totalHarga = parseInt(row.querySelector('td:last-child').textContent.replace(/\D/g, ''));

                subtotal -= totalHarga;
                row.remove();

                addedItems.delete(idBarang);

                document.querySelector('tfoot tr th:last-child').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
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
            checkInputFields(); // Periksa apakah barang sudah ada atau belum

            let idBarang = this.value;
            if (idBarang) {
                fetch(`/admin/get-stock-details/${idBarang}`)
                    .then(response => response.json())
                    .then(data => {
                        let hppBaru = data.hpp_baru || 0;
                        let totalHargaSuccess = data.total_harga_success || 0;
                        let totalQtySuccess = data.total_qty_success || 0;

                        document.querySelector('.card-text strong.stock').textContent = data.stock || '0';
                        document.querySelector('.card-text strong.hpp-awal').textContent = `Rp ${data.hpp_awal.toLocaleString('id-ID')}`;
                        document.querySelector('.card-text strong.hpp-baru').textContent = `Rp ${hppBaru.toLocaleString('id-ID')}`;

                        setupInputListeners(totalHargaSuccess, totalQtySuccess);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                document.querySelector('.card-text strong.stock').textContent = '0';
                document.querySelector('.card-text strong.hpp-awal').textContent = 'Rp 0';
                document.querySelector('.card-text strong.hpp-baru').textContent = 'Rp 0';
            }
        });

        // Fungsi untuk mendengarkan perubahan input jumlah dan harga
        function setupInputListeners(totalHarga, totalQty) {
            document.querySelectorAll('.jumlah-item, .harga-barang').forEach(function (input) {
                input.addEventListener('input', function () {
                    calculateHPP(totalHarga, totalQty);
                });
            });
        }

        // Fungsi untuk menghitung HPP Baru
        function calculateHPP(totalHarga, totalQty) {
            let jumlah = parseFloat(document.querySelector('.jumlah-item').value) || 0;
            let harga = parseFloat(document.querySelector('.harga-barang').value) || 0;

            if (jumlah > 0 && harga > 0) {
                let totalHargaBaru = jumlah * harga;

                // Hitung total keseluruhan harga dan total qty
                let totalKeseluruhanHarga = totalHargaBaru + totalHarga;
                let totalKeseluruhanQty = jumlah + totalQty;

                // Hitung HPP baru
                let finalHpp = totalKeseluruhanHarga / totalKeseluruhanQty;

                // Tampilkan hasil HPP baru
                document.querySelector('.card-text strong.hpp-baru').textContent = `Rp ${Math.round(finalHpp).toLocaleString('id-ID')}`;
            } else {
                // Jika input kosong, HPP Baru tidak dihitung
                document.querySelector('.card-text strong.hpp-baru').textContent = 'Rp 0';
            }
        }

        function updateNumbers() {
            document.querySelectorAll('tbody tr .numbered').forEach((element, index) => {
                element.textContent = index + 1;
            });
        }
    });

    </script>
@endsection
