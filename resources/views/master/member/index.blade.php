@extends('layouts.main')

@section('title')
    Data Member
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
                        <div class="card-header custom-header">
                            <div class="custom-left">
                                <div class="custom-btn-tambah-wrap">
                                    <a class="btn btn-primary custom-btn-tambah text-white" data-toggle="modal"
                                        data-target=".bd-example-modal-lg">
                                        <span data-container="body" data-toggle="tooltip" data-placement="top"
                                            title="Tambah Data Member"><i class="fa fa-plus-circle mr-1"></i>Tambah</span>
                                    </a>
                                </div>
                                <form action="{{ route('master.member.import') }}" method="POST"
                                    enctype="multipart/form-data" class="custom-form-import">
                                    @csrf
                                    <input type="file" name="file" class="custom-input-file" accept=".xlsx" required>
                                    <button type="submit" class="btn btn-success custom-btn-import">
                                        <i class="fa fa-file-import"></i> Import
                                    </button>
                                </form>
                            </div>
                            <div class="custom-right">
                                <div class="custom-limit-page">
                                    <select name="limitPage" id="limitPage" class="form-control">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                    </select>
                                </div>
                                <div class="custom-search">
                                    <input id="tb-search" class="tb-search form-control" type="search" name="search"
                                        placeholder="Cari Data" aria-label="search">
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
                                                <th class="text-wrap align-top">Nama Member</th>
                                                <th class="text-wrap align-top">Nama Toko</th>
                                                <th class="text-wrap align-top">Level</th>
                                                <th class="text-wrap align-top">No. Hp</th>
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

    <div id="modal-form" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title h4" id="myLargeModalLabel">Tambah Data Member</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body table-border-style">
                                    <div class="table-responsive">
                                        <form action="{{ route('master.member.store') }}" method="post" class="">
                                            @csrf
                                            <div class="form-group">
                                                <label for="id_toko" class="form-control-label">Nama Toko<span
                                                        style="color: red">*</span></label>
                                                <select id="id_toko" name="id_toko" class="form-control id-toko select2">
                                                    <option value="" selected>~Silahkan Pilih Toko~</option>
                                                    @foreach ($toko as $tk)
                                                        <option value="{{ $tk->id }}"
                                                            {{ count($toko) === 1 ? 'selected' : '' }}>
                                                            {{ $tk->nama_toko }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="nama_member" class=" form-control-label">Nama Member<span
                                                        style="color: red">*</span></label>
                                                <input type="text" id="nama_member" name="nama_member"
                                                    placeholder="Contoh : Member 1" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="jenis_barang" class="form-control-label">Jenis Barang</label>
                                                <ul class="list-group list-group-flush">
                                                    @foreach ($jenis_barang as $jb)
                                                        <li class="list-group-item">
                                                            <h6>{{ $jb->nama_jenis_barang }}
                                                                <select name="level_harga[{{ $jb->id }}]"
                                                                    id="level_harga_{{ $jb->id }}"
                                                                    class="form-control">
                                                                    <option value="" selected>~Silahkan Pilih Toko
                                                                        Dahulu~</option>
                                                                    @foreach ($levelharga as $lh)
                                                                    @endforeach
                                                                </select>
                                                            </h6>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            <div class="form-group">
                                                <label for="no_hp" class=" form-control-label">No HP<span
                                                        style="color: red">*</span></label>
                                                <input type="number" id="no_hp" name="no_hp"
                                                    placeholder="Contoh : member123@gmail.com" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="alamat" class=" form-control-label">Alamat<span
                                                        style="color: red">*</span></label>
                                                <textarea name="alamat" id="alamat" rows="4"
                                                    placeholder="Contoh : Jl. Nyimas Gandasari No.18 Plered - Cirebon" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-dot-circle-o"></i> Simpan
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

    <!-- Modal form edit -->
    @foreach ($member as $mbr)
        <div class="modal fade bd-example-modal-lg" id="editMemberModal{{ $mbr->id }}" tabindex="-1"
            role="dialog" aria-labelledby="editMemberModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('master.member.update', $mbr->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editMemberModalLabel">Edit Data Member</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Nama Member -->
                            <div class="form-group">
                                <label for="nama_member">Nama Member<span style="color: red">*</span></label>
                                <input type="text" name="nama_member" class="form-control"
                                    value="{{ $mbr->nama_member }}" required>
                            </div>

                            <!-- Nama Toko -->
                            <div class="form-group">
                                <label for="id_toko_{{ $mbr->id }}" class=" form-control-label">Nama Toko<span
                                        style="color: red">*</span></label>
                                <select name="id_toko" id="selectors" class="form-control" required>
                                    <option value="">~Silahkan Pilih Toko~</option>
                                    @foreach ($toko as $tk)
                                        <option value="{{ $tk->id }}"
                                            {{ old('id_toko', $mbr->id_toko) == $tk->id ? 'selected' : '' }}>
                                            {{ $tk->nama_toko }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="jenis_barang" class="form-control-label">Jenis Barang</label>
                                <ul class="list-group list-group-flush">
                                    @foreach ($jenis_barang as $jb)
                                        <li class="list-group-item">
                                            <h6>{{ $jb->nama_jenis_barang }}
                                                <select name="level_harga[{{ $jb->id }}]"
                                                    id="level_harga_{{ $jb->id }}" class="form-control">
                                                    <option value="">~Silahkan Pilih~</option>
                                                    @foreach ($levelharga as $lh)
                                                        <option value="{{ $lh->id }}"
                                                            {{ isset($selected_levels[$mbr->id][$jb->id]) && $selected_levels[$mbr->id][$jb->id] == $lh->id ? 'selected' : '' }}>
                                                            {{ $lh->nama_level_harga }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </h6>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Nomor Hp -->
                            <div class="form-group">
                                <label for="no_hp">No HP<span style="color: red">*</span></label>
                                <input type="number" name="no_hp" class="form-control" value="{{ $mbr->no_hp }}"
                                    required>
                            </div>

                            <!-- Alamat -->
                            <div class="form-group">
                                <label for="alamat" class=" form-control-label">Alamat<span
                                        style="color: red">*</span></label>
                                <textarea name="alamat" id="alamat" rows="4" class="form-control">{{ $mbr->alamat }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('asset_js')
    <script src="{{ asset('js/pagination.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Data Member';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listData').html(loadingData());

            let filterParams = {};

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.getmember') }}', {
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
            let edit_button = `
                <a class="p-1 btn edit-data action_button" data-toggle="modal" data-target="#editMemberModal${data.id}"
                    data-id='${data.id}'>
                    <span class="text-dark" data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Edit ${title}: ${data.nama_member}">Edit</span>
                    <div class="icon text-warning" data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Edit ${title}: ${data.nama_member}">
                        <i class="fa fa-edit"></i>
                    </div>
                </a>`;

            let delete_button = `
                <a class="p-1 btn hapus-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Hapus ${title}: ${data.nama_member}"
                    data-id='${data.id}'
                    data-name='${data.nama_member}'>
                    <span class="text-dark">Hapus</span>
                    <div class="icon text-danger">
                        <i class="fa fa-trash"></i>
                    </div>
                </a>`;

            return {
                id: data?.id ?? '-',
                nama_member: data?.nama_member ?? '-',
                nama_toko: data?.nama_toko ?? '<span class="badge badge-danger">Tidak Ada Toko</span>',
                level: data?.level ?? [],
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
                let levelList = '';
                if (Array.isArray(element.level) && element.level.length > 0) {
                    levelList = '<div class="mb-0">';
                    element.level.forEach(levelItem => {
                        levelList +=
                            `<div>${levelItem.nama_jenis_barang} : ${levelItem.nama_level_harga}</div>`;
                    });
                    levelList += '</div>';
                } else {
                    levelList = '<span class="badge badge-danger">Tidak Ada Level</span>';
                }

                getDataTable += `
                    <tr class="text-dark">
                        <td class="${classCol} text-center">${display_from + index}.</td>
                        <td class="${classCol}">${element.nama_member}</td>
                        <td class="${classCol}">${element.nama_toko}</td>
                        <td class="${classCol}">${levelList}</td>
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
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();
        }

        async function deleteData() {
            $(document).on("click", ".hapus-data", async function() {
                isActionForm = "destroy";
                let id = $(this).attr("data-id");
                let name = $(this).attr("data-name");

                swal({
                    title: `Hapus Member ${name}`,
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
                        `member/delete/${id}`
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

        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('selector');
            const choices = new Choices(element, {
                removeItemButton: true, // Memungkinkan penghapusan item
                searchEnabled: true, // Mengaktifkan pencarian
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            $('#id_toko').on('change', function() {
                var idToko = $(this).val(); // Get the selected value of id_toko

                // Loop through all types of goods
                @foreach ($jenis_barang as $jb)
                    (function(jbId) {
                        var levelHargaDropdown = document.getElementById('level_harga_' +
                            jbId); // Get the level_harga dropdown for this type of good

                        // Reset the dropdown
                        levelHargaDropdown.innerHTML = '<option value="">~Silahkan Pilih~</option>';

                        if (idToko) { // Ensure there is a selected id_toko
                            // Make an AJAX request to get level_harga
                            var xhr = new XMLHttpRequest();
                            xhr.open('GET', '/admin/get-level-harga/' + idToko, true);

                            xhr.onload = function() {
                                if (xhr.status === 200) {
                                    console.log('Server response:', xhr.responseText);

                                    var data = JSON.parse(xhr.responseText);

                                    // Check if there are level harga
                                    if (data.length > 0) {
                                        // Loop through the data and add options to the dropdown
                                        data.forEach(function(level) {
                                            var option = document.createElement('option');
                                            option.value = level.id; // Level price ID
                                            option.text = level
                                                .nama_level_harga; // Level price name
                                            levelHargaDropdown.appendChild(option);
                                        });
                                    } else {
                                        // If no level harga, add "No Level" option
                                        var option = document.createElement('option');
                                        option.value = ""; // Empty value
                                        option.text = "Tidak ada Level"; // Text for the option
                                        levelHargaDropdown.appendChild(option);
                                    }
                                } else {
                                    console.error('Error fetching data from server');
                                }
                            };

                            xhr.send(); // Send the request
                        }
                    })({{ $jb->id }});
                @endforeach
            });

            const element = document.getElementById('selectors');
            const choices = new Choices(element, {
                removeItemButton: true,
                searchEnabled: true,
            });

            // document.addEventListener('DOMContentLoaded', function() {
            //     // Ambil semua tombol dengan atribut data-id
            //     var editButtons = document.querySelectorAll('button[data-id]');

            //     // Tambahkan event listener ke setiap tombol edit
            //     editButtons.forEach(function(button) {
            //         button.addEventListener('click', function() {
            //             // Ambil id_member dari atribut data-id
            //             var idMember = this.getAttribute('data-id');

            //             // Tampilkan id_member di console log
            //             console.log('Tombol Edit diklik, ID Member:', idMember);
            //         });
            //     });
            // });

            function editMember(id) {
                console.log('ID Member yang diedit:', id); // Log id_member yang sedang diedit

                $.ajax({
                    url: '/member/' + id + '/edit', // URL menuju method edit
                    method: 'GET',
                    success: function(data) {
                        console.log('ID Toko yang dipilih:', data
                            .id_toko); // Log id_toko yang dipilih

                        // Isi form modal dengan data dari server
                        $('#edit_nama_member').val(data.nama_member);
                        $('#edit_no_hp').val(data.no_hp);
                        $('#edit_alamat').val(data.alamat);
                        $('#edit_toko').val(data.id_toko);

                        // Muat level harga berdasarkan id_toko yang sudah dipilih tanpa reset
                        loadLevelHargaEditWithoutReset(data.id_toko, data.level_info);

                        // Tampilkan modal
                        $('#editMemberModal').modal(
                            'show'); // Tampilkan modal jika belum otomatis
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr);
                    }
                });
            }
        });

        function selectFormat(isParameter, isPlaceholder, isDisabled = true) {
            if (!$(isParameter).find('option[value=""]').length) {
                $(isParameter).prepend('<option value=""></option>');
            }

            $(isParameter).select2({
                dropdownParent: $('#modal-form'),
                disabled: isDisabled,
                dropdownAutoWidth: true,
                width: '100%',
                placeholder: isPlaceholder,
                allowClear: true,
            });
        }

        async function initPageLoad() {
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await deleteData();
            await selectFormat('#id_toko', 'Pilih Toko', false);
        }
    </script>
@endsection
