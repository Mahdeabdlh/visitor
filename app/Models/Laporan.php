<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporans';

    protected $primaryKey = 'id_laporan';

    public const UPDATED_AT = null;

    protected $fillable = [
        'judul',
        'tanggal_awal',
        'tanggal_akhir',
        'jumlah_kunjungan',
        'jumlah_disetujui',
        'jumlah_ditolak',
        'jumlah_menunggu',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_awal' => 'date',
            'tanggal_akhir' => 'date',
            'jumlah_kunjungan' => 'integer',
            'jumlah_disetujui' => 'integer',
            'jumlah_ditolak' => 'integer',
            'jumlah_menunggu' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
