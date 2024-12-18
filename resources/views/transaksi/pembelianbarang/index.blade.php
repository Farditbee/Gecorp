@extends('layouts.main')

@section('title')
    Pembelian Barang
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                                <a class="btn btn-primary mb-2 mb-lg-0 text-white" data-toggle="modal"
                                    data-target=".bd-example-modal-lg">
                                    <i class="fa fa-plus-circle"></i> Tambah
                                </a>

                                <form id="custom-filter"
                                    class="d-flex justify-content-between align-items-center mx-2">
                                    <input class="form-control w-75 mx-1 mb-lg-0" type="text" id="daterange"
                                        name="daterange" placeholder="Pilih rentang tanggal">
                                    <button class="btn btn-warning ml-1 w-50" id="tb-filter" type="submit">
                                        <i class="fa fa-filter"></i> Filter
                                    </button>
                                </form>
                            </div>

                            <div class="d-flex justify-content-between align-items-lg-start flex-wrap">
                                <select name="limitPage" id="limitPage" class="form-control mr-2 mb-2 mb-lg-0"
                                    style="width: 100px;">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                </select>
                                <input id="tb-search" class="tb-search form-control mb-2 mb-lg-0" type="search"
                                    name="search" placeholder="Cari Data" aria-label="search" style="width: 200px;">
                            </div>
                        </div>
                        <div class="content">
                            <x-adminlte-alerts />
                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-hover m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">Status</th>
                                                <th class="text-wrap align-top">No. Nota</th>
                                                <th class="text-wrap align-top">Tanggal Nota</th>
                                                <th class="text-wrap align-top">Supplier</th>
                                                <th class="text-wrap align-top">Total Item</th>
                                                <th class="text-wrap align-top">Total Harga</th>
                                                <th class="text-center text-wrap align-top">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listData">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3">
                                    <div class="text-center text-md-start mb-2 mb-md-0">
                                        <div class="pagination">
                                            <div>Menampilkan <span id="countPage">0</span> dari <span
                                                    id="totalPage">0</span> data</div>
                                        </div>
                                    </div>
                                    <nav class="text-center text-md-end">
                                        <ul class="pagination justify-content-center justify-content-md-end"
                                            id="pagination-js">
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal untuk Filter Tanggal -->
                <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterModalLabel">Filter Tanggal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('master.pembelianbarang.index') }}" method="GET">
                                    <div class="form-group">
                                        <label for="startDate">Tanggal Mulai</label>
                                        <input type="date" name="startDate" id="startDate" class="form-control"
                                            value="{{ request('startDate') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="endDate">Tanggal Selesai</label>
                                        <input type="date" name="endDate" id="endDate" class="form-control"
                                            value="{{ request('endDate') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lgs">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="myLargeModalLabel">Pembelian Barang</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="custom-tab">
                                        <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="tambah-tab" data-toggle="tab"
                                                    href="#tambah" role="tab" aria-controls="tambah"
                                                    aria-selected="true">Tambah Pembelian</a>
                                                <a class="nav-item nav-link disabled" id="detail-tab" data-toggle="tab"
                                                    href="#detail" role="tab" aria-controls="detail"
                                                    aria-selected="false">Detail Pembelian</a>
                                            </div>
                                        </nav>
                                        <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="tambah" role="tabpanel"
                                                aria-labelledby="tambah-tab">
                                                <br>
                                                <form id="form-tambah-pembelian"
                                                    action="{{ route('master.pembelianbarang.store') }}" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <!-- Nama Supplier -->
                                                            <div class="form-group">
                                                                <label for="id_supplier" class="form-control-label">Nama
                                                                    Supplier</label>
                                                                <select name="id_supplier" id="id_supplier">
                                                                    <option value="" selected>Pilih Supplier</option>
                                                                    @foreach ($suppliers as $supplier)
                                                                        <option value="{{ $supplier->id }}">
                                                                            {{ $supplier->nama_supplier }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <label for="id_supplier" class="form-control-label">Tanggal
                                                                Nota</label>
                                                            <input class="form-control" type="date" name="tgl_nota"
                                                                id="tgl_nota">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="no_nota" class=" form-control-label">Nomor Nota<span
                                                                style="color: red">*</span></label>
                                                        <input type="number" id="no_nota" name="no_nota"
                                                            placeholder="Contoh : 001" class="form-control">
                                                    </div>
                                                    <button type="submit" style="float: right" id="save-btn"
                                                        class="btn btn-primary">
                                                        <span id="save-btn-text"><i class="fa fa-save"></i> Lanjut</span>
                                                        <span id="save-btn-spinner"
                                                            class="spinner-border spinner-border-sm" role="status"
                                                            style="display: none;"></span>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="detail" role="tabpanel"
                                                aria-labelledby="detail-tab">
                                                <br>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        <h5><i class="fa fa-home"></i> Nomor Nota <span id="no-nota"
                                                                class="badge badge-secondary pull-right"></span></h5>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <h5><i class="fa fa-globe"></i> Nama Supplier <span
                                                                id="nama-supplier"
                                                                class="badge badge-secondary pull-right"></span></h5>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <h5><i class="fa fa-map-marker"></i> &nbsp;Tanggal Nota <span
                                                                id="tgl-nota"
                                                                class="badge badge-secondary pull-right"></span></h5>
                                                    </li>
                                                </ul>
                                                <br>
                                                <form id="form-update-pembelian"
                                                    action="{{ route('master.pembelianbarang.update', ':id') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <!-- Item Container -->
                                                    <div id="item-container">
                                                        <div class="item-group">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <!-- Jenis Barang -->
                                                                    <div class="form-group">
                                                                        <label for="id_barang"
                                                                            class="form-control-label">Nama Barang<span
                                                                                style="color: red">*</span></label>
                                                                        <select name="id_barang[]" id="id_barang"
                                                                            data-placeholder="Pilih Barang...">
                                                                            <option value="" disabled selected
                                                                                required>Pilih Barang</option>
                                                                            @foreach ($barang as $brg)
                                                                                <option value="{{ $brg->id }}"
                                                                                    data-barcode="{{ $brg->barcode }}">
                                                                                    {{ $brg->nama_barang }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <!-- Jumlah Item -->
                                                                    <div class="form-group">
                                                                        <label for="jml_item"
                                                                            class="form-control-label">Jumlah Item<span
                                                                                style="color: red">*</span></label>
                                                                        <input type="number" id="jml_item"
                                                                            min="1" name="qty[]"
                                                                            placeholder="Contoh: 16"
                                                                            class="form-control jumlah-item">
                                                                    </div>
                                                                </div>

                                                                <div class="col-6">
                                                                    <!-- Harga Barang -->
                                                                    <div class="form-group">
                                                                        <label for="harga_barang"
                                                                            class="form-control-label">Harga Barang<span
                                                                                style="color: red">*</span></label>
                                                                        <input type="number" id="harga_barang"
                                                                            min="1" name="harga_barang[]"
                                                                            placeholder="Contoh: 16000"
                                                                            class="form-control harga-barang">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br><br>

                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="card border border-primary">
                                                                <div class="card-body">
                                                                    <p class="card-text">Detail Stock
                                                                        <strong>(GSS)</strong>
                                                                    </p>
                                                                    <p class="card-text">Stock :<strong
                                                                            class="stock">0</strong></p>
                                                                    <p class="card-text">Hpp Awal : <strong
                                                                            class="hpp-awal">Rp 0</strong></p>
                                                                    <p class="card-text">Hpp Baru : <strong
                                                                            class="hpp-baru">Rp 0</strong></p>
                                                                </div>
                                                                <button type="button" id="reset"
                                                                    style="float: right"
                                                                    class="btn btn-secondary">Reset</button>
                                                            </div>
                                                            <button type="button" id="add-item-detail"
                                                                style="float: right"
                                                                class="btn btn-secondary">Add</button>
                                                        </div>
                                                        <div class="col-6">
                                                            @foreach ($LevelHarga as $index => $level)
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span
                                                                            class="input-group-text">{{ $level->nama_level_harga }}</span>
                                                                    </div>
                                                                    <input type="hidden" name="level_nama[]"
                                                                        value="{{ $level->nama_level_harga }}">
                                                                    <div class="custom-file">
                                                                        <input type="text"
                                                                            class="form-control level-harga"
                                                                            name="level_harga[]"
                                                                            id="level_harga_{{ $index }}"
                                                                            data-index="{{ $index }}"
                                                                            data-hpp-baru="0">
                                                                        <label class="input-group-text"
                                                                            id="persen_{{ $index }}">0%</label>
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
                                                                        <th scope="col" colspan="5"
                                                                            style="text-align:right">SubTotal</th>
                                                                        <th scope="col">Rp </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                            <!-- Submit Button -->
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-primary pull-right"
                                                                    style="float: right">
                                                                    <i class="fa fa-dot-circle-o"></i> Simpan
                                                                </button>
                                                                <button type="button" id="cancel-button"
                                                                    class="btn btn-warning pull-right"
                                                                    style="float: right">
                                                                    <i class="fa fa-dot-circle-o"></i> Cancel
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
                    <!-- [ Main Content ] end -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('asset_js')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/daterange-picker.js') }}"></script>
    <script src="{{ asset('js/daterange-custom.js') }}"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>
@endsection

@section('js')
    <script>
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            let filterParams = {};

            if (customFilter['startDate'] && customFilter['endDate']) {
                filterParams.startDate = customFilter['startDate'];
                filterParams.endDate = customFilter['endDate'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.pembelian.get') }}', {
                    page: page,
                    limit: limit,
                    ascending: ascending,
                    search: search,
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status == 200 && Array.isArray(getDataRest.data.data)) {
                let handleDataArray = await Promise.all(
                    getDataRest.data.data.map(async item => await handleData(item))
                );
                await setListData(handleDataArray, getDataRest.data.pagination);
            } else {
                errorMessage = getDataRest?.data?.message;
                let errorRow = `
                            <tr class="text-dark">
                                <th class="text-center" colspan="${$('.tb-head th').length}"> ${errorMessage} </th>
                            </tr>`;
                $('#listData').html(errorRow);
                $('#countPage').text("0 - 0");
                $('#totalPage').text("0");
            }
        }

        async function handleData(data) {
            let status = '';
            if (data?.status === 'Sukses') {
                status =
                    `<span class="badge badge-success custom-badge"><i class="mx-1 fa fa-circle-check"></i>Sukses</span>`;
            } else if (data?.status === 'Gagal') {
                status =
                    `<span class="badge badge-danger custom-badge"><i class="mx-1 fa fa-circle-xmark"></i>Gagal</span>`;
            } else {
                status = `<span class="badge badge-secondary custom-badge">Tidak Diketahui</span>`;
            }

            let detail_button = `
            <a href="pembelianbarang/${data.id}/edit" class="p-1 btn edit-data action_button"
                data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Detail Data Nomor Nota: ${data.no_nota}"
                data-id='${data.id}'>
                <span class="text-dark">Detail</span>
                <div class="icon text-info">
                    <i class="fa fa-eye"></i>
                </div>
            </a>`;

            return {
                id: data?.id ?? '-',
                status,
                nama_supplier: data?.nama_supplier ?? '-',
                tgl_nota: data?.tgl_nota ?? '-',
                no_nota: data?.no_nota ?? '-',
                total_item: data?.total_item ?? '-',
                total_nilai: data?.total_nilai ?? '-',
                detail_button,
            };
        }

        async function setListData(dataList, pagination) {
            totalPage = pagination.total_pages;
            currentPage = pagination.current_page;
            let display_from = ((defaultLimitPage * (currentPage - 1)) + 1);
            let display_to = Math.min(display_from + dataList.length - 1, pagination.total);

            let getDataTable = '';
            let classCol = 'align-center text-dark text-wrap';
            dataList.forEach((element, index) => {
                getDataTable += `
                            <tr class="text-dark">
                                <td class="${classCol} text-center">${display_from + index}.</td>
                                <td class="${classCol}">${element.status}</td>
                                <td class="${classCol}">${element.no_nota}</td>
                                <td class="${classCol}">${element.tgl_nota}</td>
                                <td class="${classCol}">${element.nama_supplier}</td>
                                <td class="${classCol}">${element.total_item}</td>
                                <td class="${classCol}">${element.total_nilai}</td>
                                <td class="${classCol}">
                                    <div class="d-flex justify-content-center">
                                        <div class="hovering p-1">
                                            ${element.detail_button}
                                        </div>
                                    </div>
                                </td>
                            </tr>`;
            });

            $('#listData').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            renderPagination();
        }

        async function filterList() {
            let dateRangePickerList = initializeDateRangePicker();

            document.getElementById('custom-filter').addEventListener('submit', async function(e) {
                e.preventDefault();
                let startDate = dateRangePickerList.data('daterangepicker').startDate;
                let endDate = dateRangePickerList.data('daterangepicker').endDate;

                if (!startDate || !endDate) {
                    startDate = null;
                    endDate = null;
                } else {
                    startDate = startDate.startOf('day').toISOString();
                    endDate = endDate.endOf('day').toISOString();
                }

                customFilter = {
                    'startDate': $("#daterange").val() != '' ? startDate : '',
                    'endDate': $("#daterange").val() != '' ? endDate : ''
                };

                defaultSearch = $('.tb-search').val();
                defaultLimitPage = $("#limitPage").val();
                currentPage = 1;

                await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                    customFilter);
            });
        }

        async function addData() {
            let subtotal = 0;
            let addedItems = new Set();

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

            document.getElementById('add-item-detail').addEventListener('click', function() {
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

                let allLevelsFilled = true;
                document.querySelectorAll('.level-harga').forEach((input) => {
                    if (!input.value) {
                        allLevelsFilled = false;
                    }
                });

                if (!allLevelsFilled) {
                    alert('Harap atur level harga ! jika tidak, silahkan isi dengan "0"');
                    return;
                }

                addedItems.add(idBarang);

                // Menyembunyikan pilihan barang yang sudah ditambahkan
                document.querySelector(`#id_barang option[value="${idBarang}"]`).setAttribute('hidden',
                    true);

                let totalHarga = qty * harga;
                subtotal += totalHarga;

                // Generate hidden input fields for level prices
                let levelHargaInputs = '';
                document.querySelectorAll('.level-harga').forEach((input, index) => {
                    const levelHarga = input.value;
                    levelHargaInputs +=
                        `<input type="hidden" name="level_harga[${idBarang}][]" value="${levelHarga}">`;
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

                document.querySelector('.table-bordered tfoot tr th:last-child').textContent =
                    `Rp ${subtotal.toLocaleString('id-ID')}`;

                // Disable input fields after adding
                toggleInputFields(true);

                document.getElementById('id_barang').value = '';

                resetFields();

                updateNumbers();
            });

            document.querySelector('.table-bordered tbody').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    let row = e.target.closest('tr');
                    let idBarang = row.querySelector('input[name="id_barang[]"]').value;
                    let qty = row.querySelector('input[name="qty[]"]').value;
                    let harga = row.querySelector('input[name="harga_barang[]"]').value;
                    let totalHarga = parseInt(row.querySelector('td:nth-child(6)').textContent.replace(
                        /\D/g, ''));

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
                    document.querySelector('.table-bordered tfoot tr th:last-child').textContent =
                        `Rp ${subtotal.toLocaleString('id-ID')}`;

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

            document.getElementById('id_barang').addEventListener('change', function() {
                checkInputFields();
                document.getElementById('jml_item').value = '';
                document.getElementById('harga_barang').value = '';

                let idBarang = this.value;

                if (idBarang) {

                    fetch(`/admin/get-stock-details/${idBarang}`)
                        .then(response => response.json())
                        .then(data => {
                            // Simpan nilai awal yang diterima dari server
                            initialHppBaru = data.hpp_baru || 0;
                            initialStock = data.stock || 0;
                            initialHppAwal = data.hpp_awal || 0;

                            // Tampilkan nilai dari server
                            document.querySelector('.card-text strong.stock').textContent = initialStock
                                .toLocaleString('id-ID');
                            document.querySelector('.card-text strong.hpp-awal').textContent =
                                `Rp ${initialHppAwal.toLocaleString('id-ID')}`;
                            document.querySelector('.card-text strong.hpp-baru').textContent =
                                `Rp ${initialHppBaru.toLocaleString('id-ID')}`;

                            // Set data-hpp-baru di input level harga
                            document.querySelectorAll('.level-harga').forEach(function(input) {
                                input.setAttribute('data-hpp-baru', initialHppBaru);
                            });

                            // Simpan nilai level harga asli dari server
                            originalLevelHarga = {
                                ...data.level_harga
                            }; // Simpan salinan level harga asli

                            // Mengisi nilai level harga dari server dan menghitung persentase
                            document.querySelectorAll('input[name="level_nama[]"]').forEach(function(
                                namaLevelInput, index) {
                                const namaLevel = namaLevelInput.value;
                                const inputField = document.querySelectorAll(
                                    'input[name="level_harga[]"]')[index];
                                const persenElement = document.querySelector(
                                    `#persen_${index}`);

                                // Jika level ada di data server, tampilkan, jika tidak biarkan kosong
                                if (data.level_harga.hasOwnProperty(namaLevel)) {
                                    inputField.value = data.level_harga[namaLevel];
                                    let levelHarga = parseFloat(inputField.value) || 0;
                                    let persen = 0;
                                    if (initialHppAwal > 0) {
                                        persen = ((levelHarga - initialHppAwal) /
                                            initialHppAwal) * 100;
                                    }
                                    persenElement.textContent = `${persen.toFixed(2)}%`;
                                } else {
                                    inputField.value = ''; // Biarkan kosong jika tidak ada data
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
                document.querySelectorAll('.jumlah-item, .harga-barang').forEach(function(input) {
                    input.addEventListener('input', function() {
                        calculateHPP(totalHarga, totalQty);
                    });
                });
            }

            document.querySelectorAll('.jumlah-item, .harga-barang').forEach(function(input) {
                input.addEventListener('input', function() {
                    calculateHPP(0,
                        0
                    ); // Asumsikan barang baru jika tidak ada total harga atau qty dari database
                });
            });

            function calculateHPP(totalHarga, totalQty) {
                let jumlah = parseFloat(document.querySelector('.jumlah-item').value) || 0;
                let harga = parseFloat(document.querySelector('.harga-barang').value) || 0;

                let hppAwal = initialHppAwal || 0; // Ambil HPP awal dari server

                if (jumlah > 0 && harga > 0) {
                    let totalHargaBaru = jumlah * harga;

                    // Hitung total keseluruhan harga dan total qty
                    let totalKeseluruhanHarga = totalHargaBaru + totalHarga;
                    let totalKeseluruhanQty = jumlah + totalQty;

                    // Hitung HPP baru
                    let finalHpp = totalKeseluruhanHarga / totalKeseluruhanQty;

                    // Tampilkan hasil HPP baru
                    document.querySelector('.card-text strong.hpp-baru').textContent =
                        `Rp ${Math.round(finalHpp).toLocaleString('id-ID')}`;

                    // Set nilai HPP baru di setiap input level harga
                    document.querySelectorAll('.level-harga').forEach(function(input) {
                        input.setAttribute('data-hpp-baru', finalHpp);
                    });

                    // Hitung ulang persentase menggunakan HPP baru
                    updatePercentages(finalHpp);

                } else {
                    // Jika input jumlah atau harga dikosongkan, gunakan HPP awal dari server
                    document.querySelector('.card-text strong.hpp-baru').textContent =
                        `Rp ${initialHppBaru.toLocaleString('id-ID')}`;

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

                    const persenElement = document.getElementById(
                        `persen_${input.getAttribute('data-index')}`);
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

            function resetFields() {
                // Kosongkan tampilan HPP, stock, dan level harga
                document.querySelector('.card-text strong.stock').textContent = '0';
                document.querySelector('.card-text strong.hpp-awal').textContent = 'Rp 0';
                document.querySelector('.card-text strong.hpp-baru').textContent = 'Rp 0';

                // Kosongkan nilai level harga
                document.querySelectorAll('.level-harga').forEach(function(input) {
                    input.value = '';
                    const persenElement = document.getElementById(
                        `persen_${input.getAttribute('data-index')}`);
                    if (persenElement) {
                        persenElement.textContent = '0%';
                    }
                });
            }

            // Fungsi untuk mereset nilai ke nilai asli dari server
            function resetFieldsToOriginal() {
                // Cek apakah sudah ada HPP baru
                let currentHppBaru = parseFloat(document.querySelector('.card-text strong.hpp-baru').textContent
                    .replace(/\D/g, ''));

                let hppUntukPerhitungan = initialHppAwal; // Default gunakan HPP awal

                // Jika HPP baru sudah dihitung, gunakan HPP baru
                if (currentHppBaru && currentHppBaru > 0) {
                    hppUntukPerhitungan = currentHppBaru;
                }

                // Kembalikan nilai HPP dan stock dari server
                document.querySelector('.card-text strong.stock').textContent = initialStock.toLocaleString(
                    'id-ID');
                document.querySelector('.card-text strong.hpp-awal').textContent =
                    `Rp ${initialHppAwal.toLocaleString('id-ID')}`;
                document.querySelector('.card-text strong.hpp-baru').textContent =
                    `Rp ${hppUntukPerhitungan.toLocaleString('id-ID')}`;

                // Kembalikan nilai level harga ke nilai asli dari server
                document.querySelectorAll('input[name="level_nama[]"]').forEach(function(namaLevelInput, index) {
                    const namaLevel = namaLevelInput.value;
                    const inputField = document.querySelectorAll('input[name="level_harga[]"]')[index];
                    const persenElement = document.querySelector(`#persen_${index}`);

                    // Jika level ada di data server, tampilkan, jika tidak biarkan kosong
                    if (originalLevelHarga.hasOwnProperty(namaLevel)) {
                        inputField.value = originalLevelHarga[namaLevel] ||
                            ''; // Kembalikan nilai asli jika ada
                        let levelHarga = parseFloat(inputField.value) || 0;
                        let persen = 0;
                        if (hppUntukPerhitungan > 0) {
                            persen = ((levelHarga - hppUntukPerhitungan) / hppUntukPerhitungan) * 100;
                        }
                        persenElement.textContent = `${persen.toFixed(2)}%`;
                    } else {
                        inputField.value = ''; // Kosongkan jika tidak ada data
                        persenElement.textContent = '0%';
                    }
                });
            }

            // Tambahkan event listener pada tombol reset
            document.getElementById('reset').addEventListener('click', function() {
                let idBarang = document.getElementById('id_barang').value;
                if (idBarang) {
                    // Jika ada barang yang dipilih, kembalikan nilai asli dari server
                    resetFieldsToOriginal();
                } else {
                    // Jika tidak ada barang yang dipilih, reset semua field menjadi kosong
                    resetFields();
                }
            });

            document.getElementById('cancel-button').addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah event default jika ada
                location.reload(); // Reload halaman
            });

        }

        async function initPageLoad() {
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await filterList();
            await addData();
        }
        document.addEventListener('DOMContentLoaded', function() {
                new TomSelect("#id_supplier", {
                    placeholder: "Pilih Supplier",
                    allowClear: true, // Menambahkan tombol clear jika Anda membutuhkannya
                    create: false // Agar user hanya bisa memilih dari opsi yang ada, bukan membuat opsi baru
                });
            });
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#id_barang", {
                placeholder: "Pilih Barang",
                allowClear: true,
                create: false,
                valueField: "value", // ID barang akan menjadi nilai yang dipilih
                labelField: "text", // Nama barang akan ditampilkan di daftar
                searchField: ["text", "barcode"], // Cari berdasarkan nama barang atau barcode
                plugins: ['clear_button'], // Opsi tambahan, misalnya tombol clear
                onInitialize: function() {
                    const options = [];
                    document.querySelectorAll("#id_barang option").forEach(opt => {
                        options.push({
                            value: opt.value,
                            text: opt.textContent.trim(),
                            barcode: opt.getAttribute("data-barcode")
                        });
                    });
                    this.addOptions(options);
                }
            });
        });
    </script>
@endsection
