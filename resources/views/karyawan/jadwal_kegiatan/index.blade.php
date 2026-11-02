@extends('layouts.dashboard')

@section('title', 'Jadwal Kegiatan')
@section('page-heading', 'Jadwal Kegiatan')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Jadwal Kegiatan</h4>
        <p class="text-muted mb-0">
            Lihat jadwal kegiatan yang telah terdaftar.
        </p>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form
                action="{{ route('karyawan.jadwal_kegiatan.index') }}"
                method="GET"
            >
                <div class="row g-3 align-items-end">

                    <div class="col-12 col-md-4">
                        <label for="search" class="form-label">
                            Nama Kegiatan
                        </label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="form-control"
                            placeholder="Cari kegiatan..."
                            value="{{ request('search') }}"
                        >
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="tanggal_awal" class="form-label">
                            Tanggal Mulai
                        </label>
                        <input
                            type="date"
                            id="tanggal_awal"
                            name="tanggal_awal"
                            class="form-control"
                            value="{{ request('tanggal_awal') }}"
                        >
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="tanggal_akhir" class="form-label">
                            Tanggal Selesai
                        </label>
                        <input
                            type="date"
                            id="tanggal_akhir"
                            name="tanggal_akhir"
                            class="form-control"
                            value="{{ request('tanggal_akhir') }}"
                        >
                    </div>

                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>

                        <a
                            href="{{ route('karyawan.jadwal_kegiatan.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-semibold mb-0">
                <i class="bi bi-calendar-event me-2"></i>
                Daftar Kegiatan
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">No.</th>
                            <th>Kegiatan</th>
                            <th>Tanggal Awal</th>
                            <th>Tanggal Akhir</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($jadwalKegiatan as $jadwal)
                            <tr>
                                <td class="px-3">
                                    {{ $jadwalKegiatan->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $jadwal->kegiatan }}
                                </td>

                                <td>
                                    {{ \Illuminate\Support\Carbon::parse($jadwal->tanggal_awal)->format('d-m-Y') }}
                                </td>

                                <td>
                                    {{ \Illuminate\Support\Carbon::parse($jadwal->tanggal_akhir)->format('d-m-Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="bi bi-calendar-x fs-2 text-muted"></i>
                                    <p class="mt-2 mb-1 fw-semibold">
                                        Jadwal belum tersedia
                                    </p>
                                    <p class="text-muted mb-0">
                                        Belum ada kegiatan yang sesuai.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($jadwalKegiatan->hasPages())
            <div class="card-footer bg-white">
                {{ $jadwalKegiatan->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
