
@extends('layouts.dashboard')

@section('title', 'Data Jadwal Kunjungan')
@section('page-heading', 'Data Penduduk')


@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Jadwal Kunjungan</h4>
            <p class="text-muted mb-0">Pengaturan tanggal dan jam layanan kunjungan.</p>
        </div>

        <a href="{{ route('admin.jadwalkunjungan.create') }}"
           class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Jadwal
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.jadwalkunjungan.index') }}"
                  class="row g-3">
                <div class="col-md-8">
                    <label for="search" class="form-label">Cari petugas atau tanggal</label>
                    <input type="text" id="search" name="search"
                           class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Nama petugas atau tanggal">
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="Y" @selected(request('status') === 'Y')>Aktif</option>
                        <option value="T" @selected(request('status') === 'T')>Tidak aktif</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button class="btn btn-success flex-grow-1" type="submit">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('admin.jadwalkunjungan.index') }}"
                       class="btn btn-outline-secondary" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No.</th>
                            <th>Tanggal</th>
                            <th>Jam Aktif</th>
                            <th>Jam Selesai</th>
                            <th>Petugas</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                            <tr>
                                <td class="ps-3">
                                    {{ $jadwals->firstItem() + $loop->index }}
                                </td>
                                <td>{{ $jadwal->tanggal->format('d-m-Y') }}</td>
                                <td>{{ substr($jadwal->jam_aktif, 0, 5) }}</td>
                                <td>{{ substr($jadwal->jam_non_aktif, 0, 5) }}</td>
                                <td>
                                    {{ $jadwal->jabatanPenduduk?->penduduk?->nama_penduduk ?? '-' }}
                                </td>
                                <td>
                                    {{ $jadwal->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}
                                </td>
                                <td>
                                    @if($jadwal->status === 'Y')
                                        <span class="badge text-bg-success">Aktif</span>
                                    @else
                                        <span class="badge text-bg-secondary">Tidak aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.jadwalkunjungan.show', $jadwal->id_settings_kunjungan) }}"
                                           class="btn btn-sm btn-outline-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.jadwalkunjungan.edit', $jadwal->id_settings_kunjungan) }}"
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.jadwalkunjungan.destroy', $jadwal->id_settings_kunjungan) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Belum ada jadwal kunjungan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($jadwals->hasPages())
            <div class="card-footer bg-white">
                {{ $jadwals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
