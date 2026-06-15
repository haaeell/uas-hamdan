@extends('layouts.auth')

@section('title', 'Lupa Password')

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
                            <i class="fa-solid fa-key text-2xl"></i>
                        </div>

                        <h1 class="text-4xl font-extrabold mt-8 leading-tight">
                            Lupa Password?
                        </h1>

                        <p class="text-red-100 mt-4 leading-relaxed max-w-md">
                            Jangan khawatir. Masukkan email yang terdaftar dan kami akan mengirimkan link untuk mereset password kamu.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mt-10">
                        <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-4 border border-white/20">
                            <i class="fa-solid fa-envelope text-2xl mb-3"></i>
                            <p class="text-sm font-bold">Cek Email</p>
                        </div>

                        <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-4 border border-white/20">
                            <i class="fa-solid fa-link text-2xl mb-3"></i>
                            <p class="text-sm font-bold">Klik Link</p>
                        </div>

                        <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-4 border border-white/20">
                            <i class="fa-solid fa-shield-halved text-2xl mb-3"></i>
                            <p class="text-sm font-bold">Aman</p>
                        </div>
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
                            Lupa Password
                        </h2>

                        <p class="text-slate-500 mt-3">
                            Masukkan alamat email yang terdaftar. Kami akan mengirim link reset password ke email tersebut.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="mb-6 rounded-2xl border border-green-100 bg-green-50 p-4 text-sm text-green-700">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-circle-check mt-0.5 text-green-500"></i>
                                <p class="font-semibold">{{ session('status') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                            <p class="font-bold mb-1">Terjadi kesalahan</p>
                            <p>{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Alamat Email
                            </label>

                            <div class="relative">
                                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                    placeholder="Masukkan email yang terdaftar"
                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800
                                        focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition">
                            </div>

                            @error('email')
                                <p class="text-sm text-red-700 mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-2xl bg-red-600 hover:bg-red-700
                                text-white font-extrabold shadow-lg shadow-red-200 transition-all duration-300 hover:-translate-y-0.5">
                            <i class="fa-solid fa-paper-plane"></i>
                            Kirim Link Reset Password
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
