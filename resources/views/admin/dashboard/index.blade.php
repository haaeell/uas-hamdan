@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    {{-- Welcome Section --}}
    <div class="mb-8">
        <div class="relative overflow-hidden rounded-[28px] p-6 md:p-8 text-white shadow-xl"
            style="background: linear-gradient(135deg, var(--theme-color) 0%, color-mix(in srgb, var(--theme-color) 82%, #0f172a) 58%, color-mix(in srgb, var(--theme-color) 68%, #ffffff) 100%); box-shadow: 0 22px 45px color-mix(in srgb, var(--theme-color) 22%, transparent);">
            <div class="absolute -right-14 -top-16 h-44 w-44 rounded-full bg-white/15"></div>
            <div class="absolute -bottom-20 right-20 h-52 w-52 rounded-full bg-white/10"></div>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="relative">
                    <p class="text-white/80 text-sm font-semibold uppercase tracking-wide">Laporan Owner</p>
                    <h1 class="text-2xl md:text-3xl font-extrabold mt-2">
                        Panel Administrasi
                    </h1>
                    <p class="text-white/80 mt-2 max-w-2xl">
                        Pantau jumlah owner, data yang mereka buat, dan aktivitas terakhir secara cepat.
                    </p>
                </div>

                <div class="relative bg-white/15 backdrop-blur-xl rounded-3xl px-5 py-4 border border-white/20">
                    <p class="text-xs text-white/75">Status</p>
                    <p class="font-bold text-lg">{{ $canMonitorOwners ? 'Semua Owner' : 'Panel Aktif' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white border border-slate-200 rounded-[26px] p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Total Owner</p>
            <h2 class="text-4xl font-extrabold text-slate-900 mt-3">{{ $systemStats['total_owners'] }}</h2>
            <p class="text-sm text-slate-500 mt-4">Panel yang terdaftar di sistem.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-[26px] p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Owner Aktif</p>
            <h2 class="text-4xl font-extrabold text-slate-900 mt-3">{{ $systemStats['active_owners'] }}</h2>
            <p class="text-sm text-slate-500 mt-4">Owner yang sedang aktif.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-[26px] p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Total Data</p>
            <h2 class="text-4xl font-extrabold text-slate-900 mt-3">{{ $systemStats['total_records'] }}</h2>
            <p class="text-sm text-slate-500 mt-4">Akumulasi data yang dibuat owner.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-[26px] p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Aktivitas</p>
            <h2 class="text-4xl font-extrabold text-slate-900 mt-3">{{ $systemStats['total_activities'] }}</h2>
            <p class="text-sm text-slate-500 mt-4">Log aktivitas yang tercatat.</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-[28px] p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Laporan Owner</p>
                <h2 class="text-xl font-extrabold text-slate-900 mt-1">
                    {{ $canMonitorOwners ? 'Daftar Semua Owner' : 'Ringkasan Panel Anda' }}
                </h2>
                <p class="text-sm text-slate-500 mt-2">
                    Lihat data yang dibuat, aktivitas terakhir, dan status setiap owner.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-extrabold uppercase tracking-wide text-slate-400 border-b border-slate-100">
                        <th class="py-3 pr-4">Owner</th>
                        <th class="py-3 px-4">Data Dibuat</th>
                        <th class="py-3 px-4">Aktivitas Terakhir</th>
                        <th class="py-3 px-4">Total Log</th>
                        <th class="py-3 pl-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ownerMonitoring as $item)
                        <tr>
                            <td class="py-4 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-extrabold">
                                        {{ strtoupper(substr($item['owner']->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-extrabold text-slate-900 whitespace-nowrap">{{ $item['owner']->name }}</p>
                                        <p class="text-xs text-slate-500 whitespace-nowrap">{{ $item['owner']->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm font-bold text-slate-700">
                                    Siswa {{ $item['counts']['students'] }} · Jurusan {{ $item['counts']['packages'] }} · Sesi {{ $item['counts']['sessions'] }}
                                </div>
                                <div class="text-sm font-bold text-slate-700 mt-1">
                                    Soal {{ $item['counts']['questions'] }} · Pengumuman {{ $item['counts']['announcements'] }} · Hasil {{ $item['counts']['results'] }} · Pelanggaran {{ $item['counts']['violations'] }}
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-700">{{ $item['last_activity'] }}</div>
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ $item['last_activity_at'] ? $item['last_activity_at']->format('d M Y, H:i') : '-' }}
                                </div>
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-700">{{ $item['counts']['activities'] }}</td>
                            <td class="py-4 pl-4">
                                @if(!$item['owner']->email_verified_at)
                                    <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-extrabold text-amber-700">OTP</span>
                                @elseif(!$item['owner']->approved_at)
                                    <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-extrabold text-amber-700">Menunggu</span>
                                @elseif($item['owner']->is_active)
                                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold text-blue-700">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-500">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm font-semibold text-slate-500">
                                Belum ada owner terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    </script>
@endpush
