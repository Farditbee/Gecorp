<title>Edit Data User - Gecorp</title>
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
                            <h5 class="m-b-10">Edit Data User</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('master.index')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('master.user.index')}}">Data User</a></li>
                            <li class="breadcrumb-item"><a>Edit Data User</a></li>
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
                        <!-- Tombol Edit -->
                        <a href="{{ route('master.user.index')}}" class="btn btn-danger">
                            <i class="ti-plus menu-icon"></i> Kembali
                        </a>
                        <!-- Input Search -->
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <form action="{{ route('master.user.update', $user->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="id_toko" class=" form-control-label">Nama Toko<span style="color: red">*</span></label>
                                    <select name="id_toko" id="selector" class="form-control" tabindex="1">
                                        <option value="">~Pilih~</option>
                                            @foreach ($toko as $tk)
                                            <option value="{{ $tk->id }}" {{ $user->id_toko == $tk->id ? 'selected' : '' }}>{{ $tk->nama_toko }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_level" class=" form-control-label">Level<span style="color: red">*</span></label>
                                    <select name="id_level" id="selectors" class="form-control" tabindex="1">
                                        <option value="">~Pilih~</option>
                                            @foreach ($leveluser as $lu)
                                            <option value="{{ $lu->id }}" {{ old('id_level', $user->id_level) == "$lu->id" ? "selected" : "" }}>{{ $lu->nama_level }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama" class=" form-control-label">Nama<span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $user->nama) }}" placeholder="Masukkan nama">
                                </div>
                                <div class="form-group">
                                    <label for="email" class=" form-control-label">Email<span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" placeholder="Contoh : user@gmail.com">
                                </div>
                                <div class="form-group">
                                    <label for="username" class=" form-control-label">Username<span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" placeholder="Contoh : users213">
                                </div>

                                <label for="password" class=" form-control-label">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password akan sama jika tidak diubah" aria-label="Recipient's username" aria-describedby="basic-addon2" >
                                        <div class="input-group-append">
                                            <button type="button" id="toggle-password" class="btn btn-outline-secondary">👁️</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="alamat" class=" form-control-label">Alamat<span style="color: red">*</span></label>
                                    <textarea name="alamat" id="alamat" rows="4" @error('alamat') is-invalid @enderror" name="alamat" class="form-control">{{ old('alamat', $user->alamat) }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp" class=" form-control-label">No HP<span style="color: red">*</span></label>
                                    <input type="number" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="Contoh : 089xxxxxxxxxx">
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
</script>

@endsection
