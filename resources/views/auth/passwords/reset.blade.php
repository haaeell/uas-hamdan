@extends('layouts.auth')

@section('title', 'Buat Password Baru - jejakcita.id')

@section('content')
    <div class="min-h-screen bg-[#f7f8fa] text-slate-950">
        <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 py-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-950 text-sm font-black text-white">
                    JC
                </span>
                <span>
                    <span class="block text-base font-extrabold leading-5 tracking-tight">jejakcita.id</span>
                    <span class="block text-xs font-semibold text-slate-500">Password Reset</span>
                </span>
            </a>

            <a href="{{ route('login') }}"
                class="hidden rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950 sm:inline-flex">
                Kembali login
            </a>
        </header>

        <main class="mx-auto grid w-full max-w-6xl gap-8 px-5 pb-10 pt-4 lg:grid-cols-[0.9fr_1.1fr] lg:items-start lg:pt-10">
            <section class="max-w-xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-blue-700">Password baru</p>

                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-slate-950 sm:text-5xl">
                    Amankan kembali akun Anda.
                </h1>

                <p class="mt-5 max-w-lg text-base leading-7 text-slate-600">
                    Buat password baru untuk akun admin atau owner. Gunakan minimal delapan karakter.
                </p>

                <div class="mt-8 space-y-3 text-sm text-slate-600">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-check text-blue-700"></i>
                        <span>Minimal 8 karakter.</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-check text-blue-700"></i>
                        <span>Hindari password yang sama dengan email.</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-check text-blue-700"></i>
                        <span>Simpan akses hanya untuk pemilik akun.</span>
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-8 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-blue-700">Reset password</p>
                        <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-950">Buat password baru</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Isi email dan password baru.</p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600">
                        <i class="fa-solid fa-lock-open"></i>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <p class="font-extrabold">Terjadi kesalahan</p>
                        <p class="mt-1">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label class="block text-sm font-bold text-slate-700" for="resetAccountEmail">Email</label>
                        <div class="relative mt-2">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="resetAccountEmail" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus
                                placeholder="owner@sekolah.sch.id"
                                class="w-full rounded-lg border border-slate-200 bg-white py-3.5 pl-11 pr-4 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700" for="passwordInput">Password baru</label>
                        <div class="relative mt-2">
                            <i class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="passwordInput" type="password" name="password" required
                                placeholder="Masukkan password baru"
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

                    <div>
                        <label class="block text-sm font-bold text-slate-700" for="passwordConfirmInput">Konfirmasi password</label>
                        <div class="relative mt-2">
                            <i class="fa-solid fa-shield-halved absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="passwordConfirmInput" type="password" name="password_confirmation" required
                                placeholder="Ulangi password baru"
                                class="w-full rounded-lg border border-slate-200 bg-white py-3.5 pl-11 pr-12 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100">

                            <button type="button" id="togglePasswordConfirm"
                                class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-md text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                                aria-label="Tampilkan konfirmasi password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-slate-950 px-5 py-3.5 font-extrabold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan password baru
                    </button>
                </form>

                <div class="mt-6 rounded-lg border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600">
                    Batal mengganti password?
                    <a href="{{ route('login') }}" class="font-extrabold text-blue-700 hover:text-blue-900">
                        Kembali ke login
                    </a>
                </div>
            </section>
        </main>

        <footer class="mx-auto flex w-full max-w-6xl flex-col gap-2 px-5 pb-8 text-xs font-semibold text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <span>jejakcita.id</span>
            <span>Password Reset</span>
        </footer>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function setupToggle(inputId, buttonId) {
                const input = document.getElementById(inputId);
                const button = document.getElementById(buttonId);

                if (!input || !button) {
                    return;
                }

                button.addEventListener('click', function () {
                    const isPassword = input.type === 'password';

                    input.type = isPassword ? 'text' : 'password';
                    this.innerHTML = isPassword
                        ? '<i class="fa-solid fa-eye-slash"></i>'
                        : '<i class="fa-solid fa-eye"></i>';
                });
            }

            setupToggle('passwordInput', 'togglePassword');
            setupToggle('passwordConfirmInput', 'togglePasswordConfirm');
        });
    </script>
@endpush
