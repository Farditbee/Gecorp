@extends('layouts.main')

@section('title')
    Pengiriman Barang
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-xl-6 col-lg-6 mb-2">
                                    <div class="row">
                                        <div class="col-6 col-xxl-2 col-lg-4 col-xl-3">
                                            <a href="{{ route('distribusi.pengirimanbarang.create') }}"
                                                class="btn btn-primary mb-2 text-white w-100" data-container="body"
                                                data-toggle="tooltip" data-placement="top" title="Tambah Pengiriman Barang">
                                                <i class="fa fa-plus-circle"></i> Tambah
                                            </a>
                                        </div>
                                        <div class="col-6 col-xxl-2 col-lg-4 col-xl-3">
                                            <a href="{{ route('distribusi.pengirimanbarang.reture') }}"
                                                class="btn btn-warning mb-2 text-dark w-100" data-container="body"
                                                data-toggle="tooltip" data-placement="top" title="Reture Pengiriman Barang">
                                                <i class="fa fa-rotate-left"></i> Reture
                                            </a>
                                        </div>
                                        <div class="col-12 col-xxl-2 col-lg-4 col-xl-3">
                                            <button class="btn-dynamic btn btn-outline-primary mb-2 w-100" type="button"
                                                data-toggle="collapse" data-target="#filter-collapse" aria-expanded="false"
                                                aria-controls="filter-collapse"data-container="body" data-toggle="tooltip"
                                                data-placement="top" title="Filter Pengiriman Barang">
                                                <i class="fa fa-filter"></i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-xl-6 col-lg-6 mb-2">
                                    <div class="row justify-content-end">
                                        <div class="col-4 col-xl-2 col-lg-3">
                                            <select name="limitPage" id="limitPage" class="form-control mr-2 mb-2 mb-lg-0">
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                            </select>
                                        </div>
                                        <div class="col-8 col-xl-4 col-lg-4 justify-content-end">
                                            <input id="tb-search" class="tb-search form-control mb-2 mb-lg-0" type="search"
                                                name="search" placeholder="Cari Data" aria-label="search">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <x-adminlte-alerts />
                            <div class="collapse mt-2" id="filter-collapse">
                                <form id="custom-filter" class="row g-2 align-items-center mx-2">
                                    <div class="col-12 col-xl-3 col-lg-3 mb-2">
                                        <input class="form-control" type="text" id="daterange" name="daterange"
                                            placeholder="Pilih rentang tanggal">
                                    </div>
                                    <div class="col-12 col-xl-9 col-lg-3 mb-2 d-flex justify-content-end align-items-start">
                                        <button form="custom-filter" class="btn btn-info mr-2" id="tb-filter"
                                            type="submit">
                                            <i class="fa fa-magnifying-glass mr-2"></i>Cari
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="tb-reset">
                                            <i class="fa fa-rotate mr-2"></i>Reset
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-striped m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">Status</th>
                                                <th class="text-wrap align-top">Tgl Kirim</th>
                                                <th class="text-wrap align-top">Tgl Terima</th>
                                                <th class="text-wrap align-top">No. Resi</th>
                                                <th class="text-wrap align-top">Tipe Pengiriman</th>
                                                <th class="text-wrap align-top">Toko Pengirim</th>
                                                <th class="text-wrap align-top">Nama Pengirim</th>
                                                <th class="text-wrap align-top">Ekspedisi</th>
                                                <th class="text-wrap align-top">Jumlah Qty</th>
                                                <th class="text-wrap align-top">Total Harga</th>
                                                <th class="text-wrap align-top">Toko Penerima</th>
                                                <th class="text-wrap align-top">Action</th>
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
            </div>
        </div>
    </div>
@endsection

@section('asset_js')
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
            $('#listData').html(loadingData());

            let filterParams = {};

            if (customFilter['startDate'] && customFilter['endDate']) {
                filterParams.startDate = customFilter['startDate'];
                filterParams.endDate = customFilter['endDate'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.pengiriman.get') }}', {
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
            let id_toko = '{{ auth()->user()->id_toko }}';
            let status = '';
            if (data?.status === 'Sukses') {
                status =
                    `<span class="badge badge-success custom-badge"><i class="mx-1 fa fa-circle-check"></i>Sukses</span>`;
            } else if (data?.status === 'Pending') {
                status =
                    `<span class="badge badge-info custom-badge"><i class="mx-1 fa fa-circle-half-stroke "></i>Pending</span>`;
            } else if (data?.status === 'Progress') {
                status =
                    `<span class="badge badge-warning custom-badge"><i class="mx-1 fa fa-spinner"></i>Proses</span>`;
            } else if (data?.status === 'Gagal') {
                status =
                    `<span class="badge badge-danger custom-badge"><i class="mx-1 fa fa-circle-xmark"></i>Gagal</span>`;
            } else {
                status = `<span class="badge badge-secondary custom-badge">Tidak Diketahui</span>`;
            }

            let delete_button = '';
            let detail_button = '';

            if (id_toko == data?.id_toko_pengirim) {
                detail_button = (data?.status === 'Pending') ? `
                <a href="pengirimanbarang/detail/${data.id}" class="p-1 btn detail-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Edit Data Nomor Resi: ${data.no_resi}"
                    data-id='${data.id}'>
                    <span class="text-dark">Edit</span>
                    <div class="icon text-warning">
                        <i class="fa fa-edit"></i>
                    </div>
                </a>` :
                    (data?.status === 'Progress' || data?.status === 'Sukses') ? `
                <a href="pengirimanbarang/detail/${data.id}" class="p-1 btn detail-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Detail Data Nomor Resi: ${data.no_resi}"
                    data-id='${data.id}'>
                    <span class="text-dark">Detail</span>
                    <div class="icon text-info">
                        <i class="fa fa-book"></i>
                    </div>
                </a>` : '';

                delete_button = (data?.status === 'Progress' || data?.status === 'Pending') ? `
                <a class="p-1 btn hapus-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Hapus Data Nomor Resi: ${data.no_resi}"
                    data-id='${data.id}' data-name='${data.no_resi}'>
                    <span class="text-dark">Hapus</span>
                    <div class="icon text-danger">
                        <i class="fa fa-trash-alt"></i>
                    </div>
                </a>` : '';
            }

            let edit_button = '';
            if (id_toko == data?.id_toko_penerima && data?.status == 'Progress') {
                edit_button = `
                <a href="pengirimanbarang/edit/${data.id}" class="p-1 btn edit-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Verifikasi Data Nomor Resi: ${data.no_resi}"
                    data-id='${data.id}'>
                    <span class="text-dark">Verif</span>
                    <div class="icon text-success">
                        <i class="fa fa-circle-check"></i>
                    </div>
                </a>`;
            }
            if (id_toko == data?.id_toko_penerima && data?.status == 'Sukses') {
                detail_button = `
                <a href="pengirimanbarang/detail/${data.id}" class="p-1 btn detail-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Detail Data Nomor Resi: ${data.no_resi}"
                    data-id='${data.id}'>
                    <span class="text-dark">Detail</span>
                    <div class="icon text-info">
                        <i class="fa fa-book"></i>
                    </div>
                </a>`;
            }

            let action_buttons = '';
            if (edit_button || detail_button || delete_button) {
                action_buttons = `
                <div class="d-flex justify-content-start">
                    ${edit_button ? `<div class="hovering p-1">${edit_button}</div>` : ''}
                    ${detail_button ? `<div class="hovering p-1">${detail_button}</div>` : ''}
                    ${delete_button ? `<div class="hovering p-1">${delete_button}</div>` : ''}
                </div>`;
            } else {
                action_buttons = `
                <span class="badge badge-secondary">Tidak Ada Aksi</span>`;
            }

            return {
                id: data?.id ?? '-',
                status,
                no_resi: data?.no_resi ?? '-',
                ekspedisi: data?.ekspedisi ?? '-',
                tipe_pengiriman: data?.tipe_pengiriman ?? '-',
                toko_pengirim: data?.toko_pengirim ?? '-',
                nama_pengirim: data?.nama_pengirim ?? '-',
                tgl_kirim: data?.tgl_kirim ?? '-',
                tgl_terima: data?.tgl_terima ?? '-',
                total_item: data?.total_item ?? '-',
                total_nilai: data?.total_nilai ?? '-',
                toko_penerima: data?.toko_penerima ?? '-',
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
                <tr class="text-dark clickable-row" data-id="${element.id}">
                    <td class="${classCol} text-center">${display_from + index}.</td>
                    <td class="${classCol}">${element.status}</td>
                    <td class="${classCol}">${element.tgl_kirim}</td>
                    <td class="${classCol}">${element.tgl_terima}</td>
                    <td class="${classCol}">${element.no_resi}</td>
                    <td class="${classCol}">${element.tipe_pengiriman}</td>
                    <td class="${classCol}">${element.toko_pengirim}</td>
                    <td class="${classCol}">${element.nama_pengirim}</td>
                    <td class="${classCol}">${element.ekspedisi}</td>
                    <td class="${classCol}">${element.total_item}</td>
                    <td class="${classCol}">${element.total_nilai}</td>
                    <td class="${classCol}">${element.toko_penerima}</td>
                    <td class="${classCol}">${element.action_buttons}</td>
                </tr>`;
            });

            $('#listData').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();

            $('.clickable-row').on('click', function(e) {
                if ($(e.target).closest('.hapus-data').length) {
                    return;
                }

                let id = $(this).data('id');
                if (id) {
                    window.location.href = `pengirimanbarang/detail/${id}`;
                }
            });
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

            document.getElementById('tb-reset').addEventListener('click', async function() {
                $('#daterange').val('');
                customFilter = {};
                defaultSearch = $('.tb-search').val();
                defaultLimitPage = $("#limitPage").val();
                currentPage = 1;
                await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                    customFilter);
            });
        }

        async function deleteData() {
            $(document).on("click", ".hapus-data", async function() {
                let id = $(this).attr("data-id");
                let name = $(this).attr("data-name");

                swal({
                    title: `Hapus Pengiriman No Resi: ${name}`,
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
                        `pengirimanbarang/${id}/delete`
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

        async function initPageLoad() {
            await setDynamicButton();
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await filterList();
            await deleteData();
        }
    </script>
@endsection
