<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Data Rombongan Belajar (Kelas)</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola data kelas, tingkat usia (A/B), tahun ajaran, dan guru wali kelas</p>
            </div>
            <div>
                <button type="button" @click="$dispatch('open-modal', 'modal-tambah-kelas')" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-teal-700 hover:bg-teal-800 text-white text-xs font-semibold rounded-lg shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Rombel Baru
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Tabel Kelas -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3.5 w-12 text-center">No</th>
                            <th class="px-4 py-3.5">Nama Rombel</th>
                            <th class="px-4 py-3.5">Tingkat</th>
                            <th class="px-4 py-3.5">Tahun Ajaran</th>
                            <th class="px-4 py-3.5">Wali Kelas</th>
                            <th class="px-4 py-3.5 text-center">Jumlah Siswa</th>
                            <th class="px-4 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($kelasList as $index => $kelas)
                            <tr class="hover:bg-slate-50/80 transition" x-data="{ editOpen: false }">
                                <td class="px-4 py-3 text-center text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-900">
                                    {{ $kelas->nama }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $kelas->tingkat === 'B' ? 'bg-amber-100 text-amber-800' : 'bg-teal-100 text-teal-800' }}">
                                        TK {{ $kelas->tingkat ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $kelas->tahun_ajaran }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $kelas->wali_kelas ?? '-' }}</td>
                                <td class="px-4 py-3 text-center font-bold text-slate-900">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-800">
                                        {{ $kelas->siswas_count }} anak
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <a href="{{ route('siswa.kelas', $kelas) }}" class="px-2.5 py-1 rounded-md bg-teal-50 text-teal-700 hover:bg-teal-100 font-medium transition" title="Lihat Daftar Siswa">
                                            Daftar Siswa
                                        </a>

                                        <button type="button" @click="$dispatch('open-modal', 'modal-edit-kelas-{{ $kelas->id }}')" class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium transition">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('kelas.destroy', $kelas) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmAction({
                                                    form: this.closest('form'),
                                                    title: 'Hapus Rombel?',
                                                    message: 'Apakah Anda yakin ingin menghapus rombel <strong>{{ addslashes($kelas->nama) }}</strong>?<br><span class=\'text-xs text-slate-500 mt-1 block\'>Data siswa di dalam rombel ini akan tetap tersimpan.</span>',
                                                    confirmText: 'Ya, Hapus Rombel',
                                                    color: 'rose'
                                                })"
                                                class="px-2.5 py-1 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 font-medium transition cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal Edit Kelas -->
                                    <x-modal name="modal-edit-kelas-{{ $kelas->id }}" focusable>
                                        <form method="POST" action="{{ route('kelas.update', $kelas) }}" class="p-6 text-left">
                                            @csrf
                                            @method('PUT')

                                            <h2 class="text-base font-bold text-slate-900 mb-1">
                                                Edit Rombel: {{ $kelas->nama }}
                                            </h2>
                                            <p class="text-xs text-slate-500 mb-4">
                                                Perbarui rincian rombongan belajar.
                                            </p>

                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Rombel / Kelas <span class="text-rose-500">*</span></label>
                                                    <input type="text" name="nama" value="{{ old('nama', $kelas->nama) }}" required class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Contoh: TK A1 (Bintang)">
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tingkat</label>
                                                        <select name="tingkat" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                                                            <option value="A" {{ old('tingkat', $kelas->tingkat) === 'A' ? 'selected' : '' }}>Tingkat A (4-5 Tahun)</option>
                                                            <option value="B" {{ old('tingkat', $kelas->tingkat) === 'B' ? 'selected' : '' }}>Tingkat B (5-6 Tahun)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Ajaran <span class="text-rose-500">*</span></label>
                                                        <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}" required class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="2025/2026">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Wali Kelas / Guru Pengampu</label>
                                                    <input type="text" name="wali_kelas" value="{{ old('wali_kelas', $kelas->wali_kelas) }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nama Guru, S.Pd">
                                                </div>
                                            </div>

                                            <div class="mt-6 flex justify-end gap-2">
                                                <button type="button" @click="$dispatch('close')" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                    Batal
                                                </button>
                                                <button type="submit" class="px-4 py-2 bg-teal-700 hover:bg-teal-800 rounded-lg text-xs font-semibold text-white shadow-xs">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada rombel yang terdaftar. Klik tombol <strong>Tambah Rombel Baru</strong> di atas untuk membuat kelas pertama.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kelas -->
    <x-modal name="modal-tambah-kelas" focusable>
        <form method="POST" action="{{ route('kelas.store') }}" class="p-6">
            @csrf

            <h2 class="text-base font-bold text-slate-900 mb-1">
                Tambah Rombongan Belajar (Kelas) Baru
            </h2>
            <p class="text-xs text-slate-500 mb-4">
                Masukkan informasi kelas dan guru wali kelas.
            </p>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Rombel / Kelas <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Contoh: TK A1 (Bintang)">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tingkat</label>
                        <select name="tingkat" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="A">Tingkat A (4-5 Tahun)</option>
                            <option value="B">Tingkat B (5-6 Tahun)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Ajaran <span class="text-rose-500">*</span></label>
                        <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', date('Y') . '/' . (date('Y') + 1)) }}" required class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="2025/2026">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Wali Kelas / Guru Pengampu</label>
                    <input type="text" name="wali_kelas" value="{{ old('wali_kelas') }}" class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="Nama Guru, S.Pd">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" @click="$dispatch('close')" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-teal-700 hover:bg-teal-800 rounded-lg text-xs font-semibold text-white shadow-xs">
                    Simpan Rombel
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
