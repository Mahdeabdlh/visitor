<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';

    protected $primaryKey = 'id_kunjungan';

    public $timestamps = false;

    protected $fillable = [
        'id_pemohon',
        'id_settings_kunjungan',
        'konfrimasi_admin',
        'tanggal_waktu_admin',
        'konfrimasi_penduduk',
        'tanggal_waktu_penduduk',
        'catatan_admin',
        'catatan_penduduk',
        'tujuannya',
        'tanggal_waktu',
        'tujuannya',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_waktu' => 'datetime',
            'tanggal_waktu_admin' => 'datetime',
            'tanggal_waktu_penduduk' => 'datetime',
        ];
    }

    public function pemohon()
    {
        return $this->belongsTo(
            Pemohon::class,
            'id_pemohon',
            'id_pemohon'
        );
    }

    public function settingsKunjungan()
    {
        return $this->belongsTo(
            SettingsKunjungan::class,
            'id_settings_kunjungan',
            'id_settings_kunjungan'
        );
    }
}
