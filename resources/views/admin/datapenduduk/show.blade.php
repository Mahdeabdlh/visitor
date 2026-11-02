
@extends('layouts.dashboard')

@section('title', 'Detail Data Penduduk')

@section('content')
<div class="container-fluid py-4">

    {{-- Header halaman --}}
    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Detail Data Penduduk</h3>
            <p class="text-muted mb-0">
                Informasi lengkap penduduk yang terdaftar.
            </p>
        </div>

        <a href="{{ route('admin.datapenduduk.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Daftar
        </a>
    </div>

    {{-- Kartu detail --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success-subtle
                            text-success d-flex align-items-center
                            justify-content-center"
                     style="width: 55px; height: 55px;">
                    <i class="bi bi-person-vcard fs-3"></i>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        {{ $penduduk->nama_penduduk }}
                    </h5>
                    <span class="text-muted small">
                        ID Penduduk: {{ $penduduk->id_penduduk }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-4">

            <h6 class="fw-bold mb-3">
                <i class="bi bi-person-lines-fill me-2"></i>
                Informasi Pribadi
            </h6>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <tbody>
                        <tr>
                            <th class="table-light" style="width: 35%;">
                                Nama Penduduk
                            </th>
                            <td>{{ $penduduk->nama_penduduk }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">NIK</th>
                            <td>{{ $penduduk->nik }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Nomor KK</th>
                            <td>{{ $penduduk->kk }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Jenis Kelamin</th>
                            <td>
                                @if ($penduduk->jenis_kelamin === 'L')
                                    Laki-laki
                                @elseif ($penduduk->jenis_kelamin === 'P')
                                    Perempuan
                                @else
                                    -
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="table-light">Alamat</th>
                            <td>{{ $penduduk->alamat }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h6 class="fw-bold mt-4 mb-3">
                <i class="bi bi-shield-lock me-2"></i>
                Informasi Akun
            </h6>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <tbody>
                        <tr>
                            <th class="table-light" style="width: 35%;">
                                Username
                            </th>
                            <td>{{ $penduduk->username }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Status</th>
                            <td>
                                @if ($penduduk->status === 'Admin')
                                    <span class="badge bg-primary">
                                        Admin
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        Penduduk
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Tombol tindakan --}}
            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.datapenduduk.index') }}"
                   class="btn btn-light border">
                    Tutup
                </a>

                <a href="{{ route('admin.datapenduduk.edit', $penduduk->id_penduduk) }}"
                   class="btn btn-warning">
                    <i class="bi bi-pencil-square me-1"></i>
                    Edit Data
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
