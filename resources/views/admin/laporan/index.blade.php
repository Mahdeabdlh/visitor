
@extends('layouts.dashboard')

@section('title', 'Laporan Kunjungan')
@section('page-heading', 'Laporan Kunjungan')

@section('content')
<div class="container-fluid">

    {{-- Ringkasan laporan --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted mb-2">Total Kunjungan</div>
                    <h3 class="mb-0">{{ $totalKunjungan }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted mb-2">Disetujui</div>
                    <h3 class="mb-0 text-success">{{ $jumlahDisetujui }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted mb-2">Ditolak</div>
                    <h3 class="mb-0 text-danger">{{ $jumlahDitolak }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted mb-2">Menunggu Konfirmasi</div>
                    <h3 class="mb-0 text-warning">{{ $jumlahMenunggu }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Pesan sukses atau error --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter laporan --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filter Laporan</h5>
        </div>

        <div class="card-body">
            <form
                action="{{ route('admin.laporan.index') }}"
                method="GET"
            >
                <div class="row g-3">

                    <div class="col-12 col-md-6 col-xl-3">
                        <label for="search" class="form-label">
                            Cari Data
                        </label>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            placeholder="Nama, email, atau tujuan"
                            value="{{ request('search') }}"
                        >
                    </div>

                    <div class="col-12 col-md-6 col-xl-2">
                        <label for="tanggal_mulai" class="form-label">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="tanggal_mulai"
                            id="tanggal_mulai"
                            class="form-control"
                            value="{{ request('tanggal_mulai') }}"
                        >
                    </div>

                    <div class="col-12 col-md-6 col-xl-2">
                        <label for="tanggal_akhir" class="form-label">
                            Tanggal Akhir
                        </label>

                        <input
                            type="date"
                            name="tanggal_akhir"
                            id="tanggal_akhir"
                            class="form-control"
                            value="{{ request('tanggal_akhir') }}"
                        >
                    </div>

                    <div class="col-12 col-md-6 col-xl-2">
                        <label for="status" class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select"
                        >
                            <option value="">Semua Status</option>

                            <option
                                value="Y"
                                @selected(request('status') === 'Y')
                            >
                                Disetujui
                            </option>

                            <option
                                value="T"
                                @selected(request('status') === 'T')
                            >
                                Ditolak
                            </option>

                            <option
                                value="pending"
                                @selected(request('status') === 'pending')
                            >
                                Menunggu
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-xl-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                            Tampilkan
                        </button>

                        <a
                            href="{{ route('admin.laporan.index') }}"
                            class="btn btn-secondary"
                        >
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Tabel laporan --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0">Data Laporan Kunjungan</h5>

            <span class="text-muted small">
                Menampilkan {{ $kunjungans->count() }}
                dari {{ $kunjungans->total() }} data pada hasil filter
            </span>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama Pemohon</th>
                            <th>Email</th>
                            <th>Tujuan Kunjungan</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Status</th>
                            <th>Catatan Admin</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($kunjungans as $kunjungan)
                            <tr>
                                <td>
                                    {{ $kunjungans->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $kunjungan->pemohon->nama ?? '-' }}
                                </td>

                                <td>
                                    {{ $kunjungan->pemohon->email ?? '-' }}
                                </td>

                                <td>
                                    {{ $kunjungan->tujuannya ?? '-' }}
                                </td>

                                <td>
                                    {{ $kunjungan->tanggal_waktu
                                        ? $kunjungan->tanggal_waktu->format('d-m-Y H:i')
                                        : '-' }}
                                </td>

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

                                <td>
                                    {{ $kunjungan->catatan_admin ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Data laporan kunjungan tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Navigasi halaman --}}
            <div class="mt-3">
                {{ $kunjungans->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
