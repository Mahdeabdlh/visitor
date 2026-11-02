
@extends('layouts.dashboard')

@section('title', 'Data Kunjungan')
@section('page-heading', 'Data Kunjungan')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER HALAMAN --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">Data Kunjungan</h1>
            <p class="text-muted mb-0">
                Kelola permohonan dan konfirmasi kunjungan.
            </p>
        </div>
    </div>

    {{-- NOTIFIKASI BERHASIL --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>
    @endif

    {{-- NOTIFIKASI ERROR --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>
    @endif

    {{-- ERROR VALIDASI --}}
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Periksa kembali data Anda:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            {{-- PENCARIAN DAN FILTER --}}
            <form method="GET"
                  action="{{ route('admin.datakunjungan.index') }}"
                  class="row g-2 mb-4">

                <div class="col-12 col-md-6">
                    <label for="search" class="form-label">
                        Cari Kunjungan
                    </label>

                    <input type="text"
                           id="search"
                           name="search"
                           class="form-control"
                           placeholder="Nama, email, atau tujuan..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-12 col-md-3">
                    <label for="status" class="form-label">
                        Status Konfirmasi Admin
                    </label>

                    <select id="status" name="status" class="form-select">
                        <option value="">Semua status</option>

                        <option value="pending"
                            @selected(request('status') === 'pending')>
                            Menunggu konfirmasi
                        </option>

                        <option value="Y"
                            @selected(request('status') === 'Y')>
                            Disetujui
                        </option>

                        <option value="T"
                            @selected(request('status') === 'T')>
                            Ditolak
                        </option>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label d-block">
                        Aksi
                    </label>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                            Cari
                        </button>

                        <a href="{{ route('admin.datakunjungan.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- INFORMASI JUMLAH DATA --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h2 class="h5 mb-0">Daftar Kunjungan</h2>

                <span class="badge text-bg-light border">
                    {{ $kunjungans->total() }} data
                </span>
            </div>

            {{-- TABEL DATA KUNJUNGAN --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Pemohon</th>
                            <th scope="col">Tujuan</th>
                            <th scope="col">Waktu Kunjungan</th>
                            <th scope="col">Konfirmasi Admin</th>
                            <th scope="col" style="min-width: 150px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($kunjungans as $kunjungan)
                            <tr>
                                {{-- NOMOR URUT --}}
                                <td>
                                    {{ ($kunjungans->firstItem() ?? 1) + $loop->index }}
                                </td>

                                {{-- INFORMASI PEMOHON --}}
                                <td>
                                    <div class="fw-semibold">
                                        {{ $kunjungan->pemohon?->nama ?? 'Pemohon tidak ditemukan' }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $kunjungan->pemohon?->email ?? '-' }}
                                    </small>
                                </td>

                                {{-- TUJUAN --}}
                                <td>
                                    {{ $kunjungan->tujuannya ?: '-' }}
                                </td>

                                {{-- WAKTU KUNJUNGAN --}}
                                <td>
                                    {{ $kunjungan->tanggal_waktu
                                        ? \Illuminate\Support\Carbon::parse($kunjungan->tanggal_waktu)->format('d-m-Y H:i')
                                        : '-' }}
                                </td>

                                {{-- STATUS ADMIN --}}
                                <td>
                                    @if ($kunjungan->konfrimasi_admin === 'Y')
                                        <span class="badge bg-success">
                                            Disetujui
                                        </span>
                                    @elseif ($kunjungan->konfrimasi_admin === 'T')
                                        <span class="badge bg-danger">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>

                                {{-- TOMBOL AKSI --}}
                                <td>
                                    <div class="d-flex flex-wrap gap-1">

                                        {{-- DETAIL --}}
                                        <a href="{{ route('admin.datakunjungan.show', ['id' => $kunjungan->id_kunjungan]) }}"
                                           class="btn btn-sm btn-info text-white"
                                           title="Lihat detail"
                                           aria-label="Lihat detail kunjungan">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- EDIT / KONFIRMASI --}}
                                        <a href="{{ route('admin.datakunjungan.edit', ['id' => $kunjungan->id_kunjungan]) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Kelola konfirmasi"
                                           aria-label="Kelola konfirmasi kunjungan">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        {{-- HAPUS --}}
                                        <form method="POST"
                                              action="{{ route('admin.datakunjungan.destroy', ['id' => $kunjungan->id_kunjungan]) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus data kunjungan ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Hapus kunjungan"
                                                    aria-label="Hapus kunjungan">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Belum ada data kunjungan yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if ($kunjungans->hasPages())
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
                    <small class="text-muted">
                        Menampilkan {{ $kunjungans->firstItem() ?? 0 }}
                        sampai {{ $kunjungans->lastItem() ?? 0 }}
                        dari {{ $kunjungans->total() }} data.
                    </small>

                    {{ $kunjungans->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
