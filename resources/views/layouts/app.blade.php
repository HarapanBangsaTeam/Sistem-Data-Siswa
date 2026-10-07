<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' - ' : '' }}TK Harapan Bangsa</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-full" x-data="{ sidebarOpen: false }">
        <!-- Sidebar Navigation (Kiri) -->
        @include('layouts.sidebar')

        <!-- Main Wrapper (Geser ke kanan pada Desktop lg:pl-64) -->
        <div class="lg:pl-64 flex flex-col min-h-screen">
            <!-- Top Header Bar -->
            <header class="sticky top-0 z-20 bg-white/95 backdrop-blur-xs border-b border-slate-200">
                <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                    <!-- Left: Mobile Hamburger & Breadcrumb Title -->
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Mobile Hamburger Button -->
                        <button type="button"
                                @click="sidebarOpen = true"
                                class="p-2 -ml-1 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg lg:hidden"
                                aria-label="Buka menu navigasi">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                                <span>TK Harapan Bangsa</span>
                                <span>/</span>
                                <span class="text-teal-800 font-semibold truncate">
                                    {{ request()->routeIs('dashboard') ? 'Dashboard' : (request()->routeIs('siswa.*') ? 'Data Siswa' : (request()->routeIs('kelas.*') ? 'Kelas / Rombel' : 'Sistem')) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Info Tahun Ajaran & Profil Cepat -->
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-teal-800 text-xs font-semibold">
                            <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Tahun Ajaran 2025/2026</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-teal-700 text-white text-xs font-bold flex items-center justify-center shadow-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden md:inline-block text-xs font-semibold text-slate-800">
                                {{ Auth::user()->name }}
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Action Header (Jika ada) -->
            @isset($header)
                <div class="bg-white border-b border-slate-200 py-4 px-4 sm:px-6 lg:px-8 shadow-2xs">
                    {{ $header }}
                </div>
            @endisset

            <!-- Flash Alerts -->
            <div class="px-4 sm:px-6 lg:px-8 mt-4">
                @if (session('success'))
                    <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3 shadow-xs" role="alert">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm font-medium">{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-4 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3 shadow-xs" role="alert">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="text-sm font-medium">
                            {{ session('warning') }}
                            @if (session('import_errors'))
                                <ul class="mt-2 list-disc list-inside text-xs text-amber-800 space-y-1">
                                    @foreach (session('import_errors') as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3 shadow-xs" role="alert">
                        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm font-medium">{{ session('error') }}</div>
                    </div>
                @endif
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500 mt-auto">
                <div class="px-4">
                    Sistem Informasi Data Siswa &copy; {{ date('Y') }} TK Harapan Bangsa. Format Dapodik Terintegrasi.
                </div>
            </footer>
        </div>

        <!-- Global Action Confirmation Modal -->
        <x-confirm-modal />
    </body>
</html>
