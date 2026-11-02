<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Buku Tamu Digital Pemerintah Daerah</title>
    <link href="{{ asset('assets/images/logo.svg') }}" rel="icon" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>

<body class="bg-white text-slate-800 antialiased">

    {{-- navbar --}}
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">

        {{-- @if (Route::has('login')) --}}
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">


            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-800 text-xl text-white">
                    <span aria-hidden="true">🏛</span>
                </div>

                <div>
                    <h1 class="text-lg font-bold leading-tight text-emerald-900">
                        Tamu Digital Pemerintahan Daerah
                    </h1>
                    <p class="text-xs text-slate-500">Pelayanan Pemerintah Daerah</p>
                </div>
            </a>

            <!-- Tombol menu mobile -->
            <button id="menuButton" type="button"
                class="rounded-lg border border-slate-300 p-2 text-slate-700 md:hidden" aria-label="Buka menu navigasi"
                aria-controls="mobileMenu" aria-expanded="false">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Menu desktop -->
            <div class="hidden items-center gap-7 md:flex">
                <a href="#beranda" class="font-medium text-emerald-800">Beranda</a>
                <a href="#tentang" class="text-slate-600 transition hover:text-emerald-800">Tentang</a>
                <a href="#alur" class="text-slate-600 transition hover:text-emerald-800">Alur Kunjungan</a>
                <a href="#kontak" class="text-slate-600 transition hover:text-emerald-800">Kontak</a>
            </div>
            <div class="hidden items-center gap-3 md:flex">
                @php
                    $loggedUser = auth()->guard('web')->user() ?? auth()->guard('pemohon')->user();
                @endphp
               
                @if ($loggedUser)
                    <a href="{{ route('dashboard') }}"
                        class="rounded-lg bg-emerald-800 px-5 py-2.5 font-semibold text-white transition hover:bg-emerald-900 shadow-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard Saya</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="rounded-lg border border-emerald-800 px-5 py-2.5 font-semibold text-emerald-800 transition hover:bg-emerald-50">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="rounded-lg bg-emerald-800 px-5 py-2.5 font-semibold text-white transition hover:bg-emerald-900">
                        Daftar Pemohon
                    </a>
                @endif
            </div>
        </nav>

        <!-- Menu mobile -->
        <div id="mobileMenu" class="hidden border-t border-slate-200 bg-white px-5 py-4 md:hidden">
            <div class="flex flex-col gap-4">
                <a href="#beranda" class="mobileLink font-medium">Beranda</a>
                <a href="#tentang" class="mobileLink">Tentang</a>
                <a href="#alur" class="mobileLink">Alur Kunjungan</a>
                <a href="#kontak" class="mobileLink">Kontak</a>

                <div class="flex gap-3 border-t border-slate-100 pt-4">
                    @if ($loggedUser)
                        <a href="{{ route('dashboard') }}"
                            class="flex-1 rounded-lg bg-emerald-800 px-4 py-2.5 text-center font-semibold text-white">
                            Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex-1 rounded-lg border border-emerald-800 px-4 py-2.5 text-center font-semibold text-emerald-800">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                            class="flex-1 rounded-lg bg-emerald-800 px-4 py-2.5 text-center font-semibold text-white">
                            Daftar Pemohon
                        </a>
                    @endif
                </div>
            </div>
        </div>


    </header>


    <main>
        <!-- HERO -->
        <section id="beranda" class="relative isolate overflow-hidden bg-emerald-950">
            <div class="absolute inset-0 -z-10 bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-800"></div>

            <div class="mx-auto grid max-w-7xl items-center gap-12 px-5 py-20 md:py-28 lg:grid-cols-2 lg:px-8">

                <div class="text-white">
                    <span
                        class="inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-medium text-emerald-100">
                        Pelayanan Publik Digital Pemerintah Daerah
                    </span>

                    <h2 class="mt-6 text-4xl font-extrabold leading-tight sm:text-5xl lg:text-6xl">
                        Tamu Digital
                        <span class="block text-emerald-300">Pemerintah</span>
                    </h2>

                    <p class="mt-6 max-w-xl text-base leading-8 text-emerald-50/90 sm:text-lg">
                        Sistem pencatatan kunjungan yang praktis, tertib,
                        dan mudah digunakan untuk membantu meningkatkan
                        kualitas pelayanan Pemerintah Daerah.
                    </p>

                    <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                        <a href="{{ route('register') }}"
                            class="rounded-xl bg-white px-7 py-3.5 text-center font-bold text-emerald-900 shadow-lg transition hover:bg-emerald-50">
                            Daftar sebagai Pemohon
                        </a>

                        <a href="#alur"
                            class="rounded-xl border border-white/40 px-7 py-3.5 text-center font-semibold text-white transition hover:bg-white/10">
                            Pelajari Alur Kunjungan
                        </a>
                    </div>

                    <div class="mt-10 grid grid-cols-1 gap-4 text-sm text-emerald-50 sm:grid-cols-3">
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-300">✓</span> Proses praktis
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-300">✓</span> Data tercatat
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-300">✓</span> Mudah diakses
                        </div>
                    </div>
                </div>

                <!-- Kartu informasi -->
                <div class="rounded-3xl border border-white/20 bg-white p-6 shadow-2xl sm:p-8">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-2xl">
                        📋
                    </div>

                    <h3 class="mt-5 text-2xl font-bold text-emerald-950">
                        Layanan Kunjungan
                    </h3>

                    <p class="mt-3 leading-7 text-slate-600">
                        Gunakan akun Anda untuk mengajukan kunjungan,
                        melengkapi informasi, dan memantau status pengajuan.
                    </p>

                    <div class="mt-6 space-y-4">
                        <div class="flex gap-4 rounded-xl bg-slate-50 p-4">
                            <span class="text-xl">👤</span>
                            <div>
                                <h4 class="font-semibold text-slate-800">Buat akun</h4>
                                <p class="mt-1 text-sm text-slate-500">
                                    Daftarkan informasi akun Anda.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4 rounded-xl bg-slate-50 p-4">
                            <span class="text-xl">📅</span>
                            <div>
                                <h4 class="font-semibold text-slate-800">Ajukan kunjungan</h4>
                                <p class="mt-1 text-sm text-slate-500">
                                    Pilih jadwal yang tersedia.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4 rounded-xl bg-slate-50 p-4">
                            <span class="text-xl">🔔</span>
                            <div>
                                <h4 class="font-semibold text-slate-800">Pantau status</h4>
                                <p class="mt-1 text-sm text-slate-500">
                                    Periksa informasi pengajuan Anda.
                                </p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('login') }}"
                        class="mt-6 block rounded-xl bg-emerald-800 px-5 py-3.5 text-center font-bold text-white transition hover:bg-emerald-900">
                        Masuk ke Akun
                    </a>
                </div>
            </div>
        </section>

        <!-- TENTANG -->
        <section id="tentang" class="bg-white px-5 py-20 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="mx-auto max-w-2xl text-center">
                    <span class="text-sm font-bold uppercase tracking-widest text-emerald-700">
                        Tentang Layanan
                    </span>
                    <h2 class="mt-3 text-3xl font-extrabold text-slate-900 sm:text-4xl">
                        Kunjungan lebih mudah dan teratur
                    </h2>
                    <p class="mt-5 leading-7 text-slate-600">
                        Buku Tamu Digital membantu proses pencatatan kunjungan
                        agar informasi pengajuan lebih mudah dikelola.
                    </p>
                </div>

                <div class="mt-12 grid gap-6 md:grid-cols-3">
                    <article
                        class="rounded-2xl border border-slate-200 p-7 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-2xl">⚡
                        </div>
                        <h3 class="mt-5 text-xl font-bold">Praktis</h3>
                        <p class="mt-3 leading-7 text-slate-600">
                            Informasi kunjungan dapat diajukan melalui halaman web.
                        </p>
                    </article>

                    <article
                        class="rounded-2xl border border-slate-200 p-7 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-2xl">🔒
                        </div>
                        <h3 class="mt-5 text-xl font-bold">Terorganisasi</h3>
                        <p class="mt-3 leading-7 text-slate-600">
                            Data dapat dikelola secara terstruktur sesuai hak akses.
                        </p>
                    </article>

                    <article
                        class="rounded-2xl border border-slate-200 p-7 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-2xl">📱
                        </div>
                        <h3 class="mt-5 text-xl font-bold">Responsif</h3>
                        <p class="mt-3 leading-7 text-slate-600">
                            Tampilan menyesuaikan komputer, tablet, maupun ponsel.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <!-- ALUR KUNJUNGAN -->
        <section id="alur" class="bg-slate-50 px-5 py-20 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">
                        Alur Kunjungan
                    </h2>
                    <p class="mt-4 text-slate-600">
                        Ikuti langkah-langkah berikut untuk memulai.
                    </p>
                </div>

                <div class="mt-12 grid gap-8 md:grid-cols-3">
                    <div class="text-center">
                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-800 text-xl font-bold text-white">
                            1</div>
                        <h3 class="mt-5 text-lg font-bold">Registrasi akun</h3>
                        <p class="mt-2 leading-7 text-slate-600">
                            Buat akun menggunakan informasi yang benar.
                        </p>
                    </div>

                    <div class="text-center">
                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-800 text-xl font-bold text-white">
                            2</div>
                        <h3 class="mt-5 text-lg font-bold">Ajukan jadwal</h3>
                        <p class="mt-2 leading-7 text-slate-600">
                            Isi tujuan dan waktu kunjungan yang diinginkan.
                        </p>
                    </div>

                    <div class="text-center">
                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-800 text-xl font-bold text-white">
                            3</div>
                        <h3 class="mt-5 text-lg font-bold">Tunggu konfirmasi</h3>
                        <p class="mt-2 leading-7 text-slate-600">
                            Periksa status pengajuan melalui akun Anda.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="px-5 py-16 lg:px-8">
            <div
                class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 rounded-3xl bg-emerald-900 p-8 text-white sm:p-12 md:flex-row md:items-center">
                <div>
                    <h2 class="text-2xl font-extrabold sm:text-3xl">
                        Siap mengajukan kunjungan?
                    </h2>
                    <p class="mt-3 leading-7 text-emerald-100">
                        Masuk atau buat akun untuk memulai proses pengajuan.
                    </p>
                </div>

                <a href="{{ route('register') }}"
                    class="shrink-0 rounded-xl bg-white px-6 py-3.5 font-bold text-emerald-900 transition hover:bg-emerald-50">
                    Daftar Sekarang
                </a>
            </div>
        </section>
    </main>


    <!-- FOOTER -->
    <footer id="kontak" class="border-t border-slate-200 bg-white px-5 py-8 lg:px-8">
        <div
            class="mx-auto flex max-w-7xl flex-col gap-3 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} Tamu Digital Pemerintahan</p>
            <p>Pelayanan publik yang praktis dan terorganisasi.</p>
        </div>
    </footer>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
    <!-- Script navbar mobile -->
    <script>
        const menuButton = document.getElementById('menuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        menuButton.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.toggle('hidden');
            menuButton.setAttribute('aria-expanded', String(!isHidden));
        });

        document.querySelectorAll('.mobileLink').forEach((link) => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuButton.setAttribute('aria-expanded', 'false');
            });
        });
    </script>
</body>

</html>