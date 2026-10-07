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
        Schema::create('orang_tuas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->enum('hubungan', ['ayah', 'ibu', 'wali']);
            $table->string('nama', 150)->nullable();
            $table->year('tahun_lahir')->nullable();
            $table->string('jenjang_pendidikan', 50)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('penghasilan', 50)->nullable();
            $table->string('nik', 20)->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'hubungan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orang_tuas');
    }
};
