<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SiswaImport implements ToCollection, WithStartRow
{
    public int $successCount = 0;

    public int $updatedCount = 0;

    public array $errors = [];

    public function __construct(public int $angkatan) {}

    public function startRow(): int
    {
        return 4;
    }

    /**
     * @param  Collection<int, array<int, mixed>>  $rows
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $rowIndex => $row) {
            $lineNum = $rowIndex + $this->startRow();

            // Kolom 1 adalah nama siswa
            $nama = isset($row[1]) ? trim((string) $row[1]) : '';
            if (empty($nama) || $nama === '-' || strtolower($nama) === 'nama') {
                continue;
            }

            try {
                DB::transaction(function () use ($row, $nama) {
                    $nipd = ! empty($row[2]) ? trim((string) $row[2]) : null;

                    // JK: L / P
                    $jkRaw = ! empty($row[3]) ? strtoupper(trim((string) $row[3])) : 'L';
                    $jk = (str_starts_with($jkRaw, 'P') || str_contains($jkRaw, 'PEREMPUAN')) ? 'P' : 'L';

                    $nisn = ! empty($row[4]) ? trim((string) $row[4]) : null;
                    $tempatLahir = ! empty($row[5]) ? trim((string) $row[5]) : null;

                    // Tanggal Lahir (bisa excel serial number atau string tanggal)
                    $tanggalLahir = $this->parseDate($row[6] ?? null);

                    $nik = ! empty($row[7]) ? trim((string) $row[7]) : null;
                    $agama = ! empty($row[8]) ? trim((string) $row[8]) : null;
                    $alamat = ! empty($row[9]) ? trim((string) $row[9]) : null;
                    $rt = ! empty($row[10]) ? trim((string) $row[10]) : null;
                    $rw = ! empty($row[11]) ? trim((string) $row[11]) : null;
                    $dusun = ! empty($row[12]) ? trim((string) $row[12]) : null;
                    $kelurahan = ! empty($row[13]) ? trim((string) $row[13]) : null;
                    $kecamatan = ! empty($row[14]) ? trim((string) $row[14]) : null;
                    $kodePos = ! empty($row[15]) ? trim((string) $row[15]) : null;
                    $jenisTinggal = ! empty($row[16]) ? trim((string) $row[16]) : null;
                    $alatTransportasi = ! empty($row[17]) ? trim((string) $row[17]) : null;
                    $telepon = ! empty($row[18]) ? trim((string) $row[18]) : null;
                    $hp = ! empty($row[19]) ? trim((string) $row[19]) : null;
                    $email = ! empty($row[20]) ? trim((string) $row[20]) : null;
                    $skhun = ! empty($row[21]) ? trim((string) $row[21]) : null;

                    $kpsRaw = ! empty($row[22]) ? strtolower(trim((string) $row[22])) : '';
                    $penerimaKps = in_array($kpsRaw, ['ya', '1', 'true', 'y'], true);
                    $noKps = ! empty($row[23]) ? trim((string) $row[23]) : null;

                    // Rombel (Kolom 42)
                    $namaRombel = ! empty($row[42]) ? trim((string) $row[42]) : null;
                    $kelasId = null;
                    if ($namaRombel) {
                        $kelas = Kelas::firstOrCreate(
                            ['nama' => $namaRombel],
                            [
                                'tingkat' => str_contains(strtoupper($namaRombel), 'B') ? 'B' : 'A',
                                'tahun_ajaran' => now()->year.'/'.(now()->year + 1),
                            ]
                        );
                        $kelasId = $kelas->id;
                    }

                    $noPesertaUN = ! empty($row[43]) ? trim((string) $row[43]) : null;
                    $noSeriIjazah = ! empty($row[44]) ? trim((string) $row[44]) : null;

                    $kipRaw = ! empty($row[45]) ? strtolower(trim((string) $row[45])) : '';
                    $penerimaKip = in_array($kipRaw, ['ya', '1', 'true', 'y'], true);
                    $nomorKip = ! empty($row[46]) ? trim((string) $row[46]) : null;
                    $namaDiKip = ! empty($row[47]) ? trim((string) $row[47]) : null;
                    $nomorKks = ! empty($row[48]) ? trim((string) $row[48]) : null;
                    $noAkta = ! empty($row[49]) ? trim((string) $row[49]) : null;

                    // Upsert Siswa berdasarkan NISN, NIPD, NIK, atau Nama+TglLahir (termasuk data yang di sampah)
                    $siswa = null;
                    if ($nisn) {
                        $siswa = Siswa::withTrashed()->where('nisn', $nisn)->first();
                    }
                    if (! $siswa && $nipd) {
                        $siswa = Siswa::withTrashed()->where('nipd', $nipd)->first();
                    }
                    if (! $siswa && $nik) {
                        $siswa = Siswa::withTrashed()->where('nik', $nik)->first();
                    }
                    if (! $siswa && $nama && $tanggalLahir) {
                        $siswa = Siswa::withTrashed()
                            ->where('nama', $nama)
                            ->where('tanggal_lahir', $tanggalLahir)
                            ->first();
                    }

                    $dataSiswa = [
                        'kelas_id' => $kelasId,
                        'nama' => $nama,
                        'nipd' => $nipd,
                        'jk' => $jk,
                        'nisn' => $nisn,
                        'tempat_lahir' => $tempatLahir,
                        'tanggal_lahir' => $tanggalLahir,
                        'nik' => $nik,
                        'agama' => $agama,
                        'alamat' => $alamat,
                        'rt' => $rt,
                        'rw' => $rw,
                        'dusun' => $dusun,
                        'kelurahan' => $kelurahan,
                        'kecamatan' => $kecamatan,
                        'kode_pos' => $kodePos,
                        'jenis_tinggal' => $jenisTinggal,
                        'alat_transportasi' => $alatTransportasi,
                        'telepon' => $telepon,
                        'hp' => $hp,
                        'email' => $email,
                        'skhun' => $skhun,
                        'penerima_kps' => $penerimaKps,
                        'no_kps' => $noKps,
                        'no_peserta_ujian_nasional' => $noPesertaUN,
                        'no_seri_ijazah' => $noSeriIjazah,
                        'penerima_kip' => $penerimaKip,
                        'nomor_kip' => $nomorKip,
                        'nama_di_kip' => $namaDiKip,
                        'nomor_kks' => $nomorKks,
                        'no_registrasi_akta_lahir' => $noAkta,
                        'angkatan' => $this->angkatan,
                        'status' => 'aktif',
                    ];

                    if ($siswa) {
                        if ($siswa->trashed()) {
                            $siswa->restore();
                        }
                        $siswa->update($dataSiswa);
                        $this->updatedCount++;
                    } else {
                        $siswa = Siswa::create($dataSiswa);
                        $this->successCount++;
                    }

                    // Data Ayah (Kolom 24 - 29)
                    $this->syncOrangTua($siswa->id, 'ayah', [
                        'nama' => ! empty($row[24]) ? trim((string) $row[24]) : null,
                        'tahun_lahir' => ! empty($row[25]) ? (int) $row[25] : null,
                        'jenjang_pendidikan' => ! empty($row[26]) ? trim((string) $row[26]) : null,
                        'pekerjaan' => ! empty($row[27]) ? trim((string) $row[27]) : null,
                        'penghasilan' => ! empty($row[28]) ? trim((string) $row[28]) : null,
                        'nik' => ! empty($row[29]) ? trim((string) $row[29]) : null,
                    ]);

                    // Data Ibu (Kolom 30 - 35)
                    $this->syncOrangTua($siswa->id, 'ibu', [
                        'nama' => ! empty($row[30]) ? trim((string) $row[30]) : null,
                        'tahun_lahir' => ! empty($row[31]) ? (int) $row[31] : null,
                        'jenjang_pendidikan' => ! empty($row[32]) ? trim((string) $row[32]) : null,
                        'pekerjaan' => ! empty($row[33]) ? trim((string) $row[33]) : null,
                        'penghasilan' => ! empty($row[34]) ? trim((string) $row[34]) : null,
                        'nik' => ! empty($row[35]) ? trim((string) $row[35]) : null,
                    ]);

                    // Data Wali (Kolom 36 - 41)
                    $this->syncOrangTua($siswa->id, 'wali', [
                        'nama' => ! empty($row[36]) ? trim((string) $row[36]) : null,
                        'tahun_lahir' => ! empty($row[37]) ? (int) $row[37] : null,
                        'jenjang_pendidikan' => ! empty($row[38]) ? trim((string) $row[38]) : null,
                        'pekerjaan' => ! empty($row[39]) ? trim((string) $row[39]) : null,
                        'penghasilan' => ! empty($row[40]) ? trim((string) $row[40]) : null,
                        'nik' => ! empty($row[41]) ? trim((string) $row[41]) : null,
                    ]);
                });
            } catch (\Throwable $e) {
                $this->errors[] = "Baris {$lineNum} ({$nama}): ".$e->getMessage();
            }
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function syncOrangTua(int $siswaId, string $hubungan, array $data): void
    {
        $hasAnyValue = false;
        foreach ($data as $val) {
            if (! empty($val)) {
                $hasAnyValue = true;
                break;
            }
        }

        if (! $hasAnyValue) {
            return;
        }

        OrangTua::updateOrCreate(
            ['siswa_id' => $siswaId, 'hubungan' => $hubungan],
            $data
        );
    }

    private function parseDate(mixed $raw): ?string
    {
        if (empty($raw)) {
            return null;
        }

        if (is_numeric($raw)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $raw))->format('Y-m-d');
            } catch (\Throwable) {
                // fallback
            }
        }

        try {
            $rawStr = trim((string) $raw);
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $rawStr, $matches)) {
                return Carbon::createFromDate((int) $matches[3], (int) $matches[2], (int) $matches[1])->format('Y-m-d');
            }

            return Carbon::parse($rawStr)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
