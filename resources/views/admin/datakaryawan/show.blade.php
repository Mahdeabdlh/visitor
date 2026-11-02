
@extends('layouts.dashboard')

@section('title', 'Detail Karyawan')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center
                flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Detail Karyawan</h3>
            <p class="text-muted mb-0">
                Informasi penduduk dan jabatan yang ditetapkan.
            </p>
        </div>

        <a href="{{ route('admin.datakaryawan.index') }}"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Informasi Karyawan</h5>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="text-muted small">Nama</div>
                    <div class="fw-semibold">
                        {{ $karyawan->nama_penduduk }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">NIK</div>
                    <div class="fw-semibold">{{ $karyawan->nik }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Nomor KK</div>
                    <div class="fw-semibold">{{ $karyawan->kk ?: '-' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Email</div>
                    <div class="fw-semibold">{{ $karyawan->email }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Jabatan</div>
                    <div class="fw-semibold">{{ $karyawan->nama_jabatan }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Status Penempatan</div>
                    @if ($karyawan->status_jabatan === 'Y')
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Tidak Aktif</span>
                    @endif
                </div>

                <div class="col-12">
                    <div class="text-muted small">Alamat</div>
                    <div class="fw-semibold">{{ $karyawan->alamat ?: '-' }}</div>
                </div>
            </div>

            <hr>

            <a href="{{ route(
                'admin.datakaryawan.edit',
                $karyawan->id_jabatan_penduduk
            ) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i>
                Edit Karyawan
            </a>
        </div>
    </div>
</div>
@endsection
