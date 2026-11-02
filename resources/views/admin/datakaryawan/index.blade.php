
@extends('layouts.dashboard')

@section('title', 'Data Karyawan')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Data Karyawan</h3>
            <p class="text-muted mb-0">
                Kelola data penempatan penduduk dan jabatan.
            </p>
        </div>

        <a href="{{ route('admin.datakaryawan.create') }}"
           class="btn btn-success">
            <i class="bi bi-person-plus me-2"></i>
            Tambah Karyawan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success-subtle text-success
                                d-flex align-items-center justify-content-center"
                         style="width: 52px; height: 52px;">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Penempatan</div>
                        <h4 class="fw-bold mb-0">{{ $totalKaryawan }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-wrap justify-content-between
                        align-items-center gap-3">
                <h5 class="fw-bold mb-0">Daftar Karyawan</h5>

                <form action="{{ route('admin.datakaryawan.index') }}"
                      method="GET" class="d-flex gap-2">
                    <input type="search"
                           name="search"
                           value="{{ $search }}"
                           class="form-control"
                           placeholder="Cari nama, NIK, jabatan...">

                    <button class="btn btn-outline-success" type="submit">
                        <i class="bi bi-search"></i>
                    </button>

                    @if ($search)
                        <a href="{{ route('admin.datakaryawan.index') }}"
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
                            <th class="text-center">No.</th>
                            <th>Nama Karyawan</th>
                            <th>NIK</th>
                            <th>Jabatan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($karyawans as $index => $karyawan)
                            <tr>
                                <td class="text-center">
                                    {{ $karyawans->firstItem() + $index }}
                                </td>
                                <td class="fw-semibold">
                                    {{ $karyawan->nama_penduduk }}
                                </td>
                                <td>{{ $karyawan->nik }}</td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis">
                                        {{ $karyawan->nama_jabatan }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($karyawan->status_jabatan === 'Y')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route(
                                            'admin.datakaryawan.show',
                                            $karyawan->id_jabatan_penduduk
                                        ) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route(
                                            'admin.datakaryawan.edit',
                                            $karyawan->id_jabatan_penduduk
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route(
                                            'admin.datakaryawan.destroy',
                                            $karyawan->id_jabatan_penduduk
                                        ) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus penempatan karyawan ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-outline-danger"
                                                    type="submit"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Belum ada data karyawan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($karyawans->hasPages())
            <div class="card-footer bg-white">
                {{ $karyawans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
