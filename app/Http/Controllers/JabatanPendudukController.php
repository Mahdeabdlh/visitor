<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Concerns\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\JabatanPenduduk;

class JabatanPendudukController extends CrudController
{

   
public function index(Request $request)
{
    $search = $request->input('search');

    $karyawans = DB::table('jabatan_penduduk as jp')
        ->join('penduduk as p', 'p.id_penduduk', '=', 'jp.id_penduduk')
        ->join('jabatan as j', 'j.id_jabatan', '=', 'jp.id_jabatan')
        ->select(
            'jp.id_jabatan_penduduk',
            'jp.status as status_jabatan',
            'p.id_penduduk',
            'p.nama_penduduk',
            'p.nik',
            'p.email',
            'j.nama_jabatan'
        )
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('p.nama_penduduk', 'like', "%{$search}%")
                    ->orWhere('p.nik', 'like', "%{$search}%")
                    ->orWhere('p.email', 'like', "%{$search}%")
                    ->orWhere('j.nama_jabatan', 'like', "%{$search}%");
            });
        })
        ->orderBy('p.nama_penduduk')
        ->paginate(10)
        ->withQueryString();

    $totalKaryawan = DB::table('jabatan_penduduk')->count();

    return view('admin.datakaryawan.index', compact(
        'karyawans',
        'totalKaryawan',
        'search'
    ));
}


    public function create()
    {
        $penduduks = DB::table('penduduk')
            ->orderBy('nama_penduduk')
            ->get();

        $jabatans = DB::table('jabatan')
            ->orderBy('nama_jabatan')
            ->get();

        return view('admin.datakaryawan.create', compact(
            'penduduks',
            'jabatans'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_penduduk' => [
                'required',
                'integer',
                'exists:penduduk,id_penduduk',
                Rule::unique('jabatan_penduduk', 'id_penduduk'),
            ],
            'id_jabatan' => [
                'required',
                'integer',
                'exists:jabatan,id_jabatan',
            ],
            'status' => ['required', Rule::in(['Y', 'T'])],
        ]);

        DB::table('jabatan_penduduk')->insert($validated);

        return redirect()
            ->route('admin.datakaryawan.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function show(int $id)
    {
        $karyawan = $this->findKaryawan($id);

        return view('admin.datakaryawan.show', compact('karyawan'));
    }

    public function edit(int $id)
    {
        $karyawan = $this->findKaryawan($id);

        $penduduks = DB::table('penduduk')
            ->orderBy('nama_penduduk')
            ->get();

        $jabatans = DB::table('jabatan')
            ->orderBy('nama_jabatan')
            ->get();

        return view('admin.datakaryawan.edit', compact(
            'karyawan',
            'penduduks',
            'jabatans'
        ));
    }

    public function update(Request $request, int $id)
    {
        $karyawan = DB::table('jabatan_penduduk')
            ->where('id_jabatan_penduduk', $id)
            ->first();

        abort_if(!$karyawan, 404);

        $validated = $request->validate([
            'id_penduduk' => [
                'required',
                'integer',
                'exists:penduduk,id_penduduk',
                Rule::unique('jabatan_penduduk', 'id_penduduk')
                    ->ignore($id, 'id_jabatan_penduduk'),
            ],
            'id_jabatan' => [
                'required',
                'integer',
                'exists:jabatan,id_jabatan',
            ],
            'status' => ['required', Rule::in(['Y', 'T'])],
        ]);

        DB::table('jabatan_penduduk')
            ->where('id_jabatan_penduduk', $id)
            ->update($validated);

        return redirect()
            ->route('admin.datakaryawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $deleted = DB::table('jabatan_penduduk')
            ->where('id_jabatan_penduduk', $id)
            ->delete();

        abort_if(!$deleted, 404);

        return redirect()
            ->route('admin.datakaryawan.index')
            ->with('success', 'Penempatan karyawan berhasil dihapus.');
    }

 
private function findKaryawan(int $id)
{
    return DB::table('jabatan_penduduk as jp')
        ->join('penduduk as p', 'p.id_penduduk', '=', 'jp.id_penduduk')
        ->join('jabatan as j', 'j.id_jabatan', '=', 'jp.id_jabatan')
        ->select(
            'jp.id_jabatan_penduduk',
            'jp.id_penduduk',
            'jp.id_jabatan',
            'jp.status as status_jabatan',
            'p.nama_penduduk',
            'p.nik',
            'p.kk',
            'p.alamat',
            'p.email',
            'j.nama_jabatan'
        )
        ->where('jp.id_jabatan_penduduk', $id)
        ->firstOrFail();
}

    protected function modelClass(): string
    {
        return JabatanPenduduk::class;
    }

    protected function rules(?int $id = null): array
    {
        return [
            'id_jabatan' => [
                'required',
                'integer',
                'exists:jabatan,id_jabatan',
            ],
            'id_penduduk' => [
                'required',
                'integer',
                'exists:penduduk,id_penduduk',
            ],
            'status' => ['required', 'in:Y,T'],
        ];
    }
}
