<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                    <a href="{{ route('siswa.index') }}" class="hover:text-teal-700">Data Siswa</a>
                    <span>/</span>
                    <span class="text-slate-700 font-medium">Data Kelulusan</span>
                </div>
                <h1 class="text-xl font-bold text-slate-900">Daftar Siswa Lulus (Alumni)</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('siswa.export', ['status' => 'lulus', 'tahun_lulus' => request('tahun_lulus')]) }}" class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                    Export Excel Alumni
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        <!-- Filter Tahun Kelulusan -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-3">
            <form method="GET" action="{{ route('siswa.lulus') }}" class="flex items-center gap-2">
                <label class="text-xs font-semibold text-slate-700">Tahun Lulus:</label>
                <select name="tahun_lulus" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-300 py-1.5 focus:border-teal-600 focus:ring-teal-600">
                    <option value="">Semua Tahun Kelulusan</option>
                    @foreach ($tahunLulusList as $thn)
                        <option value="{{ $thn }}" {{ request('tahun_lulus') == $thn ? 'selected' : '' }}>
                            Tahun {{ $thn }}
                        </option>
                    @endforeach
                </select>
            </form>

            <div class="text-xs text-slate-500">
                Total Alumni: <strong class="text-slate-900">{{ $siswas->total() }}</strong> siswa
            </div>
        </div>

        <!-- Tabel Siswa Lulus -->
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
                            <th class="px-4 py-3.5 text-center">Angkatan</th>
                            <th class="px-4 py-3.5 text-center">Tahun Lulus</th>
                            <th class="px-4 py-3.5">No Seri Ijazah</th>
                            <th class="px-4 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($siswas as $index => $siswa)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3 text-center text-slate-400">{{ $siswas->firstItem() + $index }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-900">
                                    <a href="{{ route('siswa.show', $siswa) }}" class="hover:text-teal-700">
                                        {{ $siswa->nama }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-slate-600 font-mono text-2xs">{{ $siswa->nipd ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full font-bold text-2xs {{ $siswa->jk === 'L' ? 'bg-sky-100 text-sky-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ $siswa->jk }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 font-mono text-2xs">{{ $siswa->nisn ?? '-' }}</td>
                                <td class="px-4 py-3 text-center text-slate-600">{{ $siswa->angkatan }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-2xs font-semibold bg-indigo-100 text-indigo-800">
                                        {{ $siswa->tahun_lulus ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 font-mono text-2xs">{{ $siswa->no_seri_ijazah ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <a href="{{ route('siswa.show', $siswa) }}" class="px-2 py-1 rounded bg-teal-50 text-teal-700 hover:bg-teal-100 font-medium transition">
                                            Detail
                                        </a>

                                        <button type="button" @click="$dispatch('open-modal', 'modal-batal-lulus-{{ $siswa->id }}')" class="px-2 py-1 rounded bg-amber-50 text-amber-700 hover:bg-amber-100 font-medium transition" title="Batalkan Kelulusan">
                                            Batalkan Kelulusan
                                        </button>
                                    </div>

                                    <!-- Modal Batalkan Kelulusan -->
                                    <x-modal name="modal-batal-lulus-{{ $siswa->id }}" focusable>
                                        <form method="POST" action="{{ route('siswa.batalkan-lulus') }}" class="p-6 text-left">
                                            @csrf
                                            <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">

                                            <h2 class="text-base font-bold text-slate-900 mb-1">
                                                Batalkan Kelulusan: {{ $siswa->nama }}
                                            </h2>
                                            <p class="text-xs text-slate-500 mb-4">
                                                Siswa akan dikembalikan ke status <strong>Aktif</strong>. Harap pilih rombel / kelas untuk penempatan siswa.
                                            </p>

                                            <div class="mb-4">
                                                <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Rombel / Kelas <span class="text-rose-500">*</span></label>
                                                <select name="kelas_id" required class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                                                    <option value="">Pilih Rombel Tujuan</option>
                                                    @foreach ($kelasList as $kelas)
                                                        <option value="{{ $kelas->id }}">{{ $kelas->nama }} (Tingkat {{ $kelas->tingkat }})</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="$dispatch('close')" class="px-4 py-2 border border-slate-300 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                    Batal
                                                </button>
                                                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 rounded-lg text-xs font-semibold text-white shadow-xs">
                                                    Konfirmasi Pembatalan
                                                </button>
                                            </div>
                                        </form>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada data siswa lulus{{ request('tahun_lulus') ? ' untuk tahun ' . request('tahun_lulus') : '' }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($siswas->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                    {{ $siswas->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
