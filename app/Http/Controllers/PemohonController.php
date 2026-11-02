<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Concerns\CrudController;
use App\Models\Pemohon;
use Illuminate\Support\Facades\Hash;

class PemohonController extends CrudController
{
    protected function modelClass(): string
    {
        return Pemohon::class;
    }

    protected function rules(?int $id = null): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:15'],
            'email' => [
                'required', 'email', 'max:255',
                'unique:pemohon,email' .
                    ($id ? ",{$id},id_pemohon" : ''),
            ],
            'password' => [
                $id ? 'nullable' : 'required',
                'string', 'min:8',
            ],
        ];
    }

    protected function prepareData(array $data): array
    {
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }
}
