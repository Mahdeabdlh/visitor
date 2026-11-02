@extends('layouts.dashboard')

@section('title', 'Dashboard Pemohon')
@section('page-heading', 'Dashboard Pemohon')

@section('content')
@php
    $pemohon = auth()->guard('pemohon')->user() ?? auth()->guard('web')->user();
    $namaPemohon = $pemohon->nama ?? $pemohon->nama_penduduk ?? $pemohon->name ?? 'Pemohon';

    try {
        $idPemohon = $pemohon->id_pemohon ?? 0;
        $totalKunjunganSaya = \App\Models\Kunjungan::where('id_pemohon', $idPemohon)->count();
        $disetujuiCount = \App\Models\Kunjungan::where('id_pemohon', $idPemohon)->where('konfrimasi_admin', 'Y')->count();
        $menungguCount = \App\Models\Kunjungan::where('id_pemohon', $idPemohon)->whereNull('konfrimasi_admin')->count();

        $kunjunganSaya = \App\Models\Kunjungan::with(['settingsKunjungan.jabatanPenduduk.penduduk'])
            ->where('id_pemohon', $idPemohon)
            ->orderByDesc('tanggal_waktu')
            ->take(5)
            ->get();
    } catch (\Throwable $e) {
        $totalKunjunganSaya = 0;
        $disetujuiCount = 0;
        $menungguCount = 0;
        $kunjunganSaya = collect();
    }
@endphp

<div class="container-fluid px-2 px-sm-3 px-lg-4 py-3 py-lg-4">
    {{-- HEADER PEMOHON --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill small">
                    <i class="bi bi-person-check"></i> Portal Pemohon / Warga
                </span>
                <span class="text-muted small d-none d-md-inline">• {{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="h3 fw-bold mb-1 text-dark">Selamat Datang, {{ $namaPemohon }}</h1>
            <p class="text-muted small mb-0">Pantau status konfirmasi permohonan kunjungan dan konsultasi Anda di kantor desa.</p>
        </div>
    </div>

    {{-- KARTU STATUS KUNJUNGAN SAYA --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white" style="border-left: 4px solid #0d6efd !important;">
                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Pengajuan</span>
                <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalKunjunganSaya) }}</h3>
                <small class="text-muted mt-2 d-block">Riwayat permohonan kunjungan</small>
            </div>
        </div>

        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white" style="border-left: 4px solid #198754 !important;">
                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Disetujui Admin</span>
                <h3 class="fw-bold mb-0 text-success">{{ number_format($disetujuiCount) }}</h3>
                <small class="text-muted mt-2 d-block">Siap datang sesuai jadwal</small>
            </div>
        </div>

        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3 bg-white" style="border-left: 4px solid #ffc107 !important;">
                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Menunggu Konfirmasi</span>
                <h3 class="fw-bold mb-0 text-warning">{{ number_format($menungguCount) }}</h3>
                <small class="text-muted mt-2 d-block">Sedang diverifikasi oleh pihak desa</small>
            </div>
        </div>
    </div>

    {{-- TABEL RIWAYAT KUNJUNGAN SAYA --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 px-3 px-sm-4 border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-card-checklist text-primary"></i>
                    <span>Riwayat Pengajuan Kunjungan Saya</span>
                </h5>
                <small class="text-muted">Status permohonan kunjungan ke pejabat / staf kantor desa</small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-3 ps-sm-4">Tujuan / Staf</th>
                        <th>Keperluan</th>
                        <th>Waktu Kunjungan</th>
                        <th>Status Admin</th>
                        <th class="pe-3 pe-sm-4">Catatan Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kunjunganSaya as $kunjungan)
                        @php
                            $namaPegawai = $kunjungan->settingsKunjungan->jabatanPenduduk->penduduk->nama_penduduk ?? '-';
                        @endphp
                        <tr>
                            <td class="ps-3 ps-sm-4 fw-medium text-dark">
                                <i class="bi bi-person text-primary me-1"></i>
                                {{ $namaPegawai }}
                            </td>
                            <td>{{ $kunjungan->tujuannya }}</td>
                            <td>{{ \Carbon\Carbon::parse($kunjungan->tanggal_waktu)->translatedFormat('d M Y, H:i') }}</td>
                            <td>
                                @if ($kunjungan->konfrimasi_admin === 'Y')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i> Disetujui
                                    </span>
                                @elseif ($kunjungan->konfrimasi_admin === 'T')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">
                                        <i class="bi bi-x-circle me-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">
                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="pe-3 pe-sm-4 text-muted small">
                                {{ $kunjungan->catatan_admin ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                Anda belum memiliki riwayat pengajuan kunjungan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
