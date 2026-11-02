@php
    $currentUser = auth()->guard('web')->user() ?? auth()->guard('pemohon')->user();
    $userName = $currentUser->nama_penduduk ?? $currentUser->nama ?? $currentUser->name ?? 'Pengguna';
    $roleName = 'Pengguna';

    if (($currentUser->status ?? '') === 'Admin' || ($currentUser->role ?? '') === 'Admin') {
        $roleName = 'Administrator';
    } elseif (in_array($currentUser->status ?? '', ['Penduduk', 'Karyawan', 'Pengguna'], true) || ($currentUser->role ?? '') === 'Karyawan') {
        $roleName = 'Karyawan';
    } elseif ($currentUser instanceof \App\Models\Pemohon || isset($currentUser->id_pemohon)) {
        $roleName = 'Pemohon';
    }
@endphp

<div class="container-fluid px-3 px-lg-4">
    <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="d-none d-md-flex align-items-center ms-3">
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 rounded-pill fw-medium">
            <i class="bi bi-shield-check me-1"></i> Panel {{ $roleName }}
        </span>
    </div>

    <div class="navbar-actions ms-auto d-flex align-items-center gap-2">
        <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Ganti mode tema" title="Ganti mode tema">
            <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
        </button>

        <div class="dropdown">
            <button class="profile-button dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar-fallback rounded-circle bg-emerald-700 text-white fw-bold d-inline-flex align-items-center justify-center" style="width: 36px; height: 36px; font-size: 14px;">
                    {{ strtoupper(substr($userName, 0, 1)) }}
                </span>
                <div class="text-start d-none d-sm-block">
                    <span class="profile-name fw-semibold d-block text-truncate" style="max-width: 150px;">{{ $userName }}</span>
                    <span class="badge bg-secondary-subtle text-secondary small py-0 px-1">{{ $roleName }}</span>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li class="px-3 py-2 border-bottom">
                    <p class="mb-0 fw-semibold">{{ $userName }}</p>
                    <small class="text-muted">{{ $roleName }}</small>
                </li>
                <li>
                    <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('welcome') }}">
                        <i class="bi bi-house text-muted"></i>
                        <span>Halaman Utama</span>
                    </a>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger d-flex align-items-center gap-2">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Keluar (Logout)</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
