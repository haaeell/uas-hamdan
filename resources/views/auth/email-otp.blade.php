@extends('layouts.auth')

@section('title', 'Verifikasi Email - jejakcita.id')

@section('content')
    <div class="min-h-screen bg-[#f7f8fa] text-slate-950">
        <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 py-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-950 text-sm font-black text-white">
                    JC
                </span>
                <span>
                    <span class="block text-base font-extrabold leading-5 tracking-tight">jejakcita.id</span>
                    <span class="block text-xs font-semibold text-slate-500">Email Verification</span>
                </span>
            </a>

            <a href="{{ route('login') }}"
                class="hidden rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950 sm:inline-flex">
                Kembali login
            </a>
        </header>

        <main class="mx-auto grid w-full max-w-6xl gap-8 px-5 pb-10 pt-4 lg:grid-cols-[0.9fr_1.1fr] lg:items-start lg:pt-10">
            <section class="max-w-xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-blue-700">Verifikasi email</p>

                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-slate-950 sm:text-5xl">
                    Masukkan kode OTP.
                </h1>

                <p class="mt-5 max-w-lg text-base leading-7 text-slate-600">
                    Kode enam digit dikirim ke email yang didaftarkan. Setelah berhasil,
                    akun owner masuk ke tahap review admin.
                </p>

                <div class="mt-8 space-y-5 border-l border-slate-200 pl-5 text-sm">
                    <div>
                        <p class="font-extrabold text-slate-950">Cek inbox</p>
                        <p class="mt-1 leading-6 text-slate-600">Gunakan kode terbaru dari email jejakcita.id.</p>
                    </div>

                    <div>
                        <p class="font-extrabold text-slate-950">Kode singkat</p>
                        <p class="mt-1 leading-6 text-slate-600">OTP hanya berisi enam angka.</p>
                    </div>

                    <div>
                        <p class="font-extrabold text-slate-950">Review akun</p>
                        <p class="mt-1 leading-6 text-slate-600">Admin mengaktifkan akun setelah email valid.</p>
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-8 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-blue-700">OTP</p>
                        <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-950">Verifikasi akun</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Masukkan email dan kode yang diterima.</p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                </div>

                <form method="POST" action="{{ route('auth.email-otp.verify') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-slate-700" for="otpEmail">Email</label>
                        <div class="relative mt-2">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="otpEmail" type="email" name="email" value="{{ old('email', $email) }}" required
                                class="w-full rounded-lg border border-slate-200 bg-white py-3.5 pl-11 pr-4 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
                                placeholder="owner@sekolah.sch.id">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700" for="otpCode">Kode OTP</label>
                        <input id="otpCode" type="text" name="otp_code" value="{{ old('otp_code') }}" required maxlength="6" inputmode="numeric"
                            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-3.5 text-center text-xl font-black tracking-[0.35em] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
                            placeholder="123456">
                        @error('otp_code')
                            <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-slate-950 px-5 py-3.5 font-extrabold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200">
                        <i class="fa-solid fa-circle-check"></i>
                        Verifikasi sekarang
                    </button>
                </form>

                <form method="POST" action="{{ route('auth.email-otp.resend') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">
                    <button type="submit" class="inline-flex items-center gap-2 text-sm font-extrabold text-blue-700 hover:text-blue-900">
                        <i class="fa-solid fa-rotate-right"></i>
                        Kirim ulang kode OTP
                    </button>
                </form>

                <div class="mt-6 rounded-lg border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600">
                    Ingin memakai akun lain?
                    <a href="{{ route('login') }}" class="font-extrabold text-blue-700 hover:text-blue-900">
                        Kembali ke login
                    </a>
                </div>
            </section>
        </main>

        <footer class="mx-auto flex w-full max-w-6xl flex-col gap-2 px-5 pb-8 text-xs font-semibold text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <span>jejakcita.id</span>
            <span>Verifikasi owner</span>
        </footer>
    </div>
@endsection
