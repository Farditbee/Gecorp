@extends('layouts.main')

@section('title')
    Tambah Pengiriman Barang
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ url()->previous() }}" class="btn btn-danger">Kembali</a>
                        </div>
                        <div class="card-body">
                            <x-adminlte-alerts />
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
                                        <form action="{{ route('transaksi.pengirimanbarang.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="no_resi" class=" form-control-label">Nomor Resi<span
                                                                style="color: red">*</span></label>
                                                        <input type="number" id="no_resi" name="no_resi"
                                                            placeholder="Contoh : 001" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="tgl_kirim" class="form-control-label">Tanggal
                                                            Kirim</label>
                                                        <input class="form-control" type="date" name="tgl_kirim"
                                                            id="tgl_kirim">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class=" form-control-label">Toko Pengirim<span
                                                                style="color: red">*</span></label>
                                                        <select class="form-control select2" name="toko_pengirim"
                                                            id="toko_pengirim" style="display: block;">
                                                            <option value="{{ $myToko->id }}">{{ $myToko->nama_toko }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="nama_pengirim" class=" form-control-label">Nama Pengirim
                                                            (Admin
                                                            Toko)<span style="color: red">*</span></label>
                                                        <select name="nama_pengirim" id="nama_pengirim"
                                                            class="form-control select2">
                                                            <option value="{{ auth()->user()->nama }}">
                                                                {{ auth()->user()->nama }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class=" form-control-label">Toko Penerima<span
                                                        style="color: red">*</span></label>
                                                <select class="form-control select2" name="toko_penerima" id="toko_penerima"
                                                    style="display: block;">
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="ekspedisi" class=" form-control-label">Ekspedisi<span
                                                        style="color: red">*</span></label>
                                                <input type="text" id="ekspedisi" name="ekspedisi"
                                                    placeholder="Contoh : Sicepat" class="form-control">
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
                                                                class="badge badge-pill badge-secondary">{{ $pengiriman_barang->nama_pengirim }}</span>
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
                                                action="{{ route('transaksi.pengirimanbarang.update', $pengiriman_barang->id) }}"
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
                                                                    <select class="form-control select2" name="id_barang"
                                                                        id="id_barang">
                                                                        <option value=""></option>
                                                                        <!-- Opsi kosong untuk placeholder -->
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
                                                                        name="jml_item[]" placeholder="Contoh: 16"
                                                                        class="form-control jumlah-item" readonly>
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
                                                tofu stumptown aliqua, retro synth transaksi cleanse. Mustache cliche
                                                tempor,
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
        </div>
    </div>
@endsection

@section('asset_js')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
@endsection

@section('js')
    <script>
        let selectOptions = [{
            id: '#toko_penerima',
            isUrl: '{{ route('master.toko') }}',
            isFilter: {
                is_delete: '{{ auth()->user()->id_toko }}',
                is_admin: true,
            },
            placeholder: 'Pilih Nama Toko',
        }];
        let subtotal = 0;
        let addedItems = new Set();
        let lastDeletedItem = null;
        const tglKirim = document.getElementById('tgl_kirim');
        if (tglKirim) {
            tglKirim.addEventListener('focus', function() {
                this.showPicker();
            });
        }

        function updateNumbers() {
            let rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.querySelector('.numbered').textContent = index + 1;
            });
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0
            }).format(num);
        }

        function toggleInputFields(disabled) {
            document.getElementById('jml_item').readOnly = disabled;
            document.getElementById('harga').disabled = disabled;
            if (disabled) {
                document.getElementById('jml_item').value = '';
                document.getElementById('harga').value = '';
            }
        }

        function checkInputFields() {
            let idBarang = document.getElementById('id_barang').value;
            let isItemAdded = addedItems.has(idBarang);
            toggleInputFields(isItemAdded);
        }

        function selectFormat(isParameter, isPlaceholder, isDisabled = true) {
            if (!$(isParameter).find('option[value=""]').length) {
                $(isParameter).prepend('<option value=""></option>');
            }

            $(isParameter).select2({
                disabled: isDisabled,
                dropdownAutoWidth: true,
                width: '100%',
                placeholder: isPlaceholder,
                allowClear: true,
            });
        }

        function setCreate() {
            $('#id_barang').change(function() {
                var idBarang = $(this).val();
                var idToko = $('#tk_pengirim').val();

                if (idBarang) {
                    $.ajax({
                        url: '/admin/get-harga-barang/' + idBarang + '/' + idToko,
                        type: 'GET',
                        success: function(response) {
                            console.log("Response from server: ", response);
                            if (response.harga) {
                                $('#harga_formatted').val(formatNumber(response.harga));
                                $('#harga').val(response.harga);
                                document.getElementById('jml_item').readOnly = false;
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

            document.getElementById('add-item-detail')?.addEventListener('click', function() {
                let idBarang = document.getElementById('id_barang').value.trim();
                let selectedOption = document.getElementById('id_barang').selectedOptions[0];

                if (!idBarang || !selectedOption) {
                    notificationAlert('error', 'Error', 'Harap pilih barang terlebih dahulu!');
                    return;
                }

                let namaBarang = selectedOption.text;
                let stok = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                let qtyInput = document.getElementById('jml_item').value.trim();
                let hargaInput = document.getElementById('harga').value.trim();

                // Pastikan qty dan harga bernilai angka yang valid
                let qty = qtyInput ? parseInt(qtyInput) : 0;
                let harga = hargaInput ? parseInt(hargaInput) : 0;

                if (qty > stok) {
                    notificationAlert('error', 'Error', 'Stock barang tidak cukup!');
                    return;
                }

                if (stok === 0) {
                    notificationAlert('error', 'Error', 'Stock barang sudah habis!');
                    return;
                }

                if (qty <= 0) {
                    notificationAlert('error', 'Error', 'Jumlah item tidak boleh 0 atau kosong!');
                    return;
                }

                if (addedItems.has(idBarang)) {
                    notificationAlert('error', 'Error', 'Barang ini sudah ditambahkan sebelumnya.');
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
                document.querySelector('tfoot tr th:last-child').textContent =
                    `Rp ${subtotal.toLocaleString('id-ID')}`;

                toggleInputFields(true);
                updateNumbers();

                $('#id_barang').val(null).trigger('change');
            });

            document.querySelector('tbody')?.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    let row = e.target.closest('tr');
                    let idBarang = row.querySelector('input[name="id_barang[]"]').value;
                    let totalHarga = parseInt(row.querySelector('td:last-child').textContent
                        .replace(
                            /[^\d]/g, '')) || 0;

                    subtotal -= totalHarga;
                    row.remove();
                    addedItems.delete(idBarang);

                    lastDeletedItem = {
                        id: idBarang,
                        nama: row.querySelector('td:nth-child(3)').textContent,
                        qty: row.querySelector('td:nth-child(4)').textContent,
                        harga: row.querySelector('td:nth-child(5)').textContent.replace(/\D/g,
                            '')
                    };

                    document.querySelector('tfoot tr th:last-child').textContent =
                        `Rp ${subtotal.toLocaleString('id-ID')}`;
                    updateNumbers();

                    if (!addedItems.size) {
                        toggleInputFields(false);
                    } else {
                        document.getElementById('id_barang').value = lastDeletedItem.id;
                        document.getElementById('jml_item').value = lastDeletedItem.qty;
                        document.getElementById('harga').value = lastDeletedItem.harga;
                        checkInputFields();
                    }
                }
            });

            document.getElementById('id_barang')?.addEventListener('change', function() {
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
        }

        async function initPageLoad() {
            await selectData(selectOptions);
            await selectFormat('#toko_pengirim', 'Pilih Toko');
            await selectFormat('#nama_pengirim', 'Pilih Pengirim');
            await selectFormat('#id_barang', 'Pilih Barang', false);
            await setCreate();
        }
    </script>
@endsection
