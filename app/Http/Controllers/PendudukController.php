<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\CrudController;
use Illuminate\Validation\Rule;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class PendudukController extends CrudController
{



    public function index(Request $request)
    {
        $query = Penduduk::query();

        // Pencarian nama, NIK, atau username
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('nama_penduduk', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter status Admin atau Penduduk
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Ambil data dengan pagination
        $penduduks = $query
            ->orderBy('nama_penduduk')
            ->paginate(10)
            ->withQueryString();

        // Hitung jumlah berdasarkan status
        $jumlahAdmin = Penduduk::where('status', 'Admin')->count();

        $jumlahPenduduk = Penduduk::where('status', 'Penduduk')->count();

        // Tampilkan halaman Blade
        return view('admin.datapenduduk.index', compact(
            'penduduks',
            'jumlahAdmin',
            'jumlahPenduduk'
        ));
    }


    public function create()
    {
        return view('admin.datapenduduk.create');
    }

    /**
     * Memvalidasi dan menyimpan penduduk baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $data = $this->prepareData($data);

        penduduk::create($data);

        return redirect()
            ->route('admin.datapenduduk.index')
            ->with('success', 'Data penduduk berhasil ditambahkan.');
    }


    public function show(int $id)
    {
        $penduduk = Penduduk::query()
            ->findOrFail($id);

        return view('admin.datapenduduk.show', compact('penduduk'));
    }

    protected function modelClass(): string
    {
        return Penduduk::class;
    }




    protected function rules(?int $id = null): array
    {
        return [
            'nama_penduduk' => ['required', 'string', 'max:100'],
            'nik' => [
                'required',
                'string',
                'size:16',
                Rule::unique('penduduk', 'nik')
                    ->ignore($id, 'id_penduduk'),
            ],
            'kk' => ['required', 'string', 'size:16'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('penduduk', 'email')
                    ->ignore($id, 'id_penduduk'),
            ],

             // Saat tambah, password wajib.
            // Saat edit, password boleh kosong.
            'password' => [
                $id ? 'nullable' : 'required',
                'string',
                'min:8',
            ],
            'status' => ['required', 'in:Admin,Penduduk'],
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
