@extends('layouts.auth')

@section('title', 'Masuk - jejakcita.id')

@section('content')
    <div class="min-h-screen text-gray-950" style="background:#f6f8fb">
        <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 py-6">
            <a href="{{ route('login') }}" class="inline-flex items-center">
                <img src="{{ asset('logo.png') }}" alt="jejakcita.id" class="h-11 w-auto">
            </a>

            <a href="{{ route('register') }}"
                class="hidden rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold shadow-sm transition hover:border-gray-300 sm:inline-flex"
                style="color:#1a2d6b">
                Daftar sekarang
            </a>
        </header>

        <main class="mx-auto grid w-full max-w-6xl gap-8 px-5 pb-10 pt-4 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:pt-10">
            <section class="max-w-xl">

                <h1 class="mt-4 text-4xl font-bold leading-tight sm:text-5xl" style="color:#0f1f3d">
                    Masuk dan pantau potensi karir siswa Anda.
                </h1>

                <p class="mt-5 max-w-lg text-[0.975rem] leading-[1.75] text-gray-500">
                    jejakcita.id membantu sekolah memetakan minat, bakat, dan arah karir siswa — dari tes online hingga laporan yang siap dibagikan.
                </p>

                <div class="mt-8 grid gap-4 text-sm text-gray-500 sm:grid-cols-2">
                    <div class="border-l-2 pl-4" style="border-color:#1a2d6b">
                        <p class="font-semibold" style="color:#0f1f3d">Admin dan owner</p>
                        <p class="mt-1 leading-6">Masuk memakai email terdaftar.</p>
                    </div>

                    <div class="border-l-2 pl-4" style="border-color:#c9a227">
                        <p class="font-semibold" style="color:#0f1f3d">Portal siswa terpisah</p>
                        <p class="mt-1 leading-6">Siswa memakai link khusus dari sekolah.</p>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-8 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest" style="color:#c9a227">Masuk</p>
                        <h2 class="mt-1 text-2xl font-bold" style="color:#0f1f3d">Akses panel</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-400">Gunakan email admin atau owner untuk melanjutkan.</p>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-100 bg-gray-50 text-gray-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-600">
                        <p class="font-semibold">Login gagal</p>
                        <p class="mt-1 font-normal">{{ $errors->first('login') ?: 'Periksa kembali email dan password.' }}</p>
                        @if(str_contains($errors->first('login') ?? '', 'OTP'))
                            <form method="POST" action="{{ route('auth.email-otp.resend') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="email" value="{{ old('login') }}">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-red-600 underline underline-offset-4 hover:text-red-800">
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
                        <label class="block text-sm font-medium text-gray-700" for="loginEmail">Email</label>
                        <div class="relative mt-2">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input id="loginEmail" type="email" name="login" value="{{ old('login') }}" required autofocus
                                placeholder="admin@sekolah.sch.id"
                                class="w-full rounded-lg border border-gray-200 bg-white py-3.5 pl-11 pr-4 text-gray-900 outline-none transition placeholder:text-gray-300"
                                style="focus-within:border-color:#c9a227">
                        </div>

                        @error('login')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <label class="block text-sm font-medium text-gray-700" for="passwordInput">Password</label>
                            <a href="{{ route('password.request') }}" class="text-sm font-medium hover:opacity-80" style="color:#c9a227">
                                Lupa password?
                            </a>
                        </div>

                        <div class="relative mt-2">
                            <i class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input id="passwordInput" type="password" name="password" required
                                placeholder="Masukkan password"
                                class="w-full rounded-lg border border-gray-200 bg-white py-3.5 pl-11 pr-12 text-gray-900 outline-none transition placeholder:text-gray-300">

                            <button type="button" id="togglePassword"
                                class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-md text-gray-300 transition hover:bg-gray-100 hover:text-gray-600"
                                aria-label="Tampilkan password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>

                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3.5 text-sm font-bold text-white shadow-sm transition focus:outline-none"
                        style="background:#1a2d6b; hover:background:#111e4a"
                        onmouseover="this.style.background='#111e4a'" onmouseout="this.style.background='#1a2d6b'">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Masuk ke panel
                    </button>
                </form>

                <div class="mt-5 rounded-lg border border-gray-100 bg-gray-50 px-4 py-3.5 text-sm text-gray-500">
                    Belum punya akses?
                    <a href="{{ route('register') }}" class="font-semibold hover:opacity-80" style="color:#c9a227">
                        Daftar sekarang
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
