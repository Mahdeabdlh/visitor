
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Detail Jadwal Kunjungan</h4>
            <p class="text-muted mb-0">Informasi jadwal layanan.</p>
        </div>
        <a href="{{ route('admin.jadwalkunjungan.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <table class="table">
                <tr>
                    <th style="width: 220px">Tanggal</th>
                    <td>{{ $jadwal->tanggal->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Jam mulai</th>
                    <td>{{ substr($jadwal->jam_aktif, 0, 5) }}</td>
                </tr>
                <tr>
                    <th>Jam selesai</th>
                    <td>{{ substr($jadwal->jam_non_aktif, 0, 5) }}</td>
                </tr>
                <tr>
                    <th>Petugas</th>
                    <td>{{ $jadwal->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>{{ $jadwal->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($jadwal->status === 'Y')
                            <span class="badge text-bg-success">Aktif</span>
                        @else
                            <span class="badge text-bg-secondary">Tidak aktif</span>
                        @endif
                    </td>
                </tr>
            </table>

            <a href="{{ route('admin.jadwalkunjungan.edit', $jadwal->id_settings_kunjungan) }}"
               class="btn btn-warning">
                <i class="bi bi-pencil-square me-1"></i> Edit Jadwal
            </a>
        </div>
    </div>
</div>
@endsection
