
@extends('layouts.dashboard')

@section('title', 'Data Jabatan')

@section('content')
<div class="container-fluid py-4">

    {{-- Judul halaman --}}
    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Data Jabatan</h3>
            <p class="text-muted mb-0">
                Kelola data jabatan yang terdaftar di sistem.
            </p>
        </div>

        <a href="{{ route('admin.datajabatan.create') }}"
           class="btn btn-success">
            <i class="bi bi-plus-circle me-2"></i>
            Tambah Jabatan
        </a>
    </div>

    {{-- Notifikasi berhasil --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show"
             role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Notifikasi gagal --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-circle bg-success-subtle
                        text-success d-flex align-items-center
                        justify-content-center"
                 style="width: 52px; height: 52px;">
                <i class="bi bi-briefcase fs-4"></i>
            </div>

            <div>
                <div class="text-muted small">Total Jabatan</div>
                <h4 class="fw-bold mb-0">{{ $totalJabatan }}</h4>
            </div>
        </div>
    </div>

    {{-- Tabel data --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-wrap justify-content-between
                        align-items-center gap-3">
                <h5 class="fw-bold mb-0">Daftar Jabatan</h5>

                <form action="{{ route('admin.datajabatan.index') }}"
                      method="GET"
                      class="d-flex gap-2">
                    <input type="search"
                           name="search"
                           class="form-control"
                           placeholder="Cari nama jabatan..."
                           value="{{ $search }}"
                           aria-label="Cari nama jabatan">

                    <button type="submit" class="btn btn-outline-success">
                        <i class="bi bi-search"></i>
                    </button>

                    @if ($search)
                        <a href="{{ route('admin.datajabatan.index') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 80px;">
                                No.
                            </th>
                            <th>Nama Jabatan</th>
                            <th class="text-center" style="width: 190px;">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($jabatans as $index => $jabatan)
                            <tr>
                                <td class="text-center">
                                    {{ $jabatans->firstItem() + $index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $jabatan->nama_jabatan }}
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center
                                                gap-1">
                                        <a href="{{ route(
                                            'admin.datajabatan.show',
                                            $jabatan->id_jabatan
                                        ) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route(
                                            'admin.datajabatan.edit',
                                            $jabatan->id_jabatan
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route(
                                            'admin.datajabatan.destroy',
                                            $jabatan->id_jabatan
                                        ) }}"
                                              method="POST"
                                              onsubmit="return confirm(
                                                  'Yakin ingin menghapus jabatan ini?'
                                              )">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"
                                    class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                                    @if ($search)
                                        Data jabatan dengan kata kunci
                                        "{{ $search }}" tidak ditemukan.
                                    @else
                                        Belum ada data jabatan.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($jabatans->hasPages())
            <div class="card-footer bg-white">
                {{ $jabatans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
