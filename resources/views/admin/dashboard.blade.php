@extends('layouts.dashboard')

@section('title', 'Dashboard Administrator')
@section('page-heading', 'Dashboard Administrator')

@section('content')
@php
    try {
        $totalPenduduk = \App\Models\Penduduk::count();
        $totalKaryawan = \App\Models\JabatanPenduduk::where('status', 'Y')->count();
        $totalKunjungan = \App\Models\Kunjungan::count();
        $kunjunganPending = \App\Models\Kunjungan::whereNull('konfrimasi_admin')->count();
        $kunjunganTerbaru = \App\Models\Kunjungan::with(['pemohon', 'settingsKunjungan.jabatanPenduduk.penduduk'])
            ->orderByDesc('tanggal_waktu')
            ->take(6)
            ->get();
    } catch (\Throwable $e) {
        $totalPenduduk = 0;
        $totalKaryawan = 0;
        $totalKunjungan = 0;
        $kunjunganPending = 0;
        $kunjunganTerbaru = collect();
    }
@endphp

<div class="container-fluid px-2 px-sm-3 px-lg-4 py-3 py-lg-4">

    {{-- HEADER DASHBOARD --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                    <i class="bi bi-shield-check"></i> Panel Administrator
                </span>
                <span class="text-muted small d-none d-md-inline">• {{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="h3 fw-bold mb-1 text-dark">Ringkasan Sistem Buku Tamu</h1>
            <p class="text-muted small mb-0">Pantau permohonan kunjungan, data karyawan, dan aktivitas pelayanan warga.</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.jadwalkunjungan.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-calendar-plus"></i>
                <span>Jadwal Baru</span>
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-file-earmark-text"></i>
                <span>Lihat Laporan</span>
            </a>
        </div>
    </div>

    {{-- KARTU STATISTIK METRICS (RESPONSIVE 4 COLS) --}}
    <section class="row g-2 g-sm-3 mb-4" aria-label="Statistik Utama">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3" style="border-left: 4px solid #0d6efd !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Penduduk</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalPenduduk) }}</h3>
                    </div>
                    <span class="d-inline-flex align-items-center justify-center rounded-3 bg-primary-subtle text-primary p-2">
                        <i class="bi bi-people-fill fs-5"></i>
                    </span>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <a href="{{ route('admin.datapenduduk.index') }}" class="text-primary small text-decoration-none fw-semibold d-inline-flex align-items-center gap-1">
                        <span>Kelola Warga</span>
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3" style="border-left: 4px solid #198754 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Karyawan Aktif</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalKaryawan) }}</h3>
                    </div>
                    <span class="d-inline-flex align-items-center justify-center rounded-3 bg-success-subtle text-success p-2">
                        <i class="bi bi-person-badge-fill fs-5"></i>
                    </span>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <a href="{{ route('admin.datakaryawan.index') }}" class="text-success small text-decoration-none fw-semibold d-inline-flex align-items-center gap-1">
                        <span>Data Jabatan</span>
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3" style="border-left: 4px solid #0dcaf0 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Kunjungan</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalKunjungan) }}</h3>
                    </div>
                    <span class="d-inline-flex align-items-center justify-center rounded-3 bg-info-subtle text-info p-2">
                        <i class="bi bi-calendar-check-fill fs-5"></i>
                    </span>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <a href="{{ route('admin.datakunjungan.index') }}" class="text-info small text-decoration-none fw-semibold d-inline-flex align-items-center gap-1">
                        <span>Riwayat Kunjungan</span>
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 p-3" style="border-left: 4px solid #ffc107 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Perlu Konfirmasi</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($kunjunganPending) }}</h3>
                    </div>
                    <span class="d-inline-flex align-items-center justify-center rounded-3 bg-warning-subtle text-warning p-2">
                        <i class="bi bi-hourglass-split fs-5"></i>
                    </span>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <a href="{{ route('admin.datakunjungan.index') }}?status=pending" class="text-warning small text-decoration-none fw-semibold d-inline-flex align-items-center gap-1">
                        <span>Konfirmasi Sekarang</span>
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- MENU PINTAS & KUNJUNGAN TERBARU --}}
    <div class="row g-3">
        {{-- TABEL KUNJUNGAN TERBARU --}}
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 px-3 px-sm-4 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-primary"></i>
                            <span>Permohonan Kunjungan Terbaru</span>
                        </h5>
                        <small class="text-muted">Daftar tamu dan permohonan kunjungan terkini</small>
                    </div>
                    <a href="{{ route('admin.datakunjungan.index') }}" class="btn btn-light btn-sm">Lihat Semua</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th class="ps-3 ps-sm-4">Pemohon</th>
                                <th>Tujuan / Karyawan</th>
                                <th>Jadwal Kunjungan</th>
                                <th>Status Admin</th>
                                <th class="text-end pe-3 pe-sm-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kunjunganTerbaru as $item)
                                @php
                                    $namaPemohon = $item->pemohon->nama ?? 'Tamu Umum';
                                    $karyawanTujuan = $item->settingsKunjungan->jabatanPenduduk->penduduk->nama_penduduk ?? '-';
                                    $jabatan = $item->settingsKunjungan->jabatanPenduduk->jabatan->nama_jabatan ?? '';
                                @endphp
                                <tr>
                                    <td class="ps-3 ps-sm-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-fallback rounded-circle bg-primary-subtle text-primary fw-bold d-inline-flex align-items-center justify-center" style="width: 32px; height: 32px; font-size: 13px;">
                                                {{ strtoupper(substr($namaPemohon, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $namaPemohon }}</div>
                                                <small class="text-muted">{{ $item->pemohon->telepon ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 220px;" title="{{ $item->tujuannya }}">
                                            <span class="d-block fw-medium text-dark">{{ $karyawanTujuan }}</span>
                                            <small class="text-muted">{{ $item->tujuannya }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <i class="bi bi-calendar-event me-1 text-muted"></i>
                                            {{ \Carbon\Carbon::parse($item->tanggal_waktu)->translatedFormat('d M Y, H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->konfrimasi_admin === 'Y')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                                <i class="bi bi-check-circle me-1"></i> Disetujui
                                            </span>
                                        @elseif ($item->konfrimasi_admin === 'T')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">
                                                <i class="bi bi-x-circle me-1"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">
                                                <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3 pe-sm-4">
                                        <a href="{{ route('admin.datakunjungan.show', $item->id_kunjungan) }}" class="btn btn-outline-primary btn-sm py-1 px-2" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.datakunjungan.edit', $item->id_kunjungan) }}" class="btn btn-light btn-sm py-1 px-2 ms-1" title="Konfirmasi">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                        Belum ada permohonan kunjungan yang masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MENU PINTAS & INFORMASI SISTEM --}}
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-header bg-white py-3 px-3 px-sm-4 border-bottom">
                    <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-grid text-primary"></i>
                        <span>Menu Cepat Admin</span>
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.datapenduduk.create') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2.5 px-2 rounded-2 mb-1 border-0">
                            <span class="d-flex align-items-center gap-2.5">
                                <span class="badge bg-primary-subtle text-primary p-2 rounded-2"><i class="bi bi-person-plus"></i></span>
                                <span class="fw-medium">Tambah Data Penduduk</span>
                            </span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>

                        <a href="{{ route('admin.datajabatan.create') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2.5 px-2 rounded-2 mb-1 border-0">
                            <span class="d-flex align-items-center gap-2.5">
                                <span class="badge bg-success-subtle text-success p-2 rounded-2"><i class="bi bi-briefcase"></i></span>
                                <span class="fw-medium">Tambah Jabatan Baru</span>
                            </span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>

                        <a href="{{ route('admin.datakaryawan.create') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2.5 px-2 rounded-2 mb-1 border-0">
                            <span class="d-flex align-items-center gap-2.5">
                                <span class="badge bg-info-subtle text-info p-2 rounded-2"><i class="bi bi-person-check"></i></span>
                                <span class="fw-medium">Tetapkan Karyawan</span>
                            </span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>

                        <a href="{{ route('admin.jadwalkunjungan.create') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2.5 px-2 rounded-2 mb-1 border-0">
                            <span class="d-flex align-items-center gap-2.5">
                                <span class="badge bg-warning-subtle text-warning p-2 rounded-2"><i class="bi bi-calendar-plus"></i></span>
                                <span class="fw-medium">Atur Jadwal Pelayanan</span>
                            </span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>

                        <a href="{{ route('admin.pengaturan.index') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2.5 px-2 rounded-2 border-0">
                            <span class="d-flex align-items-center gap-2.5">
                                <span class="badge bg-secondary-subtle text-secondary p-2 rounded-2"><i class="bi bi-gear"></i></span>
                                <span class="fw-medium">Pengaturan Instansi</span>
                            </span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- PANDUAN AKUN LOGIN CARD --}}
            <div class="card border-0 shadow-sm rounded-3 bg-light">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-2 text-dark d-flex align-items-center gap-1.5">
                        <i class="bi bi-info-circle text-primary"></i>
                        <span>Informasi Hak Akses</span>
                    </h6>
                    <p class="text-muted small mb-2">Sistem ini memisahkan 3 alur pengguna:</p>
                    <ul class="text-muted small ps-3 mb-0">
                        <li><strong>Admin:</strong> Mengelola penduduk, karyawan, jadwal, dan konfirmasi kunjungan.</li>
                        <li><strong>Karyawan:</strong> Melihat jadwal kegiatan pelayanan dan kunjungan tamu.</li>
                        <li><strong>Pemohon:</strong> Warga/tamu yang mengajukan kunjungan dan memantau status.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
