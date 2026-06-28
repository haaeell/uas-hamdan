@extends('layouts.auth')

@section('title', 'Daftar - jejakcita.id')

@section('content')
    <div class="min-h-screen text-gray-950" style="background:#f6f8fb">
        <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 py-6">
            <a href="{{ route('login') }}" class="inline-flex items-center">
                <img src="{{ asset('logo.png') }}" alt="jejakcita.id" class="h-11 w-auto">
            </a>

            <a href="{{ route('login') }}"
                class="hidden rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold shadow-sm transition hover:border-gray-300 sm:inline-flex"
                style="color:#1a2d6b">
                Masuk
            </a>
        </header>

        <main class="mx-auto grid w-full max-w-6xl gap-8 px-5 pb-10 pt-4 lg:grid-cols-[0.9fr_1.1fr] lg:items-start lg:pt-10">
            <section class="max-w-xl">

                <h1 class="mt-4 text-4xl font-bold leading-tight sm:text-5xl" style="color:#0f1f3d">
                    Mulai deteksi potensi karir siswa sekolah Anda.
                </h1>

                <p class="mt-5 max-w-lg text-[0.975rem] leading-[1.75] text-gray-500">
                    Buat akun untuk mengelola panel jejakcita.id. Setelah email diverifikasi, admin akan meninjau pengajuan sebelum panel dapat digunakan.
                </p>

                <div class="mt-8 space-y-5 border-l-2 pl-5 text-sm" style="border-color:#e5e7eb">
                    <div>
                        <p class="font-semibold" style="color:#0f1f3d">1. Isi identitas</p>
                        <p class="mt-1 leading-6 text-gray-500">Gunakan nama lengkap dan email aktif yang bisa diverifikasi.</p>
                    </div>

                    <div>
                        <p class="font-semibold" style="color:#0f1f3d">2. Verifikasi OTP</p>
                        <p class="mt-1 leading-6 text-gray-500">Kode verifikasi dikirim ke email setelah form dikirim.</p>
                    </div>

                    <div>
                        <p class="font-semibold" style="color:#0f1f3d">3. Review admin</p>
                        <p class="mt-1 leading-6 text-gray-500">Akun aktif dan siap digunakan setelah disetujui admin platform.</p>
                    </div>
                </div>

                <div class="mt-8 rounded-lg border border-gray-100 bg-white px-4 py-4 text-sm leading-6 text-gray-500 shadow-sm">
                    <span class="font-semibold" style="color:#0f1f3d">Catatan:</span>
                    gunakan email institusi atau email aktif yang bisa diverifikasi.
                </div>
            </section>

            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-8 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest" style="color:#c9a227">Daftar</p>
                        <h2 class="mt-1 text-2xl font-bold" style="color:#0f1f3d">Buat akun baru</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-400">Semua kolom wajib diisi.</p>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-100 bg-gray-50 text-gray-400">
                        <i class="fa-solid fa-user-plus text-sm"></i>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="ownerName">Nama lengkap</label>
                        <div class="relative mt-2">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input id="ownerName" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full rounded-lg border border-gray-200 bg-white py-3.5 pl-11 pr-4 text-gray-900 outline-none transition placeholder:text-gray-300"
                                placeholder="Budi Santoso">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="ownerEmail">Email aktif</label>
                        <div class="relative mt-2">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input id="ownerEmail" type="email" name="email" value="{{ old('email') }}" required
                                class="w-full rounded-lg border border-gray-200 bg-white py-3.5 pl-11 pr-4 text-gray-900 outline-none transition placeholder:text-gray-300"
                                placeholder="owner@sekolah.sch.id">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="passwordInput">Password</label>
                            <div class="relative mt-2">
                                <i class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                <input id="passwordInput" type="password" name="password" required
                                    class="w-full rounded-lg border border-gray-200 bg-white py-3.5 pl-11 pr-4 text-gray-900 outline-none transition placeholder:text-gray-300"
                                    placeholder="Minimal 8 karakter">
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="passwordConfirmation">Konfirmasi</label>
                            <div class="relative mt-2">
                                <i class="fa-solid fa-shield-halved absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                <input id="passwordConfirmation" type="password" name="password_confirmation" required
                                    class="w-full rounded-lg border border-gray-200 bg-white py-3.5 pl-11 pr-4 text-gray-900 outline-none transition placeholder:text-gray-300"
                                    placeholder="Ulangi password">
                            </div>
                        </div>
                    </div>

                    <button
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3.5 text-sm font-bold text-white shadow-sm transition focus:outline-none"
                        style="background:#1a2d6b"
                        onmouseover="this.style.background='#162d58'" onmouseout="this.style.background='#1a2d6b'">
                        <i class="fa-solid fa-paper-plane"></i>
                        Kirim pengajuan
                    </button>
                </form>

                <div class="mt-5 rounded-lg border border-gray-100 bg-gray-50 px-4 py-3.5 text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold hover:opacity-80" style="color:#c9a227">
                        Masuk ke panel
                    </a>
                </div>
            </section>
        </main>

        <footer class="mx-auto flex w-full max-w-6xl flex-col gap-2 px-5 pb-8 text-xs font-medium text-gray-400 sm:flex-row sm:items-center sm:justify-between">
            <img src="{{ asset('logo.png') }}" alt="jejakcita.id" class="h-11 w-auto">
            <span>Platform deteksi potensi karir siswa</span>
        </footer>
    </div>
@endsection
