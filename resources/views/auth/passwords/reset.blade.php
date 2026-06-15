@extends('layouts.auth')

@section('title', 'Buat Password Baru')

@section('content')
    @php
        $appName = \App\Models\Setting::getSetting('app_name', 'Sistem Pemilihan Jurusan');
        $schoolName = \App\Models\Setting::getSetting('school_name', 'Sekolah Menengah Atas');
        $supportContact = \App\Models\Setting::getSetting('support_contact', 'Hubungi admin sekolah');
    @endphp

    <div class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-10">

        <div class="w-full max-w-6xl grid lg:grid-cols-2 bg-white rounded-[36px] overflow-hidden shadow-2xl shadow-red-100 border border-slate-200">

            {{-- Left Branding --}}
            <div class="hidden lg:flex relative bg-gradient-to-br from-red-700 via-red-600 to-red-500 p-10 text-white overflow-hidden">

                <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/10"></div>
                <div class="absolute bottom-10 -left-16 w-64 h-64 rounded-full bg-white/10"></div>

                <div class="relative z-10 flex flex-col justify-between w-full">
                    <div>
                        <div class="w-16 h-16 rounded-3xl bg-white/15 backdrop-blur-xl border border-white/20 flex items-center justify-center shadow-xl">
                            <i class="fa-solid fa-lock-open text-2xl"></i>
                        </div>

                        <h1 class="text-4xl font-extrabold mt-8 leading-tight">
                            Buat Password Baru
                        </h1>

                        <p class="text-red-100 mt-4 leading-relaxed max-w-md">
                            Buat password baru yang kuat dan mudah kamu ingat. Pastikan password minimal 8 karakter.
                        </p>
                    </div>

                    <div class="mt-10 bg-white/15 backdrop-blur-xl rounded-3xl p-6 border border-white/20">
                        <p class="text-sm font-bold mb-3">Tips password yang aman:</p>
                        <ul class="space-y-2 text-sm text-red-100">
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-check text-white text-xs"></i>
                                Minimal 8 karakter
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-check text-white text-xs"></i>
                                Kombinasi huruf dan angka
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fa-solid fa-check text-white text-xs"></i>
                                Jangan gunakan tanggal lahir
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="p-6 sm:p-10 lg:p-14 flex items-center">
                <div class="w-full max-w-md mx-auto">

                    <div class="mb-8">
                        <p class="text-sm font-bold text-red-600 uppercase tracking-wide">
                            Reset Password
                        </p>

                        <h2 class="text-3xl font-extrabold text-slate-900 mt-2">
                            Password Baru
                        </h2>

                        <p class="text-slate-500 mt-3">
                            Masukkan password baru untuk akun kamu.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                            <p class="font-bold mb-1">Terjadi kesalahan</p>
                            <p>{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Alamat Email
                            </label>

                            <div class="relative">
                                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                                <input type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus
                                    placeholder="Masukkan email kamu"
                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800
                                        focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition">
                            </div>

                            @error('email')
                                <p class="text-sm text-red-700 mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Password Baru
                            </label>

                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                                <input type="password" name="password" id="passwordInput" required
                                    placeholder="Masukkan password baru"
                                    class="w-full pl-11 pr-12 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800
                                        focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition">

                                <button type="button" id="togglePassword"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-600 transition">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>

                            @error('password')
                                <p class="text-sm text-red-700 mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Konfirmasi Password Baru
                            </label>

                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                                <input type="password" name="password_confirmation" id="passwordConfirmInput" required
                                    placeholder="Ulangi password baru"
                                    class="w-full pl-11 pr-12 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800
                                        focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition">

                                <button type="button" id="togglePasswordConfirm"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-600 transition">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-2xl bg-red-600 hover:bg-red-700
                                text-white font-extrabold shadow-lg shadow-red-200 transition-all duration-300 hover:-translate-y-0.5">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Password Baru
                        </button>
                    </form>

                    <a href="{{ route('login') }}"
                        class="mt-4 inline-flex w-full items-center justify-center gap-2 py-3.5 rounded-2xl bg-white hover:bg-red-50 text-red-700 border border-red-100 font-extrabold transition">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke Login
                    </a>

                    <div class="mt-8 text-center text-sm text-slate-400">
                        © {{ date('Y') }} {{ $appName }} · Bantuan: {{ $supportContact }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function setupToggle(inputId, btnId) {
                const input = document.getElementById(inputId);
                const btn = document.getElementById(btnId);
                if (!input || !btn) return;
                btn.addEventListener('click', function () {
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    this.innerHTML = isPassword
                        ? '<i class="fa-solid fa-eye-slash"></i>'
                        : '<i class="fa-solid fa-eye"></i>';
                });
            }

            setupToggle('passwordInput', 'togglePassword');
            setupToggle('passwordConfirmInput', 'togglePasswordConfirm');
        });
    </script>
@endpush
