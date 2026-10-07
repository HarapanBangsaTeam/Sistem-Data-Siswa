<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                    <a href="{{ route('siswa.index') }}" class="hover:text-teal-700">Data Siswa</a>
                    <span>/</span>
                    <span class="text-slate-700 font-medium">Angkatan {{ $angkatan }}</span>
                </div>
                <h1 class="text-xl font-bold text-slate-900">Daftar Siswa Angkatan {{ $angkatan }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <!-- Dropdown Pilih Angkatan Lain -->
                <select onchange="if(this.value) window.location.href=this.value" class="text-xs rounded-lg border-slate-300 py-1.5 focus:border-teal-600 focus:ring-teal-600">
                    @foreach ($angkatanList as $item)
                        <option value="{{ route('siswa.angkatan', $item) }}" {{ (int)$item === (int)$angkatan ? 'selected' : '' }}>
                            Angkatan {{ $item }}
                        </option>
                    @endforeach
                </select>

                <a href="{{ route('siswa.export', ['angkatan' => $angkatan]) }}" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                    Export Excel Angkatan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- 4 Kotak Ringkasan Status Angkatan -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-2xs font-semibold text-slate-500 uppercase tracking-wider">Total Terdaftar</span>
                <div class="text-xl font-bold text-slate-900 mt-1">{{ $summary['total'] }} <span class="text-xs font-normal text-slate-500">anak</span></div>
            </div>
            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-2xs font-semibold text-emerald-600 uppercase tracking-wider">Siswa Aktif</span>
                <div class="text-xl font-bold text-emerald-700 mt-1">{{ $summary['aktif'] }} <span class="text-xs font-normal text-slate-500">anak</span></div>
            </div>
            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-2xs font-semibold text-indigo-600 uppercase tracking-wider">Sudah Lulus</span>
                <div class="text-xl font-bold text-indigo-700 mt-1">{{ $summary['lulus'] }} <span class="text-xs font-normal text-slate-500">alumni</span></div>
            </div>
            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs">
                <span class="text-2xs font-semibold text-amber-600 uppercase tracking-wider">Pindah Sekolah</span>
                <div class="text-xl font-bold text-amber-700 mt-1">{{ $summary['pindah'] }} <span class="text-xs font-normal text-slate-500">anak</span></div>
            </div>
            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs col-span-2 sm:col-span-1">
                <span class="text-2xs font-semibold text-rose-600 uppercase tracking-wider">Keluar</span>
                <div class="text-xl font-bold text-rose-700 mt-1">{{ $summary['keluar'] }} <span class="text-xs font-normal text-slate-500">anak</span></div>
            </div>
        </div>

        <!-- Tabel Siswa Angkatan -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3.5 w-12 text-center">No</th>
                            <th class="px-4 py-3.5">Nama Siswa</th>
                            <th class="px-4 py-3.5">NIPD</th>
                            <th class="px-4 py-3.5 text-center">JK</th>
                            <th class="px-4 py-3.5">NISN</th>
                            <th class="px-4 py-3.5">Rombel Terakhir</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                            <th class="px-4 py-3.5 text-center">Tahun Lulus</th>
                            <th class="px-4 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($siswas as $index => $siswa)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3 text-center text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-900">
                                    <a href="{{ route('siswa.show', $siswa) }}" class="hover:text-teal-700">
                                        {{ $siswa->nama }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-slate-600 font-mono text-2xs">
                                    {{ $siswa->nipd ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full font-bold text-2xs {{ $siswa->jk === 'L' ? 'bg-sky-100 text-sky-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ $siswa->jk }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 font-mono text-2xs">
                                    {{ $siswa->nisn ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $siswa->kelas?->nama ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($siswa->status === 'aktif')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-2xs font-semibold bg-emerald-100 text-emerald-800">Aktif</span>
                                    @elseif ($siswa->status === 'lulus')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-2xs font-semibold bg-indigo-100 text-indigo-800">Lulus</span>
                                    @elseif ($siswa->status === 'pindah')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-2xs font-semibold bg-amber-100 text-amber-800">Pindah</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-2xs font-semibold bg-rose-100 text-rose-800">Keluar</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center font-medium text-slate-700">
                                    {{ $siswa->tahun_lulus ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <a href="{{ route('siswa.show', $siswa) }}" class="px-2 py-1 rounded bg-teal-50 text-teal-700 hover:bg-teal-100 font-medium transition">
                                            Detail
                                        </a>
                                        <a href="{{ route('siswa.edit', $siswa) }}" class="px-2 py-1 rounded bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium transition">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada data siswa untuk angkatan {{ $angkatan }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
