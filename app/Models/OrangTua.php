<?php

namespace App\Models;

use Database\Factories\OrangTuaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrangTua extends Model
{
    /** @use HasFactory<OrangTuaFactory> */
    use HasFactory;

    protected $table = 'orang_tuas';

    protected $fillable = [
        'siswa_id',
        'hubungan',
        'nama',
        'tahun_lahir',
        'jenjang_pendidikan',
        'pekerjaan',
        'penghasilan',
        'nik',
    ];

    /**
     * @return BelongsTo<Siswa, $this>
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
