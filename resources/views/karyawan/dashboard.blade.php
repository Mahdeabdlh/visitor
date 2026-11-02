@extends('layouts.dashboard')

@section('title', 'Dashboard Karyawan')
@section('page-heading', 'Dashboard Karyawan')

@section('content')
@php
    $penduduk = auth()->guard('web')->user();
    $namaKaryawan = $penduduk->nama_penduduk ?? 'Karyawan';
    
    try {
        $jabatanList = \App\Models\JabatanPenduduk::with('jabatan')
            ->where('id_penduduk', $penduduk->id_penduduk ?? 0)
            ->where('status', 'Y')
            ->get();
        $jabatanIds = $jabatanList->pluck('id_jabatan_penduduk');
        
        $totalKegiatan = \App\Models\SettingsKegiatan::whereIn('id_jabatan_penduduk', $jabatanIds)->count();
        $jadwalKegiatan = \App\Models\SettingsKegiatan::whereIn('id_jabatan_penduduk', $jabatanIds)
            ->orderBy('tanggal_awal', 'desc')
            ->take(5)
            ->get();
    } catch (\Throwable $e) {
        $jabatanList = collect();
        $totalKegiatan = 0;
        $jadwalKegiatan = collect();
    }
@endphp

<div class="container-fluid px-2 px-sm-3 px-lg-4 py-3 py-lg-4">
    {{-- HEADER KARYAWAN --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                    <i class="bi bi-person-badge"></i> Panel Karyawan / Pegawai
                </span>
                <span class="text-muted small d-none d-md-inline">• {{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="h3 fw-bold mb-1 text-dark">Selamat Datang, {{ $namaKaryawan }}</h1>
            <p class="text-muted small mb-0">Pantau jadwal kegiatan pelayanan dan agenda kedinasan Anda.</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('karyawan.jadwal_kegiatan.index') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-calendar-event"></i>
                <span>Lihat Semua Jadwal</span>
            </a>
        </div>
    </div>

    {{-- KARTU INFORMASI JABATAN & STATISTIK --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white">
                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Jabatan Ditugaskan</span>
                <div class="d-flex flex-wrap gap-1 mt-2">
                    @forelse ($jabatanList as $jp)
                        <span class="badge bg-primary px-2.5 py-1.5 rounded-pill fw-medium fs-6">
                            <i class="bi bi-award me-1"></i> {{ $jp->jabatan->nama_jabatan ?? 'Jabatan' }}
                        </span>
                    @empty
                        <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1.5 rounded-pill">Belum ada penugasan jabatan</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white">
                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Agenda Kegiatan</span>
                <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalKegiatan) }}</h3>
                <small class="text-muted mt-2 d-block">Kegiatan pelayanan tercatat di sistem</small>
            </div>
        </div>

        <div class="col-12 col-md-12 col-xl-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white">
                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Status Kehadiran</span>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="status-dot bg-success d-inline-block rounded-circle" style="width: 10px; height: 10px;"></span>
                    <span class="fw-semibold text-success">Aktif Bertugas</span>
                </div>
                <small class="text-muted mt-2 d-block">Akun siap melayani tamu dan kunjungan warga.</small>
            </div>
        </div>
    </div>

    {{-- TABEL AGENDA KEGIATAN TERBARU --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 px-3 px-sm-4 border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-calendar2-week text-primary"></i>
                    <span>Agenda Pelayanan Anda</span>
                </h5>
                <small class="text-muted">Jadwal kegiatan terbaru yang telah diagendakan</small>
            </div>
            <a href="{{ route('karyawan.jadwal_kegiatan.index') }}" class="btn btn-light btn-sm">Lihat Lengkap</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-3 ps-sm-4">Nama Kegiatan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th class="text-end pe-3 pe-sm-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwalKegiatan as $kegiatan)
                        <tr>
                            <td class="ps-3 ps-sm-4 fw-medium text-dark">
                                <i class="bi bi-bookmark-check text-primary me-2"></i>
                                {{ $kegiatan->kegiatan }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_awal)->translatedFormat('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_akhir)->translatedFormat('d M Y') }}</td>
                            <td class="text-end pe-3 pe-sm-4">
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                    Terjadwal
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-calendar-x fs-3 d-block mb-1"></i>
                                Belum ada agenda kegiatan yang terdaftar untuk Anda saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

