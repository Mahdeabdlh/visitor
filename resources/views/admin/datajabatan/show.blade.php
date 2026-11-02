
@extends('layouts.dashboard')

@section('title', 'Detail Jabatan')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Detail Jabatan</h3>
            <p class="text-muted mb-0">
                Informasi jabatan yang dipilih.
            </p>
        </div>

        <a href="{{ route('admin.datajabatan.index') }}"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Informasi Jabatan</h5>
        </div>

        <div class="card-body">
            <div class="mb-4">
                <div class="text-muted small mb-1">ID Jabatan</div>
                <div class="fw-semibold">
                    {{ $jabatan->id_jabatan }}
                </div>
            </div>

            <div class="mb-4">
                <div class="text-muted small mb-1">Nama Jabatan</div>
                <div class="fw-semibold fs-5">
                    {{ $jabatan->nama_jabatan }}
                </div>
            </div>

            <a href="{{ route(
                'admin.datajabatan.edit',
                $jabatan->id_jabatan
            ) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i>
                Edit Jabatan
            </a>
        </div>
    </div>
</div>
@endsection
