@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid py-4">

        {{-- Judul Halaman --}}
        <div class="mb-4">
            <h4 class="fw-bold mb-1">
                <i class="bi bi-calendar-plus me-2"></i>
                Ajukan Kunjungan
            </h4>
            <p class="text-muted mb-0">
                Silakan pilih jadwal dan lengkapi formulir pengajuan kunjungan.
            </p>
        </div>

        {{-- Notifikasi sukses --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Notifikasi validasi --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Pengajuan belum berhasil.</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">

            {{-- Form Pengajuan --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-pencil-square me-2"></i>
                            Formulir Pengajuan
                        </h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('pemohon.ajukan_kunjungan.store') }}" method="POST">
                            @csrf

                            {{-- Nama Pemohon --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Nama Pemohon
                                </label>
                                <input type="text" id="nama_pemohon" name="nama_pemohon" class="form-control"
                                    value="{{ old('nama_pemohon', $pemohon->nama) }}" placeholder="Masukkan nama Pemohon"
                                    maxlength="100" required>
                            </div>

                            {{-- Pilihan Jadwal --}}

                            {{-- Pilihan Jadwal --}}
                            <div class="mb-3">
                                <label for="id_settings_kunjungan" class="form-label fw-semibold">
                                    Pilih Jadwal <span class="text-danger">*</span>
                                </label>

                                <select name="id_settings_kunjungan" id="id_settings_kunjungan" class="form-select"
                                    required>

                                    <option value="">-- Pilih Jadwal --</option>

                                    @forelse($jadwals as $jadwal)
                                        <option value="{{ $jadwal->id_settings_kunjungan }}"
                                            data-tanggal="{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y-m-d') }}"
                                            data-mulai="{{ \Carbon\Carbon::parse($jadwal->jam_aktif)->format('H:i') }}"
                                            data-selesai="{{ \Carbon\Carbon::parse($jadwal->jam_non_aktif)->format('H:i') }}"
                                            @selected(old('id_settings_kunjungan') == $jadwal->id_settings_kunjungan)>
                                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d-m-Y') }}
                                            |
                                            {{ \Carbon\Carbon::parse($jadwal->jam_aktif)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($jadwal->jam_non_aktif)->format('H:i') }}
                                        </option>
                                    @empty
                                        <option value="" disabled>
                                            Belum ada jadwal tersedia
                                        </option>
                                    @endforelse

                                </select>

                                @if($jadwals->isEmpty())
                                    <small class="text-danger d-block mt-2">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        Belum ada jadwal kunjungan yang tersedia.
                                    </small>
                                @endif
                            </div>

                            {{-- Informasi Jadwal --}}
                            <div id="informasi-jadwal" class="alert alert-info d-none">

                                <div class="fw-semibold mb-1">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    Informasi Jadwal
                                </div>

                                <div id="detail-jadwal"></div>
                            </div>

                            {{-- Tanggal dan Jam --}}
                            <div class="mb-3">
                                <label for="tanggal_waktu" class="form-label fw-semibold">
                                    Tanggal dan Jam Kunjungan
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="datetime-local" name="tanggal_waktu" id="tanggal_waktu" class="form-control"
                                    value="{{ old('tanggal_waktu') }}" required {{ $jadwals->isEmpty() ? 'disabled' : '' }}>

                                <small class="text-muted">
                                    Pilih waktu sesuai jadwal yang tersedia.
                                </small>
                            </div>


                            {{-- Tujuan --}}
                            <div class="mb-3">
                                <label for="tujuannya" class="form-label fw-semibold">
                                    Tujuan Kunjungan
                                    <span class="text-danger">*</span>
                                </label>

                                <textarea name="tujuannya" id="tujuannya" rows="4" class="form-control"
                                    placeholder="Tuliskan tujuan kunjungan Anda..."
                                    required>{{ old('tujuannya') }}</textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" {{ $jadwals->isEmpty() ? 'disabled' : '' }}>

                                    <i class="bi bi-send me-2"></i>
                                    Kirim Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Informasi Jadwal --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-calendar-week me-2"></i>
                            Jadwal Kunjungan Tersedia
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Jabatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jadwals as $jadwal)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d-m-Y') }}
                                            </td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($jadwal->jam_aktif)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($jadwal->jam_non_aktif)->format('H:i') }}
                                            </td>

                                            <td>
                                                {{ $jadwal->jabatanPenduduk?->jabatan?->nama_jabatan ?? '-' }}
                                                dd({{ $jadwal->jabatanPenduduk?->penduduk?->nama ?? '-' }});
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                Belum ada jadwal tersedia.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Riwayat Pengajuan --}}
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-clock-history me-2"></i>
                    Riwayat Pengajuan Kunjungan
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Jadwal</th>
                                <th>Tujuan</th>
                                <th>Status Admin</th>
                                <th>Catatan Admin</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($kunjungans as $kunjungan)
                                                <tr>
                                                    <td>
                                                        {{ $kunjungans->firstItem() + $loop->index }}
                                                    </td>

                                                    <td>
                                                        {{ $kunjungan->tanggal_waktu
                                ? $kunjungan->tanggal_waktu->format('d-m-Y H:i')
                                : '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $kunjungan->settingsKunjungan?->tanggal
                                ? \Carbon\Carbon::parse($kunjungan->settingsKunjungan->tanggal)->format('d-m-Y')
                                : '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $kunjungan->tujuannya }}
                                                    </td>

                                                    <td>
                                                        @if($kunjungan->konfrimasi_admin === 'Y')
                                                            <span class="badge bg-success">
                                                                Diterima
                                                            </span>
                                                        @elseif($kunjungan->konfrimasi_admin === 'T')
                                                            <span class="badge bg-danger">
                                                                Ditolak
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">
                                                                Menunggu
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        {{ $kunjungan->catatan_admin ?? '-' }}
                                                    </td>
                                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Anda belum memiliki pengajuan kunjungan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $kunjungans->links() }}
                </div>
            </div>
        </div>

    </div>

    {{-- Mengatur tanggal dan jam berdasarkan jadwal terpilih --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectJadwal = document.getElementById('id_settings_kunjungan');
            const inputWaktu = document.getElementById('tanggal_waktu');
            const informasi = document.getElementById('informasi-jadwal');
            const detail = document.getElementById('detail-jadwal');

            function aturJadwal() {
                const option = selectJadwal.options[selectJadwal.selectedIndex];

                if (!option || !option.value) {
                    informasi.classList.add('d-none');
                    inputWaktu.removeAttribute('min');
                    inputWaktu.removeAttribute('max');
                    return;
                }

                const tanggal = option.dataset.tanggal;
                const mulai = option.dataset.mulai;
                const selesai = option.dataset.selesai;

                detail.textContent = `Tanggal: ${tanggal} | Jam: ${mulai} - ${selesai}`;

                informasi.classList.remove('d-none');

                inputWaktu.min = `${tanggal}T${mulai}`;
                inputWaktu.max = `${tanggal}T${selesai}`;

                inputWaktu.value = `${tanggal}T${mulai}`;
            }

            selectJadwal.addEventListener('change', aturJadwal);

            if (selectJadwal.value) {
                aturJadwal();
            }
        });
    </script>
@endsection