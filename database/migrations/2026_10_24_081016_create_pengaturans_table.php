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
    Schema::create('pengaturan', function (Blueprint $table) {
        $table->id();
        $table->string('nama_aplikasi', 150)
            ->default('Sistem Buku Tamu');

        $table->string('nama_instansi', 150)
            ->nullable();

        $table->text('alamat')->nullable();

        $table->string('email', 150)->nullable();

        $table->string('telepon', 30)->nullable();

        $table->text('deskripsi')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('pengaturan');
}
};
