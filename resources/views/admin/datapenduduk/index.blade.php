@extends('layouts.dashboard')

@section('title', 'Data Penduduk')
@section('page-heading', 'Data Penduduk')

@section('content')
    <div class="container-fluid py-4">
        {{-- HEADER HALAMAN --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1">Data Penduduk</h4>
                <p class="text-muted mb-0">
                    Kelola informasi dan akun penduduk yang terdaftar.
                </p>
            </div>

            <a href="{{ route('admin.datapenduduk.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus me-2"></i>
                Tambah Penduduk
            </a>
        </div>

        {{-- NOTIFIKASI BERHASIL --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        {{-- NOTIFIKASI ERROR --}}
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Terjadi kesalahan.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- RINGKASAN --}}
        <div class="row g-3 mb-4">

            <div class="col-12 col-sm-6 col-xl-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Penduduk</div>
                            <div class="stat-value">
                                {{ number_format($penduduks->total()) }}
                            </div>
                        </div>

                        <div class="stat-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Admin</div>
                            <div class="stat-value">
                                {{ number_format($jumlahAdmin) }}
                            </div>
                        </div>

                        <div class="stat-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Penduduk</div>
                            <div class="stat-value">
                                {{ number_format($jumlahPenduduk) }}
                            </div>
                        </div>

                        <div class="stat-icon">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- TABEL DATA PENDUDUK --}}
        <div class="content-card">

            {{-- JUDUL TABEL --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Penduduk</h5>
                    <p class="text-muted small mb-0">
                        Informasi penduduk yang tersimpan di sistem.
                    </p>
                </div>

                <span class="badge text-bg-light border">
                    {{ $penduduks->total() }} data
                </span>
            </div>

            {{-- PENCARIAN DAN FILTER --}}
            <form method="GET" action="{{ route('admin.datapenduduk.index') }}" class="row g-2 mb-4">

                <div class="col-12 col-lg-6">
                    <label for="search" class="form-label">
                        Cari Penduduk
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" id="search" name="search" class="form-control"
                            placeholder="Nama, NIK, atau username..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="status" class="form-label">
                        Filter Status
                    </label>

                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>

                        <option value="Admin" @selected(request('status') === 'Admin')>
                            Admin
                        </option>

                        <option value="Penduduk" @selected(request('status') === 'Penduduk')>
                            Penduduk
                        </option>
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label d-block">
                        Aksi Pencarian
                    </label>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>

                        <a href="{{ route('admin.datapenduduk.index') }}" class="btn btn-outline-secondary"
                            title="Reset pencarian">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>

            </form>

            {{-- TABEL --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Penduduk</th>
                            <th scope="col">NIK</th>
                            <th scope="col">No. KK</th>
                            <th scope="col">Jenis Kelamin</th>
                            <th scope="col">Username</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($penduduks as $penduduk)
                            <tr>
                                {{-- NOMOR URUT --}}
                                <td>
                                    {{ $penduduks->firstItem() + $loop->index }}
                                </td>

                                {{-- NAMA DAN ALAMAT --}}
                                <td>
                                    <div class="fw-semibold">
                                        {{ $penduduk->nama_penduduk }}
                                    </div>

                                    <div class="text-muted small">
                                        {{ $penduduk->alamat ?: 'Alamat belum diisi' }}
                                    </div>
                                </td>

                                {{-- NIK --}}
                                <td>{{ $penduduk->nik }}</td>

                                {{-- NOMOR KK --}}
                                <td>{{ $penduduk->kk }}</td>

                                {{-- JENIS KELAMIN --}}
                                <td>
                                    @if ($penduduk->jenis_kelamin === 'L')
                                        Laki-laki
                                    @elseif ($penduduk->jenis_kelamin === 'P')
                                        Perempuan
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- USERNAME --}}
                                <td>{{ $penduduk->username }}</td>

                                {{-- STATUS --}}
                                <td>
                                    @if ($penduduk->status === 'Admin')
                                        <span class="badge text-bg-primary">
                                            Admin
                                        </span>
                                    @else
                                        <span class="badge text-bg-success">
                                            Penduduk
                                        </span>
                                    @endif
                                </td>

                                {{-- TOMBOL AKSI --}}
                                <td>
                                    <div class="d-flex justify-content-center gap-1">

                                        <a href="{{ route('admin.datapenduduk.show', $penduduk->id_penduduk) }}"
                                            class="btn btn-sm btn-outline-info" title="Detail penduduk"
                                            aria-label="Detail penduduk">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.datapenduduk.edit', $penduduk->id_penduduk) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit penduduk"
                                            aria-label="Edit penduduk">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form method="POST"
                                            action="{{ route('admin.datapenduduk.destroy', $penduduk->id_penduduk) }}"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penduduk ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus penduduk"
                                                aria-label="Hapus penduduk">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>

                                    <h6 class="fw-bold mt-3">
                                        Data penduduk tidak ditemukan
                                    </h6>

                                    <p class="text-muted small mb-0">
                                        Coba ubah kata pencarian atau filter status.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- PAGINATION --}}
            @if ($penduduks->hasPages())
                <div class="d-flex flex-wrap justify-content-between
                                align-items-center gap-3 mt-4">

                    <div class="text-muted small">
                        Menampilkan {{ $penduduks->firstItem() ?? 0 }}
                        sampai {{ $penduduks->lastItem() ?? 0 }}
                        dari {{ $penduduks->total() }} data.
                    </div>

                    {{ $penduduks->links() }}

                </div>
            @endif

        </div>
    </div>

@endsection