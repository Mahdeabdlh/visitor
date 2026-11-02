<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->increments('id_laporan');

            $table->string('judul', 150);
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');

            $table->unsignedInteger('jumlah_kunjungan')->default(0);
            $table->unsignedInteger('jumlah_disetujui')->default(0);
            $table->unsignedInteger('jumlah_ditolak')->default(0);
            $table->unsignedInteger('jumlah_menunggu')->default(0);

            $table->timestamp('created_at')->useCurrent();

            $table->index(
                ['tanggal_awal', 'tanggal_akhir'],
                'idx_laporans_periode'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
