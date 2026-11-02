<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pemohon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:15'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:pemohon,email',
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        $pemohon = Pemohon::create([
            'nama' => $validated['name'],
            'telepon' => $request->input('telepon') ?: '-',
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($pemohon));

        Auth::guard('pemohon')->login($pemohon);

        $request->session()->regenerate();

        return redirect()->route('pemohon.dashboard');
    }
}
