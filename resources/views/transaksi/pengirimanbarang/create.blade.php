<title>Tambah Pengiriman Barang - Gecorp</title>
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
                            <h5 class="m-b-10">Tambah Pengirman Barang</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('master.index')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('master.pengirimanbarang.index')}}">Data Pengirman Barang</a></li>
                            <li class="breadcrumb-item"><a>Tambah Pengirman Barang</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('master.pengirimanbarang.index') }}" class="btn btn-danger">Kembali</a>
                    </div>
                    <div class="card-body">
                        <div class="custom-tab">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link {{ session('tab') == 'detail' ? '' : 'active' }}"
                                        id="tambah-tab" data-toggle="tab" href="#tambah" role="tab"
                                        aria-controls="tambah" aria-selected="true"
                                        {{ session('tab') == 'detail' ? 'style=pointer-events:none;opacity:0.6;' : '' }}>Tambah
                                        Pengiriman</a>
                                    <a class="nav-item nav-link {{ session('tab') == 'detail' ? 'active' : '' }}"
                                        id="detail-tab" data-toggle="tab" href="#detail" role="tab"
                                        aria-controls="detail" aria-selected="false"
                                        {{ session('tab') == '' ? 'style=pointer-events:none;opacity:0.6;' : '' }}>Detail
                                        Pengiriman</a>
                                </div>
                            </nav>
                            <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                <div class="tab-pane fade show {{ session('tab') == 'detail' ? '' : 'active' }}"
                                    id="tambah" role="tabpanel" aria-labelledby="tambah-tab">
                                    <br>
                                    <form action="{{ route('master.pengirimanbarang.store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-6">
                                                <!-- Nama Supplier -->
                                                <div class="form-group">
                                                    <label for="no_resi" class=" form-control-label">Nomor Resi<span
                                                            style="color: red">*</span></label>
                                                    <input type="number" id="no_resi" name="no_resi"
                                                        placeholder="Contoh : 001" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <label for="tgl_kirim" class="form-control-label">Tanggal Kirim</label>
                                                <input class="form-control" type="date" name="tgl_kirim"
                                                    id="tgl_kirim">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Toko Pengirim<span
                                                    style="color: red">*</span></label>
                                            <select class="form-control select2" name="toko_pengirim" id="toko_pengirim"
                                                style="display: block;">
                                                <option class="" required>~Pilih Nama Toko~</option>
                                                @foreach ($toko as $tk)
                                                    <option value="{{ $tk->id }}">{{ $tk->nama_toko }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_pengirim" class=" form-control-label">Nama Pengirim (Admin
                                                Toko)<span style="color: red">*</span></label>
                                            <select name="nama_pengirim" class="form-control select2" id="nama_pengirim"
                                                data-placeholder="~Silahkan Pilih Nama~" tabindex="1">
                                                <option class="" required>~Pilih Toko Terlebih Dahulu~</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="ekspedisi" class=" form-control-label">Ekspedisi<span
                                                    style="color: red">*</span></label>
                                            <input type="text" id="ekspedisi" name="ekspedisi"
                                                placeholder="Contoh : Sicepat" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class=" form-control-label">Toko Penerima<span
                                                    style="color: red">*</span></label>
                                            <select class="form-control" name="toko_penerima"
                                                id="toko_penerima" style="display: block;">
                                                <option class="" required>~Pilih Nama Toko~</option>
                                                @foreach ($toko as $tk)
                                                    <option value="{{ $tk->id }}">{{ $tk->nama_toko }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <button type="submit" style="float: right" class="btn btn-primary"><i
                                                class="fa fa-save"></i> Simpan</button>
                                    </form>
                                </div>
                                <div class="tab-pane fade {{ session('tab') == 'detail' ? 'show active' : '' }}"
                                    id="detail" role="tabpanel" aria-labelledby="detail-tab">
                                    <br>
                                    @php
                                        $pengiriman_barang = session(
                                            'pengiriman_barang',
                                            $pengiriman_barang ?? null,
                                        );
                                    @endphp

                                    @if ($pengiriman_barang)
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <h5 class="mb-0"><i class="fa fa-barcode"></i> Nomor Resi
                                                        </h5>
                                                    </div>
                                                    <div class="col">
                                                        <span
                                                            class="badge badge-pill badge-secondary">{{ $pengiriman_barang->no_resi }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <h5 class="mb-0"><i class="fa fa-home"></i> Toko Pengirim
                                                        </h5>
                                                    </div>
                                                    <div class="col">
                                                        <span
                                                            class="badge badge-pill badge-secondary">{{ $pengiriman_barang->toko->nama_toko }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <h5 class="mb-0"><i class="fa fa-tag"></i> Nama Pengirim
                                                        </h5>
                                                    </div>
                                                    <div class="col">
                                                        <span
                                                            class="badge badge-pill badge-secondary">{{ $pengiriman_barang->user->nama }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <h5 class="mb-0"><i class="fa fa-truck"></i> Ekspedisi</h5>
                                                    </div>
                                                    <div class="col">
                                                        <span
                                                            class="badge badge-pill badge-secondary">{{ $pengiriman_barang->ekspedisi }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <h5 class="mb-0"><i class="fa fa-home"></i> Toko Penerima
                                                        </h5>
                                                    </div>
                                                    <div class="col">
                                                        <span
                                                            class="badge badge-pill badge-secondary">{{ $pengiriman_barang->tokos->nama_toko }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <h5 class="mb-0"><i class="fa fa-calendar"></i> Tanggal
                                                            Kirim</h5>
                                                    </div>
                                                    <div class="col">
                                                        <span
                                                            class="badge badge-pill badge-secondary">{{ $pengiriman_barang->tgl_kirim }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <br>
                                        <form
                                            action="{{ route('master.pengirimanbarang.update', $pengiriman_barang->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <!-- Item Container -->
                                            <div id="item-container">
                                                <div class="item-group">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <!-- Jenis Barang -->

                                                            <!-- Hidden input untuk mengirim id_toko -->
                                                            <input type="hidden" name="tk_pengirim" id="tk_pengirim"
                                                                value="{{ $pengiriman_barang->toko_pengirim }}">

                                                            <div class="form-group">
                                                                <label for="id_barang" class="form-control-label">Nama
                                                                    Barang<span style="color: red">*</span></label>
                                                                <select name="id_barang" id="id_barang"
                                                                    class="form-control">
                                                                    <option value="" disabled selected required>
                                                                        Pilih
                                                                        Barang</option>

                                                                    @if ($pengiriman_barang->toko_pengirim == 1)
                                                                        {{-- Jika toko_pengirim adalah 1, ambil data dari StockBarang --}}
                                                                        @if ($stock->isEmpty())
                                                                            <option value="">Tidak ada Barang
                                                                            </option>
                                                                        @else
                                                                            @foreach ($stock as $tk)
                                                                                <option value="{{ $tk->id_barang }}"
                                                                                    data-stock="{{ $tk->stock }}">
                                                                                    {{ $tk->nama_barang }} ( Stock
                                                                                    Tersedia :
                                                                                    <strong>{{ $tk->stock }}</strong>
                                                                                    )
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    @else
                                                                        {{-- Jika toko_pengirim bukan 1, ambil data dari DetailToko yang sesuai dengan toko_pengirim --}}
                                                                        @php
                                                                            $detailBarang = $detail_toko->where(
                                                                                'id_toko',
                                                                                $pengiriman_barang->toko_pengirim,
                                                                            );
                                                                        @endphp

                                                                        @if ($detailBarang->isEmpty())
                                                                            <option value="">Tidak ada Barang Di
                                                                                Toko Ini</option>
                                                                        @else
                                                                            @foreach ($detailBarang as $dt)
                                                                                <option value="{{ $dt->id_barang }}"
                                                                                    data-stock="{{ $dt->qty }}">
                                                                                    {{ $dt->barang->nama_barang }} (
                                                                                    Stock Tersedia :
                                                                                    <strong>{{ $dt->qty }}</strong>
                                                                                    )
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <!-- Jumlah Item -->
                                                            <div class="form-group">
                                                                <label for="harga" class="form-control-label">Harga
                                                                    per Barang<span style="color: red">*</span></label>
                                                                <input type="text" id="harga_formatted" readonly
                                                                    placeholder="0" class="form-control">
                                                                <!-- Menampilkan harga dengan format -->
                                                                <input type="hidden" id="harga" name="harga[]">
                                                                <!-- Harga asli yang akan dikirim ke database -->
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <!-- Harga Barang -->
                                                            <div class="form-group">
                                                                <label for="jml_item"
                                                                    class="form-control-label">Jumlah Item<span
                                                                        style="color: red">*</span></label>
                                                                <input type="number" id="jml_item" min="1"
                                                                    name="qty[]" placeholder="Contoh: 16"
                                                                    class="form-control jumlah-item">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" id="add-item-detail" style="float: right"
                                                class="btn btn-secondary">Add</button>
                                            <br><br>

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
                                            <strong>Perhatian!</strong> Anda perlu menambahkan data pengiriman di tab
                                            "Tambah Pengiriman" terlebih dahulu.
                                        </div>
                                    @endif
                                    <div class="tab-pane fade" id="custom-nav-contact" role="tabpanel"
                                        aria-labelledby="custom-nav-contact-tab">
                                        <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt
                                            tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor,
                                            williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh
                                            dreamcatcher synth. Cosby sweater eu banh mi, irure terry richardson ex sd.
                                            Alip placeat salvia cillum iphone. Seitan alip s cardigan american apparel,
                                            butcher voluptate nisi .</p>
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script>
            $(document).ready(function() {
                // Event saat toko pengirim dipilih
                $('#toko_pengirim').change(function() {
                    var idToko = $(this).val();
                    if (idToko) {
                        $.ajax({
                            url: '/admin/get-users-by-toko/' + idToko,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                console.log('Data received:', data); // Log data untuk debugging
                                $('#nama_pengirim').empty();
                                $('#nama_pengirim').append(
                                    '<option value="">~Pilih Nama Pengirim~</option>');
                                $.each(data, function(key, value) {
                                    $('#nama_pengirim').append('<option value="' + value
                                        .id + '">' + value.nama + '</option>');
                                });
                            },
                            error: function(xhr, status, error) {
                                if (xhr.status === 404) { // Jika tidak ditemukan user
                                    $('#nama_pengirim').empty();
                                    $('#nama_pengirim').append(
                                        '<option value="">Toko Tidak ada Admin</option>');
                                } else {
                                    console.error(xhr.responseText); // Log error lainnya
                                }
                            }
                        });

                        // Sembunyikan toko pengirim dari dropdown toko penerima
                        $('#toko_penerima option').each(function() {
                            if ($(this).val() == idToko) {
                                $(this).hide();
                            } else {
                                $(this).show(); // Pastikan opsi lain tetap terlihat
                            }
                        });

                    } else {
                        $('#nama_pengirim').empty();
                        $('#nama_pengirim').append('<option value="">~Nama Pengirim Tidak Ditemukan~</option>');
                    }
                });

                // Event saat toko penerima dipilih ulang (reset)
                $('#toko_penerima').change(function() {
                    var selectedTokoPenerima = $(this).val();
                    $('#toko_pengirim option').each(function() {
                        if ($(this).val() != selectedTokoPenerima) {
                            $(this).show(); // Tampilkan opsi lain yang tidak terpilih
                        }
                    });
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Pilih level...",
                    allowClear: true
                });
            });

            $('#id_barang').change(function() {
                var idBarang = $(this).val();
                var idToko = $('#tk_pengirim').val(); // Ambil id_toko dari hidden input

                console.log("Selected idBarang: ", idBarang);
                console.log("Selected idToko: ", idToko);

                if (idBarang) {
                    $.ajax({
                        url: '/admin/get-harga-barang/' + idBarang + '/' + idToko,
                        type: 'GET',
                        success: function(response) {
                            console.log("Response from server: ", response);
                            if (response.harga) {
                                // Format number untuk tampilan
                                $('#harga_formatted').val(formatNumber(response.harga));
                                // Simpan harga asli ke input hidden untuk dikirim ke server
                                $('#harga').val(response.harga);
                            } else {
                                alert('Harga barang tidak ditemukan.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching price:", error);
                            alert('Gagal mendapatkan harga barang.');
                        }
                    });
                } else {
                    $('#harga_formatted').val('');
                    $('#harga').val('');
                }
            });

            // Fungsi untuk memformat angka dengan pemisah ribuan
            function formatNumber(num) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0
                }).format(num);
            }
        </script>

        {{-- Tampilkan Add --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let subtotal = 0;
                let addedItems = new Set();

                // Fungsi untuk mengatur input field
                function toggleInputFields(disabled) {
                    document.getElementById('jml_item').disabled = disabled;
                    document.getElementById('harga').disabled = disabled;
                    if (disabled) {
                        document.getElementById('jml_item').value = '';
                        document.getElementById('harga').value = '';
                    }
                }

                // Initial check to set input fields based on existing items
                function checkInputFields() {
                    let idBarang = document.getElementById('id_barang').value;
                    let isItemAdded = addedItems.has(idBarang);
                    toggleInputFields(isItemAdded);
                }

                let lastDeletedItem = null;
                document.getElementById('add-item-detail').addEventListener('click', function() {
                    let idBarang = document.getElementById('id_barang').value;
                    let namaBarang = document.getElementById('id_barang').selectedOptions[0].text;
                    let qty = parseInt(document.getElementById('jml_item').value) || 0; // Default to 0 if NaN
                    let harga = parseInt(document.getElementById('harga').value) || 0; // Default to 0 if NaN

                    if (!idBarang) {
                        alert('Harap pilih barang terlebih dahulu!');
                        return;
                    }
                    // Ambil stok barang dari atribut stok yang disimpan di dalam option
                    let stok = parseInt(document.getElementById('id_barang').selectedOptions[0].getAttribute(
                        'data-stock')) || 0; // Default to 0 if NaN

                    // Validasi apakah qty melebihi stok yang tersedia
                    if (qty > stok) {
                        alert('Stock barang tidak cukup!');
                        return;
                    }

                    if (stok === 0) {
                        alert('Stock barang sudah Habis!');
                        return;
                    }

                    if (qty === 0) {
                        alert('Jumlah item tidak boleh 0!');
                        return;
                    }

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
                            <td><input type="hidden" name="harga[]" value="${harga}">Rp ${harga.toLocaleString('id-ID')}</td>
                            <td>Rp ${totalHarga.toLocaleString('id-ID')}</td>
                        </tr>
                    `;

                    document.querySelector('tbody').insertAdjacentHTML('beforeend', row);

                    // Update subtotal in the footer
                    document.querySelector('tfoot tr th:last-child').textContent =
                        `Rp ${subtotal.toLocaleString('id-ID')}`;

                    // Disable input fields after adding
                    toggleInputFields(true);

                    updateNumbers();

                    // Reset input fields jika barang yang dihapus berbeda
                    if (!lastDeletedItem || lastDeletedItem.id !== idBarang) {
                        document.getElementById('id_barang').value = '';
                        document.getElementById('jml_item').value = '';
                        document.getElementById('harga').value = '';
                    }
                });

                document.querySelector('tbody').addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-item')) {
                        let row = e.target.closest('tr');
                        let idBarang = row.querySelector('input[name="id_barang[]"]').value;

                        // Get total price of the removed item (use a fallback of 0 to avoid NaN)
                        let totalHarga = parseInt(row.querySelector('td:last-child').textContent.replace(
                            /[^\d]/g, '')) || 0;

                        subtotal -= totalHarga;

                        row.remove();

                        addedItems.delete(idBarang);

                        // Simpan barang yang terakhir dihapus
                        lastDeletedItem = {
                            id: idBarang,
                            nama: row.querySelector('td:nth-child(3)').textContent,
                            qty: row.querySelector('td:nth-child(4)').textContent,
                            harga: row.querySelector('td:nth-child(5)').textContent.replace(/\D/g, '')
                        };

                        // Update subtotal in the footer
                        document.querySelector('tfoot tr th:last-child').textContent =
                            `Rp ${subtotal.toLocaleString('id-ID')}`;
                        updateNumbers();

                        // Enable input fields if no items are added
                        if (!addedItems.size) {
                            toggleInputFields(false);
                        } else {
                            // Isi ulang form dengan barang yang terakhir dihapus
                            document.getElementById('id_barang').value = lastDeletedItem.id;
                            document.getElementById('jml_item').value = lastDeletedItem.qty;
                            document.getElementById('harga').value = lastDeletedItem.harga;
                            // Recheck if the currently selected item is in the added items
                            checkInputFields();
                        }
                    }
                });

                // Fungsi untuk update nomor urut di tabel
                function updateNumbers() {
                    let rows = document.querySelectorAll('tbody tr');
                    rows.forEach((row, index) => {
                        row.querySelector('.numbered').textContent = index + 1;
                    });
                }

                // Memastikan input fields bisa digunakan kembali ketika dropdown barang berubah
                document.getElementById('id_barang').addEventListener('change', function() {
                    let idBarang = this.value;

                    if (idBarang) {
                        fetch(`/admin/get-stock-details/${idBarang}`)
                            .then(response => response.json())
                            .then(data => {
                                document.querySelector('.card-text strong.stock').textContent = data
                                    .stock || '0';
                                document.querySelector('.card-text strong.hpp-awal').textContent =
                                    `Rp ${data.hpp_awal.toLocaleString('id-ID')}`;
                                document.querySelector('.card-text strong.hpp-baru').textContent =
                                    `Rp ${data.hpp_baru.toLocaleString('id-ID')}`;
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    } else {
                        document.querySelector('.card-text strong.stock').textContent = '0';
                        document.querySelector('.card-text strong.hpp-awal').textContent = 'Rp 0';
                        document.querySelector('.card-text strong.hpp-baru').textContent = 'Rp 0';
                    }

                    checkInputFields();
                });
            });
        </script>

    @endsection
