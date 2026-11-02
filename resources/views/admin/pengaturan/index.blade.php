@extends('layouts.dashboard')

@section('title', 'Pengaturan Aplikasi')
@section('page-heading', 'Pengaturan Aplikasi')

@section('content')
    <div class="container-fluid">

        {{-- Informasi halaman --}}
        <div class="mb-4">
            <h4 class="mb-1">Pengaturan Aplikasi</h4>
            <p class="text-muted mb-0">
                Kelola informasi umum aplikasi dan instansi.
            </p>
        </div>

        <a href="{{ route('admin.pengaturan.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i>
            Update Pengaturan
        </a>

        {{-- Pesan berhasil --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        {{-- Pesan kesalahan --}}
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Pengaturan belum berhasil disimpan.</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-12 col-xl-9">

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-gear me-2"></i>
                            Informasi Umum
                        </h5>
                    </div>

                    <div class="card-body">

                        <form action="{{ route('admin.pengaturan.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Nama aplikasi --}}
                            <div class="mb-3">
                                <label for="nama_aplikasi" class="form-label">
                                    Nama Aplikasi
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="nama_aplikasi" id="nama_aplikasi"
                                    class="form-control @error('nama_aplikasi') is-invalid @enderror"
                                    value="{{ old('nama_aplikasi', $pengaturan->nama_aplikasi) }}" maxlength="150" required>

                                @error('nama_aplikasi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Nama instansi --}}
                            <div class="mb-3">
                                <label for="nama_instansi" class="form-label">
                                    Nama Instansi
                                </label>

                                <input type="text" name="nama_instansi" id="nama_instansi"
                                    class="form-control @error('nama_instansi') is-invalid @enderror"
                                    value="{{ old('nama_instansi', $pengaturan->nama_instansi) }}" maxlength="150">

                                @error('nama_instansi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div class="mb-3">
                                <label for="alamat" class="form-label">
                                    Alamat Instansi
                                </label>

                                <textarea name="alamat" id="alamat" rows="3" maxlength="2000"
                                    class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $pengaturan->alamat) }}</textarea>

                                @error('alamat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    Email Instansi
                                </label>

                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $pengaturan->email) }}" maxlength="150">

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Telepon --}}
                            <div class="mb-3">
                                <label for="telepon" class="form-label">
                                    Nomor Telepon
                                </label>

                                <input type="text" name="telepon" id="telepon"
                                    class="form-control @error('telepon') is-invalid @enderror"
                                    value="{{ old('telepon', $pengaturan->telepon) }}" maxlength="30">

                                @error('telepon')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="mb-4">
                                <label for="deskripsi" class="form-label">
                                    Deskripsi Aplikasi
                                </label>

                                <textarea name="deskripsi" id="deskripsi" rows="4" maxlength="2000"
                                    class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $pengaturan->deskripsi) }}</textarea>

                                @error('deskripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Tombol --}}
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>
                                    Simpan Pengaturan
                                </button>

                                <a href="{{ route('admin.pengaturan.index') }}" class="btn btn-outline-secondary">
                                    Batal
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>

            {{-- Informasi bantuan --}}
            <div class="col-12 col-xl-3 mt-3 mt-xl-0">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-info-circle me-2"></i>
                            Informasi
                        </h6>

                        <p class="text-muted small mb-0">
                            Informasi yang disimpan di halaman ini digunakan
                            sebagai konfigurasi umum aplikasi. Pastikan nama
                            aplikasi dan informasi instansi sudah benar sebelum
                            menyimpan perubahan.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection