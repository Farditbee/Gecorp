@extends('layouts.main')

@section('title')
    Tambah Data User
@endsection

@section('content')

<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Tambah Data Toko</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('master.index')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('master.toko.index')}}">Data Toko</a></li>
                            <li class="breadcrumb-item"><a>Tambah Data Toko</a></li>
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
                        <a href="{{ route('master.user.index')}}" class="btn btn-danger">
                            <i class="ti-plus menu-icon"></i> Kembali
                        </a>
                        <!-- Input Search -->
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <form action="{{ route('master.user.store')}}" method="post" class="">
                                @csrf
                                <div class="form-group">
                                    <label for="id_toko" class=" form-control-label">Nama Toko<span style="color: red">*</span></label>
                                    <select name="id_toko" id="selector" class="form-control" tabindex="1">
                                        <option value="" required>~Silahkan Pilih Toko~</option>
                                            @foreach ($toko as $tk)
                                            <option value="{{ $tk->id }}">{{ $tk->nama_toko }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_level" class=" form-control-label">Level<span style="color: red">*</span></label>
                                    <select name="id_level" id="selectors" class="form-control" tabindex="2">
                                    <option value="" required>~Pilih~</option>
                                            @foreach ($leveluser as $lu)
                                            <option value="{{ $lu->id }}">{{ $lu->nama_level }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama" class=" form-control-label">Nama<span style="color: red">*</span></label>
                                    <input required type="text" id="nama" name="nama" placeholder="Contoh : User 1" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="email" class=" form-control-label">Email<span style="color: red">*</span></label>
                                    <input type="email" id="email" name="email" placeholder="Contoh : user123@gmail.com" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="username" class=" form-control-label">Username<span style="color: red">*</span></label>
                                    <input type="text" id="username" name="username" placeholder="Contoh : user123" class="form-control">
                                </div>
                                <label for="password" class=" form-control-label">Password<span style="color: red">*</span></label>
                                <div class="input-group">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Contoh : ********" aria-label="Recipient's username" aria-describedby="basic-addon2" >
                                        <div class="input-group-append">
                                            <button type="button" id="toggle-password" class="btn btn-outline-secondary">👁️</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="alamat" class=" form-control-label">Alamat<span style="color: red">*</span></label>
                                    <textarea name="alamat" id="alamat" rows="4" placeholder="Contoh : Jl. Nyimas Gandasari No.18 Plered - Cirebon" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp" class=" form-control-label">No HP<span style="color: red">*</span></label>
                                    <input type="number" id="no_hp" name="no_hp" placeholder="Contoh : 089xxxxxxxxx" class="form-control">
                                </div>
                                <br>
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
        <!-- [ Main Content ] end -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const element = document.getElementById('selector');
    const choices = new Choices(element, {
        removeItemButton: true, // Memungkinkan penghapusan item
        searchEnabled: true,    // Mengaktifkan pencarian
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const element = document.getElementById('selectors');
    const choices = new Choices(element, {
        removeItemButton: true, // Memungkinkan penghapusan item
        searchEnabled: true,    // Mengaktifkan pencarian
    });
});

    document.getElementById('toggle-password').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? '👁️' : '🙈'; // Mengubah ikon sesuai dengan tipe
    });
</script>

@endsection
