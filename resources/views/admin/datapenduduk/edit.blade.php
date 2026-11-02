
@extends('layouts.dashboard')

@section('content')
<div class="container py-4">

    {{-- Header halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Edit Data Penduduk</h2>
            <p class="text-muted mb-0">
                Silakan perbarui informasi penduduk.
            </p>
        </div>

        <a href="{{ route('admin.datapenduduk.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </div>

    {{-- Pesan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali data Anda:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form edit penduduk --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <form
                action="{{ route('admin.datapenduduk.edit', $penduduk->id_penduduk) }}"
                method="POST">

                @csrf
                @method('PUT')

                {{-- Nama penduduk --}}
                <div class="mb-3">
                    <label for="nama_penduduk" class="form-label">
                        Nama Penduduk
                    </label>

                    <input
                        type="text"
                        id="nama_penduduk"
                        name="nama_penduduk"
                        class="form-control @error('nama_penduduk') is-invalid @enderror"
                        value="{{ old('nama_penduduk', $penduduk->nama_penduduk) }}"
                        maxlength="100"
                        required
                    >

                    @error('nama_penduduk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- NIK --}}
                <div class="mb-3">
                    <label for="nik" class="form-label">NIK</label>

                    <input
                        type="text"
                        id="nik"
                        name="nik"
                        class="form-control @error('nik') is-invalid @enderror"
                        value="{{ old('nik', $penduduk->nik) }}"
                        minlength="16"
                        maxlength="16"
                        inputmode="numeric"
                        required
                    >

                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nomor KK --}}
                <div class="mb-3">
                    <label for="kk" class="form-label">
                        Nomor Kartu Keluarga
                    </label>

                    <input
                        type="text"
                        id="kk"
                        name="kk"
                        class="form-control @error('kk') is-invalid @enderror"
                        value="{{ old('kk', $penduduk->kk) }}"
                        minlength="16"
                        maxlength="16"
                        inputmode="numeric"
                        required
                    >

                    @error('kk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jenis kelamin --}}
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">
                        Jenis Kelamin
                    </label>

                    <select
                        id="jenis_kelamin"
                        name="jenis_kelamin"
                        class="form-select @error('jenis_kelamin') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Jenis Kelamin --</option>

                        <option value="L"
                            @selected(old('jenis_kelamin', $penduduk->jenis_kelamin) === 'L')>
                            Laki-laki
                        </option>

                        <option value="P"
                            @selected(old('jenis_kelamin', $penduduk->jenis_kelamin) === 'P')>
                            Perempuan
                        </option>
                    </select>

                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>

                    <textarea
                        id="alamat"
                        name="alamat"
                        rows="3"
                        class="form-control @error('alamat') is-invalid @enderror"
                        required
                    >{{ old('alamat', $penduduk->alamat) }}</textarea>

                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Username --}}
                <div class="mb-3">
                    <label for="username" class="form-label">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username', $penduduk->username) }}"
                        required
                    >

                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>

                    <select
                        id="status"
                        name="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required
                    >
                        <option value="Admin"
                            @selected(old('status', $penduduk->status) === 'Admin')>
                            Admin
                        </option>

                        <option value="Penduduk"
                            @selected(old('status', $penduduk->status) === 'Penduduk')>
                            Penduduk
                        </option>
                    </select>

                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password: opsional ketika edit --}}
                <div class="mb-3">
                    <label for="password" class="form-label">
                        Password Baru
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        minlength="8"
                        autocomplete="new-password"
                    >

                    <div class="form-text">
                        Kosongkan jika tidak ingin mengubah password.
                    </div>

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol aksi --}}
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.datapenduduk.index') }}"
                       class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
