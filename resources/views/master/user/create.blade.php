@extends('layouts.main')

@section('title')
    Tambah Data User
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <a href="{{ route('master.user.index') }}" class="btn btn-danger">
                                <i class="ti-plus menu-icon"></i> Kembali
                            </a>
                        </div>
                        <x-adminlte-alerts />
                        <div class="card-body table-border-style">
                            <form action="{{ route('master.user.store') }}" method="post" class="">
                                <div class="table-responsive">
                                    @csrf
                                    <div class="form-group">
                                        <label for="id_toko" class=" form-control-label">Nama Toko<span
                                                style="color: red">*</span></label>
                                        <select name="id_toko" id="selector" class="form-control" tabindex="1">
                                            <option value="" required>~Silahkan Pilih Toko~</option>
                                            @foreach ($toko as $tk)
                                                <option value="{{ $tk->id }}">{{ $tk->nama_toko }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_level" class="form-control-label">Level<span style="color: red">*</span></label>
                                        <select name="id_level" id="selectors" class="form-control" tabindex="2">
                                            <option value="" required>~Pilih~</option>

                                            @if (Auth::user()->id_level == 3)
                                                @foreach ($leveluser->where('id', 4) as $lu)
                                                    <option value="{{ $lu->id }}">{{ $lu->nama_level }}</option>
                                                @endforeach
                                            @else
                                                @foreach ($leveluser as $lu)
                                                    <option value="{{ $lu->id }}">{{ $lu->nama_level }}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="nama" class=" form-control-label">Nama<span
                                                style="color: red">*</span></label>
                                        <input required type="text" id="nama" name="nama"
                                            placeholder="Contoh : User 1" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class=" form-control-label">Email<span
                                                style="color: red">*</span></label>
                                        <input type="email" id="email" name="email"
                                            placeholder="Contoh : user123@gmail.com" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="username" class=" form-control-label">Username<span
                                                style="color: red">*</span></label>
                                        <input type="text" id="username" name="username" placeholder="Contoh : user123"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class=" form-control-label">Password<span
                                                style="color: red">*</span></label>
                                        <div class="input-group">
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="Contoh : ********" aria-label="Recipient's username"
                                                aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button type="button" id="toggle-password"
                                                    class="btn btn-outline-secondary">👁️</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="alamat" class=" form-control-label">Alamat<span
                                            style="color: red">*</span></label>
                                    <textarea name="alamat" id="alamat" rows="4"
                                        placeholder="Contoh : Jl. Nyimas Gandasari No.18 Plered - Cirebon" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp" class=" form-control-label">No HP<span
                                            style="color: red">*</span></label>
                                    <input type="number" id="no_hp" name="no_hp" placeholder="Contoh : 089xxxxxxxxx"
                                        class="form-control">
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('selector');
            const choices = new Choices(element, {
                removeItemButton: true,
                searchEnabled: true,
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('selectors');
            const choices = new Choices(element, {
                removeItemButton: true,
                searchEnabled: true,
            });
        });

        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });

        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const passwordWarning = document.createElement('small');
            passwordWarning.style.marginTop = '5px';
            passwordWarning.style.display = 'block';

            const inputGroup = passwordInput.parentNode.parentNode;
            inputGroup.appendChild(passwordWarning);

            const form = passwordInput.closest('form');

            passwordInput.addEventListener('input', function() {
                const passwordValue = passwordInput.value;
                const hasNumber = /\d/.test(passwordValue);

                if (passwordValue.length < 8) {
                    passwordWarning.innerHTML = '❌ Password harus memiliki minimal 8 karakter.';
                    passwordWarning.style.color = 'red';
                    passwordWarning.style.display = 'block';
                } else if (!hasNumber) {
                    passwordWarning.innerHTML = '❌ Password harus memiliki minimal 1 angka.';
                    passwordWarning.style.color = 'red';
                    passwordWarning.style.display = 'block';
                } else {
                    passwordWarning.innerHTML = '✅ Password valid';
                    passwordWarning.style.color = 'green';
                    passwordWarning.style.display = 'block';
                }
            });

            form.addEventListener('submit', function(event) {
                const passwordValue = passwordInput.value;
                const hasNumber = /\d/.test(passwordValue);

                if (passwordValue.length < 8 || !hasNumber) {
                    event.preventDefault();
                    const errorMessage = passwordValue.length < 8 ?
                        '❌ Password harus memiliki minimal 8 karakter.' :
                        '❌ Password harus memiliki minimal 1 angka.';
                    passwordWarning.innerHTML = errorMessage;
                    passwordWarning.style.color = 'red';
                    passwordWarning.style.display = 'block';
                    notificationAlert('error', 'Pemberitahuan', errorMessage);
                }
            });
        });
    </script>
@endsection
