<?php

namespace App\Http\Controllers;

use App\Models\JabatanPenduduk;
use App\Models\Kunjungan;
use App\Models\SettingsKunjungan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class SettingsKunjunganController extends Controller
{
    public function index(Request $request)
    {
        $query = SettingsKunjungan::query()
            ->with('jabatanPenduduk.penduduk', 'jabatanPenduduk.jabatan');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('jabatanPenduduk.penduduk', function ($q) use ($search) {
                $q->where('nama_penduduk', 'like', "%{$search}%");
            })->orWhere('tanggal', 'like', "%{$search}%");
        }

        if (
            $request->filled('status') &&
            in_array($request->status, ['Y', 'T'], true)
        ) {
            $query->where('status', $request->status);
        }

        $jadwals = $query
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jadwalkunjungan.index', compact('jadwals'));
    }

    public function create()
    {
        $jabatanPenduduks = JabatanPenduduk::with([
            'penduduk',
            'jabatan',
        ])->get();

        return view('admin.jadwalkunjungan.create', compact('jabatanPenduduks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jabatan_penduduk' => [
                'required',
                'integer',
                'exists:jabatan_penduduk,id_jabatan_penduduk',
            ],
            'tanggal' => ['required', 'date'],
            'jam_aktif' => ['required', 'date_format:H:i'],
            'jam_non_aktif' => [
                'required',
                'date_format:H:i',
                'after:jam_aktif',
            ],
            'status' => ['required', Rule::in(['Y', 'T'])],
        ]);


        SettingsKunjungan::create($validated);
        return redirect()
            ->route('admin.jadwalkunjungan.index')
            ->with('success', 'Jadwal kunjungan berhasil ditambahkan.');
    }

    public function show(int $id)
    {
        $jadwal = SettingsKunjungan::with([
            'jabatanPenduduk.penduduk',
            'jabatanPenduduk.jabatan',
        ])->findOrFail($id);

        return view('admin.jadwalkunjungan.show', compact('jadwal'));
    }

    public function edit(int $id)
    {
        $jadwal = SettingsKunjungan::findOrFail($id);

        $jabatanPenduduks = JabatanPenduduk::with([
            'penduduk',
            'jabatan',
        ])->get();

        return view('admin.jadwalkunjungan.edit', compact(
            'jadwal',
            'jabatanPenduduks'
        ));
    }

    public function update(Request $request, int $id)
    {
        $jadwal = SettingsKunjungan::findOrFail($id);

        $validated = $request->validate([
            'id_jabatan_penduduk' => [
                'required',
                'integer',
                'exists:jabatan_penduduk,id_jabatan_penduduk',
            ],
            'tanggal' => ['required', 'date'],
            'jam_aktif' => ['required', 'date_format:H:i'],
            'jam_non_aktif' => [
                'required',
                'date_format:H:i',
                'after:jam_aktif',
            ],
            'status' => ['required', Rule::in(['Y', 'T'])],
        ]);

        $jadwal->update($validated);

        return redirect()
            ->route('admin.jadwalkunjungan.index')
            ->with('success', 'Jadwal kunjungan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $jadwal = SettingsKunjungan::findOrFail($id);

        try {
            $jadwal->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('admin.jadwalkunjungan.index')
                ->with(
                    'error',
                    'Jadwal tidak dapat dihapus karena sudah digunakan oleh data kunjungan.'
                );
        }

        return redirect()
            ->route('admin.jadwalkunjungan.index')
            ->with('success', 'Jadwal kunjungan berhasil dihapus.');
    }


    /**
     * Menampilkan halaman Ajukan Kunjungan untuk Pemohon.
     */



    public function riwayatKunjungan(Request $request)
    {
        // 1. Mengambil akun Pemohon yang sedang login.
        $pemohon = Auth::guard('pemohon')->user();

        // 2. Memastikan Pemohon sudah login.
        if (! $pemohon) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        // 3. Query riwayat kunjungan milik Pemohon.
        $query = Kunjungan::with([
            'pemohon',
            'settingsKunjungan.jabatanPenduduk.jabatan',
            'settingsKunjungan.jabatanPenduduk.penduduk',
        ])
            ->where('id_pemohon', $pemohon->id_pemohon);

        // 4. Filter pencarian berdasarkan tujuan kunjungan.
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('tujuannya', 'like', "%{$search}%");
        }

        // 5. Filter tanggal kunjungan.
        if ($request->filled('tanggal_awal')) {
            $query->whereDate(
                'tanggal_waktu',
                '>=',
                $request->tanggal_awal
            );
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate(
                'tanggal_waktu',
                '<=',
                $request->tanggal_akhir
            );
        }

        // 6. Filter status konfirmasi Admin.
        if ($request->filled('status')) {
            if ($request->status === 'menunggu') {
                $query->whereNull('konfrimasi_admin');
            } elseif ($request->status === 'diterima') {
                $query->where('konfrimasi_admin', 'Y');
            } elseif ($request->status === 'ditolak') {
                $query->where('konfrimasi_admin', 'T');
            }
        }

        // 7. Mengambil data dan pagination.
        $kunjungans = $query
            ->orderByDesc('tanggal_waktu')
            ->paginate(10)
            ->withQueryString();

        // 8. Menghitung statistik milik Pemohon.
        $totalKunjungan = Kunjungan::where(
            'id_pemohon',
            $pemohon->id_pemohon
        )->count();

        $menunggu = Kunjungan::where(
            'id_pemohon',
            $pemohon->id_pemohon
        )->whereNull('konfrimasi_admin')->count();

        $diterima = Kunjungan::where(
            'id_pemohon',
            $pemohon->id_pemohon
        )->where('konfrimasi_admin', 'Y')->count();

        $ditolak = Kunjungan::where(
            'id_pemohon',
            $pemohon->id_pemohon
        )->where('konfrimasi_admin', 'T')->count();

        // 9. Menampilkan halaman Riwayat Kunjungan.
        return view('pemohon.riwayat_kunjungan.index', compact(
            'pemohon',
            'kunjungans',
            'totalKunjungan',
            'menunggu',
            'diterima',
            'ditolak'
        ));
    }

    /**
     * Menampilkan status pengajuan kunjungan milik Pemohon.
     */
    public function statusKunjungan(Request $request)
    {
        // Mengambil akun Pemohon yang sedang login.
        $pemohon = Auth::guard('pemohon')->user();

        if (! $pemohon) {
            return redirect()->route('login');
        }

        // Mengambil data kunjungan milik Pemohon saja.
        $query = Kunjungan::with([
            'pemohon',
            'settingsKunjungan.jabatanPenduduk.jabatan',
            'settingsKunjungan.jabatanPenduduk.penduduk',
        ])->where('id_pemohon', $pemohon->id_pemohon);

        // Pencarian berdasarkan tujuan kunjungan.
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('tujuannya', 'like', "%{$search}%");
        }

        // Filter berdasarkan tanggal pengajuan.
        if ($request->filled('tanggal_awal')) {
            $query->whereDate(
                'tanggal_waktu',
                '>=',
                $request->tanggal_awal
            );
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate(
                'tanggal_waktu',
                '<=',
                $request->tanggal_akhir
            );
        }

        // Mengambil data dan mengurutkannya dari yang terbaru.
        $kunjungan = $query
            ->orderByDesc('tanggal_waktu')
            ->paginate(10)
            ->withQueryString();

        // Menghitung ringkasan status.
        $totalKunjungan = Kunjungan::where(
            'id_pemohon',
            $pemohon->id_pemohon
        )->count();

        $menunggu = Kunjungan::where(
            'id_pemohon',
            $pemohon->id_pemohon
        )->whereNull('konfrimasi_admin')->count();

        $diterima = Kunjungan::where(
            'id_pemohon',
            $pemohon->id_pemohon
        )->where('konfrimasi_admin', 'Y')->count();

        $ditolak = Kunjungan::where(
            'id_pemohon',
            $pemohon->id_pemohon
        )->where('konfrimasi_admin', 'T')->count();

        return view('pemohon.status_kunjungan.index', compact(
            'kunjungan',
            'totalKunjungan',
            'menunggu',
            'diterima',
            'ditolak'
        ));
    }
}
