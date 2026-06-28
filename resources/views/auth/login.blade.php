@extends('layouts.auth')

@section('title', 'Masuk - jejakcita.id')

@section('content')
    <div class="min-h-screen bg-[#f8f9fb] text-gray-950">
        <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 py-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-950 text-xs font-bold text-white">
                    JC
                </span>
                <span>
                    <span class="block text-[0.95rem] font-extrabold leading-tight tracking-tight text-gray-950">jejakcita.id</span>
                    <span class="block text-[0.7rem] font-medium text-gray-400">Panel karir siswa</span>
                </span>
            </a>

            <a href="{{ route('register') }}"
                class="hidden rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 shadow-sm transition hover:border-gray-300 hover:text-gray-950 sm:inline-flex">
                Daftar sekarang
            </a>
        </header>

        <main class="mx-auto grid w-full max-w-6xl gap-8 px-5 pb-10 pt-4 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:pt-10">
            <section class="max-w-xl">

                <h1 class="mt-4 text-4xl font-bold leading-tight text-gray-950 sm:text-5xl">
                    Masuk dan pantau potensi karir siswa Anda.
                </h1>

                <p class="mt-5 max-w-lg text-[0.975rem] leading-[1.75] text-gray-500">
                    jejakcita.id membantu sekolah memetakan minat, bakat, dan arah karir siswa — dari tes online hingga laporan yang siap dibagikan.
                </p>

                <div class="mt-8 grid gap-4 text-sm text-gray-500 sm:grid-cols-2">
                    <div class="border-l-2 border-gray-950 pl-4">
                        <p class="font-semibold text-gray-900">Admin dan owner</p>
                        <p class="mt-1 leading-6">Masuk memakai email terdaftar.</p>
                    </div>

                    <div class="border-l-2 border-blue-500 pl-4">
                        <p class="font-semibold text-gray-900">Portal siswa terpisah</p>
                        <p class="mt-1 leading-6">Siswa memakai link khusus dari sekolah.</p>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-8 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">Masuk</p>
                        <h2 class="mt-1 text-2xl font-bold text-gray-950">Akses panel</h2>
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
                                class="w-full rounded-lg border border-gray-200 bg-white py-3.5 pl-11 pr-4 text-gray-900 outline-none transition placeholder:text-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-50">
                        </div>

                        @error('login')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <label class="block text-sm font-medium text-gray-700" for="passwordInput">Password</label>
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                Lupa password?
                            </a>
                        </div>

                        <div class="relative mt-2">
                            <i class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input id="passwordInput" type="password" name="password" required
                                placeholder="Masukkan password"
                                class="w-full rounded-lg border border-gray-200 bg-white py-3.5 pl-11 pr-12 text-gray-900 outline-none transition placeholder:text-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-50">

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
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gray-950 px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-200">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Masuk ke panel
                    </button>
                </form>

                <div class="mt-5 rounded-lg border border-gray-100 bg-gray-50 px-4 py-3.5 text-sm text-gray-500">
                    Belum punya akses?
                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-800">
                        Daftar sekarang
                    </a>
                </div>
            </section>
        </main>

        <footer class="mx-auto flex w-full max-w-6xl flex-col gap-2 px-5 pb-8 text-xs font-medium text-gray-400 sm:flex-row sm:items-center sm:justify-between">
            <span class="font-semibold text-gray-900">jejakcita.id</span>
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
