@extends('layouts.auth')

@section('content')
    @php
        $appName = \App\Models\Setting::getSetting('app_name', 'Sistem Pemilihan Jurusan');
    @endphp

    <div class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-2xl bg-white border border-slate-200 rounded-[32px] p-6 sm:p-8 shadow-xl shadow-red-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-red-600 text-white flex items-center justify-center">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-red-600 uppercase tracking-wide">Pengajuan Owner</p>
                    <h1 class="text-2xl font-extrabold text-slate-900">Ajukan Akses Panel</h1>
                </div>
            </div>

            <div class="grid lg:grid-cols-[1.15fr_0.85fr] gap-6">
                <div>
                        <div class="rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-700 mb-6">
                            <p class="font-bold mb-2">Sebelum mengajukan akses</p>
                            <div class="space-y-1 leading-relaxed">
                                <div>1. Gunakan email aktif yang benar-benar bisa diakses.</div>
                                <div>2. Kode OTP akan dikirim ke email untuk verifikasi awal.</div>
                                <div>3. Setelah email terverifikasi, admin akan meninjau pengajuan akun Anda.</div>
                            </div>
                        </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Pemilik Panel</label>
                            <input name="name" value="{{ old('name') }}" required autofocus
                                class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition"
                                placeholder="Contoh: Budi Santoso">
                            @error('name')<p class="text-sm text-red-700 mt-2 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email Aktif</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition"
                                placeholder="nama@sekolah.sch.id">
                            @error('email')<p class="text-sm text-red-700 mt-2 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition"
                                placeholder="Minimal 8 karakter">
                            @error('password')<p class="text-sm text-red-700 mt-2 font-semibold">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition">
                        </div>

                        <button class="w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-extrabold shadow-lg shadow-red-200 transition">
                            <i class="fa-solid fa-paper-plane"></i>
                            Kirim Pengajuan
                        </button>
                    </form>

                    <a href="{{ route('login') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 text-sm font-bold text-red-700">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke Login
                    </a>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm font-bold text-slate-900">Alur Persetujuan</p>
                    <div class="mt-4 space-y-4 text-sm text-slate-600">
                        <div class="rounded-2xl bg-white border border-slate-200 p-4">
                            <div class="font-bold text-slate-900">1. Isi data</div>
                            <div class="mt-1">Masukkan nama, email aktif, dan password untuk mengajukan panel owner.</div>
                        </div>
                        <div class="rounded-2xl bg-white border border-slate-200 p-4">
                            <div class="font-bold text-slate-900">2. Menunggu review</div>
                            <div class="mt-1">Admin akan memeriksa pengajuan sebelum akun bisa dipakai login.</div>
                        </div>
                        <div class="rounded-2xl bg-white border border-slate-200 p-4">
                            <div class="font-bold text-slate-900">3. Dapat email</div>
                            <div class="mt-1">Begitu disetujui, sistem mengirim pemberitahuan ke email Anda.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
