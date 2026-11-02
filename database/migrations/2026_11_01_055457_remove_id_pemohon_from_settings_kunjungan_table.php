<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings_kunjungan', function (Blueprint $table) {
             $table->dropForeign(['id_pemohon']);

            // Hapus kolom id_pemohon.
            $table->dropColumn('id_pemohon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('settings_kunjungan', function (Blueprint $table) {
            $table->foreignId('id_pemohon')
                ->nullable()
                ->constrained('pemohon', 'id_pemohon')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }
};
