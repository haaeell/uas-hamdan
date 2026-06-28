@extends('layouts.auth')

@section('title', 'Lupa Password - jejakcita.id')

@section('content')
    <div class="min-h-screen bg-[#f7f8fa] text-slate-950">
        <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 py-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-950 text-sm font-black text-white">
                    JC
                </span>
                <span>
                    <span class="block text-base font-extrabold leading-5 tracking-tight">jejakcita.id</span>
                    <span class="block text-xs font-semibold text-slate-500">Account Recovery</span>
                </span>
            </a>

            <a href="{{ route('login') }}"
                class="hidden rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950 sm:inline-flex">
                Kembali login
            </a>
        </header>

        <main class="mx-auto grid w-full max-w-6xl gap-8 px-5 pb-10 pt-4 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:pt-10">
            <section class="max-w-xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-blue-700">Reset password</p>

                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-slate-950 sm:text-5xl">
                    Pulihkan akses panel.
                </h1>

                <p class="mt-5 max-w-lg text-base leading-7 text-slate-600">
                    Masukkan email admin atau owner yang terdaftar. Sistem akan mengirim tautan untuk membuat password baru.
                </p>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-8 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-bold text-blue-700">Lupa password</p>
                        <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-950">Kirim link reset</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Gunakan email yang masih aktif.</p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600">
                        <i class="fa-solid fa-key"></i>
                    </div>
                </div>

                @if (session('status'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        <p class="font-extrabold">Email terkirim</p>
                        <p class="mt-1">{{ session('status') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <p class="font-extrabold">Terjadi kesalahan</p>
                        <p class="mt-1">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-slate-700" for="resetEmail">Email</label>
                        <div class="relative mt-2">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="resetEmail" type="email" name="email" value="{{ old('email') }}" required autofocus
                                placeholder="owner@sekolah.sch.id"
                                class="w-full rounded-lg border border-slate-200 bg-white py-3.5 pl-11 pr-4 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-slate-950 px-5 py-3.5 font-extrabold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200">
                        <i class="fa-solid fa-paper-plane"></i>
                        Kirim link reset
                    </button>
                </form>

                <div class="mt-6 rounded-lg border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600">
                    Ingat password?
                    <a href="{{ route('login') }}" class="font-extrabold text-blue-700 hover:text-blue-900">
                        Masuk ke panel
                    </a>
                </div>
            </section>
        </main>

        <footer class="mx-auto flex w-full max-w-6xl flex-col gap-2 px-5 pb-8 text-xs font-semibold text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <span>jejakcita.id</span>
            <span>Account Recovery</span>
        </footer>
    </div>
@endsection
