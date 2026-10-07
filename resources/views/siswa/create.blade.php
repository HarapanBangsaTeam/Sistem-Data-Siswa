<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                    <a href="{{ route('siswa.index') }}" class="hover:text-teal-700">Data Siswa</a>
                    <span>/</span>
                    <span class="text-slate-700 font-medium">Tambah Siswa Baru</span>
                </div>
                <h1 class="text-xl font-bold text-slate-900">Formulir Pendaftaran Siswa</h1>
            </div>
            <a href="{{ route('siswa.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                Kembali
            </a>
        </div>
    </x-slot>

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs shadow-xs" role="alert">
            <div class="font-bold mb-1">Harap periksa isian formulir:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden" x-data="{ activeTab: 'pribadi' }">
        <!-- Navigation Tabs -->
        <div class="flex border-b border-slate-200 bg-slate-50/70 overflow-x-auto text-xs font-medium">
            <button type="button" @click="activeTab = 'pribadi'" :class="activeTab === 'pribadi' ? 'border-teal-700 text-teal-800 bg-white font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-3.5 border-b-2 whitespace-nowrap transition">
                1. Data Pribadi
            </button>
            <button type="button" @click="activeTab = 'alamat'" :class="activeTab === 'alamat' ? 'border-teal-700 text-teal-800 bg-white font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-3.5 border-b-2 whitespace-nowrap transition">
                2. Alamat & Kontak
            </button>
            <button type="button" @click="activeTab = 'ayah'" :class="activeTab === 'ayah' ? 'border-teal-700 text-teal-800 bg-white font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-3.5 border-b-2 whitespace-nowrap transition">
                3. Data Ayah
            </button>
            <button type="button" @click="activeTab = 'ibu'" :class="activeTab === 'ibu' ? 'border-teal-700 text-teal-800 bg-white font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-3.5 border-b-2 whitespace-nowrap transition">
                4. Data Ibu
            </button>
            <button type="button" @click="activeTab = 'wali'" :class="activeTab === 'wali' ? 'border-teal-700 text-teal-800 bg-white font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-3.5 border-b-2 whitespace-nowrap transition">
                5. Data Wali
            </button>
            <button type="button" @click="activeTab = 'rombel'" :class="activeTab === 'rombel' ? 'border-teal-700 text-teal-800 bg-white font-semibold' : 'border-transparent text-slate-600 hover:text-slate-900'" class="px-5 py-3.5 border-b-2 whitespace-nowrap transition">
                6. Rombel & Bantuan
            </button>
        </div>

        <form method="POST" action="{{ route('siswa.store') }}" class="p-6">
            @csrf

            <!-- 1. Data Pribadi -->
            <div x-show="activeTab === 'pribadi'" class="space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b pb-2">Identitas Utama Siswa</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nama lengkap siswa">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jk" required class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jk') === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                            <option value="P" {{ old('jk') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NISN (Nomor Induk Siswa Nasional)</label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="10 digit nomor (jika ada)">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIPD (Nomor Induk Peserta Didik)</label>
                        <input type="text" name="nipd" value="{{ old('nipd') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nomor registrasi internal sekolah">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="16 digit NIK dari KK / Akta">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Agama</label>
                        <select name="agama" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="">Pilih Agama</option>
                            @foreach (['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agm)
                                <option value="{{ $agm }}" {{ old('agama') === $agm ? 'selected' : '' }}>{{ $agm }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Kota/Kabupaten lahir">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="button" @click="activeTab = 'alamat'" class="px-4 py-2 bg-teal-700 text-white rounded-lg text-xs font-semibold hover:bg-teal-800 transition">
                        Lanjut ke Alamat &rarr;
                    </button>
                </div>
            </div>

            <!-- 2. Alamat & Kontak -->
            <div x-show="activeTab === 'alamat'" class="space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b pb-2">Alamat Tempat Tinggal & Kontak Siswa</h2>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nama jalan, gang, nomor rumah">{{ old('alamat') }}</textarea>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">RT</label>
                        <input type="text" name="rt" value="{{ old('rt') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="001">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">RW</label>
                        <input type="text" name="rw" value="{{ old('rw') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="002">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Dusun</label>
                        <input type="text" name="dusun" value="{{ old('dusun') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nama dusun">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="12345">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kelurahan / Desa</label>
                        <input type="text" name="kelurahan" value="{{ old('kelurahan') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nama kelurahan">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kecamatan</label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nama kecamatan">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Tinggal</label>
                        <select name="jenis_tinggal" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="Bersama orang tua" {{ old('jenis_tinggal') === 'Bersama orang tua' ? 'selected' : '' }}>Bersama orang tua</option>
                            <option value="Wali" {{ old('jenis_tinggal') === 'Wali' ? 'selected' : '' }}>Wali</option>
                            <option value="Kost" {{ old('jenis_tinggal') === 'Kost' ? 'selected' : '' }}>Kost</option>
                            <option value="Panti Asuhan" {{ old('jenis_tinggal') === 'Panti Asuhan' ? 'selected' : '' }}>Panti Asuhan</option>
                            <option value="Lainnya" {{ old('jenis_tinggal') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alat Transportasi ke Sekolah</label>
                        <input type="text" name="alat_transportasi" value="{{ old('alat_transportasi') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Contoh: Jalan kaki, Sepeda Motor, Mobil">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Telepon Rumah</label>
                        <input type="text" name="telepon" value="{{ old('telepon') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="021...">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP / WhatsApp</label>
                        <input type="text" name="hp" value="{{ old('hp') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="08...">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="alamat@email.com">
                    </div>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" @click="activeTab = 'pribadi'" class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-50">
                        &larr; Kembali
                    </button>
                    <button type="button" @click="activeTab = 'ayah'" class="px-4 py-2 bg-teal-700 text-white rounded-lg text-xs font-semibold hover:bg-teal-800">
                        Lanjut ke Data Ayah &rarr;
                    </button>
                </div>
            </div>

            <!-- 3. Data Ayah -->
            <div x-show="activeTab === 'ayah'" class="space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b pb-2">Informasi Orang Tua Kandung (Ayah)</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Ayah</label>
                        <input type="text" name="ayah[nama]" value="{{ old('ayah.nama') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nama lengkap ayah kandung">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK Ayah</label>
                        <input type="text" name="ayah[nik]" value="{{ old('ayah.nik') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="16 digit NIK ayah">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Lahir</label>
                        <input type="number" name="ayah[tahun_lahir]" value="{{ old('ayah.tahun_lahir') }}" min="1940" max="{{ date('Y') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Contoh: 1985">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenjang Pendidikan Terakhir</label>
                        <select name="ayah[jenjang_pendidikan]" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="">Pilih Pendidikan</option>
                            @foreach (['SD/Sederajat', 'SMP/Sederajat', 'SMA/SMK', 'D1/D2/D3', 'S1/D4', 'S2', 'S3', 'Tidak Sekolah'] as $pdd)
                                <option value="{{ $pdd }}" {{ old('ayah.jenjang_pendidikan') === $pdd ? 'selected' : '' }}>{{ $pdd }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pekerjaan</label>
                        <input type="text" name="ayah[pekerjaan]" value="{{ old('ayah.pekerjaan') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Contoh: Karyawan Swasta, Wiraswasta, PNS">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Penghasilan Bulanan</label>
                        <select name="ayah[penghasilan]" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="">Pilih Rentang Penghasilan</option>
                            <option value="Kurang dari Rp 1.000.000" {{ old('ayah.penghasilan') === 'Kurang dari Rp 1.000.000' ? 'selected' : '' }}>Kurang dari Rp 1.000.000</option>
                            <option value="Rp 1.000.000 - Rp 2.000.000" {{ old('ayah.penghasilan') === 'Rp 1.000.000 - Rp 2.000.000' ? 'selected' : '' }}>Rp 1.000.000 - Rp 2.000.000</option>
                            <option value="Rp 2.000.000 - Rp 5.000.000" {{ old('ayah.penghasilan') === 'Rp 2.000.000 - Rp 5.000.000' ? 'selected' : '' }}>Rp 2.000.000 - Rp 5.000.000</option>
                            <option value="Rp 5.000.000 - Rp 10.000.000" {{ old('ayah.penghasilan') === 'Rp 5.000.000 - Rp 10.000.000' ? 'selected' : '' }}>Rp 5.000.000 - Rp 10.000.000</option>
                            <option value="Lebih dari Rp 10.000.000" {{ old('ayah.penghasilan') === 'Lebih dari Rp 10.000.000' ? 'selected' : '' }}>Lebih dari Rp 10.000.000</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" @click="activeTab = 'alamat'" class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-50">
                        &larr; Kembali
                    </button>
                    <button type="button" @click="activeTab = 'ibu'" class="px-4 py-2 bg-teal-700 text-white rounded-lg text-xs font-semibold hover:bg-teal-800">
                        Lanjut ke Data Ibu &rarr;
                    </button>
                </div>
            </div>

            <!-- 4. Data Ibu -->
            <div x-show="activeTab === 'ibu'" class="space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b pb-2">Informasi Orang Tua Kandung (Ibu)</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Ibu</label>
                        <input type="text" name="ibu[nama]" value="{{ old('ibu.nama') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nama lengkap ibu kandung">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK Ibu</label>
                        <input type="text" name="ibu[nik]" value="{{ old('ibu.nik') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="16 digit NIK ibu">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Lahir</label>
                        <input type="number" name="ibu[tahun_lahir]" value="{{ old('ibu.tahun_lahir') }}" min="1940" max="{{ date('Y') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Contoh: 1988">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenjang Pendidikan Terakhir</label>
                        <select name="ibu[jenjang_pendidikan]" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="">Pilih Pendidikan</option>
                            @foreach (['SD/Sederajat', 'SMP/Sederajat', 'SMA/SMK', 'D1/D2/D3', 'S1/D4', 'S2', 'S3', 'Tidak Sekolah'] as $pdd)
                                <option value="{{ $pdd }}" {{ old('ibu.jenjang_pendidikan') === $pdd ? 'selected' : '' }}>{{ $pdd }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pekerjaan</label>
                        <input type="text" name="ibu[pekerjaan]" value="{{ old('ibu.pekerjaan') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Ibu Rumah Tangga / Karyawan / Guru">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Penghasilan Bulanan</label>
                        <select name="ibu[penghasilan]" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="">Pilih Rentang Penghasilan</option>
                            <option value="Tidak Berpenghasilan" {{ old('ibu.penghasilan') === 'Tidak Berpenghasilan' ? 'selected' : '' }}>Tidak Berpenghasilan</option>
                            <option value="Kurang dari Rp 1.000.000" {{ old('ibu.penghasilan') === 'Kurang dari Rp 1.000.000' ? 'selected' : '' }}>Kurang dari Rp 1.000.000</option>
                            <option value="Rp 1.000.000 - Rp 2.000.000" {{ old('ibu.penghasilan') === 'Rp 1.000.000 - Rp 2.000.000' ? 'selected' : '' }}>Rp 1.000.000 - Rp 2.000.000</option>
                            <option value="Rp 2.000.000 - Rp 5.000.000" {{ old('ibu.penghasilan') === 'Rp 2.000.000 - Rp 5.000.000' ? 'selected' : '' }}>Rp 2.000.000 - Rp 5.000.000</option>
                            <option value="Rp 5.000.000 - Rp 10.000.000" {{ old('ibu.penghasilan') === 'Rp 5.000.000 - Rp 10.000.000' ? 'selected' : '' }}>Rp 5.000.000 - Rp 10.000.000</option>
                            <option value="Lebih dari Rp 10.000.000" {{ old('ibu.penghasilan') === 'Lebih dari Rp 10.000.000' ? 'selected' : '' }}>Lebih dari Rp 10.000.000</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" @click="activeTab = 'ayah'" class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-50">
                        &larr; Kembali
                    </button>
                    <button type="button" @click="activeTab = 'wali'" class="px-4 py-2 bg-teal-700 text-white rounded-lg text-xs font-semibold hover:bg-teal-800">
                        Lanjut ke Data Wali &rarr;
                    </button>
                </div>
            </div>

            <!-- 5. Data Wali -->
            <div x-show="activeTab === 'wali'" class="space-y-4">
                <div class="border-b pb-2">
                    <h2 class="text-sm font-bold text-slate-900">Informasi Wali Siswa (Opsional)</h2>
                    <p class="text-xs text-slate-500">Hanya diisi jika peserta didik tidak tinggal bersama ayah/ibu kandung.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Wali</label>
                        <input type="text" name="wali[nama]" value="{{ old('wali.nama') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nama lengkap wali">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK Wali</label>
                        <input type="text" name="wali[nik]" value="{{ old('wali.nik') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="16 digit NIK wali">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Lahir</label>
                        <input type="number" name="wali[tahun_lahir]" value="{{ old('wali.tahun_lahir') }}" min="1940" max="{{ date('Y') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Contoh: 1975">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenjang Pendidikan Terakhir</label>
                        <select name="wali[jenjang_pendidikan]" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="">Pilih Pendidikan</option>
                            @foreach (['SD/Sederajat', 'SMP/Sederajat', 'SMA/SMK', 'D1/D2/D3', 'S1/D4', 'S2', 'S3', 'Tidak Sekolah'] as $pdd)
                                <option value="{{ $pdd }}" {{ old('wali.jenjang_pendidikan') === $pdd ? 'selected' : '' }}>{{ $pdd }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pekerjaan</label>
                        <input type="text" name="wali[pekerjaan]" value="{{ old('wali.pekerjaan') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Pekerjaan wali">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Penghasilan Bulanan</label>
                        <select name="wali[penghasilan]" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="">Pilih Rentang Penghasilan</option>
                            <option value="Kurang dari Rp 1.000.000" {{ old('wali.penghasilan') === 'Kurang dari Rp 1.000.000' ? 'selected' : '' }}>Kurang dari Rp 1.000.000</option>
                            <option value="Rp 1.000.000 - Rp 2.000.000" {{ old('wali.penghasilan') === 'Rp 1.000.000 - Rp 2.000.000' ? 'selected' : '' }}>Rp 1.000.000 - Rp 2.000.000</option>
                            <option value="Rp 2.000.000 - Rp 5.000.000" {{ old('wali.penghasilan') === 'Rp 2.000.000 - Rp 5.000.000' ? 'selected' : '' }}>Rp 2.000.000 - Rp 5.000.000</option>
                            <option value="Rp 5.000.000 - Rp 10.000.000" {{ old('wali.penghasilan') === 'Rp 5.000.000 - Rp 10.000.000' ? 'selected' : '' }}>Rp 5.000.000 - Rp 10.000.000</option>
                            <option value="Lebih dari Rp 10.000.000" {{ old('wali.penghasilan') === 'Lebih dari Rp 10.000.000' ? 'selected' : '' }}>Lebih dari Rp 10.000.000</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" @click="activeTab = 'ibu'" class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-50">
                        &larr; Kembali
                    </button>
                    <button type="button" @click="activeTab = 'rombel'" class="px-4 py-2 bg-teal-700 text-white rounded-lg text-xs font-semibold hover:bg-teal-800">
                        Lanjut ke Rombel & Bantuan &rarr;
                    </button>
                </div>
            </div>

            <!-- 6. Rombel & Bantuan -->
            <div x-show="activeTab === 'rombel'" class="space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b pb-2">Penempatan Rombel & Data Bantuan (Dapodik)</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Rombel Saat Ini</label>
                        <select name="kelas_id" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="">Belum Memiliki Kelas</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama }} (Tingkat {{ $kelas->tingkat }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Angkatan (Tahun Masuk) <span class="text-rose-500">*</span></label>
                        <input type="number" name="angkatan" value="{{ old('angkatan', $defaultAngkatan) }}" required min="2000" max="{{ date('Y') + 1 }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status Keaktifan <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="lulus" {{ old('status') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="pindah" {{ old('status') === 'pindah' ? 'selected' : '' }}>Pindah</option>
                            <option value="keluar" {{ old('status') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Registrasi Akta Lahir</label>
                        <input type="text" name="no_registrasi_akta_lahir" value="{{ old('no_registrasi_akta_lahir') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Sesuai akta kelahiran">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Kartu Keluarga Sejahtera (KKS)</label>
                        <input type="text" name="nomor_kks" value="{{ old('nomor_kks') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Jika memiliki KKS">
                    </div>
                </div>

                <!-- Kartu Perlindungan Sosial (KPS) -->
                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-lg space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="penerima_kps" value="1" {{ old('penerima_kps') ? 'checked' : '' }} class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        <span class="text-xs font-semibold text-slate-800">Penerima Kartu Perlindungan Sosial (KPS / PKH)</span>
                    </label>
                    <div>
                        <label class="block text-2xs text-slate-600 mb-1">Nomor KPS / KPS Number</label>
                        <input type="text" name="no_kps" value="{{ old('no_kps') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Diisi jika menerima KPS">
                    </div>
                </div>

                <!-- Kartu Indonesia Pintar (KIP) -->
                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-lg space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="penerima_kip" value="1" {{ old('penerima_kip') ? 'checked' : '' }} class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        <span class="text-xs font-semibold text-slate-800">Penerima Kartu Indonesia Pintar (KIP / PIP)</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-2xs text-slate-600 mb-1">Nomor KIP</label>
                            <input type="text" name="nomor_kip" value="{{ old('nomor_kip') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nomor KIP">
                        </div>
                        <div>
                            <label class="block text-2xs text-slate-600 mb-1">Nama Tertera di KIP</label>
                            <input type="text" name="nama_di_kip" value="{{ old('nama_di_kip') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nama sesuai fisik kartu KIP">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" @click="activeTab = 'wali'" class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-50">
                        &larr; Kembali
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-teal-700 text-white rounded-lg text-xs font-bold hover:bg-teal-800 shadow-xs transition">
                        Simpan Data Siswa
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
