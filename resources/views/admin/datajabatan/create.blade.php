
@extends('layouts.dashboard')

@section('title', 'Tambah Jabatan')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Tambah Jabatan</h3>
        <p class="text-muted mb-0">
            Masukkan nama jabatan yang ingin ditambahkan.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Form Jabatan</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.datajabatan.store') }}"
                  method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nama_jabatan" class="form-label">
                        Nama Jabatan
                    </label>

                    <input type="text"
                           id="nama_jabatan"
                           name="nama_jabatan"
                           class="form-control @error('nama_jabatan') is-invalid @enderror"
                           value="{{ old('nama_jabatan') }}"
                           placeholder="Contoh: Kepala Desa"
                           maxlength="100"
                           required>

                    @error('nama_jabatan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i>
                        Simpan
                    </button>

                    <a href="{{ route('admin.datajabatan.index') }}"
                       class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
