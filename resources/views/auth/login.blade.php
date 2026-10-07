<x-guest-layout>
    <div class="mb-5">
        <h2 class="text-base font-bold text-slate-900">Masuk ke Portal Admin</h2>
        <p class="text-xs text-slate-500 mt-0.5">Silakan masuk menggunakan akun pengelola sekolah</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
            <input id="email" class="block w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600" type="email" name="email" value="{{ old('email', 'admin@tkharapanbangsa.sch.id') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-xs font-semibold text-slate-700">Kata Sandi (Password)</label>
            </div>
            <input id="password" class="block w-full text-xs rounded-lg border-slate-300 focus:border-teal-600 focus:ring-teal-600"
                            type="password"
                            name="password"
                            value="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-teal-700 shadow-xs focus:ring-teal-500" name="remember" checked>
                <span class="ms-2 text-xs text-slate-600">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs text-teal-700 hover:text-teal-900 font-medium hover:underline" href="{{ route('password.request') }}">
                    Lupa sandi?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-2.5 px-4 bg-teal-700 hover:bg-teal-800 text-white rounded-lg text-xs font-bold shadow-xs transition">
                Masuk ke Sistem
            </button>
        </div>

        <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-2xs text-slate-600 text-center">
            Akun bawaan seeder: <strong class="text-slate-800 font-mono">admin@tkharapanbangsa.sch.id</strong> / sandi: <strong class="text-slate-800 font-mono">password</strong>
        </div>
    </form>
</x-guest-layout>
