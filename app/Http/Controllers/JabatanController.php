<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Concerns\CrudController;
use Illuminate\Validation\Rule;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class JabatanController extends CrudController
{
 

      public function index(Request $request)
    {
        $search = $request->input('search');

        $jabatans = Jabatan::query()
            ->when($search, function ($query, $search) {
                $query->where(
                    'nama_jabatan',
                    'like',
                    '%' . $search . '%'
                );
            })
            ->orderBy('nama_jabatan')
            ->paginate(10)
            ->withQueryString();

        $totalJabatan = Jabatan::count();

        return view('admin.datajabatan.index', compact(
            'jabatans',
            'totalJabatan',
            'search'
        ));
    }

    /**
     * Menampilkan form tambah jabatan.
     */
    public function create()
    {
        return view('admin.datajabatan.create');
    }

    /**
     * Menyimpan jabatan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jabatan' => [
                'required',
                'string',
                'max:100',
                'unique:jabatan,nama_jabatan',
            ],
        ], [
            'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
            'nama_jabatan.max' => 'Nama jabatan maksimal 100 karakter.',
            'nama_jabatan.unique' => 'Nama jabatan sudah terdaftar.',
        ]);

        Jabatan::create($validated);

        return redirect()
            ->route('admin.datajabatan.index')
            ->with('success', 'Data jabatan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail jabatan.
     */
    public function show(int $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        return view('admin.datajabatan.show', compact('jabatan'));
    }

    /**
     * Menampilkan form edit jabatan.
     */
    public function edit(int $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        return view('admin.datajabatan.edit', compact('jabatan'));
    }

    /**
     * Memperbarui data jabatan.
     */
    public function update(Request $request, int $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $validated = $request->validate([
            'nama_jabatan' => [
                'required',
                'string',
                'max:100',
                Rule::unique('jabatan', 'nama_jabatan')
                    ->ignore($jabatan->id_jabatan, 'id_jabatan'),
            ],
        ], [
            'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
            'nama_jabatan.max' => 'Nama jabatan maksimal 100 karakter.',
            'nama_jabatan.unique' => 'Nama jabatan sudah digunakan.',
        ]);

        $jabatan->update($validated);

        return redirect()
            ->route('admin.datajabatan.index')
            ->with('success', 'Data jabatan berhasil diperbarui.');
    }

    /**
     * Menghapus jabatan.
     */
    public function destroy(int $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        try {
            $jabatan->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()
                ->route('admin.datajabatan.index')
                ->with(
                    'error',
                    'Jabatan tidak dapat dihapus karena masih digunakan oleh data lain.'
                );
        }

        return redirect()
            ->route('admin.datajabatan.index')
            ->with('success', 'Data jabatan berhasil dihapus.');
    }

protected function modelClass(): string
    {
        return Jabatan::class;
    }

    protected function rules(?int $id = null): array
    {
        return [
            'nama_jabatan' => [
                'required',
                'string',
                'max:100',
                'unique:jabatan,nama_jabatan' .
                    ($id ? ",{$id},id_jabatan" : ''),
            ],
        ];
    }
}
