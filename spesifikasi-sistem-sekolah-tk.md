# Spesifikasi Proyek: Sistem Informasi Data Siswa TK

## 1. Ringkasan

Bangun aplikasi web **Sistem Informasi Data Siswa TK** untuk admin sekolah.

- **Framework:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL 8
- **Frontend:** Blade + Bootstrap 5 (atau AdminLTE 3)
- **Autentikasi:** Laravel Breeze (hanya admin yang boleh login)
- **Bahasa antarmuka:** Indonesia

Fitur utama:

1. Daftar siswa (semua siswa aktif)
2. Daftar siswa per kelas (rombel)
3. Daftar siswa per angkatan (tahun masuk)
4. Daftar siswa yang sudah lulus
5. Fitur meluluskan siswa
6. Import data siswa dari file Excel (format Dapodik)
7. Export daftar siswa ke Excel

Kolom data siswa mengikuti format **Daftar Peserta Didik (Dapodik)** (lihat bagian 3).

---

## 2. Aturan Bisnis

- Satu siswa punya status: `aktif`, `lulus`, `pindah`, atau `keluar`.
- **Angkatan** = tahun siswa pertama kali masuk sekolah (kolom `angkatan`, contoh `2024`).
- Siswa aktif harus punya kelas (`kelas_id`). Siswa lulus: `kelas_id` dikosongkan, `status = lulus`, `tahun_lulus` diisi.
- Satu siswa punya maksimal 3 data orang tua/wali: **ayah**, **ibu**, **wali** (tidak wajib semuanya).
- `NISN`, `NIPD`, dan `NIK` bersifat unik jika diisi (boleh kosong, terutama pada TK).
- Data tidak dihapus permanen secara default; gunakan **soft delete** pada tabel `siswas`.

---

## 3. Struktur Database

### Tabel `kelas` (Rombel)

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| nama | varchar(50) | contoh: TK A1, TK B2 |
| tingkat | varchar(10) null | A / B |
| tahun_ajaran | varchar(9) | contoh: 2025/2026 |
| wali_kelas | varchar(100) null | |
| timestamps | | |

### Tabel `siswas`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| kelas_id | FK -> kelas, nullable, nullOnDelete | Rombel Saat Ini |
| nama | varchar(150) | wajib |
| nipd | varchar(30) null unique | |
| jk | enum('L','P') | wajib |
| nisn | varchar(20) null unique | |
| tempat_lahir | varchar(100) null | |
| tanggal_lahir | date null | |
| nik | varchar(20) null unique | |
| agama | varchar(30) null | |
| alamat | text null | |
| rt | varchar(5) null | |
| rw | varchar(5) null | |
| dusun | varchar(100) null | |
| kelurahan | varchar(100) null | |
| kecamatan | varchar(100) null | |
| kode_pos | varchar(10) null | |
| jenis_tinggal | varchar(50) null | |
| alat_transportasi | varchar(50) null | |
| telepon | varchar(20) null | |
| hp | varchar(20) null | |
| email | varchar(150) null | |
| skhun | varchar(50) null | |
| penerima_kps | boolean default false | |
| no_kps | varchar(50) null | |
| no_peserta_ujian_nasional | varchar(50) null | |
| no_seri_ijazah | varchar(50) null | |
| penerima_kip | boolean default false | |
| nomor_kip | varchar(50) null | |
| nama_di_kip | varchar(150) null | |
| nomor_kks | varchar(50) null | |
| no_registrasi_akta_lahir | varchar(50) null | |
| **angkatan** | year | tahun masuk, wajib, index |
| **status** | enum('aktif','lulus','pindah','keluar') default 'aktif' | index |
| **tahun_lulus** | year null | diisi saat lulus |
| timestamps + softDeletes | | |

### Tabel `orang_tuas`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| siswa_id | FK -> siswas, cascadeOnDelete | |
| hubungan | enum('ayah','ibu','wali') | |
| nama | varchar(150) null | |
| tahun_lahir | year null | |
| jenjang_pendidikan | varchar(50) null | |
| pekerjaan | varchar(100) null | |
| penghasilan | varchar(50) null | |
| nik | varchar(20) null | |
| timestamps | | |

Constraint: `UNIQUE(siswa_id, hubungan)`.

### Relasi Eloquent

- `Kelas` hasMany `Siswa`
- `Siswa` belongsTo `Kelas`
- `Siswa` hasMany `OrangTua`; juga `hasOne` untuk `ayah`, `ibu`, `wali` (filter berdasarkan kolom `hubungan`)
- Scope pada `Siswa`: `aktif()`, `lulus()`

---

## 4. Halaman dan Fitur

### 4.1 Dashboard
- Kartu ringkasan: total siswa aktif, jumlah laki-laki, jumlah perempuan, total lulus, jumlah kelas.
- Tabel kecil: jumlah siswa per kelas.

### 4.2 Data Siswa (`/siswa`)
- Tabel siswa aktif: No, Nama, NISN, JK, Tanggal Lahir, Kelas, Angkatan, Aksi.
- Pencarian (nama / NISN / NIPD), filter kelas, filter angkatan, pagination 20 per halaman.
- Tombol: Tambah, Detail, Edit, Hapus (soft delete), Import Excel, Export Excel.
- Form tambah/edit dibagi dalam tab atau section:
  1. Data Pribadi
  2. Alamat dan Kontak
  3. Data Ayah
  4. Data Ibu
  5. Data Wali
  6. Data Kelas dan Bantuan (Rombel, KPS, KIP, KKS, Akta Lahir)
- Validasi: nama dan jk wajib; nisn/nik/nipd unik; email valid; tanggal lahir tidak boleh di masa depan.

### 4.3 Siswa Per Kelas (`/siswa/kelas/{kelas}`)
- Dropdown untuk memilih kelas, lalu tampil daftar siswa aktif di kelas tersebut.
- Tampilkan jumlah siswa, jumlah L/P, dan nama wali kelas.
- Tombol cetak/export daftar kelas.
- Disediakan juga CRUD kelas (`/kelas`).

### 4.4 Siswa Per Angkatan (`/siswa/angkatan/{angkatan}`)
- Dropdown tahun angkatan (diambil dari `DISTINCT angkatan`).
- Tampilkan semua siswa angkatan tersebut beserta statusnya (aktif/lulus/pindah/keluar).
- Ringkasan: jumlah per status.

### 4.5 Siswa Lulus (`/siswa/lulus`)
- Daftar siswa dengan `status = lulus`, filter berdasarkan `tahun_lulus`.
- Kolom: Nama, NISN, JK, Angkatan, Tahun Lulus, No Seri Ijazah.
- Tombol export Excel.

### 4.6 Meluluskan Siswa (`POST /siswa/luluskan`)
- Dari halaman per kelas: centang beberapa siswa (atau pilih semua), pilih tahun lulus, klik **Luluskan**.
- Efek: `status = lulus`, `tahun_lulus = input`, `kelas_id = null`.
- Tampilkan dialog konfirmasi sebelum proses.
- Sediakan juga opsi **Batalkan kelulusan** (mengembalikan ke `aktif` dan memilih kelas).

### 4.7 Import Excel
- Upload `.xlsx` dalam format Dapodik (lihat bagian 5).
- Validasi per baris; tampilkan ringkasan: berhasil, gagal, dan alasan gagal.
- Jika `NISN` atau `NIK` sudah ada: update data yang sudah ada (upsert), bukan duplikat.
- Jika nama rombel di Excel belum ada di tabel `kelas`, buat otomatis.
- `angkatan` tidak ada di Excel Dapodik: tampilkan input "Tahun angkatan" di form upload dan terapkan ke seluruh baris yang diimpor.

### 4.8 Export Excel
- Export daftar yang sedang difilter (kolom sama dengan format Dapodik).

---

## 5. Format File Import (Dapodik)

Paket: `maatwebsite/excel`.

Struktur sheet **"Daftar Peserta Didik"**:

- Baris 1: judul "Daftar Peserta Didik"
- Baris 2-3: header (baris 2 header utama, baris 3 sub-header untuk Data Ayah/Ibu/Wali)
- **Data mulai baris 4**

Karena header bertingkat, baca per **indeks kolom** (mulai dari 0), bukan nama header:

| Indeks | Kolom Excel | Field database |
|---|---|---|
| 0 | No | (abaikan) |
| 1 | Nama | siswas.nama |
| 2 | NIPD | nipd |
| 3 | JK | jk |
| 4 | NISN | nisn |
| 5 | Tempat Lahir | tempat_lahir |
| 6 | Tanggal Lahir | tanggal_lahir |
| 7 | NIK | nik |
| 8 | Agama | agama |
| 9 | Alamat | alamat |
| 10 | RT | rt |
| 11 | RW | rw |
| 12 | Dusun | dusun |
| 13 | Kelurahan | kelurahan |
| 14 | Kecamatan | kecamatan |
| 15 | Kode Pos | kode_pos |
| 16 | Jenis Tinggal | jenis_tinggal |
| 17 | Alat Transportasi | alat_transportasi |
| 18 | Telepon | telepon |
| 19 | HP | hp |
| 20 | E-Mail | email |
| 21 | SKHUN | skhun |
| 22 | Penerima KPS | penerima_kps |
| 23 | No. KPS | no_kps |
| 24-29 | Data Ayah: Nama, Tahun Lahir, Jenjang Pendidikan, Pekerjaan, Penghasilan, NIK | orang_tuas (hubungan = ayah) |
| 30-35 | Data Ibu (urutan sama) | orang_tuas (hubungan = ibu) |
| 36-41 | Data Wali (urutan sama) | orang_tuas (hubungan = wali) |
| 42 | Rombel Saat Ini | kelas.nama -> siswas.kelas_id |
| 43 | No Peserta Ujian Nasional | no_peserta_ujian_nasional |
| 44 | No Seri Ijazah | no_seri_ijazah |
| 45 | Penerima KIP | penerima_kip |
| 46 | Nomor KIP | nomor_kip |
| 47 | Nama di KIP | nama_di_kip |
| 48 | Nomor KKS | nomor_kks |
| 49 | No Registrasi Akta Lahir | no_registrasi_akta_lahir |

Catatan konversi data:

- `jk`: `L`/`P` (terima juga "Laki-laki"/"Perempuan").
- `penerima_kps` dan `penerima_kip`: "Ya" atau "1" -> `true`; selainnya `false`.
- `tanggal_lahir`: tangani format tanggal Excel (serial number) maupun teks `dd/mm/yyyy`.
- NISN, NIK, dan nomor lain harus dibaca sebagai **string** (jangan hilangkan angka 0 di depan dan jangan jadi notasi ilmiah).
- Jangan buat record `orang_tuas` jika seluruh isian baris orang tua/wali kosong.

---

## 6. Routing

```php
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Letakkan route khusus SEBELUM resource agar tidak tertabrak /siswa/{siswa}
    Route::get('/siswa/kelas/{kelas}',       [SiswaController::class, 'perKelas'])->name('siswa.kelas');
    Route::get('/siswa/angkatan/{angkatan}', [SiswaController::class, 'perAngkatan'])->name('siswa.angkatan');
    Route::get('/siswa/lulus',               [SiswaController::class, 'lulus'])->name('siswa.lulus');
    Route::post('/siswa/luluskan',           [SiswaController::class, 'luluskan'])->name('siswa.luluskan');
    Route::post('/siswa/batalkan-lulus',     [SiswaController::class, 'batalkanLulus'])->name('siswa.batalkan-lulus');
    Route::post('/siswa/import',             [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('/siswa/export',              [SiswaController::class, 'export'])->name('siswa.export');

    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', KelasController::class);
});
```

---

## 7. Struktur Kode yang Diharapkan

- `app/Models`: `Siswa`, `Kelas`, `OrangTua`
- `app/Http/Controllers`: `DashboardController`, `SiswaController`, `KelasController`
- `app/Http/Requests`: `StoreSiswaRequest`, `UpdateSiswaRequest` (Form Request untuk validasi)
- `app/Imports/SiswaImport.php` dan `app/Exports/SiswaExport.php`
- `database/migrations`: 3 migration (kelas, siswas, orang_tuas) dengan urutan yang benar (kelas dibuat sebelum siswas)
- `database/seeders`: seeder contoh data (3 kelas, sekitar 30 siswa dummy, beberapa siswa lulus)
- `resources/views`: layout utama, `siswa/` (index, create, edit, show, per-kelas, per-angkatan, lulus, import), `kelas/`, dan `dashboard`
- Gunakan Eloquent dengan eager loading (`with('kelas')`) untuk menghindari masalah N+1.
- Simpan data siswa dan orang tua dalam satu `DB::transaction`.

---

## 8. Tahapan Pengerjaan

1. Inisialisasi proyek Laravel, konfigurasi `.env` MySQL, pasang Breeze.
2. Buat migration, model, relasi, dan seeder.
3. Buat CRUD Kelas.
4. Buat CRUD Siswa beserta form data orang tua/wali.
5. Buat halaman laporan: per kelas, per angkatan, siswa lulus.
6. Buat fitur meluluskan dan membatalkan kelulusan.
7. Buat import dan export Excel.
8. Buat dashboard.
9. Uji fitur (Feature Test untuk CRUD, kelulusan, dan import), lalu rapikan UI.

---

## 9. Kriteria Selesai

- [ ] `php artisan migrate --seed` berjalan tanpa error.
- [ ] Login admin berfungsi dan semua halaman dilindungi autentikasi.
- [ ] CRUD siswa menyimpan semua kolom di bagian 3, termasuk data ayah, ibu, dan wali.
- [ ] Halaman per kelas, per angkatan, dan siswa lulus menampilkan data yang benar.
- [ ] Meluluskan beberapa siswa sekaligus mengubah status dan mengosongkan kelas.
- [ ] Import file Dapodik berhasil dan tidak membuat duplikat saat diimpor dua kali.
- [ ] Export Excel sesuai filter yang aktif.
- [ ] Tidak ada query N+1 pada halaman daftar.
