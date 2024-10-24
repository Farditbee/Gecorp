<title>Data Transaksi Kasir - Gecorp</title>
@extends('layouts.main')
@section('content')

    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Data Transaksi Kasir</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('master.index') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a>Data Transaksi Kasir</a></li>
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
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target=".bd-example-modal-lg">Tambah</button>
                            <!-- Input Search -->
                            <form class="d-flex" method="GET" action="{{ route('master.kasir.index') }}">
                                <input class="form-control me-2" id="search" type="search" name="search"
                                    placeholder="Cari Data Transaksi" aria-label="Search">
                            </form>
                        </div>
                        <x-adminlte-alerts />
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table table-striped" id="jsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>No Nota</th>
                                            <th>Tgl Transaksi</th>
                                            <th>Member</th>
                                            <th>Nama Toko</th>
                                            <th>Item</th>
                                            <th>Nilai</th>
                                            <th>Payment</th>
                                            <th>Kasir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        @forelse ($kasir as $ksr)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $ksr->no_nota }}</td>
                                                <td>{{ $ksr->tgl_transaksi }}</td>
                                                <td>{{ $ksr->member->nama_member }}</td>
                                                <td>{{ $ksr->toko->nama_toko }}</td>
                                                <td>{{ $ksr->total_item }}</td>
                                                <td>{{ $ksr->total_nilai }}</td>
                                                <td>{{ $ksr->metode }}</td>
                                                <td>{{ $ksr->users->nama }}</td>
                                            </tr>
                                        @empty
                                            <td colspan="9" style="text-align: center">
                                                <h4><span class="badge badge-light-danger" style="margin:10px;">Tidak Ada
                                                        Data</span></h4>
                                            </td>
                                        @endforelse
                                    </tbody>
                                </table>
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

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title h4" id="myLargeModalLabel">Data Transaksi</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <br>
                <div class="col-xl-12 d-flex justify-content-between">
                    <div class="d-flex col-6">
                        <div class="col-4">
                            <p class="mb-0">No Nota</p>
                        </div>
                        <div class="col-8">
                            <p>: 123</p>
                        </div>
                    </div>
                    <div class="d-flex col-6 justify-content-end">
                        <div class="col-4 text-end">
                            <p class="mb-0">Nama Toko</p>
                        </div>
                        <div class="col-8">
                            @if (Auth::check())
                            <h5>: <span class="badge badge-info">{{Auth::user()->toko->nama_toko}}</span></h5>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-12 d-flex justify-content-between">
                    <div class="d-flex col-6">
                        <div class="col-4">
                            <p class="mb-0">Tgl Transaksi</p>
                        </div>
                        <div class="col-8">
                            <p>: 123</p>
                        </div>
                    </div>
                    <div class="d-flex col-6 justify-content-end">
                        <div class="col-4 text-end">
                            <p class="mb-0">Kasir</p>
                        </div>
                        <div class="col-8">
                            @if (Auth::check())
                            <h5>: <span class="badge badge-info">{{Auth::user()->nama}}</span></h5>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 d-flex justify-content-between">
                    <div class="d-flex col-6">
                        <div class="col-4">
                            
                        </div>
                        <div class="col-8">
                            
                        </div>
                    </div>
                    <div class="d-flex col-6 justify-content-end">
                        <div class="col-4 text-end">
                            <p class="mb-0">Member</p>
                        </div>
                        <div class="col-8">
                            <p>:
                                <select name="id_member" id="id_member">
                                    <option value="Guest" selected>Guest</option>
                                    @foreach ($member as $mbr)
                                        <option value="{{ $mbr->id }}" data-level-info='@json($mbr->level_info)'>{{ $mbr->nama_member }}</option>
                                    @endforeach
                                </select>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body table-border-style">
                                    <div class="table-responsive">
                                        <form action="{{ route('master.kasir.store') }}" method="post" class="">
                                            @csrf
                                            <div class="row">
                                                <div class="col-6">
                                                    <!-- Nama Barang -->
                                                    <div class="form-group">
                                                        <label for="id_barang" class="form-control-label">Nama Barang<span
                                                                style="color: red">*</span></label>
                                                        <select name="id_barang" id="selector" class="form-control">
                                                            <option value="">~Silahkan Pilih Barang~</option>
                                                            {{-- Jika users id_toko nya adalah 1, ambil data dari StockBarang --}}
                                                            @if  (Auth::user()->id_level == 1)
                                                                @if ($stock->isEmpty())
                                                                    <option value="">Tidak ada Barang</option>
                                                                @else
                                                                    @foreach ($stock as $tk)
                                                                        <option value="{{ $tk->barang->id }}"
                                                                            data-stock="{{ $tk->stock }}"
                                                                            data-level-harga='@json($tk->barang->level_harga)'>
                                                                            {{ $tk->nama_barang }} (Stock :
                                                                            <strong>{{ $tk->stock }}</strong>)
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            @else
                                                                {{-- Jika users bukan dari id_toko 1, ambil data dari DetailToko --}}
                                                                @if ($detail_toko->isEmpty())
                                                                    <option value="">Tidak ada Barang di Toko Ini
                                                                    </option>
                                                                @else
                                                                    @foreach ($detail_toko as $dt)
                                                                        <option value="{{ $dt->barang->id }}"
                                                                            data-stock="{{ $dt->qty }}"
                                                                            data-level-harga='@json($dt->barang->level_harga)'>
                                                                            {{ $dt->barang->nama_barang }} (Stock :
                                                                            <strong>{{ $dt->qty }}</strong>)
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="harga" class="form-control-label">Harga<span
                                                            style="color: red">*</span></label>
                                                    <select class="form-control" name="harga" id="harga"
                                                        style="display: block;">
                                                        <option value="">~Pilih Barang Dahulu~</option>
                                                        
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                </div>
                                                <div class="col-6">
                                                    <label for="qty" class=" form-control-label">Item<span
                                                            style="color: red">*</span></label>
                                                    <input type="number" id="qty" name="qty"
                                                        placeholder="Contoh : 1" class="form-control">
                                                    <br>
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        style="float: right;">Add</button>
                                                </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var element = document.getElementById('selector'); // Barang selector
            var hargaSelect = document.getElementById('harga'); // Harga selector
            var memberSelect = document.getElementById('id_member'); // Member selector
            var choices = new Choices(element, {
                searchEnabled: true, // Aktifkan fitur pencarian
                placeholderValue: 'Silahkan Pilih Barang', // Placeholder
                noResultsText: 'Barang tidak ditemukan', // Pesan saat tidak ada hasil
            });
    
            // Fungsi untuk menampilkan semua harga
            function displayAllPrices(parsedLevelHarga) {
                parsedLevelHarga.forEach(function(entry) {
                    entry = entry.replace(/["]/g, '').trim();
                    var parts = entry.split(': ');
                    if (parts.length === 2) {
                        var harga = parts[1].replace(/,/g, '').trim(); // Ambil harga dan hilangkan koma
                        var option = document.createElement('option');
                        option.value = harga;
                        option.textContent = harga;
                        hargaSelect.appendChild(option);
                    }
                });
            }
    
            // Fungsi untuk menampilkan harga berdasarkan level dari member
            function displayMemberPrices(parsedLevelHarga, memberLevelInfo) {
                var memberParsedLevelInfo = JSON.parse(memberLevelInfo); // Parse level_info dari member
                hargaSelect.innerHTML = ''; // Kosongkan opsi harga sebelumnya
    
                memberParsedLevelInfo.forEach(function(info) {
                    var parts = info.split(': ');
                    var idJenisBarang = parts[0].trim();
                    var idLevelHarga = parts[1].trim();
    
                    parsedLevelHarga.forEach(function(entry) {
                        entry = entry.replace(/["]/g, '').trim();
                        var entryParts = entry.split(': ');
                        if (entryParts.length === 2 && entryParts[0] === `Level ${idLevelHarga}`) {
                            var harga = entryParts[1].replace(/,/g, '').trim();
                            var option = document.createElement('option');
                            option.value = harga;
                            option.textContent = harga;
                            hargaSelect.appendChild(option);
                        }
                    });
                });
            }
    
            // Event listener untuk perubahan pada select barang
            element.addEventListener('change', function(event) {
                hargaSelect.innerHTML = '<option value="">~Silahkan Pilih Harga~</option>';
    
                var selectedOption = element.selectedOptions[0]; // Mengambil opsi yang dipilih
                var id_barang = selectedOption ? selectedOption.value : null;
    
                if (selectedOption) {
                    var levelHarga = selectedOption.getAttribute('data-level-harga');
                    var parsedLevelHarga = JSON.parse(levelHarga);
    
                    if (parsedLevelHarga) {
                        try {
                            // Manipulasi string menjadi array jika perlu
                            if (typeof parsedLevelHarga === 'string') {
                                parsedLevelHarga = parsedLevelHarga.replace(/^\[|\]$/g, '').split(',');
                            }
    
                            // Ambil member yang dipilih
                            var selectedMember = memberSelect.selectedOptions[0];
                            var memberLevelInfo = selectedMember.getAttribute('data-level-info');
                            var isGuest = selectedMember.value === "Guest";
    
                            // Jika Guest, tampilkan semua harga
                            if (isGuest) {
                                displayAllPrices(parsedLevelHarga);
                            } else {
                                // Jika member tertentu, tampilkan harga sesuai dengan level_info dari member
                                if (memberLevelInfo) {
                                    console.log('Level Info untuk member ini:', memberLevelInfo); // Tampilkan level_info di console
                                    displayMemberPrices(parsedLevelHarga, memberLevelInfo);
                                }
                            }
                        } catch (error) {
                            console.error('Error processing level_harga:', error);
                        }
                    }
                }
            });
    
            // Event listener untuk perubahan pada select member
            memberSelect.addEventListener('change', function() {
    var selectedMember = memberSelect.selectedOptions[0]; // Ambil opsi yang dipilih
    console.log('Member yang dipilih:', selectedMember.value);

    // Ambil level info dari member yang dipilih
    var memberLevelInfo = selectedMember.getAttribute('data-level-info');

    // Pastikan memberLevelInfo tersedia
    if (memberLevelInfo) {
        // Perbaiki level_info dengan mengganti escape sequence \u0022 menjadi tanda kutip
        memberLevelInfo = memberLevelInfo.replace(/\\u0022/g, '"');
        
        // Hapus karakter tambahan yang tidak diinginkan seperti spasi di awal/akhir
        memberLevelInfo = memberLevelInfo.trim();

        // Lakukan logging setelah perbaikan awal
        console.log('Level Info setelah perbaikan awal:', memberLevelInfo);

        // Cek apakah level_info benar-benar string yang dimulai dengan [ dan diakhiri dengan ]
        if (memberLevelInfo.charAt(0) !== '[' || memberLevelInfo.charAt(memberLevelInfo.length - 1) !== ']') {
            console.error('Level Info tidak dalam format array JSON yang valid');
            return;
        }

        try {
            // Parse string menjadi array setelah semua perbaikan
            var parsedLevelInfo = JSON.parse(memberLevelInfo);
            console.log('Level Info setelah parsing:', parsedLevelInfo); // Tampilkan level_info setelah parsing
        } catch (error) {
            console.error('Error parsing level_info:', error);
        }

        // Trigger ulang event perubahan barang ketika member berubah
        element.dispatchEvent(new Event('change'));
    } else {
        // Jika tidak ada level info, hapus pilihan harga
        hargaSelect.innerHTML = '<option value="">~Silahkan Pilih Harga~</option>';
    }
});
        });
    </script>    

@endsection
