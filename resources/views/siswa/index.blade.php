<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Buku Induk Data Siswa</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola data seluruh peserta didik sesuai standar Dapodik</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('siswa.export', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-lg shadow-xs transition" title="Export Excel sesuai filter">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export Excel
                </a>

                <button type="button" @click="$dispatch('open-modal', 'modal-import-dapodik')" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-lg shadow-xs transition">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Import Dapodik
                </button>

                <a href="{{ route('siswa.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-teal-700 hover:bg-teal-800 text-white text-xs font-semibold rounded-lg shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Siswa
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        <!-- Filter & Search Bar -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <form method="GET" action="{{ route('siswa.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                <!-- Search Query -->
                <div class="md:col-span-2">
                    <label class="block text-2xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cari Siswa</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama, NISN, NIPD, atau NIK..." class="w-full text-xs rounded-lg border-slate-300 pl-8 pr-3 py-2 focus:border-teal-600 focus:ring-teal-600">
                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Filter Kelas -->
                <div>
                    <label class="block text-2xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Rombel / Kelas</label>
                    <select name="kelas_id" class="w-full text-xs rounded-lg border-slate-300 py-2 focus:border-teal-600 focus:ring-teal-600">
                        <option value="">Semua Rombel</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Angkatan -->
                <div>
                    <label class="block text-2xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tahun Angkatan</label>
                    <select name="angkatan" class="w-full text-xs rounded-lg border-slate-300 py-2 focus:border-teal-600 focus:ring-teal-600">
                        <option value="">Semua Angkatan</option>
                        @foreach ($angkatanList as $thn)
                            <option value="{{ $thn }}" {{ request('angkatan') == $thn ? 'selected' : '' }}>
                                Angkatan {{ $thn }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status & Tombol Submit -->
                <div>
                    <label class="block text-2xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status Siswa</label>
                    <div class="flex gap-2">
                        <select name="status" class="w-full text-xs rounded-lg border-slate-300 py-2 focus:border-teal-600 focus:ring-teal-600">
                            <option value="aktif" {{ request('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="lulus" {{ request('status') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="pindah" {{ request('status') === 'pindah' ? 'selected' : '' }}>Pindah</option>
                            <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                            <option value="semua" {{ request('status') === 'semua' ? 'selected' : '' }}>Semua Status</option>
                        </select>
                        <button type="submit" class="px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold transition" title="Terapkan Filter">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Siswa dengan Fitur Seleksi Massal (WhatsApp-Style Bulk Select) -->
        <div x-data="{
            selectedIds: [],
            selectAll: false,
            currentPageIds: {{ json_encode($siswas->pluck('id')->values()) }},
            toggleSelectAll() {
                if (this.selectAll) {
                    this.selectedIds = [...new Set([...this.selectedIds, ...this.currentPageIds])];
                } else {
                    this.selectedIds = this.selectedIds.filter(id => !this.currentPageIds.includes(id));
                }
            },
            updateSelectAllState() {
                this.selectAll = this.currentPageIds.length > 0 && this.currentPageIds.every(id => this.selectedIds.includes(id));
            },
            downloadSelected() {
                if (this.selectedIds.length === 0) return;
                document.getElementById('export-ids-input').value = this.selectedIds.join(',');
                document.getElementById('export-selected-form').submit();
            },
            deleteSelected() {
                if (this.selectedIds.length === 0) return;
                confirmAction({
                    form: document.getElementById('bulk-delete-form'),
                    title: 'Pindahkan Siswa Terpilih ke Sampah?',
                    message: 'Apakah Anda yakin ingin memindahkan <strong>' + this.selectedIds.length + ' data siswa</strong> yang dipilih ke sampah?<br><span class=\'text-xs text-slate-500 mt-1 block\'>Data ini dapat dipulihkan sewaktu-waktu dari tempat sampah.</span>',
                    confirmText: 'Ya, Pindahkan Semua',
                    color: 'rose'
                });
            },
            clearSelection() {
                this.selectedIds = [];
                this.selectAll = false;
            }
        }">
            <!-- Action Bar Langsung di Atas Tabel (Tampil seketika saat ada siswa yang dicentang) -->
            <div x-show="selectedIds.length > 0"
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2 scale-98"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 -translate-y-2 scale-98"
                 class="mb-3 p-3.5 bg-teal-900 text-white rounded-xl shadow-md border border-teal-800 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-teal-800 text-teal-200 font-bold text-xs flex items-center justify-center border border-teal-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <div>
                        <span class="text-sm font-bold text-white"><span x-text="selectedIds.length"></span> siswa dipilih</span>
                        <span class="hidden sm:inline text-xs text-teal-200 ml-1.5">— Silakan pilih aksi berikut:</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Tombol Download Excel Terpilih -->
                    <button type="button"
                            @click="downloadSelected()"
                            class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Download Excel Terpilih</span>
                    </button>

                    <!-- Tombol Hapus Massal -->
                    <button type="button"
                            @click="deleteSelected()"
                            class="px-3.5 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer"
                            title="Pindahkan siswa terpilih ke sampah">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Hapus</span>
                    </button>

                    <!-- Tombol Batal -->
                    <button type="button"
                            @click="clearSelection()"
                            class="px-3 py-2 bg-teal-800 hover:bg-teal-700 text-teal-200 hover:text-white rounded-lg text-xs font-medium transition cursor-pointer">
                        Batal
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="px-3 py-3.5 w-10 text-center">
                                    <input type="checkbox"
                                           x-model="selectAll"
                                           @change="toggleSelectAll()"
                                           class="w-4 h-4 rounded border-slate-300 text-teal-700 focus:ring-teal-600 cursor-pointer transition"
                                           title="Pilih Semua di Halaman Ini">
                                </th>
                                <th class="px-3 py-3.5 w-12 text-center">No</th>
                                <th class="px-4 py-3.5">Nama Siswa</th>
                                <th class="px-4 py-3.5">NIPD</th>
                                <th class="px-4 py-3.5 text-center">JK</th>
                                <th class="px-4 py-3.5">NISN</th>
                                <th class="px-4 py-3.5">Tanggal Lahir</th>
                                <th class="px-4 py-3.5">Rombel</th>
                                <th class="px-4 py-3.5 text-center">Angkatan</th>
                                <th class="px-4 py-3.5 text-center">Status</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($siswas as $index => $siswa)
                                <tr class="transition" :class="selectedIds.includes({{ $siswa->id }}) ? 'bg-teal-50/70' : 'hover:bg-slate-50/80'">
                                    <td class="px-3 py-3 text-center">
                                        <input type="checkbox"
                                               :value="{{ $siswa->id }}"
                                               x-model="selectedIds"
                                               @change="updateSelectAllState()"
                                               class="w-4 h-4 rounded border-slate-300 text-teal-700 focus:ring-teal-600 cursor-pointer transition">
                                    </td>
                                    <td class="px-3 py-3 text-center text-slate-400">
                                        {{ $siswas->firstItem() + $index }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('siswa.show', $siswa) }}" class="font-semibold text-slate-900 hover:text-teal-700">
                                            {{ $siswa->nama }}
                                        </a>
                                        @if ($siswa->penerima_kip || $siswa->penerima_kps)
                                            <div class="flex gap-1 mt-0.5">
                                                @if ($siswa->penerima_kip)
                                                    <span class="inline-block px-1.5 py-0.2 bg-teal-50 text-teal-700 text-3xs font-semibold rounded">KIP</span>
                                                @endif
                                                @if ($siswa->penerima_kps)
                                                    <span class="inline-block px-1.5 py-0.2 bg-amber-50 text-amber-700 text-3xs font-semibold rounded">KPS</span>
                                                @endif
                                            </div>
                                        @endif
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
                                        @if ($siswa->tempat_lahir)
                                            <div class="text-2xs text-slate-400">{{ $siswa->tempat_lahir }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($siswa->kelas)
                                            <a href="{{ route('siswa.kelas', $siswa->kelas) }}" class="font-medium text-teal-700 hover:underline">
                                                {{ $siswa->kelas->nama }}
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic">Tanpa Rombel</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center font-medium text-slate-700">
                                        <a href="{{ route('siswa.angkatan', $siswa->angkatan) }}" class="hover:text-teal-700 hover:underline">
                                            {{ $siswa->angkatan }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($siswa->status === 'aktif')
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-2xs font-semibold bg-emerald-100 text-emerald-800">
                                                Aktif
                                            </span>
                                        @elseif ($siswa->status === 'lulus')
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-2xs font-semibold bg-indigo-100 text-indigo-800">
                                                Lulus {{ $siswa->tahun_lulus ? "({$siswa->tahun_lulus})" : '' }}
                                            </span>
                                        @elseif ($siswa->status === 'pindah')
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-2xs font-semibold bg-amber-100 text-amber-800">
                                                Pindah
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-2xs font-semibold bg-rose-100 text-rose-800">
                                                Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            <a href="{{ route('siswa.show', $siswa) }}" class="px-2 py-1 rounded bg-teal-50 text-teal-700 hover:bg-teal-100 font-medium transition" title="Lihat Profil">
                                                Detail
                                            </a>
                                            <a href="{{ route('siswa.edit', $siswa) }}" class="px-2 py-1 rounded bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium transition" title="Edit Data">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('siswa.destroy', $siswa) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    onclick="confirmAction({
                                                        form: this.closest('form'),
                                                        title: 'Pindahkan ke Sampah?',
                                                        message: 'Apakah Anda yakin ingin memindahkan data <strong>{{ addslashes($siswa->nama) }}</strong> ke sampah?<br><span class=\'text-xs text-slate-500 mt-1 block\'>Data ini dapat dipulihkan sewaktu-waktu.</span>',
                                                        confirmText: 'Ya, Pindahkan',
                                                        color: 'rose'
                                                    })"
                                                    class="px-2 py-1 rounded bg-rose-50 text-rose-600 hover:bg-rose-100 font-medium transition cursor-pointer" title="Hapus Siswa">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-4 py-12 text-center text-slate-500">
                                        Tidak ada data siswa yang cocok dengan filter pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($siswas->hasPages())
                    <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                        {{ $siswas->links() }}
                    </div>
                @endif
            </div>

            <!-- Floating Bulk Action Bar (Muncul saat ada siswa yang dipilih seperti di WhatsApp) -->
            <div x-show="selectedIds.length > 0"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-slate-900/95 backdrop-blur-md text-white rounded-2xl shadow-2xl p-2.5 sm:px-5 sm:py-3 border border-slate-700/60 flex items-center justify-between gap-3 sm:gap-6 min-w-[320px] sm:min-w-[460px]">
                <!-- Counter & Status -->
                <div class="flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-full bg-teal-500/20 text-teal-300 font-bold text-xs flex items-center justify-center border border-teal-500/30">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <div class="text-xs sm:text-sm font-semibold tracking-wide">
                        <span x-text="selectedIds.length" class="text-teal-300 font-bold"></span> siswa dipilih
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <!-- Download Excel Terpilih -->
                    <button type="button"
                            @click="downloadSelected()"
                            class="px-3.5 py-1.5 sm:py-2 bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Download Excel</span>
                    </button>

                    <!-- Hapus Terpilih -->
                    <button type="button"
                            @click="deleteSelected()"
                            class="px-3 py-1.5 sm:py-2 bg-rose-600/90 hover:bg-rose-600 active:bg-rose-700 text-white rounded-xl text-xs font-semibold transition flex items-center gap-1.5 cursor-pointer"
                            title="Pindahkan siswa terpilih ke sampah">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span class="hidden sm:inline">Hapus</span>
                    </button>

                    <!-- Batal Pilihan -->
                    <button type="button"
                            @click="clearSelection()"
                            class="p-1.5 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition cursor-pointer"
                            title="Batal pilih">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Hidden Form untuk Export Selected -->
            <form id="export-selected-form" method="POST" action="{{ route('siswa.export') }}" class="hidden">
                @csrf
                <input type="hidden" name="ids" id="export-ids-input" value="">
            </form>

            <!-- Hidden Form untuk Bulk Delete -->
            <form id="bulk-delete-form" method="POST" action="{{ route('siswa.bulk-destroy') }}" class="hidden">
                @csrf
                <template x-for="id in selectedIds" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
            </form>
        </div>
    </div>

    <!-- Modal Import Excel Dapodik -->
    <x-modal name="modal-import-dapodik" focusable>
        <form method="POST" action="{{ route('siswa.import') }}" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="flex items-center gap-3 mb-2">
                <span class="p-2 rounded-lg bg-teal-50 text-teal-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                </span>
                <div>
                    <h2 class="text-base font-bold text-slate-900">
                        Import Data Siswa Dapodik
                    </h2>
                    <p class="text-xs text-slate-500">
                        Upload berkas Excel (.xlsx) format resmi Daftar Peserta Didik Dapodik.
                    </p>
                </div>
            </div>

            <div class="mt-4 p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-600 space-y-1">
                <div class="font-semibold text-slate-800">Petunjuk Format Dapodik:</div>
                <ul class="list-disc list-inside space-y-0.5 text-2xs">
                    <li>Data dibaca mulai dari baris ke-4 (setelah header bertingkat Dapodik).</li>
                    <li>Sistem membaca index kolom resmi (Nama, NIPD, JK, NISN, Tgl Lahir, NIK, Ortu, Rombel, Bantuan).</li>
                    <li>Jika NISN atau NIK sudah ada di database, data akan diperbarui (upsert).</li>
                    <li>Jika rombel belum ada di tabel kelas, akan otomatis dibuatkan.</li>
                </ul>
            </div>

            <div class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Angkatan Masuk <span class="text-rose-500">*</span></label>
                    <input type="number" name="angkatan" value="{{ date('Y') }}" min="2000" max="{{ date('Y') + 1 }}" required class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" placeholder="2025">
                    <span class="text-3xs text-slate-500">Tahun ini akan diterapkan untuk semua siswa yang ada dalam berkas.</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Berkas Excel (.xlsx, .xls) <span class="text-rose-500">*</span></label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-slate-300 rounded-lg cursor-pointer">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" @click="$dispatch('close')" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-teal-700 hover:bg-teal-800 rounded-lg text-xs font-semibold text-white shadow-xs">
                    Mulai Import
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
