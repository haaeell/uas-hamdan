@extends('layouts.auth')

@section('content')
    @php
        $appName = \App\Models\Setting::getSetting('app_name', 'Sistem Pemilihan Jurusan');
        $logoUrl = \App\Models\Setting::logoUrl();
    @endphp

    <div class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-[32px] p-6 sm:p-8 shadow-xl shadow-blue-100">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center overflow-hidden p-2">
                    <img src="{{ $logoUrl }}" alt="{{ $appName }}" class="w-full h-full object-contain">
                </div>
                <div>
                    <p class="text-sm font-bold text-blue-600 uppercase tracking-wide">Daftar Owner</p>
                    <h1 class="text-2xl font-extrabold text-slate-900">Buat Panel Baru</h1>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Owner</label>
                    <input name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">
                    @error('name')<p class="text-sm text-blue-700 mt-2 font-semibold">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">
                    @error('email')<p class="text-sm text-blue-700 mt-2 font-semibold">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">
                    @error('password')<p class="text-sm text-blue-700 mt-2 font-semibold">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">
                </div>

                <button class="w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold shadow-lg shadow-blue-200 transition">
                    <i class="fa-solid fa-user-plus"></i>
                    Buat Panel Owner
                </button>
            </form>

            <a href="{{ route('login') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 text-sm font-bold text-blue-700">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Login
            </a>
        </div>
    </div>
@endsection
