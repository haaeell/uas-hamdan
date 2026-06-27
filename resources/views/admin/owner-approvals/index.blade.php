@extends('layouts.admin')

@section('title', 'Persetujuan Owner')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-xs font-bold text-orange-500 uppercase tracking-widest mb-1">Akses Panel</p>
                    <h1 class="text-2xl font-extrabold text-slate-900">Persetujuan Owner</h1>
                    <p class="text-sm text-slate-500 mt-1 max-w-xl">
                        Setiap owner baru masuk sebagai pengajuan. Admin dapat menyetujui, menonaktifkan, dan mengaktifkan kembali akses owner.
                    </p>
                </div>

                {{-- Stats --}}
                <div class="flex gap-3 flex-wrap shrink-0">
                    <div class="flex flex-col items-center justify-center rounded-xl border border-amber-200 bg-amber-50 px-5 py-3 min-w-[90px]">
                        <span class="text-2xl font-extrabold text-amber-600">{{ $verificationPendingOwners->count() }}</span>
                        <span class="text-[10px] font-bold text-amber-500 uppercase tracking-wide mt-0.5 text-center">Menunggu OTP</span>
                    </div>
                    <div class="flex flex-col items-center justify-center rounded-xl border border-blue-200 bg-blue-50 px-5 py-3 min-w-[90px]">
                        <span class="text-2xl font-extrabold text-blue-600">{{ $pendingOwners->count() }}</span>
                        <span class="text-[10px] font-bold text-blue-500 uppercase tracking-wide mt-0.5 text-center">Pending</span>
                    </div>
                    <div class="flex flex-col items-center justify-center rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3 min-w-[90px]">
                        <span class="text-2xl font-extrabold text-emerald-600">{{ $approvedOwners->count() }}</span>
                        <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-wide mt-0.5 text-center">Disetujui</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columns --}}
        <div class="grid xl:grid-cols-3 gap-5">

            {{-- Menunggu Verifikasi Email --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 bg-amber-50">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                        <i class="fa-solid fa-envelope-open-text text-amber-500 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Menunggu Verifikasi Email</h2>
                        <p class="text-xs text-slate-400">{{ $verificationPendingOwners->count() }} owner belum verifikasi</p>
                    </div>
                </div>
                <div class="p-4 space-y-3">
                    @forelse($verificationPendingOwners as $item)
                        <div class="rounded-xl border border-amber-100 bg-amber-50/60 p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-amber-200 flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-bold text-amber-700">{{ strtoupper(substr($item['owner']->name, 0, 1)) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 text-sm truncate">{{ $item['owner']->name }}</div>
                                    <div class="text-xs text-slate-400 truncate">{{ $item['owner']->email }}</div>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-clock text-amber-500 text-xs"></i>
                                <span class="text-xs font-semibold text-amber-600">Belum memverifikasi OTP email</span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 py-10 text-center">
                            <i class="fa-solid fa-envelope-circle-check text-slate-300 text-2xl mb-2"></i>
                            <p class="text-sm text-slate-400">Tidak ada owner yang menunggu OTP.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Pengajuan Menunggu --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 bg-blue-50">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="fa-solid fa-user-clock text-blue-500 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Pengajuan Menunggu</h2>
                        <p class="text-xs text-slate-400">{{ $pendingOwners->count() }} pengajuan baru</p>
                    </div>
                </div>
                <div class="p-4 space-y-3">
                    @forelse($pendingOwners as $item)
                        <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-bold text-slate-600">{{ strtoupper(substr($item['owner']->name, 0, 1)) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 text-sm truncate">{{ $item['owner']->name }}</div>
                                    <div class="text-xs text-slate-400 truncate">{{ $item['owner']->email }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        Diajukan {{ $item['owner']->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.owner-approvals.approve', $item['owner']) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                                    <i class="fa-solid fa-check"></i>
                                    Setujui
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 py-10 text-center">
                            <i class="fa-solid fa-user-check text-slate-300 text-2xl mb-2"></i>
                            <p class="text-sm text-slate-400">Tidak ada pengajuan owner yang menunggu.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Owner Terdaftar --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 bg-emerald-50">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i class="fa-solid fa-users text-emerald-500 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Owner Terdaftar</h2>
                        <p class="text-xs text-slate-400">{{ $approvedOwners->count() }} owner aktif/nonaktif</p>
                    </div>
                </div>
                <div class="p-4 space-y-3">
                    @forelse($approvedOwners as $item)
                        <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center
                                    {{ $item['owner']->is_active ? 'bg-emerald-200' : 'bg-slate-200' }}">
                                    <span class="text-sm font-bold {{ $item['owner']->is_active ? 'text-emerald-700' : 'text-slate-500' }}">
                                        {{ strtoupper(substr($item['owner']->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <div class="font-bold text-slate-900 text-sm truncate">{{ $item['owner']->name }}</div>
                                        @if($item['owner']->is_active)
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-extrabold text-emerald-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-extrabold text-slate-500">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-400 truncate mt-0.5">{{ $item['owner']->email }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        Disetujui {{ $item['owner']->approved_at?->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                @if($item['owner']->is_active)
                                    <form method="POST" action="{{ route('admin.owner-approvals.deactivate', $item['owner']) }}"
                                        onsubmit="return confirm('Nonaktifkan owner ini? Owner akan logout pada request berikutnya dan tidak bisa login sampai diaktifkan kembali.')">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600 transition">
                                            <i class="fa-solid fa-ban"></i>
                                            Nonaktifkan
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.owner-approvals.activate', $item['owner']) }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700 transition">
                                            <i class="fa-solid fa-check"></i>
                                            Aktifkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 py-10 text-center">
                            <i class="fa-solid fa-user-slash text-slate-300 text-2xl mb-2"></i>
                            <p class="text-sm text-slate-400">Belum ada owner yang disetujui.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection
