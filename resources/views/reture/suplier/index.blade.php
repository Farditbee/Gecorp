@extends('layouts.main')

@section('title')
    Reture Supplier
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
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
                                <button class="btn btn-primary mb-2 mb-lg-0 text-white add-data">
                                    <i class="fa fa-plus-circle"></i> Tambah
                                </button>
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
                                                <th class="text-wrap align-top">Nama Supplier</th>
                                                <th class="text-wrap align-top">Tgl Retur</th>
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
                                                        <div class="col-12 col-xxl-6 col-xl-6 col-lg-6">
                                                            <label for="f_supplier" class=" form-control-label">Nama
                                                                Suplier <span class="text-danger">*</span></label>
                                                            <select class="form-select select2" name="suplier"
                                                                id="f_supplier">
                                                            </select>
                                                        </div>
                                                        <div class="col-12 col-xxl-6 col-xl-6 col-lg-6">
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
                                                        <h5><i class="fa fa-user-tie mr-2"></i>Nama Suplier</h5>
                                                        <span id="i_supplier" class="badge badge-secondary"></span>
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
                                                                    <form id="retureForm">
                                                                        <div class="table-responsive table-scroll-wrapper">
                                                                            <table
                                                                                class="table table-bordered table-custom">
                                                                                <thead>
                                                                                    <tr class="tb-head">
                                                                                        <th
                                                                                            class="text-wrap align-top text-center">
                                                                                            No</th>
                                                                                        <th class="text-wrap align-top">
                                                                                            Metode Supplier</th>
                                                                                        <th class="text-wrap align-top">
                                                                                            Metode Member</th>
                                                                                        <th class="text-wrap align-top">Qty
                                                                                        </th>
                                                                                        <th class="text-wrap align-top">No
                                                                                            Nota</th>
                                                                                        <th class="text-wrap align-top">
                                                                                            Nama Barang</th>
                                                                                        <th class="text-wrap align-top">
                                                                                            Harga Beli (Rp)</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody id="listData">
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
@endsection

@section('js')
    <script>
        let customFilter2 = {};
        let customFilter3 = {};
        let rowCount = 0;
        let dataTemp = {};
        let dataTempDetail = [];
        let globalIdSupplier = null;
        let barcodeResponses = {};

        let title = 'Retur';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};

        let selectOptions = [{
            id: '#f_supplier',
            isFilter: {
                id_toko: '{{ auth()->user()->id_toko }}',
            },
            isUrl: '{{ route('master.suplier') }}',
            placeholder: 'Pilih Suplier',
            isModal: '#modal-form'
        }];

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listDataTable').html(loadingData());

            let filterParams = {};

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.getreturesupplier') }}', {
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
            let elementData = encodeURIComponent(JSON.stringify(data));
            let detail_button = '';

            if (data.status === 'pending') {
                detail_button = `
                    <button class="p-1 btn detail-data action_button"
                        data-container="body" data-toggle="tooltip" data-placement="top" data="${elementData}"
                        title="Detail ${title} No. Nota: ${data.no_nota}">
                        <span class="text-dark">Detail</span>
                        <div class="icon text-info">
                            <i class="fa fa-book"></i>
                        </div>
                    </button>`;
            }

            let delete_button = `
            <a class="p-1 btn delete-data action_button"
                data-container="body" data-toggle="tooltip" data-placement="top"
                title="Hapus ${title} No.Nota: ${data.no_nota}" data="${elementData}">
                <span class="text-dark">Hapus</span>
                <div class="icon text-danger">
                    <i class="fa fa-trash"></i>
                </div>
            </a>`;

            let action_buttons = '';
            if (detail_button || delete_button) {
                action_buttons = `
                <div class="d-flex justify-content-start">
                    ${detail_button ? `<div class="hovering p-1">${detail_button}</div>` : ''}
                    ${delete_button ? `<div class="hovering p-1">${delete_button}</div>` : ''}
                </div>`;
            } else {
                action_buttons = `
                <span class="badge badge-danger">Tidak Ada Aksi</span>`;
            }

            let status = ''
            if (data.status == 'done') {
                status = `<span class="badge badge-success"><i class="fa fa-circle-check mr-1"></i>Sukses</span>`;
            } else if (data.status == 'pending') {
                status = `<span class="badge badge-info"><i class="fa fa-circle-half-stroke mr-1"></i>Pending</span>`;
            }

            return {
                id: data?.id ?? '-',
                no_nota: data?.no_nota ?? '-',
                nama_supplier: data?.nama_supplier ?? '-',
                tgl_retur: data?.tgl_retur ?? '-',
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
                        <td class="${classCol}">${element.nama_supplier}</td>
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

        async function addData() {
            $(document).on("click", ".add-data", function() {
                setDatePicker();
                $("#modal-title").html(`Form Tambah Retur Suplier`);
                $("#modal-form").modal("show");

                $("form").find("input:not(#tgl_retur), select, textarea")
                    .val("")
                    .prop("checked", false)
                    .trigger("change");
                $("#form").data("action-url", '{{ route('create.NoteReture') }}');

                $("#tambah-tab").removeClass("d-none disabled").addClass("active").attr("aria-selected", "true")
                    .css({
                        "pointer-events": "auto",
                        "opacity": "1"
                    });
                $("#tambah").addClass("show active");

                $("#detail-tab").addClass("disabled").removeClass("active").attr("aria-selected", "false").css({
                    "pointer-events": "none",
                    "opacity": "0.6"
                });
                $("#detail").removeClass("show active");
                $("#submit-reture").removeClass("d-none");
                submitMultiForm('{{ route('reture.suplier.store') }}');
            });
        }

        async function detailData() {
            $(document).on("click", ".detail-data", async function() {
                let rawData = $(this).attr("data");
                let data = JSON.parse(decodeURIComponent(rawData));

                dataTemp.id_retur = data.id;
                dataTemp.no_nota = data.no_nota;

                $("#modal-title").html(`Form Detail Retur Supplier No. Nota: ${data.no_nota}`);
                $("#modal-form").modal("show");

                $("form").find("input, select, textarea").val("").prop("checked", false).trigger("change");

                $("#i_no_nota").html(data.no_nota);
                $("#i_tgl_retur").html(data.tgl_retur);
                $("#i_supplier").html(data.nama_supplier);

                $("#tambah-tab").removeClass("active").addClass("d-none");
                $("#tambah").removeClass("show active");
                $("#detail-tab").removeClass("disabled").addClass("active").css({
                    "pointer-events": "auto",
                    "opacity": "1",
                });
                $("#detail").addClass("show active");

                if (data.status == 'done') {
                    $("#submit-reture").addClass("d-none");
                } else {
                    $("#submit-reture").removeClass("d-none");
                }

                try {
                    const response = await renderAPI('GET', '{{ route('master.detailReture') }}', {
                        id_supplier: data.id_supplier
                    });

                    if (response && response.status === 200) {
                        const dataItems = response.data.data;
                        dataTempDetail = dataItems;

                        if (Array.isArray(dataItems) && dataItems.length > 0) {
                            $("#listData").empty();
                            dataItems.forEach(item => addRowToTable(item));
                        } else {
                            handleEmptyState();
                        }
                    } else {
                        notificationAlert('info', 'Pemberitahuan', 'Tidak ada data sementara ditemukan.');
                    }
                } catch (error) {
                    const errorMessage = error?.response?.data?.message ||
                        'Terjadi kesalahan saat memuat data sementara.';
                    notificationAlert('error', 'Kesalahan', errorMessage);
                }

                submitMultiForm('{{ route('reture.suplier.store') }}');
            });
        }

        async function deleteData() {
            $(document).on("click", ".delete-data", async function() {
                let rawData = $(this).attr("data");
                let data = JSON.parse(decodeURIComponent(rawData));

                swal({
                    title: `Hapus ${title} No Nota: ${data.no_nota}`,
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
                        '{{ route('reture.suplier.delete') }}', {
                            id_retur: data.id,
                            id_supplier: data.id_supplier,
                            no_nota: data.no_nota,
                        }
                    ).then(function(response) {
                        return response;
                    }).catch(function(error) {
                        let resp = error.response;
                        return resp;
                    });

                    if (postDataRest.status == 200) {
                        setTimeout(function() {
                            getListData(defaultLimitPage, currentPage, defaultAscending,
                                defaultSearch, customFilter);
                        }, 500);
                        notificationAlert('success', 'Pemberitahuan', postDataRest.data
                            .message);
                    }
                }).catch(swal.noop);
            })
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
                    id_supplier: $('#f_supplier').select2('data').length > 0 ? $('#f_supplier').select2(
                        'data')[0].id : null,
                    tgl_retur: $('#tgl_retur').val(),
                };

                const method = 'POST';
                try {
                    const postData = await renderAPI(method, actionUrl, formData);

                    loadingPage(false);

                    if (postData.status >= 200 && postData.status < 300) {
                        const rest_data = postData.data.data;
                        const dataItems = postData.data.detail_retur;

                        $('#nav-tab a[href="#detail"]').tab('show');

                        $('#i_no_nota').text(rest_data.no_nota);
                        $('#i_tgl_retur').text(rest_data.tgl_retur);
                        $('#i_supplier').text(rest_data.nama_supplier);

                        $('#detail-tab').removeAttr('style').removeClass('disabled');

                        $('#tambah-tab').css({
                            "pointer-events": "none",
                            "opacity": "0.6"
                        }).addClass("disabled");

                        dataTemp = rest_data;
                        dataTempDetail = dataItems;
                        globalIdSupplier = rest_data.id_supplier;

                        if (Array.isArray(dataItems) && dataItems.length > 0) {
                            $("#listData").empty();
                            dataItems.forEach(item => addRowToTable(item));
                        }

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

        function addRowToTable(data) {
            const tbody = document.getElementById('listData');
            rowCount++;
            const tr = document.createElement('tr');
            const rowId = `row-${rowCount}`;
            tr.id = rowId;

            tr.innerHTML = `
                <td class="text-wrap align-top text-center">${rowCount}</td>
                <td class="text-wrap align-top">
                    <select name="metode_reture[]" class="form form-select select2 select-metode" placeholder="Pilih Metode">
                        <option value="" disabled selected>Pilih Metode</option>
                        <option value="Cash">Cash</option>
                        <option value="Barang">Barang</option>
                    </select>
                </td>
                <td class="text-wrap align-top">${data.metode || ''}</td>
                <td class="text-wrap align-top">${data.qty_acc || 0}</td>
                <td class="text-wrap align-top">${data.no_nota || ''}</td>
                <td class="text-wrap align-top">${data.nama_barang || 0}</td>
                <td class="text-wrap align-top">${data.hpp_jual || 0}</td>
            `;

            tbody.appendChild(tr);

            $(`#${rowId} .select-metode`).select2({
                placeholder: "Pilih Metode",
                allowClear: true,
                width: "100%",
                dropdownParent: $("#modal-form"),
            });
        }

        async function submitMultiForm(actionUrl) {
            $("#retureForm").off("submit").on("submit", async function(e) {
                e.preventDefault();
                loadingPage(true);

                if (!dataTemp.id_retur || !dataTemp.no_nota) {
                    loadingPage(false);
                    notificationAlert('error', 'Pemberitahuan', 'ID Retur dan No Nota wajib diisi.');
                    return;
                }

                let url = actionUrl;

                let metodeRetureValues = $("select[name='metode_reture[]']").map(function() {
                    return $(this).val();
                }).get().filter(value => value !== "");

                if (metodeRetureValues.length === 0) {
                    notificationAlert('error', 'Pemberitahuan', 'Metode Reture wajib dipilih.');
                    loadingPage(false);
                    return;
                }

                let formData = {
                    id_retur: dataTempDetail.map(item => item.id_retur),
                    no_nota: dataTemp.no_nota,
                    id_supplier: dataTemp.id_supplier,
                    id_transaksi: dataTempDetail.map(item => item.id_transaksi),
                    id_barang: dataTempDetail.map(item => item.id_barang),
                    qty_acc: dataTempDetail.map(item => item.qty_acc),
                    metode_reture: metodeRetureValues,
                    qrcode: dataTempDetail.map(item => item.qrcode),
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
            });
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
            });
        }

        async function initPageLoad() {
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await selectData(selectOptions);
            await resetModal();
            await addData();
            await detailData();
            await deleteData();
            await submitForm();
        }
    </script>
@endsection
