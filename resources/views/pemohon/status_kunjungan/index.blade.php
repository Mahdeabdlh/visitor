@extends('layouts.dashboard')

@section('title', 'Status Kunjungan')

@section('content')
<div class="container-fluid py-4">

    {{-- Judul halaman --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Status Kunjungan</h4>
            <p class="text-muted mb-0">
                Pantau perkembangan pengajuan kunjungan Anda.
            </p>
        </div>
    </div>

    {{-- Ringkasan status --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Pengajuan</div>
                    <h3 class="fw-bold mb-0">{{ $totalKunjungan }}</h3>
                    <small class="text-muted">Seluruh kunjungan</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Menunggu</div>
                    <h3 class="fw-bold text-warning mb-0">{{ $menunggu }}</h3>
                    <small class="text-muted">Belum dikonfirmasi Admin</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Diterima</div>
                    <h3 class="fw-bold text-success mb-0">{{ $diterima }}</h3>
                    <small class="text-muted">Pengajuan disetujui</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Ditolak</div>
                    <h3 class="fw-bold text-danger mb-0">{{ $ditolak }}</h3>
                    <small class="text-muted">Pengajuan tidak disetujui</small>
                </div>
            </div>
        </div>

    </div>

    {{-- Filter dan pencarian --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

            <form action="{{ route('pemohon.status_kunjungan.index') }}"
                  method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <label class="form-label">Cari Tujuan</label>
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Masukkan tujuan kunjungan..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tanggal Awal</label>
                        <input type="date"
                               name="tanggal_awal"
                               class="form-control"
                               value="{{ request('tanggal_awal') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date"
                               name="tanggal_akhir"
                               class="form-control"
                               value="{{ request('tanggal_akhir') }}">
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>

                        <a href="{{ route('pemohon.status_kunjungan.index') }}"
                           class="btn btn-secondary"
                           title="Reset filter">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- Tabel status kunjungan --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Daftar Status Kunjungan</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Tujuan Kunjungan</th>
                            <th>Jabatan Tujuan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Konfirmasi Admin</th>
                            <th>Konfirmasi Pemohon</th>
                            <th>Catatan Admin</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($kunjungan as $item)
                            <tr>
                                <td class="text-center">
                                    {{ $kunjungan->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <span class="fw-semibold">
                                        {{ $item->tujuannya }}
                                    </span>
                                </td>

                                <td>
                                    {{ $item->settingsKunjungan
                                        ->jabatanPenduduk
                                        ->jabatan
                                        ->nama_jabatan ?? '-' }}
                                </td>

                                <td>
                                    @if ($item->tanggal_waktu)
                                        {{ \Carbon\Carbon::parse($item->tanggal_waktu)->format('d-m-Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if ($item->konfrimasi_admin === 'Y')
                                        <span class="badge bg-success">
                                            Diterima
                                        </span>
                                    @elseif ($item->konfrimasi_admin === 'T')
                                        <span class="badge bg-danger">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($item->konfrimasi_penduduk === 'Y')
                                        <span class="badge bg-success">
                                            Dikonfirmasi
                                        </span>
                                    @elseif ($item->konfrimasi_penduduk === 'T')
                                        <span class="badge bg-danger">
                                            Tidak Dikonfirmasi
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Belum Ada
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->catatan_admin ?? '-' }}
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-inbox fs-2 text-muted"></i>
                                    <p class="text-muted mb-0 mt-2">
                                        Belum ada data status kunjungan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">

                <small class="text-muted">
                    Menampilkan
                    {{ $kunjungan->firstItem() ?? 0 }}
                    sampai
                    {{ $kunjungan->lastItem() ?? 0 }}
                    dari
                    {{ $kunjungan->total() }}
                    data
                </small>

                {{ $kunjungan->links() }}

            </div>

        </div>
    </div>

</div>
@endsection