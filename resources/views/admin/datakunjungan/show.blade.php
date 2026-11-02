
@extends('layouts.dashboard')

@section('title', 'Detail Data Kunjungan')
@section('page-heading', 'Detail Data Kunjungan')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER HALAMAN --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Detail Data Kunjungan</h1>
            <p class="text-muted mb-0">
                Informasi lengkap permohonan kunjungan.
            </p>
        </div>

        <a href="{{ route('admin.datakunjungan.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            {{-- INFORMASI PEMOHON --}}
            <h2 class="h5 mb-3">Informasi Pemohon</h2>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <tbody>
                        <tr>
                            <th style="width: 230px;">ID Kunjungan</th>
                            <td>{{ $kunjungan->id_kunjungan }}</td>
                        </tr>
                        <tr>
                            <th>Nama Pemohon</th>
                            <td>{{ $kunjungan->pemohon?->nama ?? 'Pemohon tidak ditemukan' }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ $kunjungan->pemohon?->telepon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $kunjungan->pemohon?->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tujuan Kunjungan</th>
                            <td>{{ $kunjungan->tujuannya ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Waktu Kunjungan</th>
                            <td>
                                {{ $kunjungan->tanggal_waktu
                                    ? \Illuminate\Support\Carbon::parse($kunjungan->tanggal_waktu)->format('d-m-Y H:i')
                                    : '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- INFORMASI JADWAL --}}
            <h2 class="h5 mt-4 mb-3">Informasi Jadwal Kunjungan</h2>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <tbody>
                        <tr>
                            <th style="width: 230px;">ID Jadwal Kunjungan</th>
                            <td>
                                {{ $kunjungan->settingsKunjungan?->id_settings_kunjungan ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Jadwal</th>
                            <td>
                                @php
                                    $tanggalJadwal = $kunjungan->settingsKunjungan?->tanggal;
                                @endphp

                                {{ $tanggalJadwal
                                    ? \Illuminate\Support\Carbon::parse($tanggalJadwal)->format('d-m-Y')
                                    : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Jam Aktif</th>
                            <td>{{ $kunjungan->settingsKunjungan?->jam_aktif ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jam Nonaktif</th>
                            <td>{{ $kunjungan->settingsKunjungan?->jam_non_aktif ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- KONFIRMASI ADMIN --}}
            <h2 class="h5 mt-4 mb-3">Konfirmasi Admin</h2>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <tbody>
                        <tr>
                            <th style="width: 230px;">Status</th>
                            <td>
                                @if ($kunjungan->konfrimasi_admin === 'Y')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif ($kunjungan->konfrimasi_admin === 'T')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu konfirmasi</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Waktu Konfirmasi</th>
                            <td>
                                {{ $kunjungan->tanggal_waktu_admin
                                    ? \Illuminate\Support\Carbon::parse($kunjungan->tanggal_waktu_admin)->format('d-m-Y H:i:s')
                                    : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Catatan Admin</th>
                            <td>{{ $kunjungan->catatan_admin ?: '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- KONFIRMASI PENDUDUK --}}
            <h2 class="h5 mt-4 mb-3">Konfirmasi Penduduk</h2>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <tbody>
                        <tr>
                            <th style="width: 230px;">Status</th>
                            <td>
                                @if ($kunjungan->konfrimasi_penduduk === 'Y')
                                    <span class="badge bg-success">Dikonfirmasi</span>
                                @elseif ($kunjungan->konfrimasi_penduduk === 'T')
                                    <span class="badge bg-danger">Tidak dikonfirmasi</span>
                                @else
                                    <span class="badge bg-secondary">Belum ada konfirmasi</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Waktu Konfirmasi</th>
                            <td>
                                {{ $kunjungan->tanggal_waktu_penduduk
                                    ? \Illuminate\Support\Carbon::parse($kunjungan->tanggal_waktu_penduduk)->format('d-m-Y H:i:s')
                                    : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Catatan Penduduk</th>
                            <td>{{ $kunjungan->catatan_penduduk ?: '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- AKSI --}}
            <div class="d-flex flex-wrap gap-2 mt-4">
                <a href="{{ route('admin.datakunjungan.edit', $kunjungan->id_kunjungan) }}"
                   class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i>
                    Kelola Konfirmasi Admin
                </a>

                <a href="{{ route('admin.datakunjungan.index') }}"
                   class="btn btn-outline-secondary">
                    Tutup Detail
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
