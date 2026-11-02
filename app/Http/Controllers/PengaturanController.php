<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    /**
     * Menampilkan halaman pengaturan.
     */
    public function index()
    {
        // Ambil pengaturan yang sudah tersimpan.
        // Jika belum ada, buat data awal.
        $pengaturan = Pengaturan::firstOrCreate([], [
            'nama_aplikasi' => 'Sistem Buku Tamu',
            'nama_instansi' => '',
            'alamat' => '',
            'email' => '',
            'telepon' => '',
            'deskripsi' => '',
        ]);

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function edit()
{
    $pengaturan = Pengaturan::firstOrCreate([], [
        'nama_aplikasi' => 'Sistem Buku Tamu',
    ]);

    return view('admin.pengaturan.update', compact('pengaturan'));
}

    /**
     * Menyimpan perubahan pengaturan.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_aplikasi' => [
                'required',
                'string',
                'max:150',
            ],
            'nama_instansi' => [
                'nullable',
                'string',
                'max:150',
            ],
            'alamat' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'email' => [
                'nullable',
                'email',
                'max:150',
            ],
            'telepon' => [
                'nullable',
                'string',
                'max:30',
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        // Gunakan data pengaturan yang sama.
        $pengaturan = Pengaturan::firstOrCreate(
            [],
            ['nama_aplikasi' => 'Sistem Buku Tamu']
        );

        $pengaturan->update($validated);

        return redirect()
            ->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
