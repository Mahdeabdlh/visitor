@php
    $pendudukLogin = auth()->guard('web')->user();
    $pemohonLogin = auth()->guard('pemohon')->user();

    $isAdmin = $pendudukLogin && (($pendudukLogin->status ?? '') === 'Admin' || ($pendudukLogin->role ?? '') === 'Admin');
    $isKaryawan = $pendudukLogin && (in_array($pendudukLogin->status ?? '', ['Penduduk', 'Karyawan', 'Pengguna'], true) || ($pendudukLogin->role ?? '') === 'Karyawan');
    $isPemohon = (bool) $pemohonLogin;
@endphp

<nav class="sidebar-nav flex-1 overflow-y-auto" aria-label="Sidebar navigation">

    {{-- ROLE 1: ADMIN --}}
    @if ($isAdmin)

        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            href="{{ route('admin.dashboard') }}">
            <span class="nav-icon">
                <i class="bi bi-speedometer2" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Dashboard</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.datapenduduk.*') ? 'active' : '' }}"
            href="{{ route('admin.datapenduduk.index') }}">
            <span class="nav-icon">
                <i class="bi bi-people" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Data Penduduk</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.datajabatan.*') ? 'active' : '' }}"
            href="{{ route('admin.datajabatan.index') }}">
            <span class="nav-icon">
                <i class="bi bi-person-plus" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Data Jabatan</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.datakaryawan.*') ? 'active' : '' }}"
            href="{{ route('admin.datakaryawan.index') }}">
            <span class="nav-icon">
                <i class="bi bi-person-badge" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Data Karyawan</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.jadwalkunjungan.*') ? 'active' : '' }}"
            href="{{ route('admin.jadwalkunjungan.index') }}">
            <span class="nav-icon">
                <i class="bi bi-calendar-event" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Jadwal Kunjungan</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.datakunjungan.*') ? 'active' : '' }}"
            href="{{ route('admin.datakunjungan.index') }}">
            <span class="nav-icon">
                <i class="bi bi-calendar-check" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Data Kunjungan</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}"
            href="{{ route('admin.laporan.index') }}">
            <span class="nav-icon">
                <i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Laporan</span>
        </a>

        <a class="nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}"
            href="{{ route('admin.pengaturan.index') }}">
            <span class="nav-icon">
                <i class="bi bi-gear" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Pengaturan</span>
        </a>

        {{-- ROLE 2: KARYAWAN / PENGGUNA (PENDUDUK) --}}
    @elseif ($isKaryawan)

        <a class="nav-link {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}"
            href="{{ route('karyawan.dashboard') }}">
            <span class="nav-icon">
                <i class="bi bi-speedometer2" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Dashboard</span>
        </a>

        <a class="nav-link {{ request()->routeIs('karyawan.jadwal_kegiatan.*') ? 'active' : '' }}"
            href="{{ route('karyawan.jadwal_kegiatan.index') }}">
            <span class="nav-icon">
                <i class="bi bi-calendar-event" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Jadwal Kegiatan</span>
        </a>

        <a class="nav-link {{ request()->routeIs('karyawan.datakunjungan.*') ? 'active' : '' }} 
                                " href="{{ route('karyawan.datakunjungan.index') }}">
            <span class="nav-icon">
                <i class="bi bi-calendar-check" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Data Kunjungan</span>
        </a>


        <a class="nav-link {{ request()->routeIs('karyawan.konfirmasi_kunjungan.*') ? 'active' : '' }}"
            href="{{ route('karyawan.konfirmasi_kunjungan.index') }}">
            <span class="nav-icon">
                <i class="bi bi-calendar2-check me-2" aria-hidden="true"></i>
            </span>
            <span>Konfirmasi Kunjungan</span>
        </a>

        <a class="nav-link {{ request()->routeIs('karyawan.profil.*') ? 'active' : '' }}"
            href="{{ route('karyawan.profil.index') }}">
            <span class="nav-icon">
                <i class="bi bi-person-circle me-2" aria-hidden="true"></i>
            </span>
            <span>Profil Saya</span>
        </a>

        {{-- ROLE 3: PEMOHON --}}
    @elseif ($isPemohon)

        <a class="nav-link {{ request()->routeIs('pemohon.dashboard') ? 'active' : '' }}"
            href="{{ route('pemohon.dashboard') }}">
            <span class="nav-icon">
                <i class="bi bi-speedometer2" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Dashboard</span>
        </a>

        {{-- <a class="nav-link {{ request()->routeIs('pemohon.kunjungan.*') ? 'active' : '' }}"
            href="{{ route('pemohon.kunjungan.index') }}">
            <span class="nav-icon">
                <i class="bi bi-calendar-check" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Kunjungan Saya</span>
        </a> --}}
        <a class="nav-link {{ request()->routeIs('pemohon.ajukan_kunjungan.*') ? 'active' : '' }}"
            href="{{ route('pemohon.ajukan_kunjungan.index') }}">
            <span class="nav-icon">
                <i class="bi bi-calendar-check" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Ajukan Kunjungan</span>
        </a>

        <a class="nav-link" href="{{ route('pemohon.riwayat_kunjungan.index') }}"
            {{ request()->routeIs('pemohon.riwayat_kunjungan.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <i class="bi bi-calendar-check me-2" aria-hidden="true"></i>
            </span>
            <span>Riwayat Kunjungan</span>

        </a>


        <a class="nav-link {{ request()->routeIs('pemohon.status_kunjungan.*') ? 'active' : '' }}"
            href="{{ route('pemohon.status_kunjungan.index') }}">
            <span class="nav-icon">
                <i class="bi bi-clipboard-check" aria-hidden="true"></i>
            </span>
            <span>Status Kunjungan</span>

        </a>

        
        <a class="nav-link {{ request()->routeIs('pemohon.profil.*') ? 'active' : '' }}" 
            href="{{ route('pemohon.profil.edit') }}">
            <span class="nav-icon">
                <i class="bi bi-person-circle" aria-hidden="true"></i>
            </span>
            <span>Profil Saya</span>

        </a>




    @endif

</nav>