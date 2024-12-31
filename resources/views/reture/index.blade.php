@extends('layouts.main')

@section('title')
    Barang Reture
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
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
                                <a class="btn btn-primary mb-2 mb-lg-0 text-white add-data">
                                    <i class="fa fa-plus-circle"></i> Tambah
                                </a>

                                <form id="custom-filter" class="d-flex justify-content-between align-items-center mx-2">
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
                                                <th class="text-center text-wrap align-top">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listDataTable">
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

                <div id="modal-form" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                    aria-labelledby="modal-title" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 90%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="modal-title"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="custom-tab">
                                        <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link {{ session('tab') == 'detail' ? '' : 'active' }}"
                                                    id="tambah-tab" data-toggle="tab" href="#tambah" role="tab"
                                                    aria-controls="tambah" aria-selected="true"
                                                    {{ session('tab') == 'detail' ? 'style=pointer-events:none;opacity:0.6;' : '' }}>Tambah
                                                    Reture</a>
                                                <a class="nav-item nav-link {{ session('tab') == 'detail' ? 'active' : '' }}"
                                                    id="detail-tab" data-toggle="tab" href="#detail" role="tab"
                                                    aria-controls="detail" aria-selected="false"
                                                    {{ session('tab') == '' ? 'style=pointer-events:none;opacity:0.6;' : '' }}>Detail
                                                    Reture</a>
                                            </div>
                                        </nav>
                                        <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="tambah" role="tabpanel"
                                                aria-labelledby="tambah-tab">
                                                <br>
                                                <form id="form">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label for="tgl_retur" class="form-control-label">Tanggal
                                                                Reture</label>
                                                            <input class="form-control tgl_retur" type="text"
                                                                name="tgl_retur" id="tgl_retur"
                                                                placeholder="Pilih tanggal" readonly>
                                                        </div>
                                                        <div class="col-6">
                                                            <label for="no_nota" class=" form-control-label">Nomor
                                                                Nota<span style="color: red">*</span></label>
                                                            <input type="number" id="no_nota" name="no_nota"
                                                                placeholder="Contoh : 001" class="form-control">
                                                        </div>
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
                                                <ul class="list-group list-group-flush my-4">
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <h5><i class="fa fa-home mr-2"></i>Nomor Nota</h5>
                                                        <span id="i_no_nota" class="badge badge-secondary"></span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <h5><i class="fa fa-calendar mr-2"></i>Tanggal Reture</h5>
                                                        <span id="i_tgl_retur" class="badge badge-secondary"></span>
                                                    </li>
                                                </ul>
                                                {{-- <form id="form-update-pembelian"
                                                    action="{{ route('reture.updateStore') }}" method="POST">
                                                    @csrf --}}
                                                <!-- Item Container -->
                                                <div id="item-container">
                                                    <div class="item-group">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="table-responsive">
                                                                    <div class="mb-4">
                                                                        <label for="search-data" class="form-label">Scan
                                                                            QR Code Barang<span style="color: red"
                                                                                class="ml-1">*</span></label>
                                                                        <div class="input-group">
                                                                            <input id="search-data" type="text"
                                                                                class="form-control"
                                                                                placeholder="Masukkan/Scan QR Code Barang"
                                                                                aria-label="QRCode" required>
                                                                            <div class="input-group-append">
                                                                                <button id="btn-search-data"
                                                                                    class="btn btn-primary"
                                                                                    type="button"><i
                                                                                        class="fa fa-magnifying-glass mr-2"></i>Cari</button>
                                                                            </div>
                                                                        </div>
                                                                        <small class="text-danger"><b
                                                                                id="info-input"></b></small>
                                                                    </div>
                                                                    <form action="{{ route('reture.updateStore') }}"
                                                                        method="post" id="retureForm">
                                                                        @csrf
                                                                        <div class="table-responsive table-scroll-wrapper">
                                                                            <table class="table table-bordered">
                                                                                <thead>
                                                                                    <tr class="tb-head">
                                                                                        <th
                                                                                            class="text-wrap align-top text-center">
                                                                                            No</th>
                                                                                        <th
                                                                                            class="text-wrap align-top text-center">
                                                                                            #</th>
                                                                                        <th class="text-wrap align-top">Qty
                                                                                        </th>
                                                                                        <th class="text-wrap align-top">ID
                                                                                            Transaksi</th>
                                                                                        <th class="text-wrap align-top">
                                                                                            Nama Toko</th>
                                                                                        <th class="text-wrap align-top">No
                                                                                            Nota</th>
                                                                                        <th class="text-wrap align-top">
                                                                                            Nama Member</th>
                                                                                        <th class="text-wrap align-top">
                                                                                            Harga Jual (Rp)</th>
                                                                                        <th class="text-wrap align-top">
                                                                                            Nama Barang</th>
                                                                                        <th
                                                                                            class="text-wrap align-top text-center">
                                                                                            #</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody id="listData">
                                                                                    <tr class="empty-row">
                                                                                        <td colspan="10"
                                                                                            class="text-center font-weight-bold">
                                                                                            <span class="badge badge-info"
                                                                                                style="font-size: 14px;">
                                                                                                <i
                                                                                                    class="fa fa-circle-info mr-2"></i>Silahkan
                                                                                                masukkan QR-Code
                                                                                                terlebih dahulu.
                                                                                            </span>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="form-group mt-4">
                                                                            <button type="submit"
                                                                                class="btn btn-success">
                                                                                <i class="fa fa-save mr-2"></i>Simpan
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- <br>
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
                                                            </div> --}}
                                                    </div>
                                                </div>
                                                {{-- <br><br><br>
                                                </form> --}}
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
    </div>
@endsection

@section('asset_js')
    <script src="{{ asset('js/flatpickr.js') }}"></script>
    <script src="{{ asset('js/sortable.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/daterange-picker.js') }}"></script>
    <script src="{{ asset('js/daterange-custom.js') }}"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>
@endsection

@section('js')
    <script>
        let customFilter = {};
        let rowCount = 0;

        function getExistingTransactionIds() {
            const transactionInputs = document.querySelectorAll('input[name="id_transaksi[]"]');
            return Array.from(transactionInputs).map(input => input.value);
        }

        async function getData(customFilter = {}) {
            const tbody = document.getElementById('listData');

            const loadingRow = document.querySelector('#listData .loading-row');
            if (!loadingRow) {
                handleEmptyState();
                tbody.innerHTML += loadingData();
            }

            let filterParams = {};
            if (customFilter['qrcode']) {
                filterParams.qrcode = customFilter['qrcode'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.getreture') }}', {
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status === 200) {
                let data = getDataRest.data.data;
                if (data) {
                    $('#info-input').html('Masukkan QR Code lain, jika ingin menambah reture')
                    addRowToTable(data);
                    resetQRCodeInput();
                } else {
                    handleEmptyState();
                }
            } else {
                let errorMessage = getDataRest?.data?.message || 'Data gagal dimuat';
                notificationAlert('error', 'Kesalahan', errorMessage);
                handleEmptyState();
            }
        }

        function addRowToTable(data) {
            const tbody = document.getElementById('listData');

            const loadingRow = document.querySelector('#listData .loading-row');
            if (loadingRow) {
                tbody.removeChild(loadingRow);
            }

            const existingIds = getExistingTransactionIds();

            rowCount++;
            const tr = document.createElement('tr');
            const rowId = `row-${rowCount}`;
            tr.id = rowId;

            tr.innerHTML = `
                <td class="text-wrap align-top text-center">${rowCount}</td>
                <td class="text-wrap align-top text-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow('${rowId}')">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
                <td class="text-wrap align-top">
                    <input type="number" name="qty[]" value="0" max="${data.qty_beli || 0}" class="form-control" required>
                    <small class="text-danger"><b>Maksimal Qty: ${data.qty_beli || 0}</b></small>
                </td>
                <td class="text-wrap align-top"><input type="text" name="id_transaksi[]" value="${data.id_transaksi || ''}" class="form-control" readonly required></td>
                <td class="text-wrap align-top"><input type="text" name="nama_toko[]" value="${data.nama_toko || ''}" class="form-control" readonly required></td>
                <td class="text-wrap align-top"><input type="text" name="no_nota[]" value="${data.no_nota || ''}" class="form-control" readonly required></td>
                <td class="text-wrap align-top"><input type="text" name="nama_member[]" value="${data.nama_member || 'Guest'}" class="form-control" readonly required></td>
                <td class="text-wrap align-top"><input type="text" name="harga_jual[]" value="${data.harga_jual || 0}" class="form-control" readonly required></td>
                <td class="text-wrap align-top"><input type="text" name="nama_barang[]" value="${data.nama_barang || ''}" class="form-control" readonly required></td>
                <td class="text-wrap align-top text-center">
                    <button class="btn btn-sm btn-outline-secondary move-icon" style="cursor: grab;"><i class="fa fa-up-down mx-1"></i></button>
                </td>
            `;

            tbody.appendChild(tr);
        }

        function setSortable() {
            const tbody = document.getElementById('listData');

            const sortable = new Sortable(tbody, {
                handle: '.move-icon',
                animation: 150,
                onEnd: function(evt) {
                    updateRowNumbers();
                }
            });
        }

        function removeRow(rowId) {
            const row = document.getElementById(rowId);
            if (row) {
                row.remove();
            }

            updateRowNumbers();

            handleEmptyState();
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#listData tr:not(.empty-row)');
            rowCount = 0;
            rows.forEach((row, index) => {
                rowCount++;
                const numberCell = row.querySelector('td:first-child');
                if (numberCell) {
                    numberCell.textContent = rowCount;
                }
            });
        }

        function handleEmptyState() {
            const tbody = document.getElementById('listData');

            const loadingRow = document.querySelector('#listData .loading-row');
            if (loadingRow) {
                tbody.removeChild(loadingRow);
            }

            if (!tbody.querySelector('tr:not(.loading-row)')) {
                const emptyRow = document.createElement('tr');
                emptyRow.className = 'empty-row';
                emptyRow.innerHTML = `
                <td colspan="8" class="text-center font-weight-bold">
                    <span class="badge badge-info" style="font-size: 14px;">
                        <i class="fa fa-circle-info mr-2"></i>Silahkan masukkan QR-Code terlebih dahulu.
                    </span>
                </td>`;
                tbody.appendChild(emptyRow);
            }
        }

        function resetQRCodeInput() {
            document.getElementById('search-data').value = '';

            const emptyRow = document.querySelector('#listData .empty-row');
            if (emptyRow) {
                emptyRow.remove();
            }
        }

        async function searchData() {
            const searchInput = document.getElementById('search-data');
            const searchButton = document.getElementById('btn-search-data');

            if (!searchButton.hasAttribute('listener-attached')) {
                searchButton.setAttribute('listener-attached', 'true');
                searchButton.addEventListener('click', async () => {
                    await triggerSearch();
                });
            }

            if (!searchInput.hasAttribute('listener-attached')) {
                searchInput.setAttribute('listener-attached', 'true');
                searchInput.addEventListener('keypress', async (event) => {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        await triggerSearch();
                    }
                });
            }
        }

        async function triggerSearch() {
            const qrcodeValue = document.getElementById('search-data').value;
            if (qrcodeValue.trim() === "") {
                notificationAlert('info', 'Pemberitahuan', 'Masukkan QRCode terlebih dahulu.');
                return;
            }
            customFilter = {
                qrcode: qrcodeValue
            };
            await getData(customFilter);
        }

        async function setDatePicker() {
            flatpickr("#tgl_retur", {
                dateFormat: "Y-m-d",
                defaultDate: null,
                allowInput: true,
                appendTo: document.querySelector('.modal-body'),
                position: "above",
                onDayCreate: (dObj, dStr, fp, dayElem) => {
                    dayElem.addEventListener('click', () => {
                        fp.calendarContainer.querySelectorAll('.selected').forEach(el => {
                            el.style.backgroundColor = "#1abc9c";
                            el.style.color = "#fff";
                        });
                    });
                }
            });
        }

        async function addData() {
            $(document).on("click", ".add-data", function() {
                $("#modal-title").html(`Form Tambah Reture`);
                $("#modal-form").modal("show");
                $("form").find("input, select, textarea").val("").prop("checked", false)
                    .trigger("change");

                $("#form").data("action-url", '{{ route('reture.storeNota') }}');
            });
        }

        async function submitForm() {
            $(document).on("submit", "#form", async function(e) {
                e.preventDefault();
                loadingPage(true);

                let actionUrl = $("#form").data("action-url");
                let formData = {
                    tgl_retur: $('#tgl_retur').val(),
                    no_nota: $('#no_nota').val()
                };

                let method = 'POST';
                try {
                    let postData = await renderAPI(method, actionUrl, formData);

                    loadingPage(false);
                    if (postData.status >= 200 && postData.status < 300) {
                        let rest_data = postData.data.data;
                        $('#nav-tab a[href="#detail"]').tab('show');
                        $('#i_no_nota').text(rest_data.no_nota);
                        $('#i_tgl_retur').text(rest_data.tgl_retur);

                        $('#tambah-tab').removeAttr(
                            'style');
                        $('#detail-tab').removeAttr(
                            'style');
                    } else {
                        notificationAlert('info', 'Pemberitahuan', postData.message || 'Terjadi kesalahan');
                    }
                } catch (error) {
                    loadingPage(false);
                    let resp = error.response || {};
                }
            });
        }

        async function initPageLoad() {
            await addData();
            await submitForm();
            await setDatePicker();
            await searchData();
            await setSortable();
        }
    </script>
@endsection
