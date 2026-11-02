
@extends('layouts.dashboard')

@section('title', 'Edit Data Kunjungan')
@section('page-heading', 'Edit Data Kunjungan')

@section('content')
<div class="container-fluid">

    {{-- Tombol kembali --}}
    <div class="mb-3">
        <a href="{{ route('admin.datakunjungan.index') }}"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    {{-- Pesan validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali formulir Anda.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form edit kunjungan --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Formulir Konfirmasi Kunjungan</h5>
        </div>

        <div class="card-body">

            <form
                action="{{ route('admin.datakunjungan.update', ['id' => $kunjungan->id_kunjungan]) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                {{-- Informasi pemohon --}}
                <h6 class="mb-3">Informasi Pemohon</h6>

                <div class="mb-3">
                    <label class="form-label">Nama Pemohon</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ $kunjungan->pemohon->nama ?? '-' }}"
                        readonly
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Pemohon</label>
                    <input
                        type="email"
                        class="form-control"
                        value="{{ $kunjungan->pemohon->email ?? '-' }}"
                        readonly
                    >
                </div>

                {{-- Informasi kunjungan --}}
                <hr>
                <h6 class="mb-3">Informasi Kunjungan</h6>

                <div class="mb-3">
                    <label class="form-label">Tujuan Kunjungan</label>
                    <textarea
                        class="form-control"
                        rows="3"
                        readonly
                    >{{ $kunjungan->tujuannya ?? '-' }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal dan Waktu Kunjungan</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ $kunjungan->tanggal_waktu?->format('d-m-Y H:i') ?? '-' }}"
                        readonly
                    >
                </div>

                {{-- Konfirmasi admin --}}
                <hr>
                <h6 class="mb-3">Keputusan Admin</h6>

                <div class="mb-3">
                    <label for="konfrimasi_admin" class="form-label">
                        Status Konfirmasi <span class="text-danger">*</span>
                    </label>

                    <select
                        name="konfrimasi_admin"
                        id="konfrimasi_admin"
                        class="form-select @error('konfrimasi_admin') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Status --</option>

                        <option
                            value="Y"
                            @selected(old('konfrimasi_admin', $kunjungan->konfrimasi_admin) === 'Y')
                        >
                            Disetujui
                        </option>

                        <option
                            value="T"
                            @selected(old('konfrimasi_admin', $kunjungan->konfrimasi_admin) === 'T')
                        >
                            Ditolak
                        </option>
                    </select>

                    @error('konfrimasi_admin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <small class="text-muted">
                        Pilih Disetujui atau Ditolak untuk menyimpan keputusan admin.
                    </small>
                </div>

                {{-- Catatan admin --}}
                <div class="mb-3">
                    <label for="catatan_admin" class="form-label">
                        Catatan Admin
                    </label>

                    <textarea
                        name="catatan_admin"
                        id="catatan_admin"
                        rows="4"
                        maxlength="255"
                        class="form-control @error('catatan_admin') is-invalid @enderror"
                        placeholder="Masukkan catatan admin jika diperlukan..."
                    >{{ old('catatan_admin', $kunjungan->catatan_admin) }}</textarea>

                    @error('catatan_admin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol aksi --}}
                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i>
                        Simpan Perubahan
                    </button>

                    <a
                        href="{{ route('admin.datakunjungan.show', ['id' => $kunjungan->id_kunjungan]) }}"
                        class="btn btn-outline-secondary"
                    >
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
