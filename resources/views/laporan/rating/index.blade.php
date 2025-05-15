@extends('layouts.main')

@section('title')
    Rekapitulasi Rating Barang
@endsection

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Atur kolom No */
        #jsTables th:nth-child(1),
        #jsTables td:nth-child(1) {
            width: 5%;
            /* Lebar kecil untuk kolom No */
            text-align: left;
            /* Nomor tetap rata kiri */
            padding-left: 8px;
            /* Opsional: tambahkan sedikit padding kiri */
        }

        /* Atur kolom Nama Barang */
        #jsTables th:nth-child(2),
        #jsTables td:nth-child(2) {
            width: 30%;
            /* Tetapkan lebar tetap untuk kolom Nama Barang */
            word-wrap: break-word;
            /* Bungkus teks panjang */
            word-break: break-word;
            /* Pecah kata panjang */
            white-space: normal;
            /* Izinkan teks turun ke baris baru */
        }

        /* Atur kolom Nama Toko (mulai dari kolom ke-3 dan seterusnya) */
        #jsTables th:nth-child(n+3),
        #jsTables td:nth-child(n+3) {
            width: 10%;
            /* Tetapkan lebar tetap untuk kolom Toko */
            word-wrap: break-word;
            /* Bungkus teks panjang */
            word-break: break-word;
            /* Pecah kata panjang */
            white-space: normal;
            /* Izinkan teks turun ke baris baru */
            text-align: center;
            /* Rata tengah untuk teks dan angka */
        }

        /* Pastikan tabel tidak terlalu melebar */
        #jsTables {
            table-layout: fixed;
            /* Gunakan layout tabel tetap */
            width: 100%;
            /* Pastikan tabel menggunakan 100% lebar halaman */
        }

        /* Opsional: Tambahkan margin antara kolom */
        #jsTables td,
        #jsTables th {
            padding: 5px 10px;
            /* Atur padding sesuai keinginan */
        }
    </style>

    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <!-- Tombol Tambah, Filter, dan Reset Filter -->
                            <div>
                                <!-- Select moved between Filter and Reset buttons -->
                                <div class="row mt-3">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <select class="form-control" id="selector" name="toko_select[]" multiple>
                                                <option value="">~Silahkan Pilih Toko~</option>
                                                @foreach ($toko as $tk)
                                                    @if ($tk->id != 1)
                                                    <option value="{{ $tk->id }}"
                                                        data-singkatan="{{ $tk->singkatan }}">{{ $tk->nama_toko }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-start">
                                        <a href="#" class="btn btn-warning mr-2" data-toggle="modal"
                                            data-target="#filterModal">
                                            <i class="ti-plus menu-icon"></i> Filter
                                        </a>
                                        <a href="{{ route('laporan.rating.index') }}" class="btn btn-secondary"
                                            onclick="resetFilter()">
                                            Reset
                                        </a>
                                    </div>
                                </div>

                                <!-- Keterangan Periode Tanggal di Bawah Tombol Filter -->
                                @if (request('startDate') && request('endDate'))
                                    <p class="text-muted mt-2 mb-0">
                                        Data dimuat dalam periode dari tanggal
                                        {{ \Carbon\Carbon::parse(request('startDate'))->format('d M Y') }} s/d
                                        {{ \Carbon\Carbon::parse(request('endDate'))->format('d M Y') }}.
                                    </p>
                                @endif
                            </div>
                        </div>
                        <x-adminlte-alerts />
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table table-striped" id="jsTables">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barang as $brg)
                                        <tr data-barang-id="{{ $brg->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $brg->nama_barang }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal untuk Filter Tanggal -->
            <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">Filter Tanggal</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('laporan.rating.index') }}" method="GET">
                                <div class="form-group">
                                    <label for="startDate">Tanggal Mulai</label>
                                    <input type="date" name="startDate" id="startDate" class="form-control"
                                        value="{{ request('startDate') }}">
                                </div>
                                <div class="form-group">
                                    <label for="endDate">Tanggal Selesai</label>
                                    <input type="date" name="endDate" id="endDate" class="form-control"
                                        value="{{ request('endDate') }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- [ Main Content ] end -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('selector');
            const choices = new Choices(element, {
                removeItemButton: true,
                searchEnabled: true,
            });
        });
    </script>
    <script>
        const dataBarangTerjual = @json($dataBarang);
        console.log('Data Barang Terjual:', dataBarangTerjual);
    </script>
    <script>
        document.getElementById('selector').addEventListener('change', function() {
    const selectedOptions = Array.from(this.selectedOptions)
        .filter(option => option.value !== "")
        .map(option => ({
            id: option.value,
            singkatan: option.getAttribute('data-singkatan')
        }));

    console.log('Selected Toko:', selectedOptions);

    // Kirim permintaan AJAX untuk mendapatkan data barang terjual
    fetch('{{ route('get-barang-jual') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ toko_select: selectedOptions.length > 0 ? selectedOptions.map(option => option.id) : null })
    })
    .then(response => response.json())
    .then(dataBarangTerjual => {
        console.log('Data Barang Terjual:', dataBarangTerjual);

        const table = document.getElementById('jsTables');
        const headerRow = table.querySelector('thead tr');
        const bodyRows = table.querySelectorAll('tbody tr');

        // Hapus semua kolom toko sebelumnya
        const existingHeaders = headerRow.querySelectorAll('.dynamic-column');
        existingHeaders.forEach(header => header.remove());

        bodyRows.forEach(row => {
            const dynamicCells = row.querySelectorAll('.dynamic-cell');
            dynamicCells.forEach(cell => cell.remove());
        });

        // Tambahkan header toko
        const tokoList = selectedOptions.length > 0 ? selectedOptions : dataBarangTerjual.toko_list; // Ambil semua toko jika tidak ada yang dipilih
        tokoList.forEach(toko => {
            const th = document.createElement('th');
            th.textContent = toko.singkatan;
            th.classList.add('dynamic-column');
            headerRow.appendChild(th);
        });

        // Tambahkan data barang terjual ke tabel
        bodyRows.forEach(row => {
            const barangId = row.getAttribute('data-barang-id'); // ID Barang
            if (!barangId) {
                console.warn(`Row tanpa data-barang-id ditemukan:`, row);
                return;
            }

            console.log(`Processing Barang ID: ${barangId}`);

            tokoList.forEach(toko => {
                const tokoData = (dataBarangTerjual[barangId] || []).find(d => d.id_toko == toko.id);
                const jumlahTerjual = tokoData ? tokoData.total_terjual : 0;

                console.log(`Barang ID: ${barangId}, Toko ID: ${toko.id}, Jumlah Terjual: ${jumlahTerjual}`);

                const td = document.createElement('td');
                td.textContent = jumlahTerjual; // Tampilkan jumlah terjual
                td.classList.add('dynamic-cell');
                row.appendChild(td);
            });
        });

        console.log('Updated Table Rows:', Array.from(bodyRows).map(row => row.outerHTML));
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });
});

    </script>

    <script>
        $(document).ready(function() {
            // Buka modal ketika tombol filter diklik
            $('#filterButton').on('click', function() {
                $('#filterModal').modal('show');
            });

            // Saat modal ditutup, bersihkan tanggal jika diperlukan
            $('#filterModal').on('hidden.bs.modal', function() {
                $('#startDate').val('');
                $('#endDate').val('');
            });
        });
    </script>

    <script>
        function resetFilter() {
            const url = new URL(window.location.href);
            url.searchParams.delete('startDate');
            url.searchParams.delete('endDate');
            window.location.href = url.toString();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');

            if (startDateInput) {
                startDateInput.addEventListener('focus', function() {
                    this.showPicker?.(); // Modern browsers
                });
            }

            if (endDateInput) {
                endDateInput.addEventListener('focus', function() {
                    this.showPicker?.(); // Modern browsers
                });
            }
        });
    </script>
@endsection
