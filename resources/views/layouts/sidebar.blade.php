<!-- Mobile Sidebar Backdrop & Drawer (Alpine.js controlled) -->
<div x-show="sidebarOpen"
     x-transition:enter="transition-opacity ease-linear duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs lg:hidden"
     @click="sidebarOpen = false"
     style="display: none;"></div>

<!-- Mobile Sidebar Panel -->
<div x-show="sidebarOpen"
     x-transition:enter="transition ease-in-out duration-300 transform"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in-out duration-300 transform"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col lg:hidden shadow-xl"
     style="display: none;">
    <!-- Mobile Brand & Close Button -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200 bg-slate-50/50">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <span class="w-9 h-9 rounded-xl bg-teal-700 text-white flex items-center justify-center font-bold shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </span>
            <div class="leading-tight">
                <span class="block text-slate-900 font-bold text-sm tracking-tight">TK Harapan Bangsa</span>
                <span class="block text-2xs text-teal-700 font-medium">Sistem Data Siswa</span>
            </div>
        </a>
        <button type="button" @click="sidebarOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100" aria-label="Tutup menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links Mobile -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <div class="px-3 pb-2 text-3xs font-bold uppercase tracking-wider text-slate-400">
            Menu Utama
        </div>

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-teal-700 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <a href="{{ route('siswa.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('siswa.index', 'siswa.create', 'siswa.edit', 'siswa.show', 'siswa.kelas', 'siswa.angkatan') ? 'bg-teal-700 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('siswa.index', 'siswa.create', 'siswa.edit', 'siswa.show', 'siswa.kelas', 'siswa.angkatan') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Data Siswa
        </a>

        <a href="{{ route('kelas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('kelas.index') ? 'bg-teal-700 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('kelas.index') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Kelas / Rombel
        </a>

        <a href="{{ route('siswa.lulus') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('siswa.lulus') ? 'bg-teal-700 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('siswa.lulus') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
            Siswa Lulus
        </a>

        <div class="pt-4 px-3 pb-2 text-3xs font-bold uppercase tracking-wider text-slate-400">
            Pengaturan
        </div>

        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('profile.edit') ? 'bg-teal-700 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('profile.edit') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profil Akun
        </a>
    </nav>

    <!-- Mobile User Profile & Logout -->
    <div class="p-3 border-t border-slate-200 bg-slate-50/60">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="w-8 h-8 rounded-full bg-teal-100 text-teal-800 text-xs font-bold flex items-center justify-center shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-3xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50" title="Keluar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Desktop Static Left Sidebar -->
<aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-30 lg:w-64 lg:bg-white lg:border-r lg:border-slate-200 lg:flex lg:flex-col shadow-xs">
    <!-- Brand Header -->
    <div class="h-16 flex items-center px-5 border-b border-slate-200 bg-slate-50/50">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
            <span class="w-9 h-9 rounded-xl bg-teal-700 text-white flex items-center justify-center font-bold shadow-xs group-hover:bg-teal-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </span>
            <div class="leading-tight">
                <span class="block text-slate-900 font-bold text-sm tracking-tight">TK Harapan Bangsa</span>
                <span class="block text-2xs text-teal-700 font-semibold">Sistem Data Siswa</span>
            </div>
        </a>
    </div>

    <!-- Navigation Links Desktop -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <div class="px-3 pb-2 text-3xs font-bold uppercase tracking-wider text-slate-400">
            Menu Utama
        </div>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-teal-700 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('siswa.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('siswa.index', 'siswa.create', 'siswa.edit', 'siswa.show', 'siswa.kelas', 'siswa.angkatan') ? 'bg-teal-700 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('siswa.index', 'siswa.create', 'siswa.edit', 'siswa.show', 'siswa.kelas', 'siswa.angkatan') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Data Siswa</span>
        </a>

        <a href="{{ route('kelas.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('kelas.index') ? 'bg-teal-700 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('kelas.index') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span>Kelas / Rombel</span>
        </a>

        <a href="{{ route('siswa.lulus') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('siswa.lulus') ? 'bg-teal-700 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('siswa.lulus') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
            <span>Siswa Lulus</span>
        </a>

        <div class="pt-4 px-3 pb-2 text-3xs font-bold uppercase tracking-wider text-slate-400">
            Pengaturan
        </div>

        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('profile.edit') ? 'bg-teal-700 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('profile.edit') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Profil Akun</span>
        </a>
    </nav>

    <!-- Desktop User Footer -->
    <div class="p-3.5 border-t border-slate-200 bg-slate-50/60">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="w-8 h-8 rounded-full bg-teal-100 text-teal-800 text-xs font-bold flex items-center justify-center shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-3xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Keluar dari sistem">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
