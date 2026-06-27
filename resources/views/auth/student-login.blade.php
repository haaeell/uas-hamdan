@extends('layouts.auth')

@section('content')
    @php
        $appName    = \App\Models\Setting::getSetting('app_name', 'Sistem Pemilihan Jurusan');
        $schoolName = \App\Models\Setting::getSetting('school_name', 'Sekolah Menengah Atas');
        $supportContact = \App\Models\Setting::getSetting('support_contact', 'Hubungi admin sekolah');
    @endphp

    <div class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-6xl grid lg:grid-cols-2 bg-white rounded-[36px] overflow-hidden shadow-2xl shadow-blue-100 border border-slate-200">

            {{-- Left Branding --}}
            <div class="hidden lg:flex relative p-10 text-white overflow-hidden"
                style="background: linear-gradient(135deg, var(--theme-color, #2563eb) 0%, color-mix(in srgb, var(--theme-color, #2563eb) 70%, #0f172a) 100%)">
                <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/10"></div>
                <div class="absolute bottom-10 -left-16 w-64 h-64 rounded-full bg-white/10"></div>

                <div class="relative z-10 flex flex-col justify-between w-full">
                    <div>
                        <div class="w-16 h-16 rounded-3xl bg-white/15 backdrop-blur-xl border border-white/20 flex items-center justify-center shadow-xl">
                            <i class="fa-solid fa-user-graduate text-2xl"></i>
                        </div>

                        <h1 class="text-4xl font-extrabold mt-8 leading-tight">Login Siswa</h1>
                        <p class="text-white/80 mt-3 text-lg font-semibold">{{ $owner->name }}</p>
                        <p class="text-white/60 mt-1">{{ $schoolName }}</p>
                        <p class="text-white/70 mt-4 leading-relaxed max-w-md">
                            Masuk menggunakan NISN dan password yang diberikan oleh admin untuk mengikuti tes pemilihan jurusan.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mt-10">
                        <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-4 border border-white/20">
                            <i class="fa-solid fa-id-card text-2xl mb-3"></i>
                            <p class="text-sm font-bold">Login NISN</p>
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
                        <p class="text-sm font-bold uppercase tracking-wide" style="color: var(--theme-color, #2563eb)">
                            Portal Siswa
                        </p>
                        <h2 class="text-3xl font-extrabold text-slate-900 mt-2">Masuk ke Akun</h2>
                        <p class="text-slate-500 mt-2 text-sm">
                            Login sebagai siswa <span class="font-semibold text-slate-700">{{ $owner->name }}</span>
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                            <p class="font-bold mb-1">Login gagal</p>
                            <p>{{ $errors->first('nisn') ?: $errors->first('password') ?: 'Periksa kembali NISN dan password.' }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('student.login.submit', $token) }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">NISN</label>
                            <div class="relative">
                                <i class="fa-solid fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="nisn" value="{{ old('nisn') }}" required autofocus
                                    placeholder="Masukkan NISN kamu"
                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800
                                        focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="password" name="password" id="passwordInput" required
                                    placeholder="Masukkan password"
                                    class="w-full pl-11 pr-12 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800
                                        focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">
                                <button type="button" id="togglePassword"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-2xl text-white font-extrabold shadow-lg transition-all duration-300 hover:-translate-y-0.5"
                            style="background-color: var(--theme-color, #2563eb)">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            Masuk
                        </button>
                    </form>

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
            const toggle = document.getElementById('togglePassword');
            const input  = document.getElementById('passwordInput');

            if (toggle && input) {
                toggle.addEventListener('click', function () {
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    this.innerHTML = isPassword
                        ? '<i class="fa-solid fa-eye-slash"></i>'
                        : '<i class="fa-solid fa-eye"></i>';
                });
            }
        });
    </script>
@endpush
