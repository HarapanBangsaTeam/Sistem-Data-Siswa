<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $siswa = $this->route('siswa');
        $siswaId = is_object($siswa) ? $siswa->id : $siswa;

        return [
            'nama' => ['required', 'string', 'max:150'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'nipd' => ['nullable', 'string', 'max:30', Rule::unique('siswas', 'nipd')->ignore($siswaId)],
            'jk' => ['required', 'in:L,P'],
            'nisn' => ['nullable', 'string', 'max:20', Rule::unique('siswas', 'nisn')->ignore($siswaId)],
            'nik' => ['nullable', 'string', 'max:20', Rule::unique('siswas', 'nik')->ignore($siswaId)],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'agama' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            'rt' => ['nullable', 'string', 'max:5'],
            'rw' => ['nullable', 'string', 'max:5'],
            'dusun' => ['nullable', 'string', 'max:100'],
            'kelurahan' => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'jenis_tinggal' => ['nullable', 'string', 'max:50'],
            'alat_transportasi' => ['nullable', 'string', 'max:50'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'skhun' => ['nullable', 'string', 'max:50'],
            'penerima_kps' => ['nullable', 'boolean'],
            'no_kps' => ['nullable', 'string', 'max:50'],
            'no_peserta_ujian_nasional' => ['nullable', 'string', 'max:50'],
            'no_seri_ijazah' => ['nullable', 'string', 'max:50'],
            'penerima_kip' => ['nullable', 'boolean'],
            'nomor_kip' => ['nullable', 'string', 'max:50'],
            'nama_di_kip' => ['nullable', 'string', 'max:150'],
            'nomor_kks' => ['nullable', 'string', 'max:50'],
            'no_registrasi_akta_lahir' => ['nullable', 'string', 'max:50'],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:'.(now()->year + 1)],
            'status' => ['required', 'in:aktif,lulus,pindah,keluar'],
            'tahun_lulus' => ['nullable', 'integer', 'min:2000', 'max:'.(now()->year + 1)],

            // Orang tua
            'ayah' => ['nullable', 'array'],
            'ayah.nama' => ['nullable', 'string', 'max:150'],
            'ayah.tahun_lahir' => ['nullable', 'integer', 'min:1940', 'max:'.now()->year],
            'ayah.jenjang_pendidikan' => ['nullable', 'string', 'max:50'],
            'ayah.pekerjaan' => ['nullable', 'string', 'max:100'],
            'ayah.penghasilan' => ['nullable', 'string', 'max:50'],
            'ayah.nik' => ['nullable', 'string', 'max:20'],

            'ibu' => ['nullable', 'array'],
            'ibu.nama' => ['nullable', 'string', 'max:150'],
            'ibu.tahun_lahir' => ['nullable', 'integer', 'min:1940', 'max:'.now()->year],
            'ibu.jenjang_pendidikan' => ['nullable', 'string', 'max:50'],
            'ibu.pekerjaan' => ['nullable', 'string', 'max:100'],
            'ibu.penghasilan' => ['nullable', 'string', 'max:50'],
            'ibu.nik' => ['nullable', 'string', 'max:20'],

            'wali' => ['nullable', 'array'],
            'wali.nama' => ['nullable', 'string', 'max:150'],
            'wali.tahun_lahir' => ['nullable', 'integer', 'min:1940', 'max:'.now()->year],
            'wali.jenjang_pendidikan' => ['nullable', 'string', 'max:50'],
            'wali.pekerjaan' => ['nullable', 'string', 'max:100'],
            'wali.penghasilan' => ['nullable', 'string', 'max:50'],
            'wali.nik' => ['nullable', 'string', 'max:20'],
        ];
    }
}
