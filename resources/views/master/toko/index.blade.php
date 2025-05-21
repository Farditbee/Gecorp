@extends('layouts.main')

@section('title')
    Data Toko
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
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
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-xl-2 col-lg-2 mb-2">
                                    @if (Auth::user()->id_level == 1 || Auth::user()->id_level == 2)
                                        <a href="{{ route('master.toko.create') }}" class="mr-2 btn btn-primary w-100"
                                            data-container="body" data-toggle="tooltip" data-placement="top"
                                            title="Tambah Data Toko">
                                            <i class="fa fa-circle-plus"></i> Tambah
                                        </a>
                                    @endif
                                </div>
                                <div class="col-12 col-xl-10 col-lg-10 mb-2">
                                    <div class="row justify-content-end">
                                        <div class="col-4 col-xl-2 col-lg-2">
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
                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-striped m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">Nama Toko</th>
                                                <th class="text-wrap align-top">Singkatan</th>
                                                <th class="text-wrap align-top">Level Harga</th>
                                                <th class="text-wrap align-top">Wilayah</th>
                                                <th class="text-wrap align-top">Alamat</th>
                                                <th class="text-wrap align-top">List Barang</th>
                                                @if (auth()->user()->id_level === '1' || auth()->user()->id_level === '2')
                                                    <th class="text-center text-wrap align-top">Action</th>
                                                @endif
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
    <script src="{{ asset('js/pagination.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Data Toko';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listData').html(loadingData());

            let filterParams = {
                id_level: @json(auth()->user()->id_level),
                id_toko: @json(auth()->user()->id_toko),
            };

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.gettoko') }}', {
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
            let detail_button = `
            <a href='toko/detail/${data.id}' class="p-1 btn detail-data btn btn-primary"
                data-container="body" data-toggle="tooltip" data-placement="top"
                title="Lihat Detail ${title}: ${data.nama_toko}"
                data-id='${data.id}'>
                <span class="text-white"><i class="fa fa-eye mr-1"></i>Cek Detail</span>
            </a>`;

            let edit_button = `
            <a href='toko/edit/${data.id}' class="p-1 btn edit-data action_button"
                data-container="body" data-toggle="tooltip" data-placement="top"
                title="Edit ${title}: ${data.nama_toko}"
                data-id='${data.id}'>
                <span class="text-dark">Edit</span>
                <div class="icon text-warning">
                    <i class="fa fa-edit"></i>
                </div>
            </a>`;

            let delete_button = `
            <a class="p-1 btn hapus-data action_button"
                data-container="body" data-toggle="tooltip" data-placement="top"
                title="Hapus ${title}: ${data.nama_toko}"
                data-id='${data.id}'
                data-name='${data.nama_toko}'>
                <span class="text-dark">Hapus</span>
                <div class="icon text-danger">
                    <i class="fa fa-trash"></i>
                </div>
            </a>`;

            let action_buttons = '';
            if ((@json(auth()->user()->id_level) === '1' || @json(auth()->user()->id_level) === '2') && (edit_button ||
                    delete_button)) {
                action_buttons = `
                <div class="d-flex justify-content-start">
                    ${edit_button ? `<div class="hovering p-1">${edit_button}</div>` : ''}
                    ${delete_button ? `<div class="hovering p-1">${delete_button}</div>` : ''}
                </div>`;
            } else {
                action_buttons = `
                <span class="badge badge-secondary">Tidak Ada Aksi</span>`;
            }

            return {
                id: data?.id ?? '-',
                nama_toko: data?.nama_toko ?? '-',
                singkatan: data?.singkatan ?? '-',
                nama_level_harga: data?.nama_level_harga ?? '-',
                wilayah: data?.wilayah ?? '-',
                alamat: data?.alamat ?? '-',
                detail_button,
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
                let actionButtons = (@json(auth()->user()->id_level) === '1' || @json(auth()->user()->id_level) ===
                        '2') ?
                    `<td class="${classCol}">${element.action_buttons}</td>` : '';

                getDataTable += `
                <tr class="text-dark">
                    <td class="${classCol} text-center">${display_from + index}.</td>
                    <td class="${classCol}">${element.nama_toko}</td>
                    <td class="${classCol}">${element.singkatan}</td>
                    <td class="${classCol}">${element.nama_level_harga}</td>
                    <td class="${classCol}">${element.wilayah}</td>
                    <td class="${classCol}">${element.alamat}</td>
                    <td class="${classCol}">${element.detail_button}</td>
                    ${actionButtons}
                </tr>`;
            });

            $('#listData').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();
        }

        async function deleteData() {
            $(document).on("click", ".hapus-data", async function() {
                isActionForm = "destroy";
                let id = $(this).attr("data-id");
                let name = $(this).attr("data-name");

                swal({
                    title: `Hapus Toko ${name}`,
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
                        `toko/delete/${id}`
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

        async function initPageLoad() {
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await deleteData();
        }
    </script>
@endsection
