<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JabatanPendudukController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PemohonController;
use App\Http\Controllers\SettingsKegiatanController;
use App\Http\Controllers\SettingsKunjunganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\Concerns\CrudController;
use Illuminate\Support\Facades\Route;



Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
Route::get('/', function () {
    return view('welcome');
})->name('welcome');



Route::get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::guard('web')->user() ?? \Illuminate\Support\Facades\Auth::guard('pemohon')->user();

    if (! $user) {
        return redirect()->route('welcome');
    }

    if (($user->status ?? '') === 'Admin' || ($user->role ?? '') === 'Admin') {
        return redirect()->route('admin.dashboard');
    }

    if (
        in_array($user->status ?? '', ['Karyawan', 'Pengguna', 'Penduduk'], true)
        || ($user->role ?? '') === 'Karyawan'
    ) {
        return redirect()->route('karyawan.dashboard');
    }

    return redirect()->route('pemohon.dashboard');
})->middleware(['auth:web,pemohon'])->name('dashboard');



// ==========================================
// 1. ROUTE KHUSUS ADMIN (Kontrol Penuh)
// ==========================================
Route::middleware(['auth:web', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // Kontrol semua data & laporan
    })->name('dashboard');

    // Data Penduduk
    Route::get('/datapenduduk', [PendudukController::class, 'index'])
        ->name('datapenduduk.index');
    Route::get('/datapenduduk/create', [PendudukController::class, 'create'])
        ->name('datapenduduk.create');
    Route::post('/datapenduduk', [PendudukController::class, 'store'])
        ->name('datapenduduk.store');
    Route::get('/datapenduduk/edit/{id}', [PendudukController::class, 'edit'])
        ->whereNumber('id')
        ->name('datapenduduk.edit');
    Route::get('/datapenduduk/{id}', [PendudukController::class, 'show'])
        ->whereNumber('id')
        ->name('datapenduduk.show');
    Route::delete('/datapenduduk/{id}', [PendudukController::class, 'destroy'])
        ->whereNumber('id')
        ->name('datapenduduk.destroy');


    // data jabatan
    Route::get('/datajabatan', [JabatanController::class, 'index'])->name('datajabatan.index');
    Route::get('/datajabatan/create', [JabatanController::class, 'create'])->name('datajabatan.create');
    Route::post('/datajabatan', [JabatanController::class, 'store'])->name('datajabatan.store');
    Route::get('/datajabatan/{id}', [JabatanController::class, 'show'])
        ->whereNumber('id')
        ->name('datajabatan.show');
    Route::get('/datajabatan/{id}/edit', [JabatanController::class, 'edit'])
        ->whereNumber('id')
        ->name('datajabatan.edit');
    Route::put('/datajabatan/{id}', [JabatanController::class, 'update'])
        ->whereNumber('id')
        ->name('datajabatan.update');
    Route::delete('/datajabatan/{id}', [JabatanController::class, 'destroy'])
        ->whereNumber('id')
        ->name('datajabatan.destroy');

    // data karyawan
    Route::get('/datakaryawan', [JabatanPendudukController::class, 'index'])->name('datakaryawan.index');
    Route::get('/datakaryawan/create', [JabatanPendudukController::class, 'create'])
        ->name('datakaryawan.create');
    Route::post('/datakaryawan', [JabatanPendudukController::class, 'store'])
        ->name('datakaryawan.store');
    Route::get('/datakaryawan/{id}', [JabatanPendudukController::class, 'show'])
        ->whereNumber('id')
        ->name('datakaryawan.show');
    Route::get('/datakaryawan/{id}/edit', [JabatanPendudukController::class, 'edit'])
        ->whereNumber('id')
        ->name('datakaryawan.edit');
    Route::put('/datakaryawan/{id}', [JabatanPendudukController::class, 'update'])
        ->whereNumber('id')
        ->name('datakaryawan.update');
    Route::delete('/datakaryawan/{id}', [JabatanPendudukController::class, 'destroy'])
        ->whereNumber('id')
        ->name('datakaryawan.destroy');

    //data jadwal kunjungan
    Route::get('/jadwalkunjungan', [SettingsKunjunganController::class, 'index'])->name('jadwalkunjungan.index');
    Route::get('/jadwalkunjungan/create', [SettingsKunjunganController::class, 'create'])->name('jadwalkunjungan.create');
    Route::post('/jadwalkunjungan', [SettingsKunjunganController::class, 'store'])->name('jadwalkunjungan.store');
    Route::get('/jadwalkunjungan/{id}/edit', [SettingsKunjunganController::class, 'edit'])->whereNumber('id')->name('jadwalkunjungan.edit');
    Route::put('/jadwalkunjungan/{id}', [SettingsKunjunganController::class, 'update'])->whereNumber('id')->name('jadwalkunjungan.update');
    Route::delete('/jadwalkunjungan/{id}', [SettingsKunjunganController::class, 'destroy'])->whereNumber('id')->name('jadwalkunjungan.destroy');
    Route::get('/jadwalkunjungan/{id}', [SettingsKunjunganController::class, 'show'])->whereNumber('id')->name('jadwalkunjungan.show');


    //kunjungan
    Route::get('/datakunjungan', [KunjunganController::class, 'index'])->name('datakunjungan.index');
    Route::get('/datakunjungan/{id}', [KunjunganController::class, 'show'])->whereNumber('id')->name('datakunjungan.show');
    Route::get('/datakunjungan/{id}/edit', [KunjunganController::class, 'edit'])->whereNumber('id')->name('datakunjungan.edit');
    Route::put('/datakunjungan/{id}', [KunjunganController::class, 'update'])->whereNumber('id')->name('datakunjungan.update');
    Route::delete('/datakunjungan/{id}', [KunjunganController::class, 'destroy'])->whereNumber('id')->name('datakunjungan.destroy');


    // laporan kunjungan

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    // Route::post('/simpan', [LaporanController::class, 'simpan'])->name('simpan');
    // Route::get('/cetak', [LaporanController::class, 'cetak'])->name('cetak')->name('laporan.cetak');


    // pengaturan aplikasi
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::get('/pengaturan/update', [PengaturanController::class, 'edit'])->name('pengaturan.edit');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
});


// ==========================================
// 2. ROUTE KHUSUS KARYAWAN / PENGGUNA (Kehadiran & Kegiatan)
// ==========================================
Route::middleware(['auth:web', 'role:Karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('karyawan.dashboard');
    });

    Route::get('/dashboard', function () {
        return view('karyawan.dashboard'); // Panel isi kehadiran & status kegiatan (Ada/Tidak ada)
    })->name('dashboard');

    // Jadwal Kegiatan
    Route::get('/jadwal_kegiatan', [SettingsKegiatanController::class, 'jadwal'])->name('jadwal_kegiatan.index');

    Route::get('/datakunjungan', [KunjunganController::class, 'indexKaryawan'])->name('datakunjungan.index');

    Route::get('/konfirmasi-kunjungan', [KunjunganController::class, 'indexKonfirmasiKaryawan'])->name('konfirmasi_kunjungan.index');

    Route::put('/konfirmasi-kunjungan/{id}/konfirmasi', [KunjunganController::class, 'simpanKonfirmasiKaryawan'])->whereNumber('id')->name('konfirmasi_kunjungan.simpan');

    Route::get('/profil', [ProfileController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');
});



// ==========================================
// 3. ROUTE KHUSUS PEMOHON (Mengajukan Kunjungan)
// ==========================================
Route::middleware(['auth:pemohon,web', 'role:Pemohon'])->prefix('pemohon')->name('pemohon.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('pemohon.dashboard');
    });

    Route::get('/dashboard', function () {
        return view('pemohon.dashboard'); // Panel untuk mengajukan kunjungan ke karyawan
    })->name('dashboard');

    Route::get('/ajukan_kunjungan', [KunjunganController::class, 'indexAjukanKunjungan'])->name('ajukan_kunjungan.index');

    Route::post('/ajukan_kunjungan', [KunjunganController::class, 'storeAjukanKunjungan'])->name('ajukan_kunjungan.store');
    Route::get('/riwayat_kunjungan', [SettingsKunjunganController::class, 'riwayatKunjungan'])->name('riwayat_kunjungan.index');
    Route::get('/status_kunjungan', [SettingsKunjunganController::class, 'statusKunjungan'])->name('status_kunjungan.index');

    Route::get('/profil', [ProfileController::class, 'editPemohon'])->name('profil.edit');

    Route::patch('/profil', [ProfileController::class, 'updatePemohon'])->name('profil.update');

    // Route::get('/kunjungan', function () {
    //     return redirect()->route('pemohon.dashboard');
    // })->name('kunjungan.index');
});

// 

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
