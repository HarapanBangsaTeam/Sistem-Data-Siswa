<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                    <a href="{{ route('siswa.index') }}" class="hover:text-teal-700">Data Siswa</a>
                    <span>/</span>
                    <a href="{{ route('kelas.index') }}" class="hover:text-teal-700">Rombel</a>
                    <span>/</span>
                    <span class="text-slate-700 font-medium">{{ $kelas->nama }}</span>
                </div>
                <h1 class="text-xl font-bold text-slate-900">Daftar Siswa Rombel: {{ $kelas->nama }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <!-- Dropdown Pilih Kelas Lain -->
                <select onchange="if(this.value) window.location.href=this.value" class="text-xs rounded-lg border-slate-300 py-1.5 focus:border-teal-600 focus:ring-teal-600">
                    @foreach ($kelasList as $item)
                        <option value="{{ route('siswa.kelas', $item) }}" {{ $item->id === $kelas->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>

                <a href="{{ route('siswa.export', ['kelas_id' => $kelas->id]) }}" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                    Export Excel Kelas
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ selectedIds: [], selectAll: false, toggleAll() { if (this.selectAll) { this.selectedIds = [{{ $siswas->pluck('id')->join(',') }}]; } else { this.selectedIds = []; } } }">
        <!-- Banner Info Kelas -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <span class="w-12 h-12 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-base">
                    TK {{ $kelas->tingkat ?? '-' }}
                </span>
                <div>
                    <h2 class="text-base font-bold text-slate-900">{{ $kelas->nama }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Tahun Ajaran: <strong>{{ $kelas->tahun_ajaran }}</strong> &bull; Wali Kelas: <strong>{{ $kelas->wali_kelas ?? 'Belum ditentukan' }}</strong>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 text-xs">
                <div class="px-3 py-2 bg-slate-50 rounded-lg border border-slate-200 text-center">
                    <div class="text-slate-500 text-2xs uppercase">Laki-Laki</div>
                    <div class="text-base font-bold text-sky-700">{{ $totalL }}</div>
                </div>
                <div class="px-3 py-2 bg-slate-50 rounded-lg border border-slate-200 text-center">
                    <div class="text-slate-500 text-2xs uppercase">Perempuan</div>
                    <div class="text-base font-bold text-pink-700">{{ $totalP }}</div>
                </div>
                <div class="px-3 py-2 bg-teal-50 rounded-lg border border-teal-200 text-center">
                    <div class="text-teal-700 text-2xs uppercase">Total Siswa</div>
                    <div class="text-base font-bold text-teal-900">{{ $siswas->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Form Kelulusan Massal -->
        <form method="POST" action="{{ route('siswa.luluskan') }}">
            @csrf

            <!-- Action Bar saat ada siswa terpilih -->
            <div x-show="selectedIds.length > 0" class="mb-4 p-3 bg-amber-50 border border-amber-300 rounded-xl flex flex-wrap items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2 text-xs font-semibold text-amber-900">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span x-text="selectedIds.length"></span> siswa dipilih untuk diluluskan
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs font-medium text-amber-900">Tahun Lulus:</label>
                    <input type="number" name="tahun_lulus" value="{{ date('Y') }}" min="2000" max="{{ date('Y') + 1 }}" required class="w-24 text-xs rounded-lg border-amber-300 py-1.5 focus:border-amber-600 focus:ring-amber-600">
                    <button type="button"
                        onclick="confirmAction({
                            form: this.closest('form'),
                            title: 'Luluskan Siswa Terpilih?',
                            message: 'Apakah Anda yakin ingin meluluskan siswa yang dipilih?<br><span class=\'text-xs text-slate-500 mt-1 block\'>Siswa yang lulus akan dipindahkan ke daftar kelulusan (alumni) dan rombelnya dikosongkan.</span>',
                            confirmText: 'Ya, Luluskan Siswa',
                            color: 'amber'
                        })"
                        class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold transition shadow-xs cursor-pointer">
                        Luluskan Siswa Terpilih
                    </button>
                </div>
            </div>

            <!-- Tabel Siswa Per Kelas -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3.5 w-10 text-center">
                                    <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                </th>
                                <th class="px-4 py-3.5 w-12 text-center">No</th>
                                <th class="px-4 py-3.5">Nama Siswa</th>
                                <th class="px-4 py-3.5">NIPD</th>
                                <th class="px-4 py-3.5 text-center">JK</th>
                                <th class="px-4 py-3.5">NISN</th>
                                <th class="px-4 py-3.5">Tanggal Lahir</th>
                                <th class="px-4 py-3.5">Nama Orang Tua</th>
                                <th class="px-4 py-3.5 text-center">Angkatan</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($siswas as $index => $siswa)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" x-model="selectedIds" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                    </td>
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
                                    <td class="px-4 py-3 text-slate-600">
                                        {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">
                                        {{ $siswa->ayah?->nama ?? ($siswa->ibu?->nama ?? '-') }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-medium text-slate-700">
                                        {{ $siswa->angkatan }}
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
                                    <td colspan="10" class="px-4 py-12 text-center text-slate-500">
                                        Belum ada siswa aktif yang ditempatkan di rombel {{ $kelas->nama }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
