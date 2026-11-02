@extends('layouts.dashboard')

@section('content')

<div class="container-fluid py-3">

    {{-- Judul Halaman --}}
    <div class="mb-4">
        <h5 class="fw-bold mb-1">
            <i class="bi bi-calendar-check me-2"></i>
            Riwayat Kunjungan
        </h5>

        <p class="text-muted mb-0">
            Lihat seluruh riwayat pengajuan kunjungan Anda.
        </p>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistik --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Kunjungan</p>
                            <h4 class="fw-bold mb-0">
                                {{ $totalKunjungan }}
                            </h4>
                        </div>

                        <i class="bi bi-calendar2-week fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Menunggu</p>
                            <h4 class="fw-bold mb-0 text-warning">
                                {{ $menunggu }}
                            </h4>
                        </div>

                        <i class="bi bi-clock-history fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Diterima Admin</p>
                            <h4 class="fw-bold mb-0 text-success">
                                {{ $diterima }}
                            </h4>
                        </div>

                        <i class="bi bi-check-circle fs-2 text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Ditolak Admin</p>
                            <h4 class="fw-bold mb-0 text-danger">
                                {{ $ditolak }}
                            </h4>
                        </div>

                        <i class="bi bi-x-circle fs-2 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Filter Pencarian --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-search me-2"></i>
                Pencarian Riwayat
            </h6>
        </div>

        <div class="card-body">

            <form action="{{ route('pemohon.riwayat_kunjungan.index') }}"
                  method="GET">

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Tujuan Kunjungan
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Cari tujuan kunjungan..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            Tanggal Awal
                        </label>

                        <input type="date"
                               name="tanggal_awal"
                               class="form-control"
                               value="{{ request('tanggal_awal') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            Tanggal Akhir
                        </label>

                        <input type="date"
                               name="tanggal_akhir"
                               class="form-control"
                               value="{{ request('tanggal_akhir') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            Status Admin
                        </label>

                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>

                            <option value="menunggu"
                                @selected(request('status') === 'menunggu')>
                                Menunggu
                            </option>

                            <option value="diterima"
                                @selected(request('status') === 'diterima')>
                                Diterima
                            </option>

                            <option value="ditolak"
                                @selected(request('status') === 'ditolak')>
                                Ditolak
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>

                        <a href="{{ route('pemohon.riwayat_kunjungan.index') }}"
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-list-ul me-2"></i>
                Daftar Riwayat Kunjungan
            </h6>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th width="55">No.</th>
                            <th>Tujuan</th>
                            <th>Jadwal</th>
                            <th>Waktu Pengajuan</th>
                            <th>Jabatan Tujuan</th>
                            <th>Status Admin</th>
                            <th>Status Penduduk</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($kunjungans as $index => $kunjungan)

                            <tr>
                                <td>
                                    {{ $kunjungans->firstItem() + $index }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $kunjungan->tujuannya }}
                                    </div>
                                </td>

                                <td>
                                    @if($kunjungan->settingsKunjungan)
                                        {{ $kunjungan->settingsKunjungan->tanggal?->format('d-m-Y') ?? '-' }}

                                        <div class="small text-muted">
                                            {{ \Carbon\Carbon::parse($kunjungan->settingsKunjungan->jam_aktif)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($kunjungan->settingsKunjungan->jam_non_aktif)->format('H:i') }}
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($kunjungan->tanggal_waktu)->format('d-m-Y H:i') }}
                                </td>

                                <td>
                                    {{ $kunjungan->settingsKunjungan?->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}
                                </td>

                                {{-- Status Admin --}}
                                <td>
                                    @if($kunjungan->konfrimasi_admin === 'Y')
                                        <span class="badge bg-success">
                                            Diterima
                                        </span>
                                    @elseif($kunjungan->konfrimasi_admin === 'T')
                                        <span class="badge bg-danger">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>

                                {{-- Status Penduduk --}}
                                <td>
                                    @if($kunjungan->konfrimasi_penduduk === 'Y')
                                        <span class="badge bg-success">
                                            Diterima
                                        </span>
                                    @elseif($kunjungan->konfrimasi_penduduk === 'T')
                                        <span class="badge bg-danger">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Belum Dikonfirmasi
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center py-5">

                                    <i class="bi bi-calendar-x fs-1 text-muted"></i>

                                    <h6 class="mt-3 fw-semibold">
                                        Belum Ada Riwayat Kunjungan
                                    </h6>

                                    <p class="text-muted mb-3">
                                        Anda belum mempunyai pengajuan kunjungan
                                        atau data tidak ditemukan.
                                    </p>

                                    <a href="{{ route('pemohon.ajukan_kunjungan.index') }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Ajukan Kunjungan
                                    </a>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">

                <small class="text-muted">
                    Menampilkan
                    {{ $kunjungans->firstItem() ?? 0 }}
                    sampai
                    {{ $kunjungans->lastItem() ?? 0 }}
                    dari
                    {{ $kunjungans->total() }}
                    data
                </small>

                {{ $kunjungans->links() }}

            </div>

        </div>
    </div>

</div>

@endsection
