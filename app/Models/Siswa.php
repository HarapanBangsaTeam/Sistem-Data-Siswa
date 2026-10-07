<?php

namespace App\Models;

use Database\Factories\SiswaFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Siswa extends Model
{
    /** @use HasFactory<SiswaFactory> */
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $table = 'siswas';

    protected $fillable = [
        'kelas_id',
        'nama',
        'nipd',
        'jk',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'nik',
        'agama',
        'alamat',
        'rt',
        'rw',
        'dusun',
        'kelurahan',
        'kecamatan',
        'kode_pos',
        'jenis_tinggal',
        'alat_transportasi',
        'telepon',
        'hp',
        'email',
        'skhun',
        'penerima_kps',
        'no_kps',
        'no_peserta_ujian_nasional',
        'no_seri_ijazah',
        'penerima_kip',
        'nomor_kip',
        'nama_di_kip',
        'nomor_kks',
        'no_registrasi_akta_lahir',
        'angkatan',
        'status',
        'tahun_lulus',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'penerima_kps' => 'boolean',
            'penerima_kip' => 'boolean',
            'angkatan' => 'integer',
            'tahun_lulus' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Kelas, $this>
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * @return HasMany<OrangTua, $this>
     */
    public function orangTuas(): HasMany
    {
        return $this->hasMany(OrangTua::class, 'siswa_id');
    }

    /**
     * @return HasOne<OrangTua, $this>
     */
    public function ayah(): HasOne
    {
        return $this->hasOne(OrangTua::class, 'siswa_id')->where('hubungan', 'ayah');
    }

    /**
     * @return HasOne<OrangTua, $this>
     */
    public function ibu(): HasOne
    {
        return $this->hasOne(OrangTua::class, 'siswa_id')->where('hubungan', 'ibu');
    }

    /**
     * @return HasOne<OrangTua, $this>
     */
    public function wali(): HasOne
    {
        return $this->hasOne(OrangTua::class, 'siswa_id')->where('hubungan', 'wali');
    }

    /**
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    /**
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeLulus(Builder $query): Builder
    {
        return $query->where('status', 'lulus');
    }
}
