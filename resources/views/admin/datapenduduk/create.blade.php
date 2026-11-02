
@extends('layouts.dashboard')

@section('title', 'Tambah Data Penduduk')

@section('content')
<div class="container-fluid py-4">

    {{-- Header halaman --}}
    <div class="d-flex flex-wrap justify-content-between
                align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1">Tambah Data Penduduk</h3>
            <p class="text-muted mb-0">
                Tambahkan data penduduk baru ke dalam sistem.
            </p>
        </div>

        <a href="{{ route('admin.datapenduduk.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    {{-- Pesan validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Data belum dapat disimpan.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-person-vcard me-2"></i>
                Informasi Penduduk
            </h5>
        </div>

        <div class="card-body p-4">

            <form action="{{ route('admin.datapenduduk.store') }}"
                  method="POST">
                @csrf

                <div class="row g-3">

                    {{-- Nama --}}
                    <div class="col-md-6">
                        <label for="nama_penduduk"
                               class="form-label fw-semibold">
                            Nama Penduduk
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="nama_penduduk"
                               id="nama_penduduk"
                               class="form-control @error('nama_penduduk') is-invalid @enderror"
                               value="{{ old('nama_penduduk') }}"
                               maxlength="100"
                               required>
                        @error('nama_penduduk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div class="col-md-6">
                        <label for="nik" class="form-label fw-semibold">
                            NIK
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="nik"
                               id="nik"
                               class="form-control @error('nik') is-invalid @enderror"
                               value="{{ old('nik') }}"
                               inputmode="numeric"
                               pattern="[0-9]{16}"
                               minlength="16"
                               maxlength="16"
                               required>
                        <div class="form-text">
                            Masukkan tepat 16 digit angka.
                        </div>
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nomor KK --}}
                    <div class="col-md-6">
                        <label for="kk" class="form-label fw-semibold">
                            Nomor Kartu Keluarga (KK)
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="kk"
                               id="kk"
                               class="form-control @error('kk') is-invalid @enderror"
                               value="{{ old('kk') }}"
                               inputmode="numeric"
                               pattern="[0-9]{16}"
                               minlength="16"
                               maxlength="16"
                               required>
                        <div class="form-text">
                            Masukkan tepat 16 digit angka.
                        </div>
                        @error('kk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jenis kelamin --}}
                    <div class="col-md-6">
                        <label for="jenis_kelamin"
                               class="form-label fw-semibold">
                            Jenis Kelamin
                            <span class="text-danger">*</span>
                        </label>
                        <select name="jenis_kelamin"
                                id="jenis_kelamin"
                                class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L"
                                {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="P"
                                {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="col-12">
                        <label for="alamat" class="form-label fw-semibold">
                            Alamat
                            <span class="text-danger">*</span>
                        </label>
                        <textarea name="alamat"
                                  id="alamat"
                                  rows="3"
                                  maxlength="255"
                                  class="form-control @error('alamat') is-invalid @enderror"
                                  required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">
                            Email               
                              
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="email"
                               id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               maxlength="255"
                               autocomplete="email"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">
                            Status
                            <span class="text-danger">*</span>
                        </label>
                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Penduduk"
                                {{ old('status', 'Penduduk') === 'Penduduk' ? 'selected' : '' }}>
                                Penduduk
                            </option>
                            <option value="Admin"
                                {{ old('status') === 'Admin' ? 'selected' : '' }}>
                                Admin
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="col-12">
                        <label for="password" class="form-label fw-semibold">
                            Password
                            <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               minlength="8"
                               autocomplete="new-password"
                               required>
                        <div class="form-text">
                            Minimal 8 karakter. Password akan di-hash
                            sebelum disimpan ke database.
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                {{-- Tombol --}}
                <div class="d-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ route('admin.datapenduduk.index') }}"
                       class="btn btn-light border">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i>
                        Simpan Data
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
