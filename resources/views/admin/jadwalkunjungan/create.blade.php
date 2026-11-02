
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-4">
    <h4 class="fw-bold mb-1">Tambah Jadwal Kunjungan</h4>
    <p class="text-muted mb-4">Masukkan tanggal dan jam layanan.</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.jadwalkunjungan.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="id_jabatan_penduduk">Petugas</label>
                    <select name="id_jabatan_penduduk" id="id_jabatan_penduduk"
                            class="form-select" required>
                        <option value="">-- Pilih petugas --</option>
                        @foreach($jabatanPenduduks as $jp)
                            <option value="{{ $jp->id_jabatan_penduduk }}"
                                @selected(old('id_jabatan_penduduk') == $jp->id_jabatan_penduduk)>
                                {{ $jp->penduduk?->nama_penduduk ?? 'Nama tidak tersedia' }}
                                — {{ $jp->jabatan?->nama_jabatan ?? 'Jabatan tidak tersedia' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="tanggal">Tanggal layanan</label>
                    <input type="date" name="tanggal" id="tanggal"
                           class="form-control" value="{{ old('tanggal') }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="jam_aktif">Jam mulai</label>
                        <input type="time" name="jam_aktif" id="jam_aktif"
                               class="form-control" value="{{ old('jam_aktif') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="jam_non_aktif">Jam selesai</label>
                        <input type="time" name="jam_non_aktif" id="jam_non_aktif"
                               class="form-control" value="{{ old('jam_non_aktif') }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="status">Status layanan</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Y" @selected(old('status', 'Y') === 'Y')>Aktif</option>
                        <option value="T" @selected(old('status') === 'T')>Tidak aktif</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-1"></i> Simpan Jadwal
                </button>
                <a href="{{ route('admin.jadwalkunjungan.index') }}"
                   class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
