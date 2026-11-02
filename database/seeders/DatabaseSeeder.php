<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        /*
|--------------------------------------------------------------------------
| Akun login untuk 3 role
|--------------------------------------------------------------------------
*/

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@visitor.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('AdminVisitor123!'),
                'role' => 'Admin',
                'email_verified_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'karyawan@visitor.test'],
            [
                'name' => 'Karyawan Contoh',
                'password' => Hash::make('Karyawan123!'),
                'role' => 'Karyawan',
                'email_verified_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'pemohon@example.com'],
            [
                'name' => 'Pemohon Contoh',
                'password' => Hash::make('Pemohon123!'),
                'role' => 'Pemohon',
                'email_verified_at' => now(),
                'updated_at' => now(),
            ]
        );



        DB::transaction(function (): void {

            /*
            |--------------------------------------------------------------------------
            | 1. Data Jabatan
            |--------------------------------------------------------------------------
            */

            DB::table('jabatan')->updateOrInsert(
                ['nama_jabatan' => 'Kepala Desa'],
                []
            );

            DB::table('jabatan')->updateOrInsert(
                ['nama_jabatan' => 'Sekretaris Desa'],
                []
            );

            $kepalaDesaId = DB::table('jabatan')
                ->where('nama_jabatan', 'Kepala Desa')
                ->value('id_jabatan');

            $sekretarisDesaId = DB::table('jabatan')
                ->where('nama_jabatan', 'Sekretaris Desa')
                ->value('id_jabatan');


            /*
            |--------------------------------------------------------------------------
            | 2. Data Penduduk dan Admin
            |--------------------------------------------------------------------------
            */

           
DB::table('penduduk')->updateOrInsert(
    ['nik' => '0000000000000001'],
    [
        'kk' => '0000000000000001',
        'nama_penduduk' => 'Administrator',
        'jenis_kelamin' => 'L',
        'alamat' => 'Kantor Desa',
        'email' => 'admin.penduduk@visitor.test',
        'password' => Hash::make('AdminVisitor123!'),
        'status' => 'Admin',
    ]
);

DB::table('penduduk')->updateOrInsert(
    ['nik' => '0000000000000002'],
    [
        'kk' => '0000000000000002',
        'nama_penduduk' => 'Penduduk Contoh',
        'jenis_kelamin' => 'L',
        'alamat' => 'Desa Contoh',
        'email' => 'penduduk01@visitor.test',
        'password' => Hash::make('Penduduk123!'),
        'status' => 'Penduduk',
    ]
);


            $adminId = DB::table('penduduk')
                ->where('nik', '0000000000000001')
                ->value('id_penduduk');

            $pendudukId = DB::table('penduduk')
                ->where('nik', '0000000000000002')
                ->value('id_penduduk');


            /*
            |--------------------------------------------------------------------------
            | 3. Relasi Jabatan dan Penduduk
            |--------------------------------------------------------------------------
            */

            DB::table('jabatan_penduduk')->updateOrInsert(
                [
                    'id_jabatan' => $kepalaDesaId,
                    'id_penduduk' => $adminId,
                ],
                ['status' => 'Y']
            );

            DB::table('jabatan_penduduk')->updateOrInsert(
                [
                    'id_jabatan' => $sekretarisDesaId,
                    'id_penduduk' => $pendudukId,
                ],
                ['status' => 'Y']
            );

            $jabatanPendudukId = DB::table('jabatan_penduduk')
                ->where('id_jabatan', $kepalaDesaId)
                ->where('id_penduduk', $adminId)
                ->value('id_jabatan_penduduk');


            /*
            |--------------------------------------------------------------------------
            | 4. Pengaturan Kegiatan
            |--------------------------------------------------------------------------
            */

            $tanggalAwal = now()->toDateString();
            $tanggalAkhir = now()->addDays(30)->toDateString();

            DB::table('settings_kegiatan')->updateOrInsert(
                [
                    'id_jabatan_penduduk' => $jabatanPendudukId,
                    'tanggal_awal' => $tanggalAwal,
                    'tanggal_akhir' => $tanggalAkhir,
                    'kegiatan' => 'Pelayanan Administrasi Desa',
                ],
                []
            );


             DB::table('pemohon')->updateOrInsert(  
                ['email' => 'pemohon@example.com'],
                [
                    'nama' => 'Pemohon Contoh',
                    'telepon' => '081234567890',
                    'password' => Hash::make('Pemohon123!'),
                ]
            );

            $pemohonId = DB::table('pemohon')
            ->where('email', 'pemohon@example.com')
            ->value('id_pemohon');


            /*
            |--------------------------------------------------------------------------
            | 5. Pengaturan Jadwal Kunjungan
            |--------------------------------------------------------------------------
            */

            $tanggalKunjungan = now()->addDay()->toDateString();

            DB::table('settings_kunjungan')->updateOrInsert(
                
                [
                    'id_jabatan_penduduk' => $jabatanPendudukId,
                    'tanggal' => $tanggalKunjungan,
                ],
                [   
                     'id_pemohon' => $pemohonId,
                    'jam_aktif' => '08:00:00',
                    'jam_non_aktif' => '15:00:00',
                    'status' => 'Y',
                ]
            );

            $settingsKunjunganId = DB::table('settings_kunjungan')
                ->where('id_jabatan_penduduk', $jabatanPendudukId)
                ->where('tanggal', $tanggalKunjungan)
                ->value('id_settings_kunjungan');


            /*
            |--------------------------------------------------------------------------
            | 6. Data Kunjungan Contoh
            |--------------------------------------------------------------------------
            */

            $tanggalWaktu = now()
                ->addDay()
                ->setTime(10, 0, 0)
                ->format('Y-m-d H:i:s');

           
DB::table('kunjungan')->updateOrInsert(
    [
        'id_settings_kunjungan' => $settingsKunjunganId,
        'id_pemohon' => $pemohonId,
        'tanggal_waktu' => $tanggalWaktu,
    ],
    [
        'konfrimasi_admin' => null,
        'tanggal_waktu_admin' => null,
        'konfrimasi_penduduk' => null,
        'tanggal_waktu_penduduk' => null,
        'catatan_admin' => null,
        'catatan_penduduk' => null,
        'tujuannya' => 'Konsultasi administrasi desa',
    ]
);



            /*
            |--------------------------------------------------------------------------
            | 7. Data Pemohon
            |--------------------------------------------------------------------------
            */

           
        });
    }
}
