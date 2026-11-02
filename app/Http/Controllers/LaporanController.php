<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan kunjungan.
     */
    public function index(Request $request)
    {
        // Query dasar laporan.
        $query = Kunjungan::with('pemohon');

        // 1. Filter pencarian.
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('tujuannya', 'like', "%{$search}%")
                    ->orWhereHas('pemohon', function ($pemohon) use ($search) {
                        $pemohon->where('nama', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // 2. Filter tanggal mulai.
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate(
                'tanggal_waktu',
                '>=',
                $request->input('tanggal_mulai')
            );
        }

        // 3. Filter tanggal akhir.
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate(
                'tanggal_waktu',
                '<=',
                $request->input('tanggal_akhir')
            );
        }

        // 4. Hitung ringkasan laporan.
        // Ringkasan mengikuti pencarian dan periode tanggal.
        $totalKunjungan = (clone $query)->count();

        $jumlahDisetujui = (clone $query)
            ->where('konfrimasi_admin', 'Y')
            ->count();

        $jumlahDitolak = (clone $query)
            ->where('konfrimasi_admin', 'T')
            ->count();

        $jumlahMenunggu = (clone $query)
            ->whereNull('konfrimasi_admin')
            ->count();

        // 5. Filter status khusus untuk tabel.
        $status = $request->input('status');

        if (in_array($status, ['Y', 'T', 'pending'], true)) {
            if ($status === 'pending') {
                $query->whereNull('konfrimasi_admin');
            } else {
                $query->where('konfrimasi_admin', $status);
            }
        }

        // 6. Ambil data untuk tabel.
        $kunjungans = $query
            ->orderByDesc('tanggal_waktu')
            ->paginate(10)
            ->withQueryString();

        // 7. Kirim semua variabel ke view.
        return view('admin.laporan.index', [
            'kunjungans' => $kunjungans,
            'totalKunjungan' => $totalKunjungan,
            'jumlahDisetujui' => $jumlahDisetujui,
            'jumlahDitolak' => $jumlahDitolak,
            'jumlahMenunggu' => $jumlahMenunggu,
        ]);
    }
}
