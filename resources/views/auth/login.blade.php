<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Logo & Judul Aplikasi -->
    <div class="flex flex-col items-center justify-center mb-6 text-center">
        <div class="p-2 flex items-center justify-center">
            <img src="{{ asset('assets/images/logo.svg') }}" class="w-20 h-20 sm:w-24 sm:h-24 object-contain drop-shadow" alt="Logo Buku Tamu">
        </div>
        <h2 class="text-xl sm:text-2xl font-bold text-emerald-950 mt-2">Sistem Buku Tamu & Kunjungan</h2>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Masuk untuk mengakses portal Admin, Karyawan, atau Pemohon</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Username atau Email -->
        <div>
            <x-input-label for="email" :value="__(' Email')" class="text-emerald-900 font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-lg shadow-sm text-sm" 
                          type="text" 
                          name="email" 
                          :value="old('email')" 
                          placeholder="Masukkan  email Anda" 
                          required 
                          autofocus 
                          autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-emerald-900 font-semibold" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-lg shadow-sm text-sm"
                          type="password"
                          name="password"
                          placeholder="••••••••"
                          required 
                          autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-800 shadow-sm focus:ring-emerald-600" name="remember">
                <span class="ms-2 text-xs sm:text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs text-emerald-800 hover:text-emerald-600 hover:underline" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <!-- Tombol Login -->
        <div class="pt-2">
            <button type="submit" class="w-full bg-emerald-800 hover:bg-emerald-900 active:bg-emerald-950 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 text-white font-medium px-4 py-2.5 rounded-lg shadow transition flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span>{{ __('Masuk ke Sistem') }}</span>
            </button>
        </div>

        <!-- Registrasi Pemohon Link -->
        <div class="pt-3 border-t border-gray-100 text-center text-xs text-gray-600">
            <span>Belum memiliki akun Pemohon? </span>
            <a href="{{ route('register') }}" class="font-semibold text-emerald-800 hover:text-emerald-600 hover:underline">
                Daftar sebagai Pemohon
            </a>
        </div>
    </form>
</x-guest-layout>
