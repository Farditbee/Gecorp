@extends('layouts.main')

@section('title')
    Data Stock Barang
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notyf.min.css') }}">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex mb-2 mb-lg-0">
                                @if (Auth::user()->id_level == 1)
                                    <a href="{{ route('transaksi.pembelianbarang.index') }}" class="mr-2 btn btn-primary"
                                        data-container="body" data-toggle="tooltip" data-placement="top"
                                        title="Tambah Data Stock Barang">
                                        <i class="fa fa-circle-plus"></i> Tambah
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
                                    <table class="table table-striped m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">Nama Barang</th>
                                                <th class="text-wrap align-top">
                                                    Stock
                                                    <button class="btn btn-link p-0" id="sortAscStock">▲</button>
                                                    <button class="btn btn-link p-0" id="sortDescStock">▼</button>
                                                </th>
                                                @if (Auth::user()->id_level == 1)
                                                    <th class="text-wrap align-top">Harga Satuan (Hpp Baru)</th>
                                                @endif
                                                <th class="text-wrap align-top">Detail</th>
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

    @foreach ($stock as $stk)
        <div class="modal fade" id="mediumModal-{{ $stk->id }}" tabindex="-1" role="dialog"
            aria-labelledby="mediumModalLabel-{{ $stk->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mediumModalLabel-{{ $stk->id }}">
                            {{ $stk->barang->nama_barang }}
                            @php
                                $stokBarang = $stock->where('id_barang', $stk->id_barang)->first();
                            @endphp

                            @if (in_array(Auth::user()->id_level, [1, 2]))
                                : Rp. {{ number_format($stokBarang->hpp_baru, 0, ',', '.') }}
                            @endif
                        </h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs mb-3" id="myTab-{{ $stk->id }}" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-uppercase" id="home-tab-{{ $stk->id }}"
                                    data-toggle="tab" href="#home-{{ $stk->id }}" role="tab"
                                    aria-controls="home-{{ $stk->id }}" aria-selected="true">
                                    Barang Di Toko
                                </a>
                            </li>

                            @if (Auth::user()->id_level == 1)
                                <li class="nav-item">
                                    <a class="nav-link text-uppercase" id="atur-harga-tab-{{ $stk->id }}"
                                        data-toggle="tab" href="#atur-harga-{{ $stk->id }}" role="tab"
                                        aria-controls="atur-harga-{{ $stk->id }}" aria-selected="false">
                                        Atur Harga
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->id_level != 1)
                                <li class="nav-item">
                                    <a class="nav-link text-uppercase" id="detail--barang-tab-{{ $stk->id }}"
                                        data-toggle="tab" href="#detail--barang-{{ $stk->id }}" role="tab"
                                        aria-controls="detail--barang-{{ $stk->id }}" aria-selected="false">
                                        Detail Barang
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <div class="tab-content" id="myTabContent-{{ $stk->id }}">
                            <!-- Tab 1: Barang Di Toko -->
                            <div class="tab-pane fade show active" id="home-{{ $stk->id }}" role="tabpanel"
                                aria-labelledby="home-tab-{{ $stk->id }}">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="jsTable-{{ $stk->id }}">
                                        <thead>
                                            <tr>
                                                <th>Nama Toko</th>
                                                <th>Stock</th>
                                                @if (Auth::user()->id_level == 1)
                                                    <th>Level Harga</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $idTokoLogin = auth()->user()->id_toko;
                                                $toko = $toko->sortByDesc(fn($tk) => $tk->id == $idTokoLogin ? 1 : 0);
                                            @endphp
                                            @foreach ($toko as $tk)
                                                <tr class="{{ $tk->id == $idTokoLogin ? 'highlight-row' : '' }}">
                                                    <td>{{ $tk->nama_toko }}</td>
                                                    @if ($tk->id == 1)
                                                        @php
                                                            $stokBarangTokoUtama = $stock
                                                                ->where('id_barang', $stk->id_barang)
                                                                ->first();
                                                        @endphp
                                                        <td>{{ $stokBarangTokoUtama ? $stokBarangTokoUtama->stock : 0 }}
                                                        </td>
                                                    @else
                                                        @php
                                                            $stokBarangLain = $stokTokoLain
                                                                ->where('id_barang', $stk->id_barang)
                                                                ->where('id_toko', $tk->id)
                                                                ->sum('qty');
                                                        @endphp
                                                        <td>{{ $stokBarangLain > 0 ? $stokBarangLain : 0 }}</td>
                                                    @endif

                                                    @if (Auth::user()->id_level == 1)
                                                        <td>
                                                            @php
                                                                $levelHargaArray =
                                                                    json_decode($tk->id_level_harga, true) ?? [];
                                                                if (is_int($levelHargaArray)) {
                                                                    $levelHargaArray = [$levelHargaArray];
                                                                }
                                                            @endphp
                                                            @if (!empty($levelHargaArray) && is_array($levelHargaArray))
                                                                @foreach ($levelHargaArray as $i => $levelHargaId)
                                                                    @php $levelHarga = \App\Models\LevelHarga::find($levelHargaId); @endphp
                                                                    {{ $levelHarga ? $levelHarga->nama_level_harga : 'N/A' }}
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                Tidak Ada Level Harga
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab 2: Atur Harga -->
                            @if (Auth::user()->id_level == 1)
                                <div class="tab-pane fade" id="atur-harga-{{ $stk->id }}" role="tabpanel"
                                    aria-labelledby="atur-harga-tab-{{ $stk->id }}">
                                    <div class="harga-form mt-3" id="harga-form-{{ $stk->id_barang }}">
                                        <form method="POST" action="{{ route('updateLevelHarga') }}"
                                            class="level-harga-form">
                                            @csrf
                                            <input type="hidden" name="id_barang" value="{{ $stk->id_barang }}">
                                            @foreach ($levelharga as $index => $lh)
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{{ $lh->nama_level_harga }}</span>
                                                    </div>
                                                    <input type="text" name="level_harga[]"
                                                        id="harga-{{ $stk->id_barang }}-{{ str_replace(' ', '-', $lh->nama_level_harga) }}"
                                                        class="form-control level-harga" placeholder="Atur harga baru"
                                                        value="{{ isset($lh->harga) }}" oninput="formatCurrency(this)"
                                                        onblur="updateRawValue(this, {{ $index }})">
                                                    <input type="hidden" id="level_harga_raw_{{ $index }}"
                                                        name="harga_level_{{ str_replace(' ', '_', $lh->nama_level_harga) }}_barang_{{ $stk->id_barang }}"
                                                        value="{{ isset($lh->harga) ? $lh->harga : '' }}">
                                                    <input type="hidden" name="level_nama[]"
                                                        value="{{ $lh->nama_level_harga }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"
                                                            id="persen-{{ $stk->id_barang }}-{{ str_replace(' ', '-', $lh->nama_level_harga) }}">0%</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <input type="hidden" id="hpp-baru-{{ $stk->id_barang }}"
                                                value="{{ $stokBarang->hpp_baru }}">
                                        </form>
                                    </div>
                                </div>
                            @endif

                            @if (Auth::user()->id_level != 1)
                                <div class="tab-pane fade" id="detail--barang-{{ $stk->id }}" role="tabpanel"
                                    aria-labelledby="detail--barang-tab-{{ $stk->id }}">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead-light">
                                                <tr class="tb-head">
                                                    <th>#</th>
                                                    <th>Nama Barang</th>
                                                    <th>QR Code Pembelian</th>
                                                    <th>Stock</th>
                                                    <th>Harga Satuan (Hpp Baru)</th>
                                                </tr>
                                            </thead>
                                            <tbody id="detailData-{{ $stk->id }}">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('asset_js')
    <script src="{{ asset('js/pagination.js') }}"></script>
    <script src="{{ asset('js/notyf.min.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Stock Barang';
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
                '{{ route('master.getstockbarang') }}', {
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
            let detail_button = `
                <button id="detail-${data.id}" class="p-1 btn detail-data btn-primary atur-harga-btn"
                    data-toggle="modal" data-target="#mediumModal-${data.id}"
                    data-id='${data.id}' data-id-barang='${data.id_barang}' onclick="detailBarang(${data.id})">
                    <span class="text-white" data-container="body" data-toggle="tooltip" data-placement="top" title="Detail ${title}: ${data.nama_barang}"><i class="fa fa-eye mr-1"></i>Detail</span>
                </button>`;

            let delete_button = `
                <button class="p-1 btn hapus-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Hapus ${title}: ${data.nama_barang}"
                    data-id='${data.id}'
                    data-name='${data.nama_barang}'>
                    <span class="text-dark">Hapus</span>
                    <div class="icon text-danger">
                        <i class="fa fa-trash"></i>
                    </div>
                </button>`;

            return {
                id: data?.id ?? '-',
                nama_barang: data?.nama_barang ?? '-',
                stock: data?.stock ?? '-',
                hpp_baru: formatRupiah(data?.hpp_baru),
                detail_button,
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
                        <td class="${classCol}">${element.nama_barang}</td>
                        <td class="${classCol}">${element.stock}</td>
                        @if (Auth::user()->id_level == 1)
                        <td class="${classCol}">${element.hpp_baru}</td>
                        @endif
                        <td class="${classCol}">${element.detail_button}</td>
                        <td class="${classCol}">
                            <div class="d-flex justify-content-center w-100">
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
            detailPage();
        }

        async function detailBarang(stk_id) {
            let getDataRest = await renderAPI(
                'GET',
                `/admin/get-detail-barang/${stk_id}`, {}
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status == 200 && Array.isArray(getDataRest.data.data)) {
                let dataList = getDataRest.data.data;
                let getDataTable = '';
                let classCol = 'align-center text-dark text-wrap';
                dataList.forEach((element, index) => {
                    getDataTable += `
                    <tr class="text-dark">
                        <td class="${classCol} text-center">${index + 1}.</td>
                        <td class="${classCol}">${element.nama_barang}</td>
                        <td class="${classCol}">
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <span class="mr-1 mb-1 text-break" id="qrcode-text-${index}">${element.qrcode}</span>
                                <button type="button" class="btn btn-sm btn-outline-primary copy-btn"
                                    data-toggle="tooltip" title="Salin: ${element.qrcode}" data-target="qrcode-text-${index}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </td>
                        <td class="${classCol}">${element.qty}</td>
                        <td class="${classCol}">${element.harga}</td>
                    </tr>`;
                });
                $(`#detailData-${stk_id}`).html(getDataTable);

                const notyf = new Notyf({
                    duration: 3000,
                    position: {
                        x: 'center',
                        y: 'top',
                    }
                });

                document.querySelectorAll('.copy-btn').forEach(function(button) {
                    button.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const textToCopy = document.getElementById(targetId).innerText;

                        navigator.clipboard.writeText(textToCopy).then(function() {
                            notyf.success('QR Code berhasil disalin');
                        }).catch(function(err) {
                            notyf.error('Gagal menyalin QR Code');
                        });
                    });
                });

            } else {
                errorMessage = getDataRest?.data?.message;
                let errorRow = `
                            <tr class="text-dark">
                                <th class="text-center" colspan="${$('.tb-head th').length}"> ${errorMessage} </th>
                            </tr>`;
                $(`#detailData-${stk_id}`).html(errorRow);

            }
        }

        async function deleteData() {
            $(document).on("click", ".hapus-data", async function() {
                isActionForm = "destroy";
                let id = $(this).attr("data-id");
                let name = $(this).attr("data-name");

                swal({
                    title: `Hapus ${title} ${name}`,
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
                        `stock_barang/delete/${id}`
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

        async function detailPage() {
            const aturHargaButtons = document.querySelectorAll('.atur-harga-btn');
            aturHargaButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    const id_barang = button.getAttribute('data-id-barang');
                    const id_modal = button.getAttribute('data-id');
                    const modalId = `#atur-harga-${id_modal}`;

                    fetch(`/admin/get-stock-details/${id_barang}`)
                        .then(response => response.json())
                        .then(data => {
                            const modal = document.querySelector(modalId);
                            if (modal) {
                                let hppBaru = parseFloat(document.querySelector(
                                    `#hpp-baru-${id_barang}`).value) || 0;

                                modal.querySelectorAll('.level-harga').forEach(function(input) {
                                    input.setAttribute('data-hpp-baru', hppBaru);
                                });

                                Object.keys(data.level_harga).forEach(function(level_name) {
                                    const inputField = modal.querySelector(
                                        `#harga-${id_barang}-${level_name.replace(' ', '-')}`
                                    );

                                    if (inputField) {
                                        let levelHarga = parseFloat(data.level_harga[
                                            level_name].replace(/,/g, ''));
                                        inputField.setAttribute('data-raw-value',
                                            levelHarga); // Simpan nilai asli
                                        inputField.value = levelHarga
                                            .toLocaleString(); // Tampilkan nilai dengan pemisah ribuan

                                        calculatePercentage(inputField, hppBaru);

                                        inputField.addEventListener('input', function() {
                                            let rawValue = this.value.replace(
                                                /[^0-9]/g, '');
                                            this.setAttribute('data-raw-value',
                                                rawValue);

                                            if (rawValue) {
                                                this.value = parseInt(rawValue)
                                                    .toLocaleString();
                                            } else {
                                                this.value = '';
                                            }

                                            calculatePercentage(inputField,
                                                hppBaru);
                                        });
                                    }
                                });
                            } else {
                                console.error(`Modal dengan ID ${modalId} tidak ditemukan.`);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                        });
                });
            });

            function calculatePercentage(inputField, hppBaru) {
                let levelHarga = parseFloat(inputField.getAttribute('data-raw-value')) || 0;
                let persen = 0;
                if (hppBaru > 0) {
                    persen = ((levelHarga - hppBaru) / hppBaru) * 100;
                }

                const levelName = inputField.id.split('-').slice(2).join('-');
                const persenElement = inputField.closest('.input-group').querySelector(
                    `#persen-${inputField.id.split('-')[1]}-${levelName}`);
                if (persenElement) {
                    persenElement.textContent = `${persen.toFixed(2)}%`;
                }
            }

            function prepareFormData(event) {
                event.preventDefault();

                const form = event.target;
                const levelHargaInputs = form.querySelectorAll('.level-harga');

                levelHargaInputs.forEach(input => {
                    const rawValue = input.getAttribute('data-raw-value') || input.value.replace(/[^0-9]/g, '');

                    const hiddenInput = form.querySelector(`#${input.id}-hidden`);
                    if (hiddenInput) {
                        hiddenInput.value = rawValue;
                    }
                });

                form.submit();
            }

            // Tambahkan event listener untuk submit form
            document.querySelector('form').addEventListener('submit', prepareFormData);
        }

        async function initPageLoad() {
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await deleteData();
        }

        document.getElementById('sortAscStock').addEventListener('click', function() {
            handleSort('asc');
        });

        document.getElementById('sortDescStock').addEventListener('click', function() {
            handleSort('desc');
        });

        function handleSort(orderBy) {
            const currentSearch = document.getElementById('searchInput')?.value ||
                ''; // Adjust based on your search input ID
            const currentPage = 1; // Reset to the first page when sorting
            getListData(defaultLimitPage, currentPage, orderBy === 'asc' ? 1 : 0, currentSearch);
        }
    </script>
@endsection
