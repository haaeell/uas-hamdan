@extends('layouts.auth')

@section('title', 'Daftar Owner - jejakcita.id')

@section('content')
    <div class="min-h-screen bg-[#f7f8fa] text-slate-950">
        <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 py-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-950 text-sm font-black text-white">
                    JC
                </span>
                <span>
                    <span class="block text-base font-extrabold leading-5 tracking-tight">jejakcita.id</span>
                    <span class="block text-xs font-semibold text-slate-500">Owner Registration</span>
                </span>
            </a>

            <a href="{{ route('login') }}"
                class="hidden rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950 sm:inline-flex">
                Masuk
            </a>
        </header>

        <main class="mx-auto grid w-full max-w-6xl gap-8 px-5 pb-10 pt-4 lg:grid-cols-[0.9fr_1.1fr] lg:items-start lg:pt-10">
            <section class="max-w-xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-blue-700">Pengajuan owner</p>

                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-slate-950 sm:text-5xl">
                    Ajukan akses panel sekolah.
                </h1>

                <p class="mt-5 max-w-lg text-base leading-7 text-slate-600">
                    Buat akun owner untuk mengelola panel jejakcita.id. Setelah email diverifikasi,
                    admin akan meninjau pengajuan sebelum panel dapat digunakan.
                </p>

                <div class="mt-8 space-y-5 border-l border-slate-200 pl-5 text-sm">
                    <div>
                        <p class="font-extrabold text-slate-950">1. Isi identitas</p>
                        <p class="mt-1 leading-6 text-slate-600">Gunakan nama pemilik panel dan email aktif.</p>
                    </div>

                    <div>
                        <p class="font-extrabold text-slate-950">2. Verifikasi OTP</p>
                        <p class="mt-1 leading-6 text-slate-600">Kode verifikasi dikirim ke email setelah form dikirim.</p>
                    </div>

                    <div>
                        <p class="font-extrabold text-slate-950">3. Review admin</p>
                        <p class="mt-1 leading-6 text-slate-600">Akun aktif setelah disetujui oleh admin platform.</p>
                    </div>
                </div>

                <div class="mt-8 rounded-lg border border-slate-200 bg-white px-4 py-4 text-sm leading-6 text-slate-600 shadow-sm">
                    <span class="font-extrabold text-slate-950">Catatan:</span>
                    gunakan email institusi atau email aktif yang bisa diverifikasi.
                </div>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-8 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-blue-700">Daftar owner</p>
                        <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-950">Data akun</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Semua kolom wajib diisi.</p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-slate-700" for="ownerName">Nama pemilik panel</label>
                        <div class="relative mt-2">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="ownerName" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full rounded-lg border border-slate-200 bg-white py-3.5 pl-11 pr-4 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
                                placeholder="Budi Santoso">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700" for="ownerEmail">Email aktif</label>
                        <div class="relative mt-2">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="ownerEmail" type="email" name="email" value="{{ old('email') }}" required
                                class="w-full rounded-lg border border-slate-200 bg-white py-3.5 pl-11 pr-4 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
                                placeholder="owner@sekolah.sch.id">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-bold text-slate-700" for="passwordInput">Password</label>
                            <div class="relative mt-2">
                                <i class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="passwordInput" type="password" name="password" required
                                    class="w-full rounded-lg border border-slate-200 bg-white py-3.5 pl-11 pr-4 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
                                    placeholder="Minimal 8 karakter">
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700" for="passwordConfirmation">Konfirmasi</label>
                            <div class="relative mt-2">
                                <i class="fa-solid fa-shield-halved absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="passwordConfirmation" type="password" name="password_confirmation" required
                                    class="w-full rounded-lg border border-slate-200 bg-white py-3.5 pl-11 pr-4 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
                                    placeholder="Ulangi password">
                            </div>
                        </div>
                    </div>

                    <button
                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-slate-950 px-5 py-3.5 font-extrabold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200">
                        <i class="fa-solid fa-paper-plane"></i>
                        Kirim pengajuan
                    </button>
                </form>

                <div class="mt-6 rounded-lg border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-extrabold text-blue-700 hover:text-blue-900">
                        Masuk ke panel
                    </a>
                </div>
            </section>
        </main>

        <footer class="mx-auto flex w-full max-w-6xl flex-col gap-2 px-5 pb-8 text-xs font-semibold text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <span>jejakcita.id</span>
            <span>Pengajuan owner</span>
        </footer>
    </div>
@endsection
