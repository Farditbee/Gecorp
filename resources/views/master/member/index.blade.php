<title>Data Member - Gecorp</title>
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
                            <h5 class="m-b-10">Data Member</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('master.index')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a>Data Member</a></li>
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
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Tambah</button>
                        <!-- Input Search -->
                        <form class="d-flex" method="GET" action="{{ route('master.member.index') }}">
                            <input class="form-control me-2" id="search" type="search" name="search" placeholder="Cari Member" aria-label="Search">
                        </form>
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table table-striped" id="jsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Member</th>
                                        <th>Nama Toko</th>
                                        <th>Level</th>
                                        <th>No. Hp</th>
                                        <th>Alamat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @forelse ($member as $mbr)
                                    <tr>
                                        <td>{{$no++}}</td>
                                        <td>{{$mbr->nama_member}}</td>
                                        <td>{{$mbr->toko->nama_toko}}</td>
                                        <td>
                                            @if (!empty($mbr->level_data))
                                                @foreach ($mbr->level_data as $data)
                                                    <p style="margin-top: 10px;">{{ $data['jenis_barang'] }} : {{ $data['level_harga'] }}</p>
                                                @endforeach
                                            @else
                                                <span>Tidak ada level</span>
                                            @endif
                                        </td>
                                        <td>{{$mbr->no_hp}}</td>
                                        <td>{{$mbr->alamat}}</td>
                                        <form onsubmit="return confirm('Ingin menghapus Data ini ? ?');" action="{{ route('master.member.delete', $mbr->id)}}" method="post">
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editMemberModal{{ $mbr->id }}" data-id="{{ $mbr->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                                {{-- <a href{{ route('master.member.index', $mbr->id)}} type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target=".bds-example-modal-lg"><i class="fa fa-edit" style="color: white;"></i></a> --}}
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash menu-icon"></i></button>
                                            </td>
                                        </form>
                                    </tr>
                                    @empty
                                    <td colspan="7" style="text-align: center"><h4><span class="badge badge-light-danger" style="margin:10px;">Tidak Ada Data</span></h4></td>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    Menampilkan <span id="current-count">0</span> data dari <span id="total-count">0</span> total data.
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

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title h4" id="myLargeModalLabel">Tambah Data Member</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <form action="{{ route('master.member.store')}}" method="post" class="">
                                        @csrf
                                        <div class="form-group">
                                            <label for="id_toko" class="form-control-label">Nama Toko<span style="color: red">*</span></label>
                                            <select id="id_toko" name="id_toko" class="form-control id-toko">
                                                <option value="" selected>~Silahkan Pilih Toko~</option> <!-- Selalu dipilih secara default -->
                                                @foreach ($toko as $tk)
                                                <option value="{{ $tk->id }}">
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
                                                        <select name="level_harga[{{ $jb->id }}]" id="level_harga_{{ $jb->id }}" class="form-control">
                                                            <option value="" selected>~Silahkan Pilih Toko Dahulu~</option>
                                                            @foreach($levelharga as $lh)
                                                            
                                                            @endforeach
                                                        </select>
                                                    </h6>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="nama_member" class=" form-control-label">Nama Member<span style="color: red">*</span></label>
                                            <input type="text" id="nama_member" name="nama_member" placeholder="Contoh : Member 1" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="no_hp" class=" form-control-label">No HP<span style="color: red">*</span></label>
                                            <input type="number" id="no_hp" name="no_hp" placeholder="Contoh : member123@gmail.com" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat" class=" form-control-label">Alamat<span style="color: red">*</span></label>
                                            <textarea name="alamat" id="alamat" rows="4" placeholder="Contoh : Jl. Nyimas Gandasari No.18 Plered - Cirebon" class="form-control"></textarea>
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
@foreach($member as $mbr)
<div class="modal fade bd-example-modal-lg" id="editMemberModal{{ $mbr->id }}" tabindex="-1" role="dialog" aria-labelledby="editMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('master.member.update', $mbr->id)}}" method="post">
            @csrf
            @method('put')
            <div class="modal-content">
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
                        <input type="text" name="nama_member" class="form-control" value="{{ $mbr->nama_member }}" required>
                    </div>

                    <!-- Nama Toko -->
                    <div class="form-group">
                        <label for="id_toko_{{ $mbr->id }}" class=" form-control-label">Nama Toko<span style="color: red">*</span></label>
                        <select name="id_toko" id="selectors" class="form-control">
                            <option value="" required>~Silahkan Pilih Toko~</option>
                                @foreach ($toko as $tk)
                                <option value="{{ $tk->id }}" {{ $mbr->id_toko == $tk->id ? 'selected' : '' }}>
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
                                    <select name="level_harga[{{ $jb->id }}]" id="level_harga_{{ $jb->id }}" class="form-control">
                                        <option value="">~Silahkan Pilih~</option>
                                        @foreach($levelharga as $lh)
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
                        <input type="number" name="no_hp" class="form-control" value="{{ $mbr->no_hp }}" required>
                    </div>

                    <!-- Alamat -->
                    <div class="form-group">
                        <label for="alamat" class=" form-control-label">Alamat<span style="color: red">*</span></label>
                        <textarea name="alamat" id="alamat" rows="4" class="form-control">{{ $mbr->alamat}}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>

    document.addEventListener('DOMContentLoaded', function () {
    const element = document.getElementById('selector');
    const choices = new Choices(element, {
        removeItemButton: true, // Memungkinkan penghapusan item
        searchEnabled: true,    // Mengaktifkan pencarian
    });
});

document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('id_toko').addEventListener('change', function() {
        var idToko = this.value; // Ambil nilai id_toko yang dipilih

// Loop melalui semua jenis barang
@foreach ($jenis_barang as $jb)
(function(jbId) {
    var levelHargaDropdown = document.getElementById('level_harga_' + jbId); // Ambil elemen dropdown level_harga untuk jenis barang ini

    // Reset isi dropdown level_harga
    levelHargaDropdown.innerHTML = '<option value="">~Silahkan Pilih~</option>';

    if (idToko) { // Pastikan ada id_toko yang dipilih
        // Buat request AJAX untuk mendapatkan level_harga
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/admin/get-level-harga/' + idToko, true);
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Respon dari server:', xhr.responseText);
                
                var data = JSON.parse(xhr.responseText);
                
                // Cek apakah data level harga ada
                if (data.length > 0) {
                    // Loop melalui data level harga yang diterima dan tambahkan opsi ke dropdown
                    data.forEach(function(level) {
                        var option = document.createElement('option');
                        option.value = level.id; // ID dari level harga
                        option.text = level.nama_level_harga; // Nama dari level harga
                        levelHargaDropdown.appendChild(option);
                    });
                } else {
                    // Jika tidak ada level harga, tambahkan opsi "Tidak ada Level"
                    var option = document.createElement('option');
                    option.value = ""; // Value kosong
                    option.text = "Tidak ada Level"; // Teks untuk opsi
                    levelHargaDropdown.appendChild(option);
                }
            } else {
                console.error('Error mendapatkan data dari server');
            }
        };

        xhr.send(); // Kirim request
    }
})({{ $jb->id }});
@endforeach
});

    const element = document.getElementById('selectors');
    const choices = new Choices(element, {
        removeItemButton: true, // Memungkinkan penghapusan item
        searchEnabled: true,    // Mengaktifkan pencarian
    });

    document.addEventListener('DOMContentLoaded', function () {
    // Ambil semua tombol dengan atribut data-id
    var editButtons = document.querySelectorAll('button[data-id]');

    // Tambahkan event listener ke setiap tombol edit
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Ambil id_member dari atribut data-id
            var idMember = this.getAttribute('data-id');

            // Tampilkan id_member di console log
            console.log('Tombol Edit diklik, ID Member:', idMember);
        });
    });
});

function editMember(id) {
    console.log('ID Member yang diedit:', id);  // Log id_member yang sedang diedit

    $.ajax({
        url: '/member/' + id + '/edit',  // URL menuju method edit
        method: 'GET',
        success: function(data) {
            console.log('ID Toko yang dipilih:', data.id_toko);  // Log id_toko yang dipilih
            
            // Isi form modal dengan data dari server
            $('#edit_nama_member').val(data.nama_member);
            $('#edit_no_hp').val(data.no_hp);
            $('#edit_alamat').val(data.alamat);
            $('#edit_toko').val(data.id_toko);

            // Muat level harga berdasarkan id_toko yang sudah dipilih tanpa reset
            loadLevelHargaEditWithoutReset(data.id_toko, data.level_info);

            // Tampilkan modal
            $('#editMemberModal').modal('show');  // Tampilkan modal jika belum otomatis
        },
        error: function(xhr) {
            console.log('Error:', xhr);
        }
    });
}

});
</script>

@endsection
