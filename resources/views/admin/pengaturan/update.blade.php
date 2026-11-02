```blade
@extends('layouts.dashboard')

@section('title', 'Update Pengaturan')
@section('page-heading', 'Update Pengaturan Aplikasi')

@section('content')
<div class="container-fluid">

    {{-- Header halaman --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h4 class="fw-bold mb-1">Update Pengaturan Aplikasi</h4>
            <p class="text-muted mb-0">
                Perbarui informasi umum aplikasi dan instansi.
            </p>
        </div>

        <a href="{{ route('admin.pengaturan.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Pesan kesalahan validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Periksa kembali data berikut:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-gear me-2"></i>
                Form Pengaturan
            </h5>
        </div>

        <div class="card-body p-4">

            <form action="{{ route('admin.pengaturan.update') }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Nama aplikasi --}}
                    <div class="col-12 col-md-6">
                        <label for="nama_aplikasi" class="form-label fw-semibold">
                            Nama Aplikasi <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama_aplikasi"
                            id="nama_aplikasi"
                            class="form-control @error('nama_aplikasi') is-invalid @enderror"
                            value="{{ old('nama_aplikasi', $pengaturan->nama_aplikasi) }}"
                            maxlength="150"
                            required
                        >

                        @error('nama_aplikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama instansi --}}
                    <div class="col-12 col-md-6">
                        <label for="nama_instansi" class="form-label fw-semibold">
                            Nama Instansi
                        </label>

                        <input
                            type="text"
                            name="nama_instansi"
                            id="nama_instansi"
                            class="form-control @error('nama_instansi') is-invalid @enderror"
                            value="{{ old('nama_instansi', $pengaturan->nama_instansi) }}"
                            maxlength="150"
                        >

                        @error('nama_instansi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label fw-semibold">
                            Email Instansi
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $pengaturan->email) }}"
                            maxlength="150"
                        >

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Telepon --}}
                    <div class="col-12 col-md-6">
                        <label for="telepon" class="form-label fw-semibold">
                            Nomor Telepon
                        </label>

                        <input
                            type="text"
                            name="telepon"
                            id="telepon"
                            class="form-control @error('telepon') is-invalid @enderror"
                            value="{{ old('telepon', $pengaturan->telepon) }}"
                            maxlength="30"
                        >

                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="col-12">
                        <label for="alamat" class="form-label fw-semibold">
                            Alamat Instansi
                        </label>

                        <textarea
                            name="alamat"
                            id="alamat"
                            rows="3"
                            maxlength="2000"
                            class="form-control @error('alamat') is-invalid @enderror"
                            placeholder="Masukkan alamat lengkap instansi"
                        >{{ old('alamat', $pengaturan->alamat) }}</textarea>

                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label for="deskripsi" class="form-label fw-semibold">
                            Deskripsi Aplikasi
                        </label>

                        <textarea
                            name="deskripsi"
                            id="deskripsi"
                            rows="4"
                            maxlength="2000"
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            placeholder="Jelaskan fungsi atau tujuan aplikasi"
                        >{{ old('deskripsi', $pengaturan->deskripsi) }}</textarea>

                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                {{-- Tombol aksi --}}
                <div class="d-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ route('admin.pengaturan.index') }}"
                       class="btn btn-light border">
                        Batal
                    </a>

                    <button type="reset" class="btn btn-outline-warning">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset Form
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
```