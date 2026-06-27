@extends('layouts.admin')

@section('title', 'Dashboard Owner')

@section('content')
    <div class="mb-8">
        <div class="relative overflow-hidden rounded-[28px] p-6 md:p-8 text-white shadow-xl"
            style="background: linear-gradient(135deg, var(--theme-color) 0%, color-mix(in srgb, var(--theme-color) 82%, #0f172a) 58%, color-mix(in srgb, var(--theme-color) 68%, #ffffff) 100%); box-shadow: 0 22px 45px color-mix(in srgb, var(--theme-color) 22%, transparent);">
            <div class="absolute -right-14 -top-16 h-44 w-44 rounded-full bg-white/15"></div>
            <div class="absolute -bottom-20 right-20 h-52 w-52 rounded-full bg-white/10"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-white/80 text-sm font-semibold uppercase tracking-wide">Ringkasan Panel</p>
                    <h1 class="text-2xl md:text-3xl font-extrabold mt-2">
                        Dashboard Pemilihan Jurusan
                    </h1>
                    <p class="text-white/80 mt-2 max-w-2xl">
                        Pantau data siswa, sesi tes, instrumen peminatan, hasil, dan aktivitas panel Anda.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="bg-white/15 backdrop-blur-xl rounded-3xl px-5 py-4 border border-white/20">
                        <p class="text-xs text-white/75">Status Panel</p>
                        <p class="font-bold text-lg">Aktif</p>
                    </div>

                    <button type="button" onclick="document.getElementById('resetOwnerModal').classList.remove('hidden'); document.getElementById('resetOwnerModal').classList.add('flex')"
                        class="bg-white/10 hover:bg-red-500/80 backdrop-blur-xl rounded-3xl px-5 py-4 border border-white/20 hover:border-red-400 transition-all text-left">
                        <p class="text-xs text-white/75">Bahaya</p>
                        <p class="font-bold text-lg flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-sm"></i> Reset Data</p>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        @foreach([
            ['label' => 'Total Siswa', 'value' => $totalStudents, 'icon' => 'fa-users', 'note' => 'Data siswa terdaftar'],
            ['label' => 'Selesai Tes', 'value' => $completedStudents, 'icon' => 'fa-circle-check', 'note' => 'Siswa selesai mengikuti tes'],
            ['label' => 'Sesi Aktif', 'value' => $activeSessions, 'icon' => 'fa-clock', 'note' => 'Sesi tes sedang aktif'],
            ['label' => 'Pelanggaran', 'value' => $totalViolations, 'icon' => 'fa-shield-halved', 'note' => 'Catatan pelanggaran CBT'],
        ] as $card)
            <div class="group bg-white border border-slate-200 rounded-[26px] p-6 shadow-sm hover:shadow-xl hover:shadow-blue-100 transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">{{ $card['label'] }}</p>
                        <h2 class="text-4xl font-extrabold text-slate-900 mt-3">{{ $card['value'] }}</h2>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fa-solid {{ $card['icon'] }} text-xl"></i>
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-2 text-sm text-slate-500">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    {{ $card['note'] }}
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid md:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('admin.students.index') }}" class="bg-white border border-slate-200 rounded-[24px] p-5 shadow-sm hover:shadow-lg hover:shadow-blue-100 transition">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div class="font-extrabold text-slate-900">Kelola Siswa</div>
            <div class="text-sm text-slate-500 mt-1">Tambah, import, dan aktifkan akun siswa.</div>
        </a>

        <a href="{{ route('admin.psychology-questions.index') }}" class="bg-white border border-slate-200 rounded-[24px] p-5 shadow-sm hover:shadow-lg hover:shadow-blue-100 transition">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <i class="fa-solid fa-brain"></i>
            </div>
            <div class="font-extrabold text-slate-900">Soal Instrumen</div>
            <div class="text-sm text-slate-500 mt-1">{{ $totalQuestions }} soal tersimpan.</div>
        </a>

        <a href="{{ route('admin.test-sessions.index') }}" class="bg-white border border-slate-200 rounded-[24px] p-5 shadow-sm hover:shadow-lg hover:shadow-blue-100 transition">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div class="font-extrabold text-slate-900">Sesi Tes</div>
            <div class="text-sm text-slate-500 mt-1">Atur jadwal dan kelas peserta.</div>
        </a>

        <a href="{{ route('admin.reports.index') }}" class="bg-white border border-slate-200 rounded-[24px] p-5 shadow-sm hover:shadow-lg hover:shadow-blue-100 transition">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <i class="fa-solid fa-file-arrow-down"></i>
            </div>
            <div class="font-extrabold text-slate-900">Laporan</div>
            <div class="text-sm text-slate-500 mt-1">{{ $totalResults }} hasil tes tersedia.</div>
        </a>
    </div>

    <div class="grid xl:grid-cols-2 gap-6 mb-8">
        <div class="bg-white border border-slate-200 rounded-[28px] p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Instrumen Peminatan</p>
                    <h2 class="text-xl font-extrabold text-slate-900 mt-1">Distribusi Rekomendasi</h2>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-chart-column"></i>
                </div>
            </div>
            <div class="h-[320px]">
                <canvas id="recommendationChart"></canvas>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-[28px] p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide">Jurusan</p>
                    <h2 class="text-xl font-extrabold text-slate-900 mt-1">Preferensi Jurusan</h2>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
            </div>
            <div class="h-[320px] flex items-center justify-center">
                <canvas id="packageChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid xl:grid-cols-2 gap-6">
        <div class="bg-white border border-slate-200 rounded-[28px] p-6 shadow-sm">
            <h2 class="text-xl font-extrabold text-slate-900 mb-4">Sesi Aktif Terdekat</h2>
            <div class="space-y-3">
                @forelse($upcomingSessions as $session)
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <div class="font-extrabold text-slate-900">{{ $session->name }}</div>
                        <div class="text-sm text-slate-500 mt-1">
                            {{ \Illuminate\Support\Carbon::parse($session->test_date)->format('d M Y') }} · {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }}
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 p-6 text-center text-sm text-slate-500">
                        Belum ada sesi aktif.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-[28px] p-6 shadow-sm">
            <h2 class="text-xl font-extrabold text-slate-900 mb-4">Aktivitas Terbaru</h2>
            <div class="space-y-3">
                @forelse($recentActivities as $activity)
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <div class="font-extrabold text-slate-900">{{ $activity->display_label }}</div>
                        <div class="text-sm text-slate-500 mt-1">
                            {{ $activity->user?->name ?? 'Sistem' }} · {{ $activity->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 p-6 text-center text-sm text-slate-500">
                        Belum ada aktivitas tercatat.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

{{-- Modal Reset Data Owner --}}
<div id="resetOwnerModal" class="hidden fixed inset-0 bg-slate-900/70 backdrop-blur-sm z-50 items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
            </div>
            <div>
                <h2 class="font-extrabold text-slate-900 text-lg">Reset Semua Data</h2>
                <p class="text-sm text-slate-500">Tindakan ini tidak bisa dibatalkan</p>
            </div>
        </div>

        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700 mb-5 space-y-1">
            <p class="font-bold mb-2">Data yang akan dihapus permanen:</p>
            <ul class="list-disc list-inside space-y-0.5">
                <li>Seluruh data siswa & akun login siswa</li>
                <li>Sesi tes & jawaban siswa</li>
                <li>Hasil tes & distribusi kelas</li>
                <li>Pelanggaran & pengumuman</li>
                <li>Soal instrumen peminatan</li>
                <li>Jurusan (paket) & mata pelajaran</li>
                <li>Log aktivitas</li>
            </ul>
            <p class="font-semibold mt-2 text-red-800">Semua data akan dihapus permanen.</p>
        </div>

        <form method="POST" action="{{ route('admin.dashboard.reset-owner-data') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Ketik <span class="font-bold text-red-600">RESET</span> untuk konfirmasi
                </label>
                <input type="text" name="confirmation" placeholder="RESET" autocomplete="off"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 focus:border-red-400 focus:ring-4 focus:ring-red-100 outline-none transition font-mono tracking-widest">
            </div>

            <div class="flex gap-3">
                <button type="button"
                    onclick="document.getElementById('resetOwnerModal').classList.add('hidden'); document.getElementById('resetOwnerModal').classList.remove('flex')"
                    class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold transition">
                    Ya, Reset Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        const themeColor = getComputedStyle(document.documentElement).getPropertyValue('--theme-color').trim() || '#2563eb';

        function mixColor(hex, mixHex, weight = 0.5) {
            const normalize = (value) => value.replace('#', '');
            const first = normalize(hex);
            const second = normalize(mixHex);
            const result = [0, 2, 4].map((offset) => {
                const colorA = parseInt(first.substring(offset, offset + 2), 16);
                const colorB = parseInt(second.substring(offset, offset + 2), 16);

                return Math.round(colorA * (1 - weight) + colorB * weight)
                    .toString(16)
                    .padStart(2, '0');
            });

            return `#${result.join('')}`;
        }

        const themePalette = [
            themeColor,
            mixColor(themeColor, '#ffffff', 0.18),
            mixColor(themeColor, '#ffffff', 0.34),
            mixColor(themeColor, '#0f172a', 0.18),
            mixColor(themeColor, '#0f172a', 0.34),
            mixColor(themeColor, '#ffffff', 0.5),
            mixColor(themeColor, '#0f172a', 0.5),
            '#0f172a'
        ];

        new Chart(document.getElementById('recommendationChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($recommendationDistribution)) !!},
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: {!! json_encode(array_values($recommendationDistribution)) !!},
                    backgroundColor: themeColor,
                    borderRadius: 14,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {grid: {display: false}, ticks: {color: '#64748b'}},
                    y: {beginAtZero: true, grid: {color: '#e2e8f0'}, ticks: {color: '#64748b', precision: 0}}
                }
            }
        });

        new Chart(document.getElementById('packageChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($packagePreferences->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($packagePreferences->map(fn($p) => $p->first_choices_count + $p->second_choices_count)) !!},
                    backgroundColor: themePalette,
                    borderColor: '#ffffff',
                    borderWidth: 4,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#475569',
                            padding: 18,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                }
            }
        });
    </script>
@endpush
