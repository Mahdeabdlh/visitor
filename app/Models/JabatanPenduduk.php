<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JabatanPenduduk extends Model
{
    protected $table = 'jabatan_penduduk';
    protected $primaryKey = 'id_jabatan_penduduk';
    public $timestamps = false;

    protected $fillable = [
        'id_jabatan',
        'id_penduduk',
        'status',
    ];

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(
            Jabatan::class,
            'id_jabatan',
            'id_jabatan'
        );
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(
            Penduduk::class,
            'id_penduduk',
            'id_penduduk'
        );
    }

    public function settingsKegiatan(): HasMany
    {
        return $this->hasMany(
            SettingsKegiatan::class,
            'id_jabatan_penduduk',
            'id_jabatan_penduduk'
        );
    }

    public function settingsKunjungan(): HasMany
    {
        return $this->hasMany(
            SettingsKunjungan::class,
            'id_jabatan_penduduk',
            'id_jabatan_penduduk'
        );
    }
}
