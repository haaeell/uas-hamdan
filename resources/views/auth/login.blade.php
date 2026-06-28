@extends('layouts.auth')

@section('title', 'Masuk - jejakcita.id')

@section('content')
    <div class="min-h-screen bg-[#f7f8fa] text-slate-950">
        <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 py-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-950 text-sm font-black text-white">
                    JC
                </span>
                <span>
                    <span class="block text-base font-extrabold leading-5 tracking-tight">jejakcita.id</span>
                    <span class="block text-xs font-semibold text-slate-500">Admin & Owner Console</span>
                </span>
            </a>

            <a href="{{ route('register') }}"
                class="hidden rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950 sm:inline-flex">
                Daftar owner
            </a>
        </header>

        <main class="mx-auto grid w-full max-w-6xl gap-8 px-5 pb-10 pt-4 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:pt-10">
            <section class="max-w-xl">

                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-slate-950 sm:text-5xl">
                    Masuk untuk mengelola peminatan siswa.
                </h1>

                <p class="mt-5 max-w-lg text-base leading-7 text-slate-600">
                    jejakcita.id membantu sekolah mengatur data siswa, sesi tes,
                    distribusi kelas, dan pengumuman hasil dari satu panel.
                </p>

                <div class="mt-8 grid gap-4 text-sm text-slate-600 sm:grid-cols-2">
                    <div class="border-l-2 border-slate-950 pl-4">
                        <p class="font-extrabold text-slate-950">Admin dan owner</p>
                        <p class="mt-1 leading-6">Masuk memakai email terdaftar.</p>
                    </div>

                    <div class="border-l-2 border-blue-600 pl-4">
                        <p class="font-extrabold text-slate-950">Portal siswa terpisah</p>
                        <p class="mt-1 leading-6">Siswa memakai link khusus dari sekolah.</p>
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-8 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-blue-700">Masuk</p>
                        <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-950">Akses panel</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Gunakan email admin atau owner untuk melanjutkan.</p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <p class="font-extrabold">Login gagal</p>
                        <p class="mt-1">{{ $errors->first('login') ?: 'Periksa kembali email dan password.' }}</p>
                        @if(str_contains($errors->first('login') ?? '', 'OTP'))
                            <form method="POST" action="{{ route('auth.email-otp.resend') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="email" value="{{ old('login') }}">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 text-sm font-extrabold text-red-700 underline underline-offset-4 hover:text-red-900">
                                    <i class="fa-solid fa-paper-plane text-xs"></i>
                                    Kirim ulang OTP
                                </button>
                            </form>
                        @endif
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-slate-700" for="loginEmail">Email</label>
                        <div class="relative mt-2">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="loginEmail" type="email" name="login" value="{{ old('login') }}" required autofocus
                                placeholder="admin@sekolah.sch.id"
                                class="w-full rounded-lg border border-slate-200 bg-white py-3.5 pl-11 pr-4 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100">
                        </div>

                        @error('login')
                            <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <label class="block text-sm font-bold text-slate-700" for="passwordInput">Password</label>
                            <a href="{{ route('password.request') }}" class="text-sm font-bold text-blue-700 hover:text-blue-900">
                                Lupa password?
                            </a>
                        </div>

                        <div class="relative mt-2">
                            <i class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="passwordInput" type="password" name="password" required
                                placeholder="Masukkan password"
                                class="w-full rounded-lg border border-slate-200 bg-white py-3.5 pl-11 pr-12 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100">

                            <button type="button" id="togglePassword"
                                class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-md text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                                aria-label="Tampilkan password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>

                        @error('password')
                            <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-slate-950 px-5 py-3.5 font-extrabold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Masuk ke panel
                    </button>
                </form>

                <div class="mt-6 rounded-lg border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600">
                    Belum punya akses owner?
                    <a href="{{ route('register') }}" class="font-extrabold text-blue-700 hover:text-blue-900">
                        Ajukan akses baru
                    </a>
                </div>
            </section>
        </main>

        <footer class="mx-auto flex w-full max-w-6xl flex-col gap-2 px-5 pb-8 text-xs font-semibold text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <span>jejakcita.id</span>
            <span>Admin & Owner Console</span>
        </footer>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('passwordInput');

            if (!togglePassword || !passwordInput) {
                return;
            }

            togglePassword.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';

                passwordInput.type = isPassword ? 'text' : 'password';
                this.innerHTML = isPassword
                    ? '<i class="fa-solid fa-eye-slash"></i>'
                    : '<i class="fa-solid fa-eye"></i>';
            });
        });
    </script>
@endpush
