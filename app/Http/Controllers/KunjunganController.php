<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Concerns\CrudController;
use App\Models\Kunjungan;
use App\Models\SettingsKunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class KunjunganController extends CrudController
{
    /**
     * Menampilkan daftar data kunjungan.
     */
    public function index(Request $request)
    {
        $query = Kunjungan::with('pemohon');

        // Pencarian berdasarkan nama, email, atau tujuan.
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

        // Filter berdasarkan konfirmasi admin.
        $status = $request->input('status');

        if (in_array($status, ['Y', 'T', 'pending'], true)) {
            if ($status === 'pending') {
                $query->whereNull('konfrimasi_admin');
            } else {
                $query->where('konfrimasi_admin', $status);
            }
        }

        $kunjungans = $query
            ->orderByDesc('tanggal_waktu')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.datakunjungan.index',
            compact('kunjungans')
        );
    }

    /**
     * Menampilkan detail data kunjungan.
     */
    public function show(int $id)
    {
        $kunjungan = Kunjungan::with([
            'pemohon',
            'settingsKunjungan.jabatanPenduduk.penduduk',
            'settingsKunjungan.jabatanPenduduk.jabatan',
        ])->findOrFail($id);

        return view('admin.datakunjungan.show', compact('kunjungan'));
    }

    /**
     * Menampilkan formulir edit atau konfirmasi admin.
     */
    public function edit(int $id)
    {
        $kunjungan = Kunjungan::with('pemohon')
            ->findOrFail($id);

        return view('admin.datakunjungan.edit', compact('kunjungan'));
    }

    /**
     * Memperbarui konfirmasi dan catatan admin.
     */
    public function update(Request $request, int $id)
    {
        $kunjungan = Kunjungan::findOrFail($id);

        $validated = $request->validate([
            'konfrimasi_admin' => [
                'required',
                Rule::in(['Y', 'T']),
            ],
            'catatan_admin' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $validated['tanggal_waktu_admin'] = now();

        $kunjungan->update($validated);

        return redirect()
            ->route('admin.datakunjungan.show', [
                'id' => $kunjungan->id_kunjungan,
            ])
            ->with('success', 'Konfirmasi kunjungan berhasil diperbarui.');
    }

    /**
     * Menghapus data kunjungan.
     */
    public function destroy(int $id)
    {
        $kunjungan = Kunjungan::findOrFail($id);

        try {
            $kunjungan->delete();

            return redirect()
                ->route('admin.datakunjungan.index')
                ->with('success', 'Data kunjungan berhasil dihapus.');
        } catch (QueryException $e) {
            report($e);

            return redirect()
                ->route('admin.datakunjungan.index')
                ->with(
                    'error',
                    'Data kunjungan tidak dapat dihapus karena masih terkait dengan data lain.'
                );
        }
    }

    public function indexKaryawan(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'tanggal_awal' => ['nullable', 'date'],
            'tanggal_akhir' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_awal',
            ],
        ]);

        // Mengambil data kunjungan, pemohon, dan jadwal layanan.
        $query = DB::table('kunjungan')
            ->leftJoin(
                'pemohon',
                'kunjungan.id_pemohon',
                '=',
                'pemohon.id_pemohon'
            )
            ->leftJoin(
                'settings_kunjungan',
                'kunjungan.id_settings_kunjungan',
                '=',
                'settings_kunjungan.id_settings_kunjungan'
            )
            ->select(
                'kunjungan.id_kunjungan',
                'kunjungan.id_pemohon',
                'kunjungan.id_settings_kunjungan',
                'kunjungan.tujuannya',
                'kunjungan.tanggal_waktu',
                'kunjungan.konfrimasi_admin',
                'kunjungan.tanggal_waktu_admin',
                'kunjungan.konfrimasi_penduduk',
                'kunjungan.tanggal_waktu_penduduk',
                'kunjungan.catatan_admin',
                'kunjungan.catatan_penduduk',
                'pemohon.nama as nama_pemohon',
                'pemohon.telepon',
                'pemohon.email',
                'settings_kunjungan.tanggal as tanggal_jadwal',
                'settings_kunjungan.jam_aktif',
                'settings_kunjungan.jam_non_aktif',
                'settings_kunjungan.status as status_jadwal'
            );

        // Pencarian berdasarkan nama pemohon, tujuan, telepon, atau email.
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('pemohon.nama', 'like', "%{$search}%")
                    ->orWhere('pemohon.telepon', 'like', "%{$search}%")
                    ->orWhere('pemohon.email', 'like', "%{$search}%")
                    ->orWhere('kunjungan.tujuannya', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan tanggal dan waktu kunjungan yang diajukan.
        if ($request->filled('tanggal_awal')) {
            $query->whereDate(
                'kunjungan.tanggal_waktu',
                '>=',
                $request->input('tanggal_awal')
            );
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate(
                'kunjungan.tanggal_waktu',
                '<=',
                $request->input('tanggal_akhir')
            );
        }

        $kunjungans = $query
            ->orderBy('kunjungan.tanggal_waktu', 'desc')
            ->orderBy('kunjungan.id_kunjungan', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view(
            'karyawan.datakunjungan.index',
            compact('kunjungans')
        );
    }



    public function indexKonfirmasiKaryawan(Request $request)
    {
        $user = Auth::guard('web')->user();

        if (! $user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }
        // Mengambil ID Penduduk dari akun Karyawan yang login.
        $idPenduduk = $user->id_penduduk;

        if (! $idPenduduk) {
            abort(
                403,
                'ID Penduduk pada akun Karyawan tidak ditemukan.'
            );
        }

        $query = Kunjungan::query()
            ->with([
                'settingsKunjungan.jabatanPenduduk.penduduk',
                'settingsKunjungan.jabatanPenduduk.jabatan',
            ])
            // Hanya kunjungan yang sudah dikonfirmasi Admin.
            ->where('konfrimasi_admin', 'Y')
            // Hanya jadwal yang menjadi tanggung jawab Karyawan login.
            ->whereHas(
                'settingsKunjungan.jabatanPenduduk',
                function ($q) use ($idPenduduk) {
                    $q->where('id_penduduk', $idPenduduk);
                }
            );

        // Pencarian tujuan dan catatan Admin.
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('tujuannya', 'LIKE', "%{$search}%")
                    ->orWhere('catatan_admin', 'LIKE', "%{$search}%");
            });
        }

        // Filter tanggal awal.
        if ($request->filled('tanggal_awal')) {
            $query->whereDate(
                'tanggal_waktu',
                '>=',
                $request->input('tanggal_awal')
            );
        }

        // Filter tanggal akhir.
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate(
                'tanggal_waktu',
                '<=',
                $request->input('tanggal_akhir')
            );
        }

        $kunjungans = $query
            ->orderByDesc('tanggal_waktu')
            ->paginate(10)
            ->withQueryString();

        return view(
            'konfirmasi_kunjungan.index',
            compact('kunjungans')
        );
        // dd(view('karyawan.konfirmasi_kunjungan.index', compact('kunjungans')));
    }

    public function simpanKonfirmasiKaryawan(Request $request, int $id)
    {
        $user = Auth::guard('web')->user();
        if (! $user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        $idPenduduk = $user->id_penduduk;

        if (! $idPenduduk) {
            abort(
                403,
                'ID Penduduk pada akun Karyawan tidak ditemukan.'
            );
        }

        $data = $request->validate([
            'konfrimasi_penduduk' => [
                'required',
                Rule::in(['Y', 'T']),
            ],
            'catatan_penduduk' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        // Pastikan kunjungan memang menjadi tanggung jawab Karyawan ini.
        $kunjungan = Kunjungan::query()
            ->where('id_kunjungan', $id)
            ->where('konfrimasi_admin', 'Y')
            ->whereHas(
                'settingsKunjungan.jabatanPenduduk',
                function ($q) use ($idPenduduk) {
                    $q->where('id_penduduk', $idPenduduk);
                }
            )
            ->firstOrFail();


        // Jangan izinkan konfirmasi kedua kali.
        abort_if(
            in_array(
                $kunjungan->konfrimasi_penduduk,
                ['Y', 'T'],
                true
            ),
            403,
            'Kunjungan ini sudah dikonfirmasi.'
        );

        $kunjungan->konfrimasi_penduduk =
            $data['konfrimasi_penduduk'];

        $kunjungan->tanggal_waktu_penduduk = now();

        $kunjungan->catatan_penduduk =
            $data['catatan_penduduk'] ?? null;

        $kunjungan->save();

        return redirect()
            ->route('karyawan.konfirmasi_kunjungan.index')
            ->with(
                'success',
                $data['konfrimasi_penduduk'] === 'Y'
                    ? 'Kunjungan berhasil diterima.'
                    : 'Kunjungan berhasil ditolak.'
            );
    }


    /**
     * Menampilkan halaman pengajuan kunjungan Pemohon.
     */
    public function indexAjukanKunjungan()
    {
        $pemohon = Auth::guard('pemohon')->user();
        if (! $pemohon) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        // Mengambil jadwal yang masih aktif dan belum lewat.
        $jadwals = SettingsKunjungan::with([
            'jabatanPenduduk.jabatan',
            'jabatanPenduduk.penduduk',
        ])
            ->where('status', 'Y')
            ->whereDate('tanggal', '>=', today())
            ->orderBy('tanggal')
            ->orderBy('jam_aktif')
            ->get();

        // Mengambil riwayat pengajuan milik Pemohon yang login.
        $kunjungans = Kunjungan::with([
            'settingsKunjungan.jabatanPenduduk.jabatan',
            'settingsKunjungan.jabatanPenduduk.penduduk',
        ])
            ->where('id_pemohon', $pemohon->id_pemohon)
            ->orderByDesc('id_kunjungan')
            ->paginate(10);

        return view('pemohon.ajukan_kunjungan.index', compact(
            'pemohon',
            'jadwals',
            'kunjungans'
        ));
    }


    /**
     * Menyimpan pengajuan kunjungan Pemohon.
     */
    public function storeAjukanKunjungan(Request $request)
    {
        $pemohon = Auth::guard('pemohon')->user();
        if (! $pemohon) {

            abort(401, 'Silakan login terlebih dahulu.');
        }


        $validated = $request->validate([
            'id_settings_kunjungan' => [
                'required',
                'integer',
                'exists:settings_kunjungan,id_settings_kunjungan',
            ],
            'tujuannya' => [
                'required',
                'string',
                'max:255',
            ],
            'tanggal_waktu' => [
                'required',
                'date_format:Y-m-d\TH:i',
            ],
        ], [
            'id_settings_kunjungan.required' => 'Silakan pilih jadwal kunjungan.',
            'tujuannya.required' => 'Tujuan kunjungan wajib diisi.',
            'tanggal_waktu.required' => 'Tanggal dan jam kunjungan wajib diisi.',
            'tanggal_waktu.date_format' => 'Format tanggal dan jam tidak valid.',
        ]);

        // Pastikan jadwal masih aktif.
        $jadwal = SettingsKunjungan::where(
            'id_settings_kunjungan',
            $validated['id_settings_kunjungan']
        )
            ->where('status', 'Y')
            ->firstOrFail();

        $waktuKunjungan = Carbon::createFromFormat(
            'Y-m-d\TH:i',
            $validated['tanggal_waktu']
        );

        // Waktu yang dipilih harus sesuai tanggal jadwal.
        $tanggalJadwal = Carbon::parse($jadwal->tanggal)->format('Y-m-d');

        $jamDipilih = $waktuKunjungan->format('H:i');
        $jamMulai = Carbon::parse($jadwal->jam_aktif)->format('H:i');
        $jamSelesai = Carbon::parse($jadwal->jam_non_aktif)->format('H:i');

        if (
            $waktuKunjungan->format('Y-m-d') !== $tanggalJadwal ||
            $jamDipilih < $jamMulai ||
            $jamDipilih > $jamSelesai
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'tanggal_waktu' => 'Waktu kunjungan harus sesuai dengan tanggal dan jam jadwal yang tersedia.',
                ]);
        }

        // Cegah pengajuan ganda pada jadwal dan waktu yang sama.
        $sudahDiajukan = Kunjungan::where(
            'id_settings_kunjungan',
            $jadwal->id_settings_kunjungan
        )
            ->where('tanggal_waktu', $waktuKunjungan)
            ->exists();

        if ($sudahDiajukan) {
            return back()
                ->withInput()
                ->withErrors([
                    'tanggal_waktu' => 'Jadwal pada waktu tersebut sudah diajukan. Silakan pilih waktu lain.',
                ]);
        }

        Kunjungan::create([
            'id_pemohon' => $pemohon->id_pemohon,
            'id_settings_kunjungan' => $jadwal->id_settings_kunjungan,
            'tujuannya' => $validated['tujuannya'],
            'tanggal_waktu' => $waktuKunjungan,
            'konfrimasi_admin' => null,
            'konfrimasi_penduduk' => null,
        ]);

        return redirect()
            ->route('pemohon.ajukan_kunjungan.index')
            ->with('success', 'Pengajuan kunjungan berhasil dikirim dan sedang menunggu konfirmasi Admin.');
    }

    /**
     * Menentukan model untuk CrudController.
     */
    protected function modelClass(): string
    {
        return Kunjungan::class;
    }

    /**
     * Aturan validasi data kunjungan.
     */
    protected function rules(?int $id = null): array
    {
        return [
            'id_pemohon' => [
                'required',
                'integer',
                'exists:pemohon,id_pemohon',
            ],
            'id_settings_kunjungan' => [
                'required',
                'integer',
                'exists:settings_kunjungan,id_settings_kunjungan',
            ],
            'konfrimasi_admin' => ['nullable', 'in:Y,T'],
            'tanggal_waktu_admin' => ['nullable', 'date'],
            'konfrimasi_penduduk' => ['nullable', 'in:Y,T'],
            'tanggal_waktu_penduduk' => ['nullable', 'date'],
            'catatan_admin' => ['nullable', 'string', 'max:255'],
            'catatan_penduduk' => ['nullable', 'string', 'max:255'],
            'tujuannya' => ['required', 'string', 'max:255'],
            'tanggal_waktu' => ['required', 'date'],
        ];
    }
}
