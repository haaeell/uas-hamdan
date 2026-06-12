@extends('layouts.admin')

@section('title', 'Persetujuan Owner')

@section('content')
    <div class="space-y-8">
        <div class="bg-white border border-slate-200 rounded-[28px] p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Akses Panel</p>
                    <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Persetujuan Owner</h1>
                    <p class="text-sm text-slate-500 mt-2 max-w-3xl">
                        Setiap owner baru masuk sebagai pengajuan. Admin bisa menyetujui, menonaktifkan, dan mengaktifkan kembali akses owner.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 px-4 py-3">
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">Pending</div>
                        <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $pendingOwners->count() }}</div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 px-4 py-3">
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">Disetujui</div>
                        <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $approvedOwners->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid xl:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200 rounded-[28px] p-6 shadow-sm">
                <h2 class="text-xl font-extrabold text-slate-900 mb-4">Pengajuan Menunggu</h2>
                <div class="space-y-4">
                    @forelse($pendingOwners as $item)
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="font-extrabold text-slate-900">{{ $item['owner']->name }}</div>
                                    <div class="text-sm text-slate-500 mt-1">{{ $item['owner']->email }}</div>
                                    <div class="text-xs text-slate-400 mt-2">
                                        Diajukan {{ $item['owner']->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.owner-approvals.approve', $item['owner']) }}">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-2xl font-bold transition">
                                        <i class="fa-solid fa-check"></i>
                                        Setujui
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-500">
                            Tidak ada pengajuan owner yang menunggu.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-[28px] p-6 shadow-sm">
                <h2 class="text-xl font-extrabold text-slate-900 mb-4">Owner Terdaftar</h2>
                <div class="space-y-4">
                    @forelse($approvedOwners as $item)
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="font-extrabold text-slate-900">{{ $item['owner']->name }}</div>
                                    <div class="text-sm text-slate-500 mt-1">{{ $item['owner']->email }}</div>
                                    <div class="text-xs text-slate-400 mt-2">
                                        Disetujui {{ $item['owner']->approved_at?->format('d M Y, H:i') }}
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-3">
                                    @if($item['owner']->is_active)
                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold text-blue-700">
                                            Aktif
                                        </span>

                                        <form method="POST" action="{{ route('admin.owner-approvals.deactivate', $item['owner']) }}"
                                            onsubmit="return confirm('Nonaktifkan owner ini? Owner akan logout pada request berikutnya dan tidak bisa login sampai diaktifkan kembali.')">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-2xl bg-red-50 px-4 py-2 text-sm font-bold text-red-700 border border-red-100 hover:bg-red-600 hover:text-white hover:border-red-600 transition">
                                                <i class="fa-solid fa-ban"></i>
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-500">
                                            Nonaktif
                                        </span>

                                        <form method="POST" action="{{ route('admin.owner-approvals.activate', $item['owner']) }}">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-4 py-2 text-sm font-bold text-white hover:bg-blue-700 transition">
                                                <i class="fa-solid fa-check"></i>
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-500">
                            Belum ada owner yang disetujui.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
