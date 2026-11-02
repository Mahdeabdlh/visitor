<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Concerns\CrudController;
use App\Models\SettingsKegiatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SettingsKegiatanController extends CrudController
{
    protected function modelClass(): string
    {
        return SettingsKegiatan::class;
    }

    protected function rules(?int $id = null): array
    {
        return [
            'tanggal_awal' => [
                'required',
                'date',
            ],
            'tanggal_akhir' => [
                'required',
                'date',
                'after_or_equal:tanggal_awal',
            ],
            'id_jabatan_penduduk' => [
                'required',
                'integer',
                'exists:jabatan_penduduk,id_jabatan_penduduk',
            ],
            'kegiatan' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

      public function jadwal(Request $request)
    {
        // Validasi input pencarian dan tanggal.
        $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],
            'tanggal_awal' => [
                'nullable',
                'date',
            ],
            'tanggal_akhir' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_awal',
            ],
        ]);

        // Mengambil data kegiatan beserta penduduk dan jabatan.
        $query = DB::table('settings_kegiatan')
            ->leftJoin(
                'jabatan_penduduk',
                'settings_kegiatan.id_jabatan_penduduk',
                '=',
                'jabatan_penduduk.id_jabatan_penduduk'
            )
            ->leftJoin(
                'penduduk',
                'jabatan_penduduk.id_penduduk',
                '=',
                'penduduk.id_penduduk'
            )
            ->leftJoin(
                'jabatan',
                'jabatan_penduduk.id_jabatan',
                '=',
                'jabatan.id_jabatan'
            )
            ->select(
                'settings_kegiatan.id_settings_kegiatan',
                'settings_kegiatan.tanggal_awal',
                'settings_kegiatan.tanggal_akhir',
                'settings_kegiatan.kegiatan',
                'penduduk.nama_penduduk',
                'jabatan.nama_jabatan'
            );

        // Cari berdasarkan kegiatan, nama penduduk, atau jabatan.
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where(
                    'settings_kegiatan.kegiatan',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'penduduk.nama_penduduk',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'jabatan.nama_jabatan',
                    'like',
                    "%{$search}%"
                );
            });
        }

        // Filter berdasarkan tanggal awal.
        if ($request->filled('tanggal_awal')) {
            $query->whereDate(
                'settings_kegiatan.tanggal_awal',
                '>=',
                $request->input('tanggal_awal')
            );
        }

        // Filter berdasarkan tanggal akhir.
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate(
                'settings_kegiatan.tanggal_akhir',
                '<=',
                $request->input('tanggal_akhir')
            );
        }

        // Urutkan jadwal terbaru dan tampilkan 10 data per halaman.
        $jadwalKegiatan = $query
            ->orderBy(
                'settings_kegiatan.tanggal_awal',
                'desc'
            )
            ->orderBy(
                'settings_kegiatan.id_settings_kegiatan',
                'desc'
            )
            ->paginate(10)
            ->withQueryString();

        return view(
            'karyawan.jadwal_kegiatan.index',
            compact('jadwalKegiatan')
        );
    }
}
