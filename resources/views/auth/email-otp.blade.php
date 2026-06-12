@extends('layouts.auth')

@section('content')
    <div class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-2xl bg-white border border-slate-200 rounded-[32px] p-6 sm:p-8 shadow-xl shadow-red-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-red-600 text-white flex items-center justify-center">
                    <i class="fa-solid fa-shield-halved text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-red-600 uppercase tracking-wide">Verifikasi Email</p>
                    <h1 class="text-2xl font-extrabold text-slate-900">Masukkan Kode OTP</h1>
                </div>
            </div>

            <div class="grid lg:grid-cols-[1.15fr_0.85fr] gap-6">
                <div>
                    <div class="rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-700 mb-6">
                        <p class="font-bold mb-2">Kode OTP sudah dikirim ke email Anda.</p>
                        <div class="space-y-1 leading-relaxed">
                            <div>1. Buka email yang Anda daftarkan.</div>
                            <div>2. Masukkan kode OTP 6 digit ke form ini.</div>
                            <div>3. Setelah berhasil, akun masuk ke tahap persetujuan admin.</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('auth.email-otp.verify') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $email) }}" required
                                class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition"
                                placeholder="nama@domain.com">
                            @error('email')<p class="text-sm text-red-700 mt-2 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Kode OTP</label>
                            <input type="text" name="otp_code" value="{{ old('otp_code') }}" required maxlength="6" inputmode="numeric"
                                class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 tracking-[0.35em] text-center text-xl font-extrabold focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition"
                                placeholder="123456">
                            @error('otp_code')<p class="text-sm text-red-700 mt-2 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <button class="w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-extrabold shadow-lg shadow-red-200 transition">
                            <i class="fa-solid fa-circle-check"></i>
                            Verifikasi Sekarang
                        </button>
                    </form>

                    <form method="POST" action="{{ route('auth.email-otp.resend') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="email" value="{{ old('email', $email) }}">
                        <button type="submit" class="inline-flex items-center gap-2 text-sm font-bold text-red-700">
                            <i class="fa-solid fa-rotate-right"></i>
                            Kirim ulang kode OTP
                        </button>
                    </form>

                    <a href="{{ route('login') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 text-sm font-bold text-red-700">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke Login
                    </a>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm font-bold text-slate-900">Alur Verifikasi</p>
                    <div class="mt-4 space-y-4 text-sm text-slate-600">
                        <div class="rounded-2xl bg-white border border-slate-200 p-4">
                            <div class="font-bold text-slate-900">1. Registrasi</div>
                            <div class="mt-1">Anda membuat akun owner dengan email aktif.</div>
                        </div>
                        <div class="rounded-2xl bg-white border border-slate-200 p-4">
                            <div class="font-bold text-slate-900">2. OTP masuk</div>
                            <div class="mt-1">Sistem mengirim kode OTP ke email Anda.</div>
                        </div>
                        <div class="rounded-2xl bg-white border border-slate-200 p-4">
                            <div class="font-bold text-slate-900">3. Admin review</div>
                            <div class="mt-1">Setelah email terverifikasi, admin meninjau pengajuan akun Anda.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
