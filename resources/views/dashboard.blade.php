<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Dashboard Siswa TK Harapan Bangsa</h1>
                <p class="text-xs text-slate-500 mt-0.5">Ringkasan status peserta didik, pembagian rombel, dan data kelulusan</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('siswa.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-teal-700 hover:bg-teal-800 text-white text-xs font-semibold rounded-lg shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Siswa Baru
                </a>
                <a href="{{ route('siswa.index') }}#importSection" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-lg shadow-xs transition">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Import Dapodik
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- 5 Ringkasan Metrik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Siswa Aktif -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500">Siswa Aktif</span>
                    <span class="p-2 rounded-lg bg-teal-50 text-teal-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-slate-900">{{ $totalAktif }}</span>
                    <span class="text-xs text-slate-500">anak</span>
                </div>
                <div class="mt-2 text-xs text-teal-700 font-medium">Terdaftar di rombel</div>
            </div>

            <!-- Laki-laki -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500">Laki-laki (Aktif)</span>
                    <span class="p-2 rounded-lg bg-sky-50 text-sky-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-slate-900">{{ $totalLakiLaki }}</span>
                    <span class="text-xs text-slate-500">anak</span>
                </div>
                <div class="mt-2 text-xs text-slate-500">
                    {{ $totalAktif > 0 ? round(($totalLakiLaki / $totalAktif) * 100) : 0 }}% dari total aktif
                </div>
            </div>

            <!-- Perempuan -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500">Perempuan (Aktif)</span>
                    <span class="p-2 rounded-lg bg-pink-50 text-pink-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-slate-900">{{ $totalPerempuan }}</span>
                    <span class="text-xs text-slate-500">anak</span>
                </div>
                <div class="mt-2 text-xs text-slate-500">
                    {{ $totalAktif > 0 ? round(($totalPerempuan / $totalAktif) * 100) : 0 }}% dari total aktif
                </div>
            </div>

            <!-- Total Lulus -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500">Siswa Lulus</span>
                    <span class="p-2 rounded-lg bg-indigo-50 text-indigo-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-slate-900">{{ $totalLulus }}</span>
                    <span class="text-xs text-slate-500">alumni</span>
                </div>
                <div class="mt-2 text-xs text-indigo-700 font-medium">
                    <a href="{{ route('siswa.lulus') }}" class="hover:underline">Lihat daftar lulus &rarr;</a>
                </div>
            </div>

            <!-- Total Kelas -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500">Rombongan Belajar</span>
                    <span class="p-2 rounded-lg bg-amber-50 text-amber-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </span>
                </div>
                <div class="mt-2 flex items-baseline gap-1">
                    <span class="text-2xl font-bold text-slate-900">{{ $totalKelas }}</span>
                    <span class="text-xs text-slate-500">kelas</span>
                </div>
                <div class="mt-2 text-xs text-amber-700 font-medium">
                    <a href="{{ route('kelas.index') }}" class="hover:underline">Kelola rombel &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Bagian Distribusi Rombel & Siswa Terbaru -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Tabel Sebaran Siswa Per Kelas (2 Kolom) -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Distribusi Siswa Per Rombel</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Jumlah siswa aktif di tiap rombongan belajar</p>
                    </div>
                    <a href="{{ route('kelas.index') }}" class="text-xs font-medium text-teal-700 hover:text-teal-900 hover:underline">
                        Kelola Kelas &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">Nama Kelas</th>
                                <th class="px-4 py-3">Tingkat</th>
                                <th class="px-4 py-3">Tahun Ajaran</th>
                                <th class="px-4 py-3">Wali Kelas</th>
                                <th class="px-4 py-3 text-center">L</th>
                                <th class="px-4 py-3 text-center">P</th>
                                <th class="px-4 py-3 text-center">Total</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($kelasSummary as $item)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        {{ $item['nama'] }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $item['tingkat'] === 'B' ? 'bg-amber-100 text-amber-800' : 'bg-teal-100 text-teal-800' }}">
                                            TK {{ $item['tingkat'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">{{ $item['tahun_ajaran'] }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $item['wali_kelas'] }}</td>
                                    <td class="px-4 py-3 text-center font-medium text-sky-700">{{ $item['total_l'] }}</td>
                                    <td class="px-4 py-3 text-center font-medium text-pink-700">{{ $item['total_p'] }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-slate-900">{{ $item['total_siswa'] }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('siswa.kelas', $item['id']) }}" class="inline-flex items-center px-2.5 py-1 rounded-md bg-teal-50 text-teal-700 hover:bg-teal-100 font-medium transition">
                                            Daftar Siswa
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-slate-500">
                                        Belum ada data rombel/kelas yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Siswa Baru Ditambahkan (1 Kolom) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden flex flex-col">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Siswa Baru Terdaftar</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Entri data siswa paling baru</p>
                    </div>
                    <a href="{{ route('siswa.index') }}" class="text-xs font-medium text-teal-700 hover:text-teal-900 hover:underline">
                        Semua Siswa &rarr;
                    </a>
                </div>

                <div class="divide-y divide-slate-100 flex-1">
                    @forelse ($siswaTerbaru as $siswa)
                        <div class="p-3.5 flex items-center justify-between hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center font-semibold text-xs {{ $siswa->jk === 'L' ? 'bg-sky-100 text-sky-800' : 'bg-pink-100 text-pink-800' }}">
                                    {{ $siswa->jk }}
                                </span>
                                <div>
                                    <a href="{{ route('siswa.show', $siswa) }}" class="font-semibold text-slate-900 text-xs hover:text-teal-700">
                                        {{ $siswa->nama }}
                                    </a>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        {{ $siswa->kelas?->nama ?? 'Tanpa Rombel' }} &bull; Angkatan {{ $siswa->angkatan }}
                                    </div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 text-2xs font-semibold rounded-full {{ $siswa->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst($siswa->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-slate-500">
                            Belum ada data siswa.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
