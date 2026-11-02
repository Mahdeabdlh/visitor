<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel jabatan
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id('id_jabatan');
            $table->string('nama_jabatan', 100)
                ->unique('uq_jabatan_nama');
        });

        // Tabel penduduk
        Schema::create('penduduk', function (Blueprint $table) {
            $table->id('id_penduduk');

            $table->string('nama_penduduk', 100);
            $table->char('nik', 16)->unique();
            $table->char('kk', 16);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('alamat', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->enum('status', ['Admin', 'Penduduk']);
        });

        // Tabel pemohon harus dibuat sebelum tabel yang mereferensikannya
        Schema::create('pemohon', function (Blueprint $table) {
            $table->id('id_pemohon');

            $table->string('nama', 255);
            $table->char('telepon', 15);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
        });

        // Relasi jabatan dan penduduk
        Schema::create('jabatan_penduduk', function (Blueprint $table) {
            $table->id('id_jabatan_penduduk');

            $table->foreignId('id_jabatan')
                ->constrained('jabatan', 'id_jabatan')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('id_penduduk')
                ->constrained('penduduk', 'id_penduduk')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->enum('status', ['Y', 'T']);

            $table->index('id_jabatan');
            $table->index('id_penduduk');
        });

        // Pengaturan kegiatan
        Schema::create('settings_kegiatan', function (Blueprint $table) {
            $table->id('id_settings_kegiatan');

            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');

            $table->foreignId('id_jabatan_penduduk')
                ->constrained('jabatan_penduduk', 'id_jabatan_penduduk')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('kegiatan', 255);
        });

        // Pengaturan jadwal kunjungan
        Schema::create('settings_kunjungan', function (Blueprint $table) {
            $table->id('id_settings_kunjungan');
            $table->index('id_jabatan_penduduk');
            // $table->index('id_pemohon');

            
            $table->date('tanggal');
            $table->time('jam_aktif');
            $table->time('jam_non_aktif');
            $table->enum('status', ['Y', 'T']);
            
            $table->foreignId('id_jabatan_penduduk')
                ->constrained('jabatan_penduduk', 'id_jabatan_penduduk')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // $table->foreignId('id_pemohon')
            //     ->constrained('pemohon', 'id_pemohon')
            //     ->cascadeOnUpdate()
            //     ->restrictOnDelete();
        });

        // Data kunjungan
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id('id_kunjungan');

            $table->foreignId('id_settings_kunjungan')
                ->constrained('settings_kunjungan', 'id_settings_kunjungan')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('id_pemohon')
                ->constrained('pemohon', 'id_pemohon')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->enum('konfrimasi_admin', ['Y', 'T'])->nullable();
            $table->dateTime('tanggal_waktu_admin')->nullable();

            $table->enum('konfrimasi_penduduk', ['Y', 'T'])->nullable();
            $table->dateTime('tanggal_waktu_penduduk')->nullable();

            $table->string('catatan_admin', 255)->nullable();
            $table->string('catatan_penduduk', 255)->nullable();

            $table->string('tujuannya', 255);
            $table->dateTime('tanggal_waktu');

            $table->index('id_settings_kunjungan');
            $table->index('id_pemohon');
            $table->index('tanggal_waktu');
        });
    }

    public function down(): void
    {
        // Hapus tabel anak terlebih dahulu
        Schema::dropIfExists('kunjungan');
        Schema::dropIfExists('settings_kunjungan');
        Schema::dropIfExists('settings_kegiatan');
        Schema::dropIfExists('jabatan_penduduk');

        // Kemudian hapus tabel induk
        Schema::dropIfExists('pemohon');
        Schema::dropIfExists('penduduk');
        Schema::dropIfExists('jabatan');
    }
};
