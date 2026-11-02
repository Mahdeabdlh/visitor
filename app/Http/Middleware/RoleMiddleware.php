<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $role,
        ?string $guard = null
    ): Response {
        $user = $guard
            ? $request->user($guard)
            : ($request->user('web') ?? $request->user('pemohon'));

        if (! $user) {
            return redirect()->route('login')->withErrors([
                'username' => 'Silakan login terlebih dahulu.',
            ]);
        }

        $actualRole = strtolower(trim((string) (
            $user->role ?? $user->status ?? ''
        )));

        if ($user instanceof \App\Models\Pemohon || isset($user->id_pemohon)) {
            $actualRole = 'pemohon';
        }

        $requiredRole = strtolower(trim($role));

        // Penduduk / Pengguna dapat menggunakan area Karyawan
        if (
            $requiredRole === 'karyawan'
            && in_array($actualRole, ['penduduk', 'pengguna', 'karyawan'], true)
        ) {
            $actualRole = 'karyawan';
        }

        if ($actualRole !== $requiredRole) {
            abort(403, 'Anda tidak memiliki izin mengakses halaman ini.');
        }

        return $next($request);
    }
}
