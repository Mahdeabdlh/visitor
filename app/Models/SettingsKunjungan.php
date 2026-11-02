<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SettingsKunjungan extends Model
{
    protected $table = 'settings_kunjungan';
    protected $primaryKey = 'id_settings_kunjungan';
    public $timestamps = false;

    protected $fillable = [
        'id_jabatan_penduduk',
        'id_pemohon',
        'tanggal',
        'jam_aktif',
        'jam_non_aktif',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function jabatanPenduduk(): BelongsTo
    {
        return $this->belongsTo(
            JabatanPenduduk::class,
            'id_jabatan_penduduk',
            'id_jabatan_penduduk'
        );
    }

    public function kunjungan(): HasMany
    {
        return $this->hasMany(
            Kunjungan::class,
            'id_settings_kunjungan',
            'id_settings_kunjungan'
        );
    }

    public function pemohon(): BelongsTo
    {
        return $this ->belongsTo(
            pemohon::class,
            'id_pemohon',
            'id_pemohon'
        );
    }
}
