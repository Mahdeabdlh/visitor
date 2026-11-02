@extends('layouts.dashboard')

@section('title', 'Profil Karyawan')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Profil Karyawan</h4>
        <p class="text-muted mb-0">
            Informasi identitas dan data akun Karyawan.
        </p>
    </div>

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show"
             role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Pesan validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali data yang Anda masukkan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        {{-- Informasi identitas --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">

                    <div class="rounded-circle bg-success bg-opacity-10
                                text-success d-flex align-items-center
                                justify-content-center mx-auto mb-3"
                         style="width: 90px; height: 90px;">

                        <i class="bi bi-person-fill"
                           style="font-size: 3rem;"></i>
                    </div>

                    <h5 class="fw-bold mb-1">
                        {{ $penduduk->nama_penduduk }}
                    </h5>

                    <p class="text-muted mb-3">
                        {{ $penduduk->email }}
                    </p>

                    <span class="badge bg-success">
                        Karyawan
                    </span>

                    <hr>

                    <div class="text-start">
                        <div class="mb-3">
                            <small class="text-muted d-block">
                                NIK
                            </small>
                            <span class="fw-semibold">
                                {{ $penduduk->nik }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Nomor KK
                            </small>
                            <span class="fw-semibold">
                                {{ $penduduk->kk }}
                            </span>
                        </div>

                        <div>
                            <small class="text-muted d-block">
                                Jenis Kelamin
                            </small>
                            <span class="fw-semibold">
                                {{ $penduduk->jenis_kelamin === 'L'
                                    ? 'Laki-laki'
                                    : 'Perempuan' }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Form edit profil --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-person-lines-fill me-2"></i>
                        Informasi Profil
                    </h5>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('karyawan.profil.update') }}"
                          method="POST">

                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label for="nama_penduduk"
                                   class="form-label fw-semibold">
                                Nama Lengkap
                            </label>

                            <input type="text"
                                   class="form-control @error('nama_penduduk') is-invalid @enderror"
                                   id="nama_penduduk"
                                   name="nama_penduduk"
                                   value="{{ old('nama_penduduk', $penduduk->nama_penduduk) }}"
                                   maxlength="100"
                                   required>

                            @error('nama_penduduk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Email
                            </label>

                            <input type="email"
                                   class="form-control"
                                   value="{{ $penduduk->email }}"
                                   readonly>

                            <small class="text-muted">
                                Email tidak dapat diubah melalui halaman ini.
                            </small>
                        </div>

                        {{-- NIK dan KK --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    NIK
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $penduduk->nik }}"
                                       readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Nomor KK
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ $penduduk->kk }}"
                                       readonly>
                            </div>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Jenis Kelamin
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $penduduk->jenis_kelamin === 'L'
                                       ? 'Laki-laki'
                                       : 'Perempuan' }}"
                                   readonly>
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-4">
                            <label for="alamat"
                                   class="form-label fw-semibold">
                                Alamat
                            </label>

                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                      id="alamat"
                                      name="alamat"
                                      rows="3"
                                      maxlength="255"
                                      required>{{ old('alamat', $penduduk->alamat) }}</textarea>

                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset"
                                    class="btn btn-light border">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Reset
                            </button>

                            <button type="submit"
                                    class="btn btn-success">
                                <i class="bi bi-save me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
