@extends('layouts.main')

@section('title')
    Retur Member
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notyf.min.css') }}">
    <style>
        #tgl_retur[readonly] {
            background-color: white !important;
            cursor: pointer !important;
            color: inherit !important;
        }
    </style>
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
                                @if (Auth::user()->id_level == 3 || Auth::user()->id_level == 4)
                                    <button class="btn btn-primary mb-2 mb-lg-0 text-white add-data">
                                        <i class="fa fa-plus-circle"></i> Tambah
                                    </button>
                                @endif
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
                                    <table class="table table-striped m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">No. Nota</th>
                                                <th class="text-wrap align-top">Nama Member</th>
                                                <th class="text-wrap align-top">Tanggal Retur</th>
                                                <th class="text-wrap align-top">Status</th>
                                                <th class="text-wrap align-top">Action</th>
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
                                <button type="button" class="btn-close reset-all close" data-bs-dismiss="modal"
                                    aria-label="Close"><i class="fa fa-xmark"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="custom-tab">
                                        <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="tambah-tab" data-toggle="tab"
                                                    href="#tambah" role="tab" aria-controls="tambah"
                                                    aria-selected="true">
                                                    Tambah Retur
                                                </a>
                                                <a class="nav-item nav-link disabled" id="detail-tab" data-toggle="tab"
                                                    href="#detail" role="tab" aria-controls="detail"
                                                    aria-selected="false" style="pointer-events: none; opacity: 0.6;">
                                                    Detail Retur
                                                </a>
                                            </div>
                                        </nav>
                                        <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="tambah" role="tabpanel"
                                                aria-labelledby="tambah-tab">
                                                <br>
                                                <form id="form">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label for="f_member" class=" form-control-label">Nama
                                                                Member <span class="text-danger">*</span></label>
                                                            <select class="form-select select2" name="member"
                                                                id="f_member">
                                                            </select>
                                                        </div>
                                                        <div class="col-6">
                                                            <label for="tgl_retur" class="form-control-label">Tanggal
                                                                Retur <span class="text-danger">*</span></label>
                                                            <input class="form-control tgl_retur" type="text"
                                                                name="tgl_retur" id="tgl_retur"
                                                                placeholder="Pilih tanggal" readonly>
                                                        </div>
                                                    </div>

                                                    <button type="submit" style="float: right" id="save-btn"
                                                        class="btn btn-primary mt-4">
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
                                                        <h5><i class="fa fa-user-tie mr-2"></i>Nama Member</h5>
                                                        <span id="i_member" class="badge badge-secondary"></span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <h5><i class="fa fa-home mr-2"></i>Nomor Nota</h5>
                                                        <span id="i_no_nota" class="badge badge-secondary"></span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <h5><i class="fa fa-calendar mr-2"></i>Tanggal Retur</h5>
                                                        <span id="i_tgl_retur" class="badge badge-secondary"></span>
                                                    </li>
                                                </ul>
                                                <div id="item-container">
                                                    <div class="item-group">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="table-responsive">
                                                                    <div id="scan-data" class="mb-4">
                                                                        <label for="search-data" class="form-label">
                                                                            Scan QR Code Barang
                                                                            <span style="color: red"
                                                                                class="ml-1">*</span>
                                                                        </label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend">
                                                                                <button id="btn-paste-data"
                                                                                    class="btn btn-secondary"
                                                                                    type="button">
                                                                                    <i
                                                                                        class="fa fa-clipboard mr-1"></i>Paste
                                                                                </button>
                                                                            </div>

                                                                            <input id="search-data" type="text"
                                                                                class="form-control"
                                                                                placeholder="Masukkan/Scan QR Code Barang"
                                                                                aria-label="QRCode" required>

                                                                            <div class="input-group-append">
                                                                                <button id="btn-search-data"
                                                                                    class="btn btn-primary"
                                                                                    type="button">
                                                                                    <i
                                                                                        class="fa fa-magnifying-glass mr-2"></i>Cari
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <small class="text-danger">
                                                                            <b id="info-input"></b>
                                                                        </small>
                                                                    </div>

                                                                    <form id="retureForm">
                                                                        <div class="table-responsive table-scroll-wrapper">
                                                                            <table
                                                                                class="table table-bordered table-custom">
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
                                                                        <div class="form-group mt-2" style="float: right">
                                                                            <button id="submit-reture" type="submit"
                                                                                class="btn btn-success">
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
    <script src="{{ asset('js/notyf.min.js') }}"></script>
@endsection

@section('js')
    <script>
        let customFilter2 = {};
        let customFilter3 = {};
        let rowCount = 0;
        let dataTemp = {};
        let globalIdMember = null;
        let qrCodeResponses = {};

        let title = 'Retur';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};

        let selectOptions = [{
            id: '#f_member',
            isFilter: {
                id_toko: '{{ auth()->user()->id_toko }}',
            },
            isUrl: '{{ route('master.member') }}',
            placeholder: 'Pilih Member',
            isModal: '#modal-form'
        }];

        document.getElementById('btn-paste-data').addEventListener('click', async function() {
            try {
                const text = await navigator.clipboard.readText();
                if (text) {
                    document.getElementById('search-data').value = text;
                    notyf.success('Berhasil menempel QR Code dari clipboard');
                } else {
                    notyf.error('Clipboard kosong atau tidak berisi teks');
                }
            } catch (err) {
                notyf.error('Gagal mengakses clipboard. Izinkan akses clipboard di browser.');
            }
        });

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listDataTable').html(loadingData());

            let filterParams = {};

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('get.tempoData') }}', {
                    page: page,
                    limit: limit,
                    ascending: ascending,
                    search: search,
                    id_toko: '{{ auth()->user()->id_toko }}',
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
                let errorMessage = getDataRest?.data?.message || 'Data gagal dimuat';
                let errorRow = `
                <tr class="text-dark">
                    <th class="text-center" colspan="${$('.tb-head th').length}"> ${errorMessage} </th>
                </tr>`;
                $('#listDataTable').html(errorRow);
                $('#countPage').text("0 - 0");
                $('#totalPage').text("0");
            }
        }

        async function handleData(data) {
            let elementData = JSON.stringify(data);
            let edit_button = '';
            let delete_button = '';

            let status = ''
            if (data.status == 'done') {
                status =
                    `<span class="badge custom-badge badge-success"><i class="fa fa-circle-check mx-1"></i>Sukses</span>`;
                if (data.action === 'edit_detail') {
                    edit_button = `
                    <button class="p-1 btn detail-data action_button"
                        data-container="body" data-toggle="tooltip" data-placement="top"
                        title="Detail ${title} No. Nota: ${data.no_nota}"
                        data-id='${data.id}'
                        data-nota='${data.no_nota}'
                        data-status='${data.status}'
                        data-tanggal='${data.tgl_retur}'
                        data-nama-member='${data.nama_member}'
                        data-id-member='${data.id_member}'>
                        <span class="text-dark">Detail</span>
                        <div class="icon text-info">
                            <i class="fa fa-book"></i>
                        </div>
                    </button>`;
                }
            } else if (data.status == 'pending') {
                if (data.action === 'edit_temp') {
                    status =
                        `<span class="badge custom-badge badge-info"><i class="fa fa-circle-half-stroke mx-1"></i>Pending</span>`;
                    edit_button = `
                    <button class="p-1 btn edit-data action_button"
                        data-container="body" data-toggle="tooltip" data-placement="top"
                        title="Edit ${title} No. Nota: ${data.no_nota}"
                        data-id='${data.id}'
                        data-nota='${data.no_nota}'
                        data-tanggal='${data.tgl_retur}'
                        data-nama-member='${data.nama_member}'
                        data-id-member='${data.id_member}'>
                        <span class="text-dark">Edit</span>
                        <div class="icon text-warning">
                            <i class="fa fa-edit"></i>
                        </div>
                    </button>`;
                } else if (data.action === 'edit_detail') {
                    status =
                        `<span class="badge custom-badge badge-warning"><i class="fa fa-spinner mx-1"></i>Proses</span>`;
                    edit_button = `
                    <button class="p-1 btn detail-data action_button"
                        data-container="body" data-toggle="tooltip" data-placement="top"
                        title="Verifikasi Metode ${title} No. Nota: ${data.no_nota}"
                        data-id='${data.id}'
                        data-nota='${data.no_nota}'
                        data-status='${data.status}'
                        data-tanggal='${data.tgl_retur}'
                        data-nama-member='${data.nama_member}'
                        data-id-member='${data.id_member}'>
                        <span class="text-dark">Verif</span>
                        <div class="icon text-success">
                            <i class="fa fa-circle-check"></i>
                        </div>
                    </button>`;
                }
                delete_button = `
                <a class="p-1 btn hapus-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Hapus ${title} No. Nota: ${data.no_nota}"
                    data-id='${data.id}'
                    data-name='${data.no_nota}'>
                    <span class="text-dark">Hapus</span>
                    <div class="icon text-danger">
                        <i class="fa fa-trash"></i>
                    </div>
                </a>`;
            }

            let action_buttons = '';
            if (edit_button || delete_button) {
                action_buttons = `
                <div class="d-flex justify-content-start">
                    ${edit_button ? `<div class="hovering p-1">${edit_button}</div>` : ''}
                    ${delete_button ? `<div class="hovering p-1">${delete_button}</div>` : ''}
                </div>`;
            } else {
                action_buttons = `
                <span class="badge badge-danger">Tidak Ada Aksi</span>`;
            }


            return {
                id: data?.id ?? '-',
                no_nota: data?.no_nota ?? '-',
                tgl_retur: data?.tgl_retur ?? '-',
                nama_member: data?.nama_member ?? '-',
                status,
                action_buttons,
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
                        <td class="${classCol}">${element.no_nota}</td>
                        <td class="${classCol}">${element.nama_member}</td>
                        <td class="${classCol}">${element.tgl_retur}</td>
                        <td class="${classCol}">${element.status}</td>
                        <td class="${classCol}">${element.action_buttons}</td>
                    </tr>`;
            });

            $('#listDataTable').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();
        }

        async function deleteData() {
            $(document).on("click", ".hapus-data", async function() {
                let id = $(this).attr("data-id");
                let name = $(this).attr("data-name");

                swal({
                    title: `Hapus ${title} No Resi: ${name}`,
                    text: "Apakah anda yakin?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Tidak, Batal!",
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    confirmButtonClass: "btn btn-danger",
                    cancelButtonClass: "btn btn-secondary",
                }).then(async (result) => {
                    let postDataRest = await renderAPI(
                        'DELETE',
                        '{{ route('delete.tempItem') }}', {
                            id: id
                        }
                    ).then(function(response) {
                        return response;
                    }).catch(function(error) {
                        let resp = error.response;
                        return resp;
                    });

                    if (postDataRest.status == 200) {
                        setTimeout(function() {
                            getListData(defaultLimitPage, currentPage,
                                defaultAscending,
                                defaultSearch, customFilter);
                        }, 500);
                        notificationAlert('success', 'Pemberitahuan', postDataRest.data
                            .message);
                    }
                }).catch(swal.noop);
            })
        }

        async function editData() {
            $(document).on("click", ".edit-data", async function() {
                let id = $(this).attr("data-id");
                let nota = $(this).attr("data-nota");
                let tanggal = $(this).attr("data-tanggal");
                let nama_member = $(this).attr("data-nama-member");
                let id_member = $(this).attr("data-id-member");
                globalIdMember = id_member;

                dataTemp.id_retur = id;
                dataTemp.no_nota = nota;

                $("#modal-title").html(`Form Edit Retur No. Nota: ${nota}`);
                $("#modal-form").modal("show");

                $("form").find("input, select, textarea").val("").prop("checked", false).trigger("change");
                $("#form").data("action-url", '{{ route('reture.storeNota') }}');

                $("#i_no_nota").html(nota);
                $("#i_tgl_retur").html(tanggal);
                $("#i_member").html(nama_member);
                $("#tambah-tab").removeClass("active").addClass("d-none");
                $("#tambah").removeClass("show active");
                $("#detail-tab").removeClass("disabled").addClass("active").css({
                    "pointer-events": "auto",
                    "opacity": "1",
                });
                $("#detail").addClass("show active");
                $("#submit-reture").removeClass("d-none");
                try {
                    const response = await renderAPI('GET', '{{ route('get.temporary.items') }}', {
                        id_retur: id
                    });
                    if (response && response.status === 200) {
                        const dataItems = response.data.data;

                        if (Array.isArray(dataItems) && dataItems.length > 0) {
                            $("#listData").empty();
                            dataItems.forEach(item => addRowToTable(item));
                        } else {
                            handleEmptyState();
                        }
                    } else {
                        notificationAlert('info', 'Pemberitahuan', 'Tidak ada data sementara ditemukan.');
                        handleEmptyState();
                    }
                } catch (error) {
                    const errorMessage = error?.response?.data?.message ||
                        'Terjadi kesalahan saat memuat data sementara.';
                    handleEmptyState();
                }
                updateTableHeaders('edit');
                submitMultiForm('temporary', '{{ route('reture.permStore') }}');
            });
        }

        async function detailData() {
            $(document).on("click", ".detail-data", async function() {
                let id = $(this).attr("data-id");
                let nota = $(this).attr("data-nota");
                let tanggal = $(this).attr("data-tanggal");
                let status = $(this).attr("data-status");
                let nama_member = $(this).attr("data-nama-member");
                let id_member = $(this).attr("data-id-member");

                dataTemp.id_retur = id;
                dataTemp.no_nota = nota;

                $("#modal-title").html(`Form Detail Retur No. Nota: ${nota}`);
                $("#modal-form").modal("show");

                $("form").find("input, select, textarea").val("").prop("checked", false).trigger("change");

                $("#i_no_nota").html(nota);
                $("#i_tgl_retur").html(tanggal);
                $("#i_member").html(nama_member);
                $("#tambah-tab").removeClass("active").addClass("d-none");
                $("#tambah").removeClass("show active");
                $("#detail-tab").removeClass("disabled").addClass("active").css({
                    "pointer-events": "auto",
                    "opacity": "1",
                });
                $("#detail").addClass("show active");
                if (status == 'done') {
                    $("#submit-reture").addClass("d-none");
                } else {
                    $("#submit-reture").removeClass("d-none");
                }
                try {
                    const response = await renderAPI('GET', '{{ route('get.retureItems') }}', {
                        id_retur: id
                    });
                    if (response && response.status === 200) {
                        const dataItems = response.data.data;

                        if (Array.isArray(dataItems) && dataItems.length > 0) {
                            $("#listData").empty();
                            dataItems.forEach(item => rowTableDetailData(item));
                        } else {
                            handleEmptyState();
                        }
                    } else {
                        notificationAlert('info', 'Pemberitahuan', 'Tidak ada data sementara ditemukan.');
                        handleEmptyState();
                    }
                } catch (error) {
                    const errorMessage = error?.response?.data?.message ||
                        'Terjadi kesalahan saat memuat data sementara.';
                    notificationAlert('error', 'Kesalahan', errorMessage);
                    handleEmptyState();
                }
                updateTableHeaders('detail', status);
                submitMultiForm('origin', '{{ route('create.updateNotaReture') }}');
            });
        }

        async function addData() {
            $(document).on("click", ".add-data", async function() {
                await setDatePicker();
                $("#modal-title").html(`Form Tambah Retur`);
                $("#modal-form").modal("show");

                $("form").find("input:not(#tgl_retur), select, textarea")
                    .val("")
                    .prop("checked", false)
                    .trigger("change");
                $("#form").data("action-url", '{{ route('reture.storeNota') }}');

                $("#tambah-tab").removeClass("d-none").addClass("active").attr("aria-selected", "true");
                $("#tambah").addClass("show active");

                $("#detail-tab").addClass("disabled").removeClass("active").attr("aria-selected", "false")
                    .css({
                        "pointer-events": "none",
                        "opacity": "0.6"
                    });
                $("#detail").removeClass("show active");
                $("#submit-reture").removeClass("d-none");
                updateTableHeaders('edit');
                submitMultiForm('temporary', '{{ route('reture.permStore') }}');
            });
        }

        function getExistingTransactionItemPairs() {
            const rows = document.querySelectorAll('#listData tr');
            return Array.from(rows).map(row => {
                const idBarang = row.querySelector('input[name="id_barang[]"]')?.value;
                const idTransaksi = row.querySelector('input[name="id_transaksi[]"]')?.value;
                const qrCode = row.querySelector('input[name="qrcode[]"]')?.value;
                return {
                    idBarang,
                    idTransaksi,
                    qrCode
                };
            });
        }

        function addRowToTable(data) {
            const tbody = document.getElementById('listData');
            const loadingRow = document.querySelector('#listData .loading-row');
            if (loadingRow) {
                tbody.removeChild(loadingRow);
            }

            rowCount++;
            const tr = document.createElement('tr');
            const rowId = `row-${rowCount}`;
            tr.id = rowId;

            tr.innerHTML = `
                <td class="text-wrap align-top text-center">${rowCount}</td>
                <td class="text-wrap align-top text-center">
                    <button type="button" class="btn btn-danger btn-sm"
                        onclick="removeRow({ rowId: '${rowId}', id_transaksi: '${data.id_transaksi}', id_barang: '${data.id_barang}' })">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
                <td class="text-wrap align-top">
                    <input type="number" name="qty[]" value="1" min="1" max="${data.qty || 0}" class="form-control" required>
                    <small class="text-danger"><b>Maksimal Qty: ${data.qty || 0}</b></small>
                </td>
                <td class="text-wrap align-top"><input type="text" name="id_transaksi[]" value="${data.id_transaksi || ''}" class="form-control" readonly required></td>
                <td class="text-wrap align-top"><input type="text" name="nama_toko[]" value="${data.nama_toko || ''}" class="form-control" readonly required></td>
                <td class="text-wrap align-top"><input type="text" name="no_nota[]" value="${data.no_nota || ''}" class="form-control" readonly required></td>
                <td class="text-wrap align-top"><input type="text" name="nama_member[]" value="${data.nama_member || 'Guest'}" class="form-control" readonly required></td>
                <td class="text-wrap align-top"><input type="text" name="harga[]" value="${data.harga || 0}" class="form-control" readonly required></td>
                <td class="text-wrap align-top">
                    <input type="text" name="nama_barang[]" value="${data.nama_barang || ''}" class="form-control" readonly required>
                    <input type="hidden" name="id_barang[]" value="${data.id_barang || ''}" class="form-control" readonly required>
                    <input type="hidden" name="qrcode[]" value="${data.qrcode || ''}" class="form-control" readonly required>
                </td>
                <td class="text-wrap align-top text-center">
                    <button class="btn btn-sm btn-outline-secondary move-icon" style="cursor: grab;"><i class="fa fa-up-down mx-1"></i></button>
                </td>
            `;

            tbody.appendChild(tr);
        }

        function rowTableDetailData(data) {
            const tbody = document.getElementById('listData');
            const loadingRow = document.querySelector('#listData .loading-row');
            let status = '';
            let info = '';

            if (loadingRow) {
                tbody.removeChild(loadingRow);
            }

            rowCount++;
            const tr = document.createElement('tr');
            const rowId = `row-${rowCount}`;
            tr.id = rowId;

            if (data.status === 'success') {
                if (data.metode && data.metode == 'Cash') {
                    info = 'badge-success';
                } else if (data.metode && data.metode == 'Barang') {
                    info = 'badge-info';
                } else {
                    info = 'badge-danger';
                }
                status =
                    `<span class="badge badge-secondary"><i class="fa fa-circle-check mr-1 text-success"></i>Sukses dengan Metode <b class="badge ${info}">${data.metode || 'Tidak Valid'}</b></span>`;
            } else {
                status = `<select name="metode[]" class="form form-select select2 select-member" onchange="handleMetodeChange(event, '${rowId}', '${data.id_barang}', '${data.qty}', '${data.id_transaksi}', '${data.qrcode}')">
                    <option value="Cash" selected>Cash</option>
                    <option value="Barang">Barang</option>
                </select>`;
            }

            tr.innerHTML = `
                <td class="text-wrap align-top text-center">${rowCount}</td>
                <td class="text-wrap align-top">
                    ${status}
                    <div class="barang-container"></div>
                </td>
                <td class="text-wrap align-top">
                    <span class="qty-awal">${data.qty || 0}</span>
                </td>
                <td class="text-wrap align-top"><span>${data.id_transaksi || ''}</span></td>
                <td class="text-wrap align-top"><span>${data.nama_toko || ''}</span></td>
                <td class="text-wrap align-top"><span>${data.no_nota || ''}</span></td>
                <td class="text-wrap align-top"><span>${data.nama_member || 'Guest'}</span></td>
                <td class="text-wrap align-top"><span>${data.harga || 0}</span></td>
                <td class="text-wrap align-top">
                    <span>${data.nama_barang || ''}</span>
                    <input type="hidden" name="id_barang[]" value="${data.id_barang || ''}" class="form-control" readonly required>
                    <input type="hidden" name="qrcode[]" value="${data.qrcode || ''}" class="form-control" readonly required>
                </td>
            `;

            tbody.appendChild(tr);
        }

        async function handleMetodeChange(event, rowId, data_id_barang, data_qty, data_id_transaksi, data_qrcode_barang) {
            const selectedValue = event.target.value;
            const row = document.getElementById(rowId);
            const barangContainer = row.querySelector('.barang-container');

            barangContainer.innerHTML = '';

            if (selectedValue === 'Barang') {
                const barangInputHtml = `
                <small class="font-weight-bold text-danger">Silahkan masukkan QRCode Barang</small>
                <div class="d-flex align-items-center">
                    <input id="qrCode-${rowId}" type="text" name="qrcode_toko[]" class="form-control w-100 mr-1 qrcode-toko-input" placeholder="Masukkan QRCode" required>
                    <button id="qrCode-search-${rowId}" type="button" class="btn btn-sm btn-primary" data-container="body" data-toggle="tooltip" data-placement="top"
                        title="Submit Pencarian QRCode Barang">
                        <i class="fa fa-magnifying-glass"></i>Cari
                    </button>
                </div>`;

                barangContainer.innerHTML = barangInputHtml;

                const searchButton = document.getElementById(`qrCode-search-${rowId}`);
                searchButton.addEventListener('click', async () => {
                    const qrCodeInput = document.getElementById(`qrCode-${rowId}`);
                    const qrCode = qrCodeInput.value;

                    if (qrCode.trim()) {
                        const id_barang = data_id_barang || '';
                        const id_transaksi = data_id_transaksi || '';
                        const qrcode_barang = data_qrcode_barang || '';
                        const customFilter3 = {
                            qrcode: qrCode,
                            qrcode_barang: qrcode_barang,
                            id_barang: id_barang,
                            id_transaksi: id_transaksi,
                            rowId: rowId
                        };

                        const response = await getDataQrCode(customFilter3);

                        if (response && response?.status === 200) {
                            qrCodeResponses[rowId] = response.data.data;
                            $('.qty-input').attr('max', data_qty || 0);
                        } else {
                            notificationAlert('error', 'Error', response.message);
                        }
                    } else {
                        notificationAlert('error', 'Error',
                            'Silahkan masukkan QRCode Barang terlebih dahulu');
                    }
                });
            } else if (selectedValue === 'Cash') {}
        }

        async function getDataQrCode(customFilter3 = {}) {
            let filterParams = {};

            if (customFilter3['qrcode']) {
                filterParams.qrcode = customFilter3['qrcode'];
            }

            if (customFilter3['qrcode_barang']) {
                filterParams.qrcode_barang = customFilter3['qrcode_barang'];
            }

            if (customFilter3['id_barang']) {
                filterParams.id_barang = customFilter3['id_barang'];
            }

            if (customFilter3['id_transaksi']) {
                filterParams.id_transaksi = customFilter3['id_transaksi'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.getretureqrcode') }}', {
                    id_toko: '{{ auth()->user()->id_toko }}',
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
                    const rowId = customFilter3.rowId;
                    const row = document.getElementById(rowId);

                    let barangInput = row.querySelector('.barang-input');
                    if (!barangInput) {
                        barangInput = document.createElement('div');
                        barangInput.classList.add('barang-input', 'mt-2');
                        row.querySelector('td:nth-child(2)').appendChild(
                            barangInput);
                    }

                    let stockElement = barangInput.querySelector('.stock-info');
                    if (!stockElement) {
                        stockElement = document.createElement('small');
                        stockElement.classList.add('font-weight-bold', 'text-primary', 'stock-info');
                        barangInput.appendChild(stockElement);
                    }
                    stockElement.textContent = `Stock Barang Tersisa: ${data.stock_toko_qty}`;

                    let qtyLabel = barangInput.querySelector('.qty-label');
                    if (!qtyLabel) {
                        qtyLabel = document.createElement('label');
                        qtyLabel.classList.add('qty-label', 'mt-2', 'd-block');
                        qtyLabel.textContent = 'Masukkan Jumlah Barang (Qty):';
                        barangInput.appendChild(qtyLabel);
                    }

                    let qtyElement = barangInput.querySelector('.qty-input');
                    if (!qtyElement) {
                        qtyElement = document.createElement('input');
                        qtyElement.type = 'number';
                        qtyElement.min = '1';
                        qtyElement.value = '1';
                        qtyElement.name = `qty_barang[]`;
                        qtyElement.classList.add('form-control', 'qty-input');
                        qtyElement.placeholder = 'Masukkan Qty';

                        qtyElement.addEventListener('input', function() {
                            let min = parseInt(this.min, 10);
                            let max = parseInt(this.max, 10);
                            let value = this.value.trim();

                            if (value === "") {
                                this.value = min;
                            } else {
                                value = parseInt(value, 10);
                                if (value < min) {
                                    this.value = min;
                                } else if (value > max) {
                                    this.value = max;
                                }
                            }
                        });

                        barangInput.appendChild(qtyElement);
                    }

                    return getDataRest;
                }
            } else {
                let errorMessage = getDataRest?.data?.message || 'Data gagal dimuat';
                notificationAlert('error', 'Kesalahan', errorMessage);
            }
        }

        function updateTableHeaders(action, status = null) {
            const table = document.querySelector('.table-custom');
            const thead = table.querySelector('thead tr');
            const ths = thead.querySelectorAll('th');
            const scanData = document.getElementById('scan-data');

            if (action === 'detail') {
                $(".select-member").select2({
                    placeholder: "Pilih Metode",
                    allowClear: true,
                    width: "100%",
                    dropdownParent: $("#modal-form"),
                });

                if (scanData) scanData.style.display = 'none';

                if (ths[1]) {
                    if (status == 'done') {
                        ths[1].textContent = 'Status';
                        ths[1].style.width = '20%';
                    } else {
                        ths[1].textContent = 'Metode';
                        ths[1].style.width = '20%';
                    }
                }

                if (ths[ths.length - 1] && ths[ths.length - 1].textContent.trim() === '#') {
                    ths[ths.length - 1].style.display = 'none';
                }

                for (let i = 0; i < ths.length; i++) {
                    if (i !== 1) {
                        ths[i].style.width = '8%';
                    }
                }
            } else {
                if (scanData) scanData.style.display = '';

                if (ths[1]) {
                    ths[1].textContent = '#';
                    ths[1].style.width = '';
                }

                if (ths[ths.length - 1] && ths[ths.length - 1].textContent.trim() === '#') {
                    ths[ths.length - 1].style.display = '';
                }

                for (let i = 0; i < ths.length; i++) {
                    ths[i].style.width = '';
                }
            }
        }

        async function getData(customFilter2 = {}) {
            const tbody = document.getElementById('listData');

            const loadingRow = document.querySelector('#listData .loading-row');
            if (!loadingRow) {
                handleEmptyState();
                tbody.innerHTML += loadingData();
            }

            let filterParams = {};
            if (customFilter2['qrcode']) {
                filterParams.qrcode = customFilter2['qrcode'];
            }
            if (customFilter2['id_member']) {
                filterParams.id_member = customFilter2['id_member'];
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
                    $('#info-input').html('Masukkan QR Code lain, jika ingin menambah reture');

                    const existingPairs = getExistingTransactionItemPairs();

                    const isDuplicate = existingPairs.some(pair =>
                        pair.idBarang == data.id_barang && pair.idTransaksi == data.id_transaksi && pair.qrCode ==
                        data.qrcode
                    );

                    if (isDuplicate) {
                        const tbody = document.getElementById('listData');
                        const loadingRow = document.querySelector('#listData .loading-row');
                        if (loadingRow) {
                            tbody.removeChild(loadingRow);
                        }
                        notificationAlert('info', 'Pemberitahuan',
                            'Data dari QRCode ini sudah ada.');
                        return;
                    } else {
                        await addRowToTable(data);
                        await postDataToTempStore(data);
                        resetQRCodeInput();
                    }

                } else {
                    handleEmptyState();
                }
            } else {
                let errorMessage = getDataRest?.data?.message || 'Data gagal dimuat';
                notificationAlert('error', 'Kesalahan', errorMessage);
                handleEmptyState();
            }
        }

        async function postDataToTempStore(data) {
            try {
                let response = await renderAPI(
                    'POST',
                    '{{ route('reture.tempStore') }}', {
                        ...dataTemp,
                        ...data
                    }
                );

                if (response.status >= 200 && response.status < 300) {} else {
                    notificationAlert('info', 'Pemberitahuan', 'Gagal menyimpan data ke TempStore');
                }
            } catch (error) {
                notificationAlert('error', 'Kesalahan', 'Terjadi kesalahan saat memposting data');
            }
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

        function removeRow(rowData) {
            const {
                rowId,
                id_transaksi,
                id_barang
            } = rowData;

            const row = document.getElementById(rowId);
            if (row) {
                row.remove()
                updateRowNumbers();
                handleEmptyState();
            }

            deleteRowTable({
                id_transaksi,
                id_barang
            });
        }

        async function deleteRowTable(data) {
            try {
                const postDataRest = await renderAPI(
                    'DELETE',
                    '{{ route('delete.tempData') }}',
                    data
                );
                if (postDataRest && postDataRest.status === 200) {}
            } catch (error) {
                const resp = error.response;
                const errorMessage = resp?.data?.message || 'Terjadi kesalahan saat menghapus data.';
                notificationAlert('error', 'Kesalahan', errorMessage);
            }
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
                <td colspan="10" class="text-center font-weight-bold">
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

            if (!globalIdMember) {
                notificationAlert('info', 'Pemberitahuan',
                    'Data Member tidak tersedia. Pastikan Anda telah memilih data yang ingin diedit.');
                return;
            }

            const customFilter2 = {
                qrcode: qrcodeValue,
                id_member: globalIdMember
            };

            await getData(customFilter2);
        }

        async function setDatePicker() {
            flatpickr("#tgl_retur", {
                dateFormat: "Y-m-d",
                defaultDate: new Date(),
                minDate: "today",
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

        async function submitForm() {
            $(document).on("submit", "#form", async function(e) {
                e.preventDefault();
                const saveButton = document.getElementById('save-btn');
                saveButton.disabled = true;

                const originalContent = saveButton.innerHTML;
                saveButton.innerHTML =
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan`;

                loadingPage(true);

                const actionUrl = $("#form").data("action-url");
                const formData = {
                    id_member: $('#f_member').select2('data').length > 0 ? $('#f_member').select2(
                        'data')[0].id : null,
                    tgl_retur: $('#tgl_retur').val(),
                };

                const method = 'POST';
                try {
                    const postData = await renderAPI(method, actionUrl, formData);

                    loadingPage(false);

                    if (postData.status >= 200 && postData.status < 300) {
                        const rest_data = postData.data.data;

                        $('#nav-tab a[href="#detail"]').tab('show');
                        $('#i_no_nota').text(rest_data.no_nota);
                        $('#i_tgl_retur').text(rest_data.tgl_retur);
                        $('#i_member').text(rest_data.nama_member);

                        $('#tambah-tab').removeAttr('style');
                        $('#detail-tab').removeAttr('style');

                        dataTemp = rest_data;
                        globalIdMember = rest_data.id_member;

                        setTimeout(async function() {
                            await getListData(defaultLimitPage, currentPage, defaultAscending,
                                defaultSearch, customFilter);
                        }, 500);
                    } else {
                        notificationAlert('info', 'Pemberitahuan', postData.message || 'Terjadi kesalahan');
                    }
                } catch (error) {
                    loadingPage(false);
                    const resp = error.response || {};
                    notificationAlert('error', 'Kesalahan', resp.data?.message ||
                        'Terjadi kesalahan saat menyimpan data.');
                } finally {
                    saveButton.disabled = false;
                    saveButton.innerHTML = originalContent;
                }
            });
        }

        async function submitMultiForm(action, actionUrl) {
            $("#retureForm").off("submit").on("submit", async function(e) {
                e.preventDefault();
                loadingPage(true);

                if (action === 'temporary') {
                    if (!dataTemp.id_retur || !dataTemp.no_nota) {
                        loadingPage(false);
                        notificationAlert('error', 'Pemberitahuan', 'ID Retur dan No Nota wajib diisi.');
                        return;
                    }

                    let url = actionUrl;

                    let formData = {
                        id_retur: dataTemp.id_retur,
                        no_nota: dataTemp.no_nota,
                        id_member: dataTemp.id_member,
                        id_transaksi: $("input[name='id_transaksi[]']").map(function() {
                            return $(this).val();
                        }).get(),
                        id_barang: $("input[name='id_barang[]']").map(function() {
                            return $(this).val();
                        }).get(),
                        qty: $("input[name='qty[]']").map(function() {
                            return $(this).val();
                        }).get(),
                        harga: $("input[name='harga[]']").map(function() {
                            return $(this).val();
                        }).get(),
                        qrcode: $("input[name='qrcode[]']").map(function() {
                            return $(this).val();
                        }).get(),
                    };

                    let method = 'POST';
                    try {
                        let postData = await renderAPI(method, url, formData);

                        loadingPage(false);
                        if (postData.status >= 200 && postData.status < 300) {
                            notificationAlert('success', 'Pemberitahuan', postData.data.message ||
                                'Berhasil');
                            setTimeout(async function() {
                                await getListData(defaultLimitPage, currentPage,
                                    defaultAscending,
                                    defaultSearch, customFilter);
                            }, 500);
                            $("#modal-form").modal("hide");
                        } else {
                            notificationAlert('error', 'Pemberitahuan', postData.message ||
                                'Terjadi kesalahan');
                        }
                    } catch (error) {
                        loadingPage(false);
                        let resp = error.response || {};
                        notificationAlert('error', 'Pemberitahuan', resp.message || 'Terjadi kesalahan');
                    }
                } else if (action === 'origin') {
                    let url = actionUrl;

                    let qrCodeDataArray = {
                        nama_barang: [],
                        stock_toko_qty: [],
                        hpp_baru: [],
                        stock_qty: [],
                        qrcode_toko: [],
                        qrcode_barang: [],
                    };

                    let formData = {
                        id_retur: dataTemp.id_retur,
                        metode: $("select[name='metode[]']").map(function() {
                            return $(this).val();
                        }).get(),
                        qty: $("#listData tr").map(function() {
                            return $(this).find(".qty-awal").text().trim();
                        }).get(),
                        id_transaksi: $("#listData tr").map(function() {
                            return $(this).find('td:nth-child(4) span').text().trim();
                        }).get(),
                        harga: $("#listData tr").map(function() {
                            return $(this).find('td:nth-child(8) span').text().trim();
                        }).get(),
                        id_barang: $("input[name='id_barang[]']").map(function() {
                            return $(this).val();
                        }).get(),
                    };

                    formData.metode.forEach((metode, index) => {
                        if (metode === 'Barang') {
                            const rowId = `row-${index + 1}`;
                            const qrCodeData = qrCodeResponses[rowId];

                            const rowElement = document.getElementById(rowId);
                            const qtyInput = rowElement ? rowElement.querySelector('.qty-input') :
                                null;
                            const qrCodeTokoInput = rowElement ? rowElement.querySelector(
                                    '.qrcode-toko-input').value :
                                null;

                            if (qrCodeData) {
                                qrCodeDataArray.nama_barang.push(qrCodeData.nama_barang || '');
                                qrCodeDataArray.stock_toko_qty.push(qrCodeData.stock_toko_qty ||
                                    0);
                                qrCodeDataArray.hpp_baru.push(qrCodeData.hpp_baru || 0);
                                qrCodeDataArray.stock_qty.push(qtyInput ? parseInt(qtyInput
                                    .value) || 0 : 0);
                                qrCodeDataArray.qrcode_toko.push(qrCodeTokoInput);
                                qrCodeDataArray.qrcode_barang.push('');
                            } else {
                                qrCodeDataArray.nama_barang.push('');
                                qrCodeDataArray.stock_toko_qty.push(0);
                                qrCodeDataArray.hpp_baru.push(0);
                                qrCodeDataArray.stock_qty.push(qtyInput ? parseInt(qtyInput
                                    .value) || 0 : 0);
                                qrCodeDataArray.qrcode_toko.push('');
                                qrCodeDataArray.qrcode_barang.push($("input[name='qrcode[]']")
                                    .val());
                            }
                        } else {
                            qrCodeDataArray.nama_barang.push('');
                            qrCodeDataArray.stock_toko_qty.push(0);
                            qrCodeDataArray.hpp_baru.push(0);
                            qrCodeDataArray.stock_qty.push(0);
                            qrCodeDataArray.qrcode_toko.push('');
                            qrCodeDataArray.qrcode_barang.push($("input[name='qrcode[]']").val());
                        }
                    });

                    formData = {
                        ...formData,
                        ...qrCodeDataArray
                    };

                    try {
                        let postData = await renderAPI('POST', url, formData);

                        loadingPage(false);
                        if (postData.status >= 200 && postData.status < 300) {
                            notificationAlert('success', 'Pemberitahuan', postData.data.message ||
                                'Berhasil');
                            setTimeout(async function() {
                                await getListData(defaultLimitPage, currentPage,
                                    defaultAscending, defaultSearch, customFilter);
                            }, 500);
                            $("#modal-form").modal("hide");
                        } else {
                            notificationAlert('error', 'Pemberitahuan', postData.message ||
                                'Terjadi kesalahan');
                        }
                    } catch (error) {
                        loadingPage(false);
                        let resp = error.response || {};
                        notificationAlert('error', 'Pemberitahuan', resp.message || 'Terjadi kesalahan');
                    }
                }
            });
        }

        function resetModal() {
            const modal = document.getElementById('modal-form');

            modal.addEventListener('hidden.bs.modal', function() {
                rowCount = 0;
                const forms = modal.querySelectorAll('form');
                forms.forEach((form) => {
                    form.reset();
                });

                const badges = modal.querySelectorAll('.badge');
                badges.forEach((badge) => {
                    badge.textContent = '';
                });

                const listData = document.getElementById('listData');
                if (listData) {
                    listData.innerHTML = `
                    <tr class="empty-row">
                        <td colspan="10" class="text-center font-weight-bold">
                            <span class="badge badge-info" style="font-size: 14px;">
                                <i class="fa fa-circle-info mr-2"></i>Silahkan masukkan QR-Code terlebih dahulu.
                            </span>
                        </td>
                    </tr>
                `;
                }

                const inputs = modal.querySelectorAll('input, textarea');
                inputs.forEach((input) => {
                    input.value = '';
                    if (input.placeholder) {
                        input.placeholder = input.getAttribute('placeholder');
                    }
                });

                const infoInput = document.getElementById('info-input');
                if (infoInput) {
                    infoInput.textContent = '';
                }

                const dynamicElements = modal.querySelectorAll('.dynamic-element');
                dynamicElements.forEach((element) => {
                    element.remove();
                });

                const tambahTab = modal.querySelector('#tambah-tab');
                const detailTab = modal.querySelector('#detail-tab');
                const tambahContent = modal.querySelector('#tambah');
                const detailContent = modal.querySelector('#detail');

                if (tambahTab && detailTab && tambahContent && detailContent) {
                    tambahTab.classList.add('active');
                    tambahTab.setAttribute('aria-selected', 'true');
                    tambahContent.classList.add('show', 'active');

                    detailTab.classList.remove('active');
                    detailTab.setAttribute('aria-selected', 'false');
                    detailContent.classList.remove('show', 'active');
                }

                const form = document.getElementById('form');

                detailTab.classList.add('disabled');
                detailTab.style.pointerEvents = 'none';
                detailTab.style.opacity = '0.6';

                saveButton.addEventListener('click', function(event) {
                    event.preventDefault();

                    if (form.checkValidity()) {
                        detailTab.classList.remove('disabled');
                        detailTab.style.pointerEvents = 'auto';
                        detailTab.style.opacity = '1';

                        tambahTab.classList.remove('active');
                        detailTab.classList.add('active');

                        const tambahPane = document.getElementById('tambah');
                        const detailPane = document.getElementById('detail');
                        tambahPane.classList.remove('show', 'active');
                        detailPane.classList.add('show', 'active');
                    } else {
                        form.reportValidity();
                    }
                });
            });
        }

        async function initPageLoad() {
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await selectData(selectOptions);
            await addData();
            await editData();
            await detailData();
            await searchData();
            await setSortable();
            await resetModal();
            await submitForm();
            await deleteData();
        }
    </script>
@endsection
