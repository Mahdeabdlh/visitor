<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pemohon extends Authenticatable
{
    use Notifiable;

    protected $table = 'pemohon';

    protected $primaryKey = 'id_pemohon';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'telepon',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function kunjungan(): HasMany
    {
        return $this->hasMany(
            Kunjungan::class,
            'id_pemohon',
            'id_pemohon'
        );
    }
}
