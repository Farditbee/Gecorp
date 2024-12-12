<title>Data User - Gecorp</title>
@extends('layouts.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb p-0 m-0" style="font-size: 18px">
                                <li class="breadcrumb-item"><a href="{{ route('master.index') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item">
                                    <b class="font-weight-bold">Data User</b>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex mb-2 mb-lg-0">
                                @if (Auth::user()->id_level == 1)
                                    <a href="{{ route('master.user.create') }}" class="mr-2 btn btn-primary">
                                        <i class="ti-plus menu-icon"></i> Tambah
                                    </a>
                                @else
                                    <a href="{{ route('master.user.create') }}" class="mr-2 btn btn-secondary disabled">
                                        <i class="ti-plus menu-icon"></i> Tambah
                                    </a>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap">
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
                                                <th class="text-wrap align-top">Nama User</th>
                                                <th class="text-wrap align-top">Level</th>
                                                <th class="text-wrap align-top">Toko</th>
                                                <th class="text-wrap align-top">Username</th>
                                                <th class="text-wrap align-top">Email</th>
                                                <th class="text-wrap align-top">No. HP</th>
                                                <th class="text-wrap align-top">Alamat</th>
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
            </div>
        </div>
    </div>
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

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.getdatauser') }}', {
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

            let edit_button = `
            <a href='user/edit/${data.id}' class="p-1 btn edit-data action_button"
                data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Edit User: ${data.nama}"
                data-id='${data.id}'>
                <span class="text-dark">Edit</span>
                <div class="icon text-warning pt-1">
                    <i class="fa fa-edit"></i>
                </div>
            </a>`;

            let delete_button = `
            <a class="p-1 btn hapus-data action_button"
                data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Hapus User: ${data.nama}"
                data-id='${data.id}'
                data-name='${data.nama}'>
                <span class="text-dark">Hapus</span>
                <div class="icon text-danger pt-1">
                    <i class="fa fa-trash"></i>
                </div>
            </a>`;

            return {
                id: data?.id ?? '-',
                nama: data?.nama ?? '-',
                nama_level: data?.nama_level ?? '-',
                nama_toko: data?.nama_toko ?? '-',
                username: data?.username ?? '-',
                email: data?.email ?? '-',
                no_hp: data?.no_hp ?? '-',
                alamat: data?.alamat ?? '-',
                edit_button,
                delete_button,
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
                        <td class="${classCol}">${element.nama}</td>
                        <td class="${classCol}">${element.nama_level}</td>
                        <td class="${classCol}">${element.nama_toko}</td>
                        <td class="${classCol}">${element.username}</td>
                        <td class="${classCol}">${element.email}</td>
                        <td class="${classCol}">${element.no_hp}</td>
                        <td class="${classCol}">${element.alamat}</td>
                        <td class="${classCol}">
                            <div class="d-flex justify-content-center w-100">
                                <div class="hovering p-1">
                                    ${element.edit_button}
                                </div>
                                <div class="hovering p-1">
                                    ${element.delete_button}
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

        function renderPagination() {
            let paginationHtml = '';

            if (currentPage > 1) {
                paginationHtml +=
                    `<button class="paginate-btn prev-btn btn btn-sm btn-outline-primary mx-1" data-page="${currentPage - 1}"><i class="fa fa-circle-chevron-left"></i></button>`;
            }

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPage, currentPage + 2);

            if (startPage > 1) {
                paginationHtml += `<button class="paginate-btn page-btn btn btn-sm btn-primary" data-page="1">1</button>`;
                if (startPage > 2) {
                    paginationHtml +=
                        `<button class="btn btn-sm btn-primary" style="pointer-events: none;"><i class="fa fa-ellipsis"></i></button>`;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationHtml +=
                    `<button class="paginate-btn page-btn btn btn-sm btn-primary ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
            }

            if (endPage < totalPage) {
                if (endPage < totalPage - 1) {
                    paginationHtml +=
                        `<button class="btn btn-sm btn-primary" style="pointer-events: none;"><i class="fa fa-ellipsis"></i></button>`;
                }
                paginationHtml +=
                    `<button class="paginate-btn page-btn btn btn-sm btn-primary" data-page="${totalPage}">${totalPage}</button>`;
            }

            if (currentPage < totalPage) {
                paginationHtml +=
                    `<button class="paginate-btn next-btn btn btn-sm btn-outline-primary mx-1" data-page="${currentPage + 1}"><i class="fa fa-circle-chevron-right"></i></button>`;
            }

            $('#pagination-js').html(paginationHtml);

            $('#pagination-js').off('click', '.paginate-btn').on('click', '.paginate-btn', async function(e) {
                const newPage = parseInt($(this).data('page'));
                if (!isNaN(newPage)) {
                    currentPage = newPage;
                    await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                        customFilter);
                }
            });
        }

        async function deleteData() {
            $(document).on("click", ".hapus-data", async function() {
                isActionForm = "destroy";
                let id = $(this).attr("data-id");
                let name = $(this).attr("data-name");

                swal({
                    title: `Hapus User ${name}`,
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
                        `user/delete/${id}`
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
            $('#limitPage').on('change', async function() {
                defaultLimitPage = parseInt($(this).val());
                currentPage = 1;
                await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                    customFilter);
            });

            $('.tb-search').on('input', debounce(async () => {
                defaultSearch = $('.tb-search').val();
                currentPage = 1;
                console.log('di klik')
                await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                    customFilter);
            }, 500));
            await deleteData();
        }

        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
    </script>
@endsection
