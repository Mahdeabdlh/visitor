
@extends('layouts.dashboard')

@section('title', 'Edit Jabatan')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Edit Jabatan</h3>
        <p class="text-muted mb-0">
            Perbarui informasi jabatan yang dipilih.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Form Edit Jabatan</h5>
        </div>

        <div class="card-body">
            <form action="{{ route(
                'admin.datajabatan.update',
                $jabatan->id_jabatan
            ) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama_jabatan" class="form-label">
                        Nama Jabatan
                    </label>

                    <input type="text"
                           id="nama_jabatan"
                           name="nama_jabatan"
                           class="form-control @error('nama_jabatan') is-invalid @enderror"
                           value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}"
                           maxlength="100"
                           required>

                    @error('nama_jabatan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i>
                        Perbarui
                    </button>

                    <a href="{{ route('admin.datajabatan.index') }}"
                       class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
