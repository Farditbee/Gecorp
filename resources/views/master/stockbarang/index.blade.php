<title>Data Stock Barang - Gecorp</title>
@extends('layouts.main')

@section('content')
<style>
    .highlight-row {
    background-color: #87fc87 !important; /* Gunakan !important jika perlu */
}

</style>
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Data Stock Barang</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('master.index')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a>Data Stock Barang</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <!-- Tombol Tambah -->
                        <a href="{{ route('master.pembelianbarang.index') }}" class="btn btn-primary">
                            <i class="ti-plus menu-icon"></i> Tambah
                        </a>
                        <!-- Input Search -->
                        <form class="d-flex" method="GET" action="{{ route('master.stockbarang.index') }}">
                            <input class="form-control me-2" id="search" type="search" name="search"
                                placeholder="Cari Barang" aria-label="Search">
                        </form>
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table table-striped" id="jsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="barcode-column">Barcode</th>
                                        <th>Nama Barang</th>
                                        <th>Jenis Barang</th>
                                        @if  (Auth::user()->id_level == 1)
                                            <th>Stock</th>
                                            <th>Harga Satuan (Hpp Baru)</th>
                                        @endif
                                        <th>Detail</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @foreach ($stock as $stk)
                                        <tr data-toggle="modal" class="atur-harga-btn" data-target="#mediumModal-{{ $stk->id }}" data-id_barang="{{ $stk->id_barang }}" data-id="{{ $stk->id }}">
                                            <td>{{ $no++ }}</td>
                                            <td class="barcode-column">{{ $stk->barang->barcode }}</td>
                                            <td>{{ $stk->nama_barang }}</td>
                                            <td>{{ $stk->barang->jenis->nama_jenis_barang }}</td>
                                            @if  (Auth::user()->id_level == 1)
                                                <td>{{ $stk->stock }}</td>
                                                <td>Rp. {{ number_format($stk->hpp_baru, 0, '.', '.') }}</td>
                                            @endif
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm atur-harga-btn" data-toggle="modal" data-target="#mediumModal-{{ $stk->id }}" data-id_barang="{{ $stk->id_barang }}" data-id="{{ $stk->id }}" style="font-size: 12px;">
                                                    Cek Detail
                                                </button>
                                            </td>
                                            <form onsubmit="return confirm('Ingin menghapus Data ini ?');" action="#" method="POST">
                                                <td>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash menu-icon"></i></button>
                                                </td>
                                            </form>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @foreach ($stock as $stk)
                                <div class="modal fade" id="mediumModal-{{ $stk->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="mediumModalLabel-{{ $stk->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="mediumModalLabel-{{ $stk->id }}">
                                                    {{ $stk->barang->nama_barang }} : @php
                                                    $stokBarang = $stock->where('id_barang', $stk->id_barang)->first();
                                                @endphp
                                                Rp. {{ number_format($stokBarang->hpp_baru, 0, ',', '.') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="nav nav-tabs mb-3" id="myTab-{{ $stk->id }}"
                                                    role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active text-uppercase"
                                                            id="home-tab-{{ $stk->id }}" data-toggle="tab"
                                                            href="#home-{{ $stk->id }}" role="tab"
                                                            aria-controls="home-{{ $stk->id }}"
                                                            aria-selected="true">Barang Di Toko</a>
                                                    </li>

                                                    @if  (Auth::user()->id_level == 1)
                                                    <li class="nav-item">
                                                        <a class="nav-link text-uppercase"
                                                            id="atur-harga-tab-{{ $stk->id }}"
                                                            data-toggle="tab"
                                                            href="#atur-harga-{{ $stk->id }}"
                                                            role="tab"
                                                            aria-controls="atur-harga-{{ $stk->id }}"
                                                            aria-selected="false">Atur Harga</a>
                                                    </li>
                                                    @endif
                                                </ul>
                                                <div class="tab-content" id="myTabContent-{{ $stk->id }}">
                                                    <div class="tab-pane fade show active"
                                                        id="home-{{ $stk->id }}" role="tabpanel"
                                                        aria-labelledby="home-tab-{{ $stk->id }}">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped"
                                                                        id="jsTable-{{ $stk->id }}">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Nama Toko</th>
                                                                                <th>Stock</th>
                                                                                @if  (Auth::user()->id_level == 1)
                                                                                <th>Level Harga</th>
                                                                                @endif
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @php
                                                                                $idTokoLogin = auth()->user()->id_toko;
                                                                                $toko = $toko->sortByDesc(function($tk) use ($idTokoLogin) {
                                                                                    return $tk->id == $idTokoLogin ? 1 : 0;
                                                                                });
                                                                            @endphp
                                                                            @foreach ($toko as $index => $tk)
                                                                            <tr class="{{ $tk->id == $idTokoLogin ? 'highlight-row' : '' }}">
                                                                                <td>{{ $tk->nama_toko }}</td>
                                                                                    @if ($tk->id == 1)
                                                                                        {{-- Tampilkan stok dari tabel stock_barang untuk toko dengan id = 1 --}}
                                                                                        @php
                                                                                            // Ambil stok dari tabel stock_barang hanya untuk barang yang sedang diklik
                                                                                            $stokBarangTokoUtama = $stock
                                                                                                ->where('id_barang',$stk->id_barang,)
                                                                                                ->first();
                                                                                        @endphp

                                                                                        @if ($stokBarangTokoUtama)
                                                                                            <td>{{ $stokBarangTokoUtama->stock }}
                                                                                            </td>
                                                                                        @else
                                                                                            <td>0</td>
                                                                                        @endif
                                                                                    @else
                                                                                        {{-- Tampilkan stok dari tabel detail_toko untuk toko selain id = 1 --}}
                                                                                        @php
                                                                                            // Ambil stok dari tabel detail_toko hanya untuk barang yang sedang diklik
                                                                                            $stokBarangLain = $stokTokoLain
                                                                                                ->where(
                                                                                                    'id_barang',
                                                                                                    $stk->id_barang,
                                                                                                )
                                                                                                ->where(
                                                                                                    'id_toko',
                                                                                                    $tk->id,
                                                                                                )
                                                                                                ->first();
                                                                                        @endphp
                                                                                        @if ($stokBarangLain)
                                                                                            <td>{{ $stokBarangLain->qty }}
                                                                                            </td>
                                                                                        @else
                                                                                            <td>0</td>
                                                                                        @endif
                                                                                    @endif
                                                                                    @if  (Auth::user()->id_level == 1)
                                                                                    <td>
                                                                                        @php
                                                                                            $levelHargaArray =
                                                                                                json_decode(
                                                                                                    $tk->id_level_harga,
                                                                                                    true,
                                                                                                ) ?? [];
                                                                                            if (
                                                                                                is_int(
                                                                                                    $levelHargaArray,
                                                                                                )
                                                                                            ) {
                                                                                                $levelHargaArray = [
                                                                                                    $levelHargaArray,
                                                                                                ];
                                                                                            }
                                                                                        @endphp
                                                                                        @if (!empty($levelHargaArray) && is_array($levelHargaArray))
                                                                                            @foreach ($levelHargaArray as $levelHargaId)
                                                                                                @php
                                                                                                    $levelHarga = \App\Models\LevelHarga::find(
                                                                                                        $levelHargaId,
                                                                                                    );
                                                                                                @endphp
                                                                                                {{ $levelHarga ? $levelHarga->nama_level_harga : 'N/A' }}
                                                                                                @if (!$loop->last)
                                                                                                    ,
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @else
                                                                                            Tidak Ada Level
                                                                                            Harga
                                                                                        @endif
                                                                                    </td>
                                                                                    @endif
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="atur-harga-{{ $stk->id }}" role="tabpanel" aria-labelledby="atur-harga-tab-{{ $stk->id }}">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="harga-form" id="harga-form-{{ $stk->id_barang }}">
                                                                    <form method="POST" action="{{ route('updateLevelHarga') }}" class="level-harga-form">
                                                                        @csrf
                                                                        <input type="hidden" name="id_barang" value="{{ $stk->id_barang }}">

                                                                        @foreach ($levelharga as $index => $lh)
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">{{ $lh->nama_level_harga }}</span>
                                                                            </div>
                                                                            <!-- Input visible untuk harga level -->
                                                                            <input type="text" name="level_harga[]"
                                                                                id="harga-{{ $stk->id_barang }}-{{ str_replace(' ', '-', $lh->nama_level_harga) }}"
                                                                                class="form-control level-harga"
                                                                                placeholder="Atur harga baru"
                                                                                value="{{ isset($lh->harga) }}"
                                                                                oninput="formatCurrency(this)"
                                                                                onblur="updateRawValue(this, {{ $index }})">

                                                                            <!-- Hidden input untuk menyimpan raw value -->
                                                                            <input type="hidden"
                                                                                id="level_harga_raw_{{ $index }}"
                                                                                name="harga_level_{{ str_replace(' ', '_', $lh->nama_level_harga) }}_barang_{{ $stk->id_barang }}"
                                                                                value="{{ isset($lh->harga) ? $lh->harga : '' }}"> <!-- Pastikan hidden input menyimpan nilai awal -->
                                                                            <input type="hidden" name="level_nama[]" value="{{ $lh->nama_level_harga}}">

                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text" id="persen-{{ $stk->id_barang }}-{{ str_replace(' ', '-', $lh->nama_level_harga) }}">0%</span>
                                                                            </div>
                                                                        </div>
                                                                        @endforeach
                                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                                        <input type="hidden" id="hpp-baru-{{ $stk->id_barang }}" value="{{ $stokBarang->hpp_baru }}">
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade"
                                                        id="contact-{{ $stk->id }}" role="tabpanel"
                                                        aria-labelledby="contact-tab-{{ $stk->id }}">
                                                        Another Tab
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    Menampilkan <span id="current-count">0</span> data dari <span
                                        id="total-count">0</span> total data.
                                </div>
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end" id="pagination">
                                        {{-- isian paginate --}}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
    // Menyembunyikan kolom barcode
    $(".barcode-column").hide();
});
</script>

<script>
    // Simpan ID tab aktif saat user klik submit
    document.querySelectorAll('.level-harga-form').forEach(form => {
        form.addEventListener('submit', function() {
            const activeTabId = document.querySelector('.tab-pane.active').id; // Ambil ID tab aktif
            localStorage.setItem('activeTab', activeTabId); // Simpan di local storage
        });
    });

    // Cek apakah ada tab aktif yang disimpan di Local Storage
    document.addEventListener('DOMContentLoaded', function () {
        // Cek apakah ada fragment di URL
        let fragment = window.location.hash;
        if (fragment) {
            // Temukan tab yang sesuai dengan fragment
            let activeTab = document.querySelector(`a[href="${fragment}"]`);
            if (activeTab) {
                // Aktifkan tab yang sesuai
                new bootstrap.Tab(activeTab).show();
            }
        }
    });
</script>

<script>
    const aturHargaButtons = document.querySelectorAll('.atur-harga-btn');
    aturHargaButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            const id_barang = button.getAttribute('data-id_barang');
            const id_modal = button.getAttribute('data-id');
            const modalId = `#atur-harga-${id_modal}`;

            fetch(`/admin/get-stock-details/${id_barang}`)
                .then(response => response.json())
                .then(data => {
                    const modal = document.querySelector(modalId);
                    if (modal) {
                        let hppBaru = parseFloat(document.querySelector(`#hpp-baru-${id_barang}`).value) || 0;
                        console.log(`HPP Baru: ${hppBaru}`); // Log nilai HPP baru

                        modal.querySelectorAll('.level-harga').forEach(function(input) {
                            input.setAttribute('data-hpp-baru', hppBaru);
                        });

                        Object.keys(data.level_harga).forEach(function(level_name) {
                            const inputField = modal.querySelector(`#harga-${id_barang}-${level_name.replace(' ', '-')}`);

                            if (inputField) {
                                let levelHarga = parseFloat(data.level_harga[level_name].replace(/,/g, ''));
                                inputField.setAttribute('data-raw-value', levelHarga); // Simpan nilai asli
                                inputField.value = levelHarga.toLocaleString(); // Tampilkan nilai dengan pemisah ribuan

                                calculatePercentage(inputField, hppBaru);

                                inputField.addEventListener('input', function() {
                                    let rawValue = this.value.replace(/[^0-9]/g, '');
                                    this.setAttribute('data-raw-value', rawValue);

                                    if (rawValue) {
                                        this.value = parseInt(rawValue).toLocaleString();
                                    } else {
                                        this.value = '';
                                    }

                                    calculatePercentage(inputField, hppBaru);
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
        const persenElement = inputField.closest('.input-group').querySelector(`#persen-${inputField.id.split('-')[1]}-${levelName}`);
        if (persenElement) {
            persenElement.textContent = `${persen.toFixed(2)}%`;
            console.log(`Level Harga: ${levelHarga}, Persentase: ${persen.toFixed(2)}%`);
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
</script>


@endsection
