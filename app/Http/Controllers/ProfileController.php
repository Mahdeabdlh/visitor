<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\models\Pemohon;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
     private function getPenduduk(): Penduduk
    {
        $user = Auth::guard('web')->user();

        if (! $user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        // Menghubungkan akun login dengan tabel penduduk
        // berdasarkan email yang sama.
        $penduduk = Penduduk::where('email', $user->email)->first();

        if (! $penduduk) {
            abort(403, 'Data profil Penduduk tidak ditemukan.');
        }

        if ($penduduk->status !== 'Penduduk') {
            abort(403, 'Halaman ini hanya untuk Karyawan.');
        }

        return $penduduk;
    }

    /**
     * Menampilkan halaman profil Karyawan.
     */
    public function index(): View
    {
        $penduduk = $this->getPenduduk();

        return view(
            'karyawan.profil.index',
            compact('penduduk')
        );
    }


    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $penduduk = $this->getPenduduk();

        $validated = $request->validate([
            'nama_penduduk' => [
                'required',
                'string',
                'max:100',
            ],

            'alamat' => [
                'required',
                'string',
                'max:255',
            ],
        ], [
            'nama_penduduk.required' => 'Nama lengkap wajib diisi.',
            'nama_penduduk.max' => 'Nama maksimal 100 karakter.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',
        ]);

        $penduduk->update($validated);

        return redirect()
            ->route('karyawan.profil.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
 * Menampilkan halaman profil Pemohon.
 */
public function editPemohon(): View
{
    $pemohon = Auth::guard('pemohon')->user();

    if (! $pemohon) {
        abort(401, 'Silakan login sebagai Pemohon terlebih dahulu.');
    }

    return view('pemohon.profil.edit', compact('pemohon'));
}

/**
 * Memperbarui profil Pemohon.
 */
public function updatePemohon(Request $request): RedirectResponse
{
    $pemohon = Auth::guard('pemohon')->user();

    if (! $pemohon) {
        abort(401, 'Silakan login sebagai Pemohon terlebih dahulu.');
    }

    $validated = $request->validate([
        'nama' => ['required', 'string', 'max:255'],
        'telepon' => ['required', 'string', 'max:20'],
        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('pemohon', 'email')
                ->ignore($pemohon->id_pemohon, 'id_pemohon'),
        ],
        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
    ]);

    // Password tidak diubah jika kolom password kosong.
    if (empty($validated['password'])) {
        unset($validated['password']);
    }

    // Perbarui data Pemohon.
    $pemohon->update($validated);

    // Kembali ke halaman edit profil.
    return redirect()
        ->route('pemohon.profil.edit')
        ->with('success', 'Profil Pemohon berhasil diperbarui.');
}


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
