<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettingsKegiatan extends Model
{
    protected $table = 'settings_kegiatan';
    protected $primaryKey = 'id_settings_kegiatan';
    public $timestamps = false;

    protected $fillable = [
        'tanggal_awal',
        'tanggal_akhir',
        'id_jabatan_penduduk',
        'kegiatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_awal' => 'date',
            'tanggal_akhir' => 'date',
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
}
