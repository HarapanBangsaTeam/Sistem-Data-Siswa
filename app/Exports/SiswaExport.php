<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SiswaExport implements FromQuery, WithColumnWidths, WithCustomStartCell, WithEvents, WithMapping, WithTitle
{
    private int $rowNumber = 0;

    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private array $filters = []) {}

    public function query(): Builder
    {
        $query = Siswa::query()->with(['kelas', 'ayah', 'ibu', 'wali']);

        if (! empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (! empty($this->filters['kelas_id'])) {
            $query->where('kelas_id', $this->filters['kelas_id']);
        }

        if (! empty($this->filters['angkatan'])) {
            $query->where('angkatan', $this->filters['angkatan']);
        }

        if (! empty($this->filters['tahun_lulus'])) {
            $query->where('tahun_lulus', $this->filters['tahun_lulus']);
        }

        if (! empty($this->filters['ids'])) {
            $ids = is_array($this->filters['ids'])
                ? $this->filters['ids']
                : array_filter(explode(',', (string) $this->filters['ids']));
            if (! empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        if (! empty($this->filters['q'])) {
            $search = $this->filters['q'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nipd', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nama');
    }

    public function title(): string
    {
        return 'Daftar Peserta Didik';
    }

    public function startCell(): string
    {
        return 'A7';
    }

    /**
     * @return array<string, float|int>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 32.5,
            'C' => 17.5,
            'D' => 6,
            'E' => 11,
            'F' => 17.5,
            'G' => 14.5,
            'H' => 17.5,
            'I' => 11,
            'J' => 45.5,
            'K' => 5,
            'L' => 5,
            'M' => 20,
            'N' => 18.5,
            'O' => 15,
            'P' => 10,
            'Q' => 18.5,
            'R' => 22.5,
            'S' => 13.5,
            'T' => 15.5,
            'U' => 26,
            'V' => 20.5,
            'W' => 15,
            'X' => 15.5,
            'Y' => 28.5,
            'Z' => 12.5,
            'AA' => 20.5,
            'AB' => 18,
            'AC' => 24.5,
            'AD' => 25.5,
            'AE' => 28.5,
            'AF' => 12.5,
            'AG' => 20.5,
            'AH' => 18.5,
            'AI' => 24.5,
            'AJ' => 25.5,
            'AK' => 28.5,
            'AL' => 12.5,
            'AM' => 20.5,
            'AN' => 18.5,
            'AO' => 18.5,
            'AP' => 22.5,
            'AQ' => 18.5,
            'AR' => 17.5,
            'AS' => 17,
            'AT' => 11,
            'AU' => 17,
            'AV' => 17,
            'AW' => 16,
            'AX' => 18.5,
            'AY' => 12,
            'AZ' => 21,
            'BA' => 19.5,
            'BB' => 16.5,
            'BC' => 18.5,
            'BD' => 27,
            'BE' => 36,
            'BF' => 15,
            'BG' => 15,
            'BH' => 15,
            'BI' => 15,
            'BJ' => 15,
            'BK' => 15,
            'BL' => 15,
            'BM' => 15,
            'BN' => 15,
        ];
    }

    /**
     * @param  Siswa  $siswa
     * @return array<int, mixed>
     */
    public function map($siswa): array
    {
        $this->rowNumber++;

        $ayah = $siswa->ayah;
        $ibu = $siswa->ibu;
        $wali = $siswa->wali;

        return [
            $this->rowNumber, // A
            $siswa->nama, // B
            $siswa->nipd, // C
            $siswa->jk, // D
            $siswa->nisn ? ' '.$siswa->nisn : '', // E
            $siswa->tempat_lahir, // F
            $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d/m/Y') : '', // G
            $siswa->nik ? ' '.$siswa->nik : '', // H
            $siswa->agama, // I
            $siswa->alamat, // J
            $siswa->rt, // K
            $siswa->rw, // L
            $siswa->dusun, // M
            $siswa->kelurahan, // N
            $siswa->kecamatan, // O
            $siswa->kode_pos, // P
            $siswa->jenis_tinggal, // Q
            $siswa->alat_transportasi, // R
            $siswa->telepon, // S
            $siswa->hp, // T
            $siswa->email, // U
            $siswa->skhun, // V
            $siswa->penerima_kps ? 'Ya' : 'Tidak', // W
            $siswa->no_kps, // X
            $ayah?->nama, // Y
            $ayah?->tahun_lahir, // Z
            $ayah?->jenjang_pendidikan, // AA
            $ayah?->pekerjaan, // AB
            $ayah?->penghasilan, // AC
            $ayah?->nik ? ' '.$ayah->nik : '', // AD
            $ibu?->nama, // AE
            $ibu?->tahun_lahir, // AF
            $ibu?->jenjang_pendidikan, // AG
            $ibu?->pekerjaan, // AH
            $ibu?->penghasilan, // AI
            $ibu?->nik ? ' '.$ibu->nik : '', // AJ
            $wali?->nama, // AK
            $wali?->tahun_lahir, // AL
            $wali?->jenjang_pendidikan, // AM
            $wali?->pekerjaan, // AN
            $wali?->penghasilan, // AO
            $wali?->nik ? ' '.$wali->nik : '', // AP
            $siswa->kelas?->nama ?? ($siswa->status === 'lulus' ? 'Lulus' : '-'), // AQ
            $siswa->no_peserta_ujian_nasional, // AR
            $siswa->no_seri_ijazah, // AS
            $siswa->penerima_kip ? 'Ya' : 'Tidak', // AT
            $siswa->nomor_kip, // AU
            $siswa->nama_di_kip, // AV
            $siswa->nomor_kks, // AW
            $siswa->no_registrasi_akta_lahir, // AX
            '', // AY: Bank
            '', // AZ: Nomor Rekening Bank
            '', // BA: Rekening Atas Nama
            '', // BB: Layak PIP (usulan dari sekolah)
            '', // BC: Alasan Layak PIP
            '', // BD: Kebutuhan Khusus
            '', // BE: Sekolah Asal
            '', // BF: Anak ke-berapa
            '', // BG: Lintang
            '', // BH: Bujur
            '', // BI: No KK
            '', // BJ: Berat Badan
            '', // BK: Tinggi Badan
            '', // BL: Lingkar Kepala
            '', // BM: Jml. Saudara Kandung
            '', // BN: Jarak Rumah ke Sekolah (KM)
        ];
    }

    /**
     * @return array<string, \Closure>
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1. Judul Sheet di A1
                $sheet->setCellValue('A1', 'Daftar Peserta Didik');
                $sheet->getStyle('A1')->getFont()->setName('Calibri')->setSize(14)->setBold(true);
                $sheet->getRowDimension(1)->setRowHeight(19.05);

                // 2. Row 5 Headers
                $row5Headers = [
                    'A' => 'No', 'B' => 'Nama', 'C' => 'NIPD', 'D' => 'JK', 'E' => 'NISN',
                    'F' => 'Tempat Lahir', 'G' => 'Tanggal Lahir', 'H' => 'NIK', 'I' => 'Agama',
                    'J' => 'Alamat', 'K' => 'RT', 'L' => 'RW', 'M' => 'Dusun', 'N' => 'Kelurahan',
                    'O' => 'Kecamatan', 'P' => 'Kode Pos', 'Q' => 'Jenis Tinggal', 'R' => 'Alat Transportasi',
                    'S' => 'Telepon', 'T' => 'HP', 'U' => 'E-Mail', 'V' => 'SKHUN', 'W' => 'Penerima KPS', 'X' => 'No. KPS',
                    'Y' => 'Data Ayah',
                    'AE' => 'Data Ibu',
                    'AK' => 'Data Wali',
                    'AQ' => 'Rombel Saat Ini', 'AR' => 'No Peserta Ujian Nasional', 'AS' => 'No Seri Ijazah',
                    'AT' => 'Penerima KIP', 'AU' => 'Nomor KIP', 'AV' => 'Nama di KIP', 'AW' => 'Nomor KKS',
                    'AX' => 'No Registrasi Akta Lahir', 'AY' => 'Bank', 'AZ' => 'Nomor Rekening Bank',
                    'BA' => 'Rekening Atas Nama', 'BB' => 'Layak PIP (usulan dari sekolah)', 'BC' => 'Alasan Layak PIP',
                    'BD' => 'Kebutuhan Khusus', 'BE' => 'Sekolah Asal', 'BF' => 'Anak ke-berapa',
                    'BG' => 'Lintang', 'BH' => 'Bujur', 'BI' => 'No KK', 'BJ' => 'Berat Badan',
                    'BK' => 'Tinggi Badan', 'BL' => 'Lingkar Kepala', 'BM' => "Jml. Saudara\nKandung",
                    'BN' => "Jarak Rumah\nke Sekolah (KM)",
                ];

                foreach ($row5Headers as $col => $val) {
                    $sheet->setCellValue("{$col}5", $val);
                }

                // 3. Row 6 Sub-headers
                $row6SubHeaders = [
                    // Data Ayah (Y - AD)
                    'Y' => 'Nama', 'Z' => 'Tahun Lahir', 'AA' => 'Jenjang Pendidikan', 'AB' => 'Pekerjaan', 'AC' => 'Penghasilan', 'AD' => 'NIK',
                    // Data Ibu (AE - AJ)
                    'AE' => 'Nama', 'AF' => 'Tahun Lahir', 'AG' => 'Jenjang Pendidikan', 'AH' => 'Pekerjaan', 'AI' => 'Penghasilan', 'AJ' => 'NIK',
                    // Data Wali (AK - AP)
                    'AK' => 'Nama', 'AL' => 'Tahun Lahir', 'AM' => 'Jenjang Pendidikan', 'AN' => 'Pekerjaan', 'AO' => 'Penghasilan', 'AP' => 'NIK',
                ];

                foreach ($row6SubHeaders as $col => $val) {
                    $sheet->setCellValue("{$col}6", $val);
                }

                // 4. Merge Cells Vertikal (Row 5 & 6)
                $verticalMerges = [
                    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
                    'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN',
                ];

                foreach ($verticalMerges as $col) {
                    $sheet->mergeCells("{$col}5:{$col}6");
                }

                // Merge Cells Horizontal untuk Orang Tua
                $sheet->mergeCells('Y5:AD5');
                $sheet->mergeCells('AE5:AJ5');
                $sheet->mergeCells('AK5:AP5');

                // 5. Styling Header
                $headerStyle = [
                    'font' => [
                        'name' => 'Calibri',
                        'size' => 11,
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];

                $sheet->getStyle('A5:BN6')->applyFromArray($headerStyle);
                $sheet->getRowDimension(5)->setRowHeight(16.05);
                $sheet->getRowDimension(6)->setRowHeight(16.05);
            },
        ];
    }
}
