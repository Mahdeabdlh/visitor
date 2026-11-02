
@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Header halaman --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Data Kunjungan
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Informasi kunjungan dan jadwal layanan yang diajukan oleh pemohon.
        </p>
    </div>

    {{-- Ringkasan jumlah data --}}
    <div class="mb-5 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                <i class="bi bi-calendar-check text-2xl"></i>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Total Data Kunjungan
                </p>

                <p class="text-2xl font-bold text-gray-800">
                    {{ $kunjungans->total() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Pencarian dan filter --}}
    <div class="mb-5 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

        <form
            action="{{ route('karyawan.datakunjungan.index') }}"
            method="GET"
            class="space-y-4"
        >
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <div>
                    <label for="search"
                        class="mb-2 block text-sm font-medium text-gray-700">
                        Cari Kunjungan
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nama, telepon, email, tujuan..."
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                    >
                </div>

                <div>
                    <label for="tanggal_awal"
                        class="mb-2 block text-sm font-medium text-gray-700">
                        Dari Tanggal
                    </label>

                    <input
                        type="date"
                        id="tanggal_awal"
                        name="tanggal_awal"
                        value="{{ request('tanggal_awal') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                    >
                </div>

                <div>
                    <label for="tanggal_akhir"
                        class="mb-2 block text-sm font-medium text-gray-700">
                        Sampai Tanggal
                    </label>

                    <input
                        type="date"
                        id="tanggal_akhir"
                        name="tanggal_akhir"
                        value="{{ request('tanggal_akhir') }}"
                        min="{{ request('tanggal_awal') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                    >
                </div>

            </div>

            <div class="flex flex-wrap gap-2">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
                >
                    <i class="bi bi-search"></i>
                    Cari
                </button>

                <a
                    href="{{ route('karyawan.datakunjungan.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100"
                >
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel data kunjungan --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-5 py-4">
            <h2 class="font-semibold text-gray-800">
                Daftar Kunjungan
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Data kunjungan terbaru ditampilkan terlebih dahulu.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left text-sm">

                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-4 py-3">No.</th>
                        <th class="px-4 py-3">Pemohon</th>
                        <th class="px-4 py-3">Kontak</th>
                        <th class="px-4 py-3">Tujuan Kunjungan</th>
                        <th class="px-4 py-3">Tanggal Kunjungan</th>
                        <th class="px-4 py-3">Jadwal Layanan</th>
                        <th class="px-4 py-3">Konfirmasi Admin</th>
                        <th class="px-4 py-3">Konfirmasi Penduduk</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse ($kunjungans as $kunjungan)
                        <tr class="align-top hover:bg-gray-50">

                            <td class="px-4 py-4 text-gray-600">
                                {{ $kunjungans->firstItem() + $loop->index }}
                            </td>

                            <td class="px-4 py-4">
                                <p class="font-medium text-gray-800">
                                    {{ $kunjungan->nama_pemohon ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    ID: {{ $kunjungan->id_kunjungan }}
                                </p>
                            </td>

                            <td class="px-4 py-4 text-gray-600">
                                <p>{{ $kunjungan->telepon ?? '-' }}</p>
                                <p class="mt-1 text-xs">
                                    {{ $kunjungan->email ?? '-' }}
                                </p>
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                {{ $kunjungan->tujuannya ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 text-gray-600">
                                @if ($kunjungan->tanggal_waktu)
                                    {{ \Carbon\Carbon::parse($kunjungan->tanggal_waktu)->format('d-m-Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="px-4 py-4 text-gray-600">
                                <p>
                                    Tanggal:
                                    @if ($kunjungan->tanggal_jadwal)
                                        {{ \Carbon\Carbon::parse($kunjungan->tanggal_jadwal)->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </p>

                                <p class="mt-1 whitespace-nowrap text-xs">
                                    Jam:
                                    {{ $kunjungan->jam_aktif ?? '-' }}
                                    –
                                    {{ $kunjungan->jam_non_aktif ?? '-' }}
                                </p>
                            </td>

                            <td class="px-4 py-4">
                                @if ($kunjungan->konfrimasi_admin === 'Y')
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                        Dikonfirmasi
                                    </span>
                                @elseif ($kunjungan->konfrimasi_admin === 'T')
                                    <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">
                                        Menunggu
                                    </span>
                                @endif

                                @if ($kunjungan->catatan_admin)
                                    <p class="mt-2 max-w-xs text-xs text-gray-500">
                                        {{ $kunjungan->catatan_admin }}
                                    </p>
                                @endif
                            </td>

                            <td class="px-4 py-4">
                                @if ($kunjungan->konfrimasi_penduduk === 'Y')
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                        Dikonfirmasi
                                    </span>
                                @elseif ($kunjungan->konfrimasi_penduduk === 'T')
                                    <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">
                                        Menunggu
                                    </span>
                                @endif

                                @if ($kunjungan->catatan_penduduk)
                                    <p class="mt-2 max-w-xs text-xs text-gray-500">
                                        {{ $kunjungan->catatan_penduduk }}
                                    </p>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                                <i class="bi bi-calendar-x mb-3 block text-3xl"></i>

                                <p class="font-medium">
                                    Data kunjungan belum ditemukan.
                                </p>

                                <p class="mt-1 text-xs">
                                    Coba ubah kata pencarian atau filter tanggal.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="border-t border-gray-200 px-5 py-4">
            {{ $kunjungans->links() }}
        </div>

    </div>
</div>
@endsection
