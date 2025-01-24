@extends('layouts.main')

@section('title')
    Reture Suplier
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
                                                <th class="text-wrap align-top">Tanggal Reture</th>
                                                <th class="text-wrap align-top">Status</th>
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
                                                    Tambah Reture
                                                </a>
                                                <a class="nav-item nav-link disabled" id="detail-tab" data-toggle="tab"
                                                    href="#detail" role="tab" aria-controls="detail"
                                                    aria-selected="false" style="pointer-events: none; opacity: 0.6;">
                                                    Detail Reture
                                                </a>
                                            </div>
                                        </nav>
                                        <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="tambah" role="tabpanel"
                                                aria-labelledby="tambah-tab">
                                                <br>
                                                <form id="form">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <label for="f_suplier" class=" form-control-label">Nama
                                                                Suplier <span class="text-danger">*</span></label>
                                                            <select class="form-select select2" name="suplier"
                                                                id="f_suplier">
                                                            </select>
                                                        </div>
                                                        <div class="col-4">
                                                            <label for="tgl_retur" class="form-control-label">Tanggal
                                                                Reture</label>
                                                            <input class="form-control tgl_retur" type="text"
                                                                name="tgl_retur" id="tgl_retur"
                                                                placeholder="Pilih tanggal" readonly>
                                                        </div>
                                                        <div class="col-4">
                                                            <label for="no_nota" class=" form-control-label">Nomor
                                                                Nota <span class="text-danger">*</span></label>
                                                            <input type="number" id="no_nota" name="no_nota"
                                                                placeholder="Contoh : 001" class="form-control">
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
                                                        <span id="i_suplier" class="badge badge-secondary"></span>
                                                    </li>
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
@endsection

@section('js')
    <script>
        let customFilter2 = {};
        let customFilter3 = {};
        let rowCount = 0;
        let dataTemp = {};
        let globalIdMember = null;
        let barcodeResponses = {};

        let title = 'Reture';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};

        let selectOptions = [{
            id: '#f_suplier',
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

            if (data.action === 'edit_temp') {
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
            } else {
                edit_button = `
                    <span class="badge badge-danger">Tidak Ada Aksi</span>`;
            }

            let delete_button = `
            <a class="p-1 btn hapus-data action_button"
                data-container="body" data-toggle="tooltip" data-placement="top"
                title="Hapus ${title}: ${data.nama_barang}"
                data-id='${data.id}'
                data-name='${data.nama_barang}'>
                <span class="text-dark">Hapus</span>
                <div class="icon text-danger">
                    <i class="fa fa-trash"></i>
                </div>
            </a>`;

            let action_buttons = '';
            if (edit_button || delete_button) {
                action_buttons = `
                <div class="d-flex justify-content-center">
                    ${edit_button ? `<div class="hovering p-1">${edit_button}</div>` : ''}
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
                status = `<span class="badge badge-info"><i class="fa fa-circle-half-stroke mr-1"></i>On going</span>`;
            }

            return {
                id: data?.id ?? '-',
                no_nota: data?.no_nota ?? '-',
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
                        <td class="${classCol}">${element.tgl_retur}</td>
                        <td class="${classCol}">${element.status}</td>
                        <td class="${classCol}">${element.action_buttons}</td>
                    </tr>`;
            });

            $('#listDataTable').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            renderPagination();
        }

        async function addData() {
            $(document).on("click", ".add-data", function() {
                $("#modal-title").html(`Form Tambah Reture Suplier`);
                $("#modal-form").modal("show");

                $("form").find("input, select, textarea").val("").prop("checked", false).trigger("change");
                $("#form").data("action-url", '{{ route('create.NoteReture') }}');

                $("#tambah-tab").removeClass("d-none").addClass("active").attr("aria-selected", "true");
                $("#tambah").addClass("show active");

                $("#detail-tab").addClass("disabled").removeClass("active").attr("aria-selected", "false").css({
                    "pointer-events": "none",
                    "opacity": "0.6"
                });
                $("#detail").removeClass("show active");
                $("#submit-reture").removeClass("d-none");
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

                // saveButton.addEventListener('click', function(event) {
                //     event.preventDefault();

                //     if (form.checkValidity()) {
                //         detailTab.classList.remove('disabled');
                //         detailTab.style.pointerEvents = 'auto';
                //         detailTab.style.opacity = '1';

                //         tambahTab.classList.remove('active');
                //         detailTab.classList.add('active');

                //         const tambahPane = document.getElementById('tambah');
                //         const detailPane = document.getElementById('detail');
                //         tambahPane.classList.remove('show', 'active');
                //         detailPane.classList.add('show', 'active');
                //     } else {
                //         form.reportValidity();
                //     }
                // });
            });
        }

        async function initPageLoad() {
            await setDatePicker();
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await selectData(selectOptions);
            await resetModal();
            await addData();
        }
    </script>
@endsection
