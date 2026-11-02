
@extends('layouts.dashboard')

@section('title', 'Edit Karyawan')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h3 class="fw-bold">Edit Karyawan</h3>
        <p class="text-muted">Perbarui data penempatan karyawan.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route(
                'admin.datakaryawan.update',
                $karyawan->id_jabatan_penduduk
            ) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="id_penduduk" class="form-label">
                        Nama Penduduk
                    </label>
                    <select name="id_penduduk" id="id_penduduk"
                            class="form-select @error('id_penduduk') is-invalid @enderror"
                            required>
                        <option value="">-- Pilih Penduduk --</option>
                        @foreach ($penduduks as $penduduk)
                            <option value="{{ $penduduk->id_penduduk }}"
                                @selected(old('id_penduduk', $karyawan->id_penduduk) == $penduduk->id_penduduk)>
                                {{ $penduduk->nama_penduduk }}
                                ({{ $penduduk->nik }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_penduduk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="id_jabatan" class="form-label">Jabatan</label>
                    <select name="id_jabatan" id="id_jabatan"
                            class="form-select @error('id_jabatan') is-invalid @enderror"
                            required>
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach ($jabatans as $jabatan)
                            <option value="{{ $jabatan->id_jabatan }}"
                                @selected(old('id_jabatan', $karyawan->id_jabatan) == $jabatan->id_jabatan)>
                                {{ $jabatan->nama_jabatan }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_jabatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">
                        Status Penempatan
                    </label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Y"
                            @selected(old('status', $karyawan->status_jabatan) === 'Y')>
                            Aktif
                        </option>
                        <option value="T"
                            @selected(old('status', $karyawan->status_jabatan) === 'T')>
                            Tidak Aktif
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-pencil-square me-1"></i> Perbarui
                </button>
                <a href="{{ route('admin.datakaryawan.index') }}"
                   class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
