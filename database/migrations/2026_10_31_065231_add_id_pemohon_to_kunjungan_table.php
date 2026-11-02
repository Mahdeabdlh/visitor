<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('kunjungan', 'id_pemohon')) {
            Schema::table('kunjungan', function (Blueprint $table) {
                $table->unsignedInteger('id_pemohon')
                    ->nullable()
                    ->after('id_settings_kunjungan');

                $table->foreign('id_pemohon')
                    ->references('id_pemohon')
                    ->on('pemohon')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kunjungan', 'id_pemohon')) {
            Schema::table('kunjungan', function (Blueprint $table) {
                $table->dropForeign(['id_pemohon']);
                $table->dropColumn('id_pemohon');
            });
        }
    }
};