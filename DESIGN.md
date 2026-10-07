# Design Direction: Sistem Informasi Data Siswa TK Harapan Bangsa

## 1. Identity & Purpose
- **Nama Produk:** Sistem Informasi Data Siswa TK Harapan Bangsa
- **Pengguna:** Administrator dan Staf Tata Usaha Sekolah TK
- **Tujuan:** Pengelolaan data induk siswa, rombel (kelas), data orang tua, kelulusan, dan sinkronisasi Dapodik (Import/Export Excel) dengan antarmuka yang efisien, mudah dipahami, dan bebas hambatan visual.

## 2. Brand & Mood
- **Karakter:** Rapi, terstruktur, ramah (nuansa edukasi usia dini), namun tetap formal dan fungsional sebagai alat kerja administrasi.
- **Tone:** Informatif, jelas, minim distraksi, tanpa ornamen berlebihan.

## 3. Palet Warna (Kontras WCAG AA)
- **Primary:** Deep Teal (`#0F766E`) - Warna institusi pendidikan yang tenang dan terpercaya.
- **Primary Hover/Active:** Darker Teal (`#115E59`).
- **Accent:** Warm Amber (`#D97706`) - Penanda aksi penting/sorotan status.
- **Surface / Backgrounds:**
  - Body: Soft Slate Light (`#F8FAFC`)
  - Card & Container: Clean White (`#FFFFFF`)
  - Border: Soft Grey (`#E2E8F0`)
- **Teks:**
  - Teks Utama: Slate 900 (`#0F172A`) (Kontras > 12:1 terhadap latar putih)
  - Teks Sekunder: Slate 600 (`#475569`) (Kontras > 5:1 terhadap latar putih)
- **Status Badges:**
  - Aktif: Emerald (`#047857` pada latar `#ECFDF5`)
  - Lulus: Slate Indigo (`#4338CA` pada latar `#EEF2FF`)
  - Pindah/Keluar: Amber/Rose (`#B45309` pada latar `#FFFBEB`)

## 4. Tipografi
- **Font:** Inter / Plus Jakarta Sans / system-ui, sans-serif
- **Hirarki:**
  - Page Heading: 1.5rem (24px), font-bold, text-slate-900
  - Section Heading: 1.125rem (18px), font-semibold
  - Body / Label: 0.875rem (14px), font-medium
  - Table & Meta: 0.8125rem (13px), regular

## 5. Dials
- **ENERGY:** 2 (Fokus kerja administratif, bersih tanpa elemen animasi yang memperlambat)
- **RHYTHM:** 2 (Struktur grid modular, tabel data responsif, form bertahap terorganisir)
- **MOTION:** 1 (Transisi halus minimal untuk hover dan modal/tab, instan dan responsif)
