<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Penduduk extends Authenticatable
{
    use Notifiable;

    protected $table = 'penduduk';

    protected $primaryKey = 'id_penduduk';

    public $timestamps = false;

    protected $fillable = [
        'nama_penduduk',
        'nik',
        'kk',
        'jenis_kelamin',
        'alamat',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function jabatanPenduduk(): HasMany
    {
        return $this->hasMany(
            JabatanPenduduk::class,
            'id_penduduk',
            'id_penduduk'
        );
    }
}
