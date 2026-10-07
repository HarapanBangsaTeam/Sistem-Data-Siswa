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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->string('nama', 150);
            $table->string('nipd', 30)->nullable()->unique();
            $table->enum('jk', ['L', 'P']);
            $table->string('nisn', 20)->nullable()->unique();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nik', 20)->nullable()->unique();
            $table->string('agama', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('dusun', 100)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('jenis_tinggal', 50)->nullable();
            $table->string('alat_transportasi', 50)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('hp', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('skhun', 50)->nullable();
            $table->boolean('penerima_kps')->default(false);
            $table->string('no_kps', 50)->nullable();
            $table->string('no_peserta_ujian_nasional', 50)->nullable();
            $table->string('no_seri_ijazah', 50)->nullable();
            $table->boolean('penerima_kip')->default(false);
            $table->string('nomor_kip', 50)->nullable();
            $table->string('nama_di_kip', 150)->nullable();
            $table->string('nomor_kks', 50)->nullable();
            $table->string('no_registrasi_akta_lahir', 50)->nullable();
            $table->year('angkatan')->index();
            $table->enum('status', ['aktif', 'lulus', 'pindah', 'keluar'])->default('aktif')->index();
            $table->year('tahun_lulus')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
