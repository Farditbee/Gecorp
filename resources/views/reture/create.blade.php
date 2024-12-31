@extends('layouts.main')

@section('title')
    Tambah Data Reture
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <a href="{{ url()->previous() }}" class="btn btn-danger">
                                <i class="ti-plus menu-icon"></i> Kembali
                            </a>
                        </div>
                        <x-adminlte-alerts />
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <div class="mb-4">
                                    <label for="search-data" class="form-label">Scan QR Code Barang<span style="color: red"
                                            class="ml-1">*</span></label>
                                    <div class="input-group">
                                        <input id="search-data" type="text" class="form-control"
                                            placeholder="Masukkan/Scan QR Code Barang" aria-label="QRCode" required>
                                        <div class="input-group-append">
                                            <button id="btn-search-data" class="btn btn-primary" type="button"><i
                                                    class="fa fa-magnifying-glass mr-2"></i>Cari</button>
                                        </div>
                                    </div>
                                    <small class="text-danger"><b id="info-input"></b></small>
                                </div>
                                <form action="{{ route('reture.store') }}" method="post" id="retureForm">
                                    @csrf
                                    <div class="table-responsive table-scroll-wrapper">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="tb-head">
                                                    <th class="text-wrap align-top text-center">No</th>
                                                    <th class="text-wrap align-top text-center">#</th>
                                                    <th class="text-wrap align-top">Qty</th>
                                                    <th class="text-wrap align-top">ID Transaksi</th>
                                                    <th class="text-wrap align-top">Nama Toko</th>
                                                    <th class="text-wrap align-top">Tipe Transaksi</th>
                                                    <th class="text-wrap align-top">Nama Member</th>
                                                    <th class="text-wrap align-top">Harga Jual (Rp)</th>
                                                    <th class="text-wrap align-top">Nama Barang</th>
                                                    <th class="text-wrap align-top text-center">#</th>
                                                </tr>
                                            </thead>
                                            <tbody id="listData">
                                                <tr class="empty-row">
                                                    <td colspan="10" class="text-center font-weight-bold">
                                                        <span class="badge badge-info" style="font-size: 14px;">
                                                            <i class="fa fa-circle-info mr-2"></i>Silahkan masukkan QR-Code
                                                            terlebih dahulu.
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-save mr-2"></i>Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('asset_js')
    <script src="{{ asset('js/sortable.js') }}"></script>
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
                <td class="text-wrap align-top"><input type="text" name="tipe_transaksi[]" value="${data.tipe_transaksi || ''}" class="form-control" readonly required></td>
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

        async function initPageLoad() {
            await searchData();
            await setSortable();
        }
    </script>
@endsection
