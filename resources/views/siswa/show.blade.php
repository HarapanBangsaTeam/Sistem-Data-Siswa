<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                    <a href="{{ route('siswa.index') }}" class="hover:text-teal-700">Data Siswa</a>
                    <span>/</span>
                    <span class="text-slate-700 font-medium">Profil Detail</span>
                </div>
                <h1 class="text-xl font-bold text-slate-900">{{ $siswa->nama }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('siswa.edit', $siswa) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-teal-700 hover:bg-teal-800 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Profil
                </a>
                <form method="POST" action="{{ route('siswa.destroy', $siswa) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        onclick="confirmAction({
                            form: this.closest('form'),
                            title: 'Pindahkan ke Sampah?',
                            message: 'Apakah Anda yakin ingin memindahkan data <strong>{{ addslashes($siswa->nama) }}</strong> ke sampah?<br><span class=\'text-xs text-slate-500 mt-1 block\'>Data ini dapat dipulihkan sewaktu-waktu dari menu sampah.</span>',
                            confirmText: 'Ya, Pindahkan',
                            color: 'rose'
                        })"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-semibold transition cursor-pointer">
                        Hapus
                    </button>
                </form>
                <a href="{{ route('siswa.index') }}" class="px-3 py-1.5 border border-slate-300 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Header Banner Siswa -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-xl font-bold shadow-xs {{ $siswa->jk === 'L' ? 'bg-sky-100 text-sky-800' : 'bg-pink-100 text-pink-800' }}">
                    {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-bold text-slate-900">{{ $siswa->nama }}</h2>
                        <span class="px-2 py-0.5 rounded-full text-2xs font-semibold {{ $siswa->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : ($siswa->status === 'lulus' ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800') }}">
                            Status: {{ ucfirst($siswa->status) }}
                        </span>
                    </div>
                    <div class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                        <span>NISN: <strong class="text-slate-700 font-mono">{{ $siswa->nisn ?? '-' }}</strong></span>
                        <span>&bull;</span>
                        <span>NIPD: <strong class="text-slate-700 font-mono">{{ $siswa->nipd ?? '-' }}</strong></span>
                        <span>&bull;</span>
                        <span>Angkatan: <strong class="text-slate-700">{{ $siswa->angkatan }}</strong></span>
                        <span>&bull;</span>
                        <span>Rombel: <strong class="text-teal-700">{{ $siswa->kelas?->nama ?? 'Belum ada rombel' }}</strong></span>
                    </div>
                </div>
            </div>

            @if ($siswa->status === 'lulus')
                <div class="p-3 bg-indigo-50 border border-indigo-200 rounded-lg text-xs text-indigo-900">
                    <div>Lulus Tahun: <strong>{{ $siswa->tahun_lulus ?? '-' }}</strong></div>
                    <div>No Seri Ijazah: <strong>{{ $siswa->no_seri_ijazah ?? '-' }}</strong></div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Data Pribadi -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                    Data Pribadi Siswa
                </h3>

                <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-xs">
                    <div>
                        <dt class="text-slate-500 font-medium">Jenis Kelamin</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->jk === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">NIK</dt>
                        <dd class="text-slate-900 font-semibold font-mono mt-0.5">{{ $siswa->nik ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">Tempat, Tanggal Lahir</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">
                            {{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d F Y') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">Agama</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->agama ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">No Registrasi Akta Lahir</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->no_registrasi_akta_lahir ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">Jenis Tinggal</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->jenis_tinggal ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">Alat Transportasi</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->alat_transportasi ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">Program Bantuan</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">
                            @if ($siswa->penerima_kps)
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded text-3xs font-bold">KPS: {{ $siswa->no_kps }}</span>
                            @endif
                            @if ($siswa->penerima_kip)
                                <span class="px-2 py-0.5 bg-teal-100 text-teal-800 rounded text-3xs font-bold">KIP: {{ $siswa->nomor_kip }}</span>
                            @endif
                            @if (!$siswa->penerima_kps && !$siswa->penerima_kip)
                                <span class="text-slate-400">Tidak ada</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Alamat & Kontak -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                    Alamat & Kontak
                </h3>

                <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-xs">
                    <div class="col-span-2">
                        <dt class="text-slate-500 font-medium">Alamat Tempat Tinggal</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->alamat ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">RT / RW</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->rt ?? '-' }} / {{ $siswa->rw ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">Dusun</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->dusun ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">Kelurahan / Desa</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->kelurahan ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">Kecamatan</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->kecamatan ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">Kode Pos</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->kode_pos ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500 font-medium">Nomor HP / WhatsApp</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->hp ?? '-' }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-slate-500 font-medium">Email</dt>
                        <dd class="text-slate-900 font-semibold mt-0.5">{{ $siswa->email ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Data Orang Tua & Wali (3 Kolom) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                Data Orang Tua & Wali
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Ayah -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                        <h4 class="text-xs font-bold text-slate-900">Data Ayah Kandung</h4>
                        <span class="px-2 py-0.5 rounded text-3xs font-semibold bg-sky-100 text-sky-800">Ayah</span>
                    </div>
                    @if ($siswa->ayah)
                        <dl class="space-y-2 text-xs">
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Nama</dt>
                                <dd class="text-slate-900 font-semibold">{{ $siswa->ayah->nama ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">NIK</dt>
                                <dd class="text-slate-900 font-mono text-2xs">{{ $siswa->ayah->nik ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Tahun Lahir</dt>
                                <dd class="text-slate-900">{{ $siswa->ayah->tahun_lahir ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Pendidikan</dt>
                                <dd class="text-slate-900">{{ $siswa->ayah->jenjang_pendidikan ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Pekerjaan</dt>
                                <dd class="text-slate-900">{{ $siswa->ayah->pekerjaan ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Penghasilan</dt>
                                <dd class="text-slate-900">{{ $siswa->ayah->penghasilan ?? '-' }}</dd>
                            </div>
                        </dl>
                    @else
                        <div class="text-xs text-slate-400 italic py-4 text-center">Data ayah belum diisi.</div>
                    @endif
                </div>

                <!-- Ibu -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                        <h4 class="text-xs font-bold text-slate-900">Data Ibu Kandung</h4>
                        <span class="px-2 py-0.5 rounded text-3xs font-semibold bg-pink-100 text-pink-800">Ibu</span>
                    </div>
                    @if ($siswa->ibu)
                        <dl class="space-y-2 text-xs">
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Nama</dt>
                                <dd class="text-slate-900 font-semibold">{{ $siswa->ibu->nama ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">NIK</dt>
                                <dd class="text-slate-900 font-mono text-2xs">{{ $siswa->ibu->nik ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Tahun Lahir</dt>
                                <dd class="text-slate-900">{{ $siswa->ibu->tahun_lahir ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Pendidikan</dt>
                                <dd class="text-slate-900">{{ $siswa->ibu->jenjang_pendidikan ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Pekerjaan</dt>
                                <dd class="text-slate-900">{{ $siswa->ibu->pekerjaan ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Penghasilan</dt>
                                <dd class="text-slate-900">{{ $siswa->ibu->penghasilan ?? '-' }}</dd>
                            </div>
                        </dl>
                    @else
                        <div class="text-xs text-slate-400 italic py-4 text-center">Data ibu belum diisi.</div>
                    @endif
                </div>

                <!-- Wali -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                        <h4 class="text-xs font-bold text-slate-900">Data Wali</h4>
                        <span class="px-2 py-0.5 rounded text-3xs font-semibold bg-slate-200 text-slate-800">Wali</span>
                    </div>
                    @if ($siswa->wali && $siswa->wali->nama)
                        <dl class="space-y-2 text-xs">
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Nama</dt>
                                <dd class="text-slate-900 font-semibold">{{ $siswa->wali->nama }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">NIK</dt>
                                <dd class="text-slate-900 font-mono text-2xs">{{ $siswa->wali->nik ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Tahun Lahir</dt>
                                <dd class="text-slate-900">{{ $siswa->wali->tahun_lahir ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Pendidikan</dt>
                                <dd class="text-slate-900">{{ $siswa->wali->jenjang_pendidikan ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Pekerjaan</dt>
                                <dd class="text-slate-900">{{ $siswa->wali->pekerjaan ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500 font-medium text-2xs">Penghasilan</dt>
                                <dd class="text-slate-900">{{ $siswa->wali->penghasilan ?? '-' }}</dd>
                            </div>
                        </dl>
                    @else
                        <div class="text-xs text-slate-400 italic py-4 text-center">Tidak ada data wali (siswa tinggal bersama orang tua).</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
