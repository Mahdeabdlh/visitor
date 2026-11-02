<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Sistem Informasi Buku Tamu dan Kunjungan Desa">
  <title>@yield('title') - Buku Tamu</title>

  <!-- Bootstrap 5 & Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- Custom Admin Styles -->
  <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

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

<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Navigasi Utama">
      <div class="sidebar-header">
        <a class="brand-mark" href="{{ route('dashboard') }}" aria-label="Buku Tamu">
          <span class="brand-icon"><i class="bi bi-person-badge-fill" aria-hidden="true"></i></span>
          <span class="brand-copy">
            <span class="brand-title">Buku Tamu</span>
            <span class="brand-subtitle">Panel {{ $roleName }}</span>
          </span>
        </a>
      </div>

      @include('partials.sidebar')

      <div class="sidebar-user d-flex align-items-center gap-2 px-3 py-2 border-top border-secondary-subtle">
        <span class="avatar-fallback rounded-circle bg-emerald-600 text-white fw-bold d-inline-flex align-items-center justify-center flex-shrink-0" style="width: 38px; height: 38px; font-size: 15px;">
            {{ strtoupper(substr($userName, 0, 1)) }}
        </span>
        <div class="text-truncate">
            <strong class="d-block text-truncate text-white" style="font-size: 0.9rem;">{{ $userName }}</strong>
            <small class="text-muted d-block" style="font-size: 0.75rem;">{{ $roleName }}</small>
        </div>
      </div>

      <div class="sidebar-footer">
        <span class="status-dot"></span>
        <span class="sidebar-footer-text">Sistem Online</span>
      </div>
    </aside>

    <div class="admin-main">
      <nav class="navbar admin-navbar navbar-expand bg-white border-bottom">
        @include('partials.navbar')
      </nav>

      <main class="dashboard-content">
        @yield('content')
      </main>

      <footer class="admin-footer">
        @include('partials.footer')
      </footer>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('assets/js/admin.js') }}"></script>
</body>

</html>
