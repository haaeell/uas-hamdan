@extends('layouts.auth')

@section('content')
    @php
        $appName = \App\Models\Setting::getSetting('app_name', 'Sistem Pemilihan Jurusan');
        $schoolName = \App\Models\Setting::getSetting('school_name', 'Sekolah Menengah Atas');
        $loginHelpText = \App\Models\Setting::getSetting('login_help_text', 'Gunakan email admin untuk melanjutkan.');
        $supportContact = \App\Models\Setting::getSetting('support_contact', 'Hubungi admin sekolah');
    @endphp
    <div class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-10">

        <div
            class="w-full max-w-6xl grid lg:grid-cols-2 bg-white rounded-[36px] overflow-hidden shadow-2xl shadow-red-100 border border-slate-200">

            {{-- Left Branding --}}
            <div
                class="hidden lg:flex relative bg-gradient-to-br from-red-700 via-red-600 to-red-500 p-10 text-white overflow-hidden">

                <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/10"></div>
                <div class="absolute bottom-10 -left-16 w-64 h-64 rounded-full bg-white/10"></div>

                <div class="relative z-10 flex flex-col justify-between w-full">
                    <div>
                        <div class="w-16 h-16 rounded-3xl bg-white/15 backdrop-blur-xl border border-white/20 flex items-center justify-center shadow-xl">
                            <i class="fa-solid fa-graduation-cap text-2xl"></i>
                        </div>

                        <h1 class="text-4xl font-extrabold mt-8 leading-tight">
                            {{ $appName }}
                        </h1>

                        <p class="text-red-100 mt-4 leading-relaxed max-w-md">
                            {{ $schoolName }} menggunakan platform ini untuk membuat proses pemilihan jurusan lebih tertata, aman, dan mudah digunakan.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mt-10">
                        <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-4 border border-white/20">
                            <i class="fa-solid fa-user-check text-2xl mb-3"></i>
                            <p class="text-sm font-bold">Login Aman</p>
                        </div>

                        <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-4 border border-white/20">
                            <i class="fa-solid fa-file-pen text-2xl mb-3"></i>
                            <p class="text-sm font-bold">Tes Online</p>
                        </div>

                        <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-4 border border-white/20">
                            <i class="fa-solid fa-chart-line text-2xl mb-3"></i>
                            <p class="text-sm font-bold">Hasil Cepat</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Login Form --}}
            <div class="p-6 sm:p-10 lg:p-14 flex items-center">
                <div class="w-full max-w-md mx-auto">

                    <div class="mb-8">
                        <p class="text-sm font-bold text-red-600 uppercase tracking-wide">
                            Selamat Datang
                        </p>

                        <h2 class="text-3xl font-extrabold text-slate-900 mt-2">
                            Masuk ke Akun
                        </h2>

                        <p class="text-slate-500 mt-3">
                            {{ $loginHelpText }}
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                            <p class="font-bold mb-1">Login gagal</p>
                            <p>{{ $errors->first('login') ?: 'Periksa kembali email dan password kamu.' }}</p>
                            @if(str_contains($errors->first('login') ?? '', 'OTP'))
                                <form method="POST" action="{{ route('auth.email-otp.resend') }}" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ old('login') }}">
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 font-bold text-red-700 underline underline-offset-2 hover:text-red-900 transition">
                                        <i class="fa-solid fa-paper-plane text-xs"></i> Kirim ulang OTP
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        {{-- Login --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Email
                            </label>

                            <div class="relative">
                                <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                                <input type="text" name="login" value="{{ old('login') }}" required autofocus
                                    placeholder="Masukkan email admin"
                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800
                                        focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition">

                            </div>

                            @error('login')
                                <p class="text-sm text-red-700 mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Password
                            </label>

                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                                <input type="password" name="password" id="passwordInput" required
                                    placeholder="Masukkan password"
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

                        {{-- Submit --}}
                        <button
                            class="w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-2xl bg-red-600 hover:bg-red-700
                                text-white font-extrabold shadow-lg shadow-red-200 transition-all duration-300 hover:-translate-y-0.5">

                            <i class="fa-solid fa-right-to-bracket"></i>
                            Masuk
                        </button>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-slate-500 hover:text-red-600 font-semibold transition">
                            <i class="fa-solid fa-key mr-1"></i>
                            Lupa Password?
                        </a>
                    </div>

                    <a href="{{ route('register') }}"
                        class="mt-4 inline-flex w-full items-center justify-center gap-2 py-3.5 rounded-2xl bg-white hover:bg-red-50 text-red-700 border border-red-100 font-extrabold transition">
                        <i class="fa-solid fa-user-plus"></i>
                        Daftar Owner Baru
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
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('passwordInput');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function () {
                    const isPassword = passwordInput.type === 'password';

                    passwordInput.type = isPassword ? 'text' : 'password';
                    this.innerHTML = isPassword
                        ? '<i class="fa-solid fa-eye-slash"></i>'
                        : '<i class="fa-solid fa-eye"></i>';
                });
            }
        });
    </script>
@endpush
