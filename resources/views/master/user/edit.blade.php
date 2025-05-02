@extends('layouts.main')

@section('title')
    Edit Data User
@endsection

@section('content')

    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <!-- Tombol Edit -->
                            <a href="{{ route('master.user.index') }}" class="btn btn-danger">
                                <i class="ti-plus menu-icon"></i> Kembali
                            </a>
                        </div>
                        <x-adminlte-alerts />
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <form action="{{ route('master.user.update', $user->id) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    @if (Auth::user()->id_level == 1 || Auth::user()->id_level == 2)

                                        <div class="form-group">
                                            <label for="id_toko" class="form-control-label">Nama Toko <span
                                                    style="color: red">*</span></label>
                                            <select name="id_toko" id="selector" class="form-control select2"
                                                tabindex="1">
                                                <option value="">~Pilih~</option>
                                                @foreach ($toko as $tk)
                                                    <option value="{{ $tk->id }}"
                                                        {{ $user->id_toko == $tk->id ? 'selected' : '' }}>
                                                        {{ $tk->nama_toko }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="id_level" class="form-control-label">Level <span
                                                    style="color: red">*</span></label>
                                            <select name="id_level" id="selectors" class="form-control select2"
                                                tabindex="2">
                                                <option value="">~Pilih~</option>
                                                @foreach ($leveluser as $lu)
                                                    <option value="{{ $lu->id }}"
                                                        {{ old('id_level', $user->id_level) == $lu->id ? 'selected' : '' }}>
                                                        {{ $lu->nama_level }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <input type="hidden" name="id_toko" value="{{ $user->id_toko }}">
                                        <input type="hidden" name="id_level" value="{{ $user->id_level }}">
                                    @endif

                                    <div class="form-group">
                                        <label for="nama" class=" form-control-label">Nama<span
                                                style="color: red">*</span></label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                            name="nama" value="{{ old('nama', $user->nama) }}"
                                            placeholder="Masukkan nama">
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class=" form-control-label">Email<span
                                                style="color: red">*</span></label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email', $user->email) }}"
                                            placeholder="Contoh : user@gmail.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="username" class=" form-control-label">Username<span
                                                style="color: red">*</span></label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            name="username" value="{{ old('username', $user->username) }}"
                                            placeholder="Contoh : users213">
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="form-control-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                placeholder="Kosongkan jika password tidak ada perubahan"
                                                aria-label="Recipient's username" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button type="button" id="toggle-password"
                                                    class="btn btn-outline-secondary">👁️</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="alamat" class=" form-control-label">Alamat<span
                                                style="color: red">*</span></label>
                                        <textarea name="alamat" id="alamat" rows="4" @error('alamat') is-invalid @enderror" name="alamat"
                                            class="form-control">{{ old('alamat', $user->alamat) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="no_hp" class=" form-control-label">No HP<span
                                                style="color: red">*</span></label>
                                        <input type="number" class="form-control @error('no_hp') is-invalid @enderror"
                                            name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                                            placeholder="Contoh : 089xxxxxxxxxx">
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
    </div>
@endsection

@section('js')
    <script>
        let selectOptions = [{
            id: '#selector',
            isUrl: '{{ route('master.toko') }}',
            placeholder: 'Pilih Nama Toko',
        }, {
            id: '#selectors',
            isUrl: '{{ route('master.levelUser') }}',
            placeholder: 'Pilih Level User',
            isFilter: {
                id_level: '{{ auth()->user()->id_level }}',
            },
        }];


        async function addData() {
            document.getElementById('toggle-password').addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁️' : '🙈';
            });

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
        }

        async function initPageLoad() {
            await addData();
            await selectData(selectOptions);
        }
    </script>
@endsection
