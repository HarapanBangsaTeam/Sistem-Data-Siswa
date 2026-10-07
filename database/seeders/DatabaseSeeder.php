<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        User::firstOrCreate(
            ['email' => 'admin@tkharapanbangsa.sch.id'],
            [
                'name' => 'Administrator TK',
                'password' => bcrypt('password'),
            ]
        );

        // 2. Data Kelas (Rombel)
        $kelasA1 = Kelas::create([
            'nama' => 'TK A1 (Bintang)',
            'tingkat' => 'A',
            'tahun_ajaran' => '2025/2026',
            'wali_kelas' => 'Siti Nurhaliza, S.Pd',
        ]);

        $kelasA2 = Kelas::create([
            'nama' => 'TK A2 (Bulan)',
            'tingkat' => 'A',
            'tahun_ajaran' => '2025/2026',
            'wali_kelas' => 'Ratna Sari, S.Pd',
        ]);

        $kelasB1 = Kelas::create([
            'nama' => 'TK B1 (Matahari)',
            'tingkat' => 'B',
            'tahun_ajaran' => '2025/2026',
            'wali_kelas' => 'Dewi Lestari, S.Pd',
        ]);

        // 3. Data Siswa Dummy & Orang Tua
        $kelasList = [$kelasA1, $kelasA2, $kelasB1];
        $namaAnakL = [
            'Ahmad Fauzi', 'Budi Santoso', 'Dimas Prasetyo', 'Fajar Ramadhan', 'Gilang Pratama',
            'Hafiz Maulana', 'Irfan Hakim', 'Kevin Anggara', 'Muhammad Rizky', 'Naufal Alfarizi',
            'Rafi Gunawan', 'Satria Wijaya', 'Teguh Saputra', 'Vino Bastian', 'Yoga Pratama',
        ];
        $namaAnakP = [
            'Aisyah Putri', 'Bella Safitri', 'Citra Kirana', 'Dinda Ayu', 'Eni Rahmawati',
            'Farah Diba', 'Gita Gutawa', 'Hana Hanifah', 'Indah Permata', 'Jasmine Zahra',
            'Kayla Nadhira', 'Larasati Dewi', 'Maya Anggraini', 'Nabila Syakieb', 'Olivia Zalianty',
        ];

        $pekerjaanList = ['Karyawan Swasta', 'PNS', 'Wiraswasta', 'Guru', 'Pedagang', 'Dokter', 'Buruh'];
        $pendidikanList = ['SMA/SMK', 'D3', 'S1', 'S2'];
        $penghasilanList = ['Rp 1.000.000 - Rp 2.000.000', 'Rp 2.000.000 - Rp 5.000.000', 'Rp 5.000.000 - Rp 10.000.000', '> Rp 10.000.000'];

        $index = 1;
        // Buat siswa aktif
        foreach ($namaAnakL as $key => $nama) {
            $kelas = $kelasList[$key % 3];
            $tglLahir = now()->subYears(5)->subMonths(rand(1, 11))->subDays(rand(1, 28))->format('Y-m-d');
            $nisn = '00'.rand(10000000, 99999999);
            $nik = '320101'.rand(100000, 999999).'000'.$index;
            $nipd = 'PD'.(2024000 + $index);

            $siswa = Siswa::create([
                'kelas_id' => $kelas->id,
                'nama' => $nama,
                'nipd' => $nipd,
                'jk' => 'L',
                'nisn' => $nisn,
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => $tglLahir,
                'nik' => $nik,
                'agama' => 'Islam',
                'alamat' => 'Jl. Harapan Bangsa No. '.$index,
                'rt' => '0'.rand(1, 9),
                'rw' => '0'.rand(1, 9),
                'kelurahan' => 'Kebayoran',
                'kecamatan' => 'Kebayoran Baru',
                'kode_pos' => '12130',
                'jenis_tinggal' => 'Bersama Orang Tua',
                'alat_transportasi' => 'Sepeda Motor',
                'hp' => '0812'.rand(10000000, 99999999),
                'email' => strtolower(str_replace(' ', '', $nama)).'@example.com',
                'penerima_kps' => ($key % 4 == 0),
                'penerima_kip' => ($key % 5 == 0),
                'angkatan' => 2024,
                'status' => 'aktif',
            ]);

            // Orang Tua Ayah
            OrangTua::create([
                'siswa_id' => $siswa->id,
                'hubungan' => 'ayah',
                'nama' => 'Bpk. '.explode(' ', $nama)[1].' Senior',
                'tahun_lahir' => rand(1980, 1992),
                'jenjang_pendidikan' => $pendidikanList[array_rand($pendidikanList)],
                'pekerjaan' => $pekerjaanList[array_rand($pekerjaanList)],
                'penghasilan' => $penghasilanList[array_rand($penghasilanList)],
                'nik' => '320101'.rand(100000, 999999).'100'.$index,
            ]);

            // Orang Tua Ibu
            OrangTua::create([
                'siswa_id' => $siswa->id,
                'hubungan' => 'ibu',
                'nama' => 'Ibu '.$namaAnakP[$key],
                'tahun_lahir' => rand(1982, 1994),
                'jenjang_pendidikan' => $pendidikanList[array_rand($pendidikanList)],
                'pekerjaan' => ($key % 2 == 0) ? 'Ibu Rumah Tangga' : $pekerjaanList[array_rand($pekerjaanList)],
                'penghasilan' => $penghasilanList[array_rand($penghasilanList)],
                'nik' => '320101'.rand(100000, 999999).'200'.$index,
            ]);

            $index++;
        }

        foreach ($namaAnakP as $key => $nama) {
            $kelas = $kelasList[($key + 1) % 3];
            $tglLahir = now()->subYears(5)->subMonths(rand(1, 11))->subDays(rand(1, 28))->format('Y-m-d');
            $nisn = '00'.rand(10000000, 99999999);
            $nik = '320101'.rand(100000, 999999).'000'.$index;
            $nipd = 'PD'.(2024000 + $index);

            $siswa = Siswa::create([
                'kelas_id' => $kelas->id,
                'nama' => $nama,
                'nipd' => $nipd,
                'jk' => 'P',
                'nisn' => $nisn,
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => $tglLahir,
                'nik' => $nik,
                'agama' => 'Islam',
                'alamat' => 'Jl. Melati No. '.$index,
                'rt' => '0'.rand(1, 9),
                'rw' => '0'.rand(1, 9),
                'kelurahan' => 'Cilandak',
                'kecamatan' => 'Cilandak',
                'kode_pos' => '12430',
                'jenis_tinggal' => 'Bersama Orang Tua',
                'alat_transportasi' => 'Mobil Pribadi',
                'hp' => '0813'.rand(10000000, 99999999),
                'email' => strtolower(str_replace(' ', '', $nama)).'@example.com',
                'penerima_kps' => false,
                'penerima_kip' => false,
                'angkatan' => 2024,
                'status' => 'aktif',
            ]);

            OrangTua::create([
                'siswa_id' => $siswa->id,
                'hubungan' => 'ayah',
                'nama' => 'Bpk. '.$namaAnakL[$key],
                'tahun_lahir' => rand(1978, 1990),
                'jenjang_pendidikan' => $pendidikanList[array_rand($pendidikanList)],
                'pekerjaan' => $pekerjaanList[array_rand($pekerjaanList)],
                'penghasilan' => $penghasilanList[array_rand($penghasilanList)],
                'nik' => '320101'.rand(100000, 999999).'100'.$index,
            ]);

            OrangTua::create([
                'siswa_id' => $siswa->id,
                'hubungan' => 'ibu',
                'nama' => 'Ibu '.explode(' ', $nama)[1].' Senior',
                'tahun_lahir' => rand(1982, 1993),
                'jenjang_pendidikan' => $pendidikanList[array_rand($pendidikanList)],
                'pekerjaan' => 'Wiraswasta',
                'penghasilan' => $penghasilanList[array_rand($penghasilanList)],
                'nik' => '320101'.rand(100000, 999999).'200'.$index,
            ]);

            $index++;
        }

        // 4. Data Siswa Lulus Dummy
        $namaLulus = ['Alif Hidayat', 'Zahra Amelia', 'Rian Kurniawan', 'Salma Salsabila', 'Danang Prasetya'];
        foreach ($namaLulus as $k => $nama) {
            $siswaLulus = Siswa::create([
                'kelas_id' => null,
                'nama' => $nama,
                'nipd' => 'PD'.(2023000 + $k + 1),
                'jk' => ($k % 2 == 0) ? 'L' : 'P',
                'nisn' => '00'.rand(10000000, 99999999),
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2018-05-10',
                'nik' => '320101'.rand(100000, 999999).'000'.$index,
                'agama' => 'Islam',
                'alamat' => 'Jl. Bunga Mawar No. '.($k + 1),
                'angkatan' => 2023,
                'status' => 'lulus',
                'tahun_lulus' => 2025,
                'no_seri_ijazah' => 'TK-HB/2025/00'.($k + 1),
            ]);

            OrangTua::create([
                'siswa_id' => $siswaLulus->id,
                'hubungan' => 'ayah',
                'nama' => 'Bpk. '.$nama,
                'tahun_lahir' => 1985,
                'nik' => '320101'.rand(100000, 999999).'100'.$index,
            ]);

            $index++;
        }
    }
}
