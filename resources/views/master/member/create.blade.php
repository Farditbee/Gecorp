<title>Tambah Data Member - Gecorp</title>
@extends('layouts.main')

@section('content')

<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header">
                    <div class="page-title">
                        <h1 class="card-title"><strong>Tambah Data - Member</strong></h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('master.index')}}">Dashboard</a></li>
                            <li><a href="{{ route('master.user.index')}}">Data Member</a></li>
                            <li class="active">Tambah Data Member</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- Content -->
        <div class="content">
            <x-adminlte-alerts />
            <!-- Animated -->
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('master.member.index')}}" class="btn btn-danger"></i> Kembali</a>
                            </div>
                            <div class="card-body">
                                {{-- Content --}}
                                <div class="card-body card-block">
                                    <form action="#" method="post" class="">
                                        @csrf
                                        <div class="form-group">
                                            <label for="id_toko" class=" form-control-label">Nama Toko</label>
                                            <select name="id_toko" id="id_toko" data-placeholder="Choose a Country..." class="standardSelect" tabindex="1">
                                                <option value="" required>~Pilih~</option>
                                                    @foreach ($toko as $tk)
                                                    <option value="{{ $tk->id }}" data-wilayah="{{ $tk->wilayah }}">{{ $tk->nama_toko }}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_barang" class=" form-control-label">Jenis Barang</label>
                                            <ul class="list-group list-group-flush">
                                                @foreach ($jenis_barang as $jb)
                                                <li class="list-group-item">
                                                    <h4><i class="fa fa-dot-circle-o"></i> {{ $jb->nama_jenis_barang}}
                                                        <select style="width: 30%; margin-left:100" name="" id="">
                                                            @foreach ($levelharga as $lh )
                                                            <option value="id">{{$lh->nama_level_harga}}</option>
                                                            @endforeach
                                                        </select>
                                                    </h4>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama" class=" form-control-label">Nama Member<span style="color: red">*</span></label>
                                            <input type="text" id="nama" name="nama" placeholder="Contoh : User 1" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat" class=" form-control-label">Alamat<span style="color: red">*</span></label>
                                            <textarea name="alamat" id="alamat" rows="4" placeholder="Contoh : Jl. Nyimas Gandasari No.18 Plered - Cirebon" class="form-control"></textarea>
                                        </div>

                                        <br>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-dot-circle-o"></i> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                {{-- end Content --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- .animated -->
        </div>
        <!-- /.content -->

        <!-- Footer -->
        <script>
            $(document).ready(function() {
                $('#id_toko').change(function() {
                    var idToko = $(this).val();
                    console.log("ID Toko yang dipilih:", idToko); // Log ID toko yang dipilih

                    if (idToko) {
                        $.ajax({
                            url: '/admin/get-wilayah',
                            type: 'GET',
                            data: { id_toko: idToko },
                            success: function(data) {
                                console.log("Data diterima dari server:", data); // Log respons dari server
                                if (data.success) {
                                    $('#wilayah').val(data.wilayah); // Isi otomatis wilayah
                                } else {
                                    alert('Wilayah tidak ditemukan untuk toko ini.');
                                    $('#wilayah').val(''); // Kosongkan input jika wilayah tidak ditemukan
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("Error:", error); // Log error jika request gagal
                                console.log("XHR:", xhr.responseText); // Log respons jika error
                                alert('Terjadi kesalahan saat mengambil wilayah.');
                                $('#wilayah').val('');
                            }
                        });
                    } else {
                        $('#wilayah').val(''); // Kosongkan input wilayah jika tidak ada nama toko yang dipilih
                    }
                });
            });
        </script>

<script>
    $(document).ready(function() {
        $('#id_toko').change(function() {
            var wilayah = $(this).find(':selected').data('wilayah'); // Ambil data-wilayah dari opsi yang dipilih
            console.log("Wilayah yang dipilih:", wilayah);
            $('#wilayah').val(wilayah || ''); // Isi input wilayah atau kosongkan jika tidak ada
        });
    });
</script>



@endsection
