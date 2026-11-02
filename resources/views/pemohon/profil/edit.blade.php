```blade
@extends('layouts.dashboard')

@section('title', 'Profil Pemohon')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h4 class="fw-bold">Profil Pemohon</h4>
        <p class="text-muted">
            Kelola informasi akun dan data pribadi Anda.
        </p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali data berikut:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        {{-- Informasi akun --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">

                    <i class="bi bi-person-circle text-primary"
                       style="font-size: 75px;"></i>

                    <h5 class="fw-bold mt-3">
                        {{ $pemohon->nama }}
                    </h5>

                    <p class="text-muted mb-2">
                        {{ $pemohon->email }}
                    </p>

                    <span class="badge bg-primary">Pemohon</span>

                    <hr>

                    <div class="text-start">
                        <p class="mb-2">
                            <small class="text-muted">ID Pemohon</small><br>
                            <strong>{{ $pemohon->id_pemohon }}</strong>
                        </p>

                        <p class="mb-0">
                            <small class="text-muted">Nomor Telepon</small><br>
                            <strong>{{ $pemohon->telepon ?? '-' }}</strong>
                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- Form profil --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-person-gear me-2"></i>
                        Ubah Informasi Profil
                    </h5>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('pemohon.profil.update') }}"
                          method="POST">

                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Lengkap
                            </label>

                            <input type="text"
                                   id="nama"
                                   name="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $pemohon->nama) }}"
                                   required>

                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label">
                                Nomor Telepon
                            </label>

                            <input type="text"
                                   id="telepon"
                                   name="telepon"
                                   class="form-control @error('telepon') is-invalid @enderror"
                                   value="{{ old('telepon', $pemohon->telepon) }}"
                                   required>

                            @error('telepon')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $pemohon->email) }}"
                                   required>

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold">Ubah Password</h6>
                        <p class="text-muted small">
                            Kosongkan password jika tidak ingin mengubahnya.
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password Baru
                            </label>

                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   autocomplete="new-password">

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation"
                                   class="form-label">
                                Konfirmasi Password Baru
                            </label>

                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="form-control"
                                   autocomplete="new-password">
                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('pemohon.dashboard') }}"
                               class="btn btn-light border">
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-primary">
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
```