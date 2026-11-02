@extends('layouts.dashboard')

@section('title', 'Konfirmasi Kunjungan')

@section('content')
<div class="container-fluid py-4">

    {{-- Header halaman --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Konfirmasi Kunjungan</h4>
        <p class="text-muted mb-0">
            Kelola persetujuan kunjungan yang telah dikonfirmasi Admin.
        </p>
    </div>

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Pesan error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Pencarian --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET"
                  action="{{ route('karyawan.konfirmasi_kunjungan.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <label class="form-label">Cari Kunjungan</label>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Masukkan tujuan kunjungan"
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tanggal Awal</label>
                        <input
                            type="date"
                            name="tanggal_awal"
                            class="form-control"
                            value="{{ request('tanggal_awal') }}"
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input
                            type="date"
                            name="tanggal_akhir"
                            class="form-control"
                            value="{{ request('tanggal_akhir') }}"
                        >
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-search"></i> Cari
                        </button>

                        <a href="{{ route('karyawan.konfirmasi_kunjungan.index') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="fw-semibold mb-0">
                Daftar Permohonan Kunjungan
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Tujuan</th>
                            <th>Catatan Admin</th>
                            <th>Status Karyawan</th>
                            <th style="min-width: 280px;">Tindakan</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($kunjungans as $kunjungan)

                            <tr>
                                <td class="text-center">
                                    {{ $kunjungans->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    @if ($kunjungan->tanggal_waktu)
                                        {{ \Carbon\Carbon::parse($kunjungan->tanggal_waktu)->format('d-m-Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ $kunjungan->tujuannya ?? '-' }}
                                </td>

                                <td>
                                    {{ $kunjungan->catatan_admin ?? '-' }}
                                </td>

                                <td>
                                    @if ($kunjungan->konfrimasi_penduduk === 'Y')
                                        <span class="badge bg-success">
                                            Diterima
                                        </span>
                                    @elseif ($kunjungan->konfrimasi_penduduk === 'T')
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
                                    @if (is_null($kunjungan->konfrimasi_penduduk)
                                        || $kunjungan->konfrimasi_penduduk === '')

                                        <form
                                            action="{{ route('karyawan.konfirmasi_kunjungan.simpan', $kunjungan->id_kunjungan) }}"
                                            method="POST">

                                            @csrf
                                            @method('PUT')

                                            <textarea
                                                name="catatan_penduduk"
                                                class="form-control form-control-sm mb-2"
                                                rows="2"
                                                maxlength="1000"
                                                placeholder="Catatan Karyawan (opsional)"
                                            ></textarea>

                                            <div class="d-flex gap-2">

                                                <button
                                                    type="submit"
                                                    name="konfrimasi_penduduk"
                                                    value="Y"
                                                    class="btn btn-success btn-sm"
                                                    onclick="return confirm('Terima kunjungan ini?')"
                                                >
                                                    <i class="bi bi-check-circle"></i>
                                                    Terima
                                                </button>

                                                <button
                                                    type="submit"
                                                    name="konfrimasi_penduduk"
                                                    value="T"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Tolak kunjungan ini?')"
                                                >
                                                    <i class="bi bi-x-circle"></i>
                                                    Tolak
                                                </button>

                                            </div>
                                        </form>

                                    @else

                                        <span class="text-muted small">
                                            Sudah dikonfirmasi.
                                        </span>

                                        @if ($kunjungan->catatan_penduduk)
                                            <div class="small mt-2">
                                                <strong>Catatan:</strong>
                                                {{ $kunjungan->catatan_penduduk }}
                                            </div>
                                        @endif

                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-calendar-x fs-1 text-muted"></i>

                                    <p class="fw-semibold mt-2 mb-1">
                                        Belum ada data kunjungan.
                                    </p>

                                    <p class="text-muted small mb-0">
                                        Kunjungan yang sudah disetujui Admin
                                        akan ditampilkan di halaman ini.
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($kunjungans->hasPages())
            <div class="card-footer bg-white">
                {{ $kunjungans->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
