<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{


    /**
     * Display the login view.
     */


    public function create(): View
    {

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::guard('web')->user() ?? Auth::guard('pemohon')->user();

        if (! $user) {

            return redirect()->route('login')->withErrors([
                'email' => 'Sesi login tidak valid.',
            ]);
        }

        // 1. Role Admin
        if (($user->status ?? '') === 'Admin' || ($user->role ?? '') === 'Admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        // 2. Role Karyawan / Pengguna (Penduduk)
        if (
            in_array($user->status ?? '', ['Karyawan', 'Pengguna', 'Penduduk'], true)
            || ($user->role ?? '') === 'Karyawan'
        ) {
            return redirect()->intended(route('karyawan.dashboard'));
        }

        // 3. Role Pemohon
        return redirect()->intended(route('pemohon.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {   
        Auth::guard('web')->logout();
        Auth::guard('pemohon')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
