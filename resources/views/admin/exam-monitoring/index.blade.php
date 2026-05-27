@extends('layouts.admin')

@section('title', 'Monitoring Ujian')

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Monitoring Ujian</h1>
                <p class="text-slate-500 mt-2">Pantau siswa yang sedang mengerjakan tes secara real-time berbasis data server.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="rounded-2xl bg-slate-50 border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-600">
                    Update terakhir: {{ now()->format('d M Y H:i:s') }}
                </div>

                <button type="button" onclick="window.location.reload()"
                    class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-2xl font-bold shadow-lg shadow-blue-200 transition">
                    <i class="fa-solid fa-rotate"></i>
                    Refresh
                </button>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="bg-white border border-slate-200 rounded-[26px] p-6 shadow-sm">
                <div class="text-sm font-semibold text-slate-500">Sedang Ujian</div>
                <div class="text-4xl font-extrabold text-slate-900 mt-3">{{ $summary['active_students'] }}</div>
            </div>

            <div class="bg-white border border-slate-200 rounded-[26px] p-6 shadow-sm">
                <div class="text-sm font-semibold text-slate-500">Tes Akademik</div>
                <div class="text-4xl font-extrabold text-blue-700 mt-3">{{ $summary['academic'] }}</div>
            </div>

            <div class="bg-white border border-slate-200 rounded-[26px] p-6 shadow-sm">
                <div class="text-sm font-semibold text-slate-500">Tes Psikologi</div>
                <div class="text-4xl font-extrabold text-blue-700 mt-3">{{ $summary['psychology'] }}</div>
            </div>

            <div class="bg-white border border-slate-200 rounded-[26px] p-6 shadow-sm">
                <div class="text-sm font-semibold text-slate-500">Pelanggaran Tinggi</div>
                <div class="text-4xl font-extrabold text-slate-900 mt-3">{{ $summary['high_violation'] }}</div>
            </div>
        </div>

        @if($students->isEmpty())
            <div class="bg-white border border-slate-200 rounded-[30px] p-10 text-center shadow-sm">
                <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-user-clock text-2xl"></i>
                </div>

                <h2 class="text-xl font-extrabold text-slate-900">Belum ada siswa yang sedang ujian</h2>
                <p class="text-slate-500 mt-2">Halaman ini akan menampilkan data otomatis saat ada sesi yang sedang berjalan.</p>
            </div>
        @else
            <div class="grid xl:grid-cols-2 gap-6">
                @foreach($students as $student)
                    <div class="bg-white border border-slate-200 rounded-[30px] p-6 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-5">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <i class="fa-solid fa-user-graduate text-xl"></i>
                                </div>

                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2 class="text-xl font-extrabold text-slate-900">{{ $student->name }}</h2>
                                        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-extrabold">
                                            {{ $student->active_exam }}
                                        </span>
                                    </div>

                                    <div class="text-sm text-slate-500 mt-1">NISN: {{ $student->nisn }}</div>
                                    <div class="text-sm font-semibold text-slate-700 mt-1">{{ $student->origin_class }}</div>
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="text-xs font-semibold text-slate-500">Sisa Waktu</div>
                                <div class="inline-flex items-center gap-2 mt-1 px-4 py-2 rounded-2xl {{ $student->remaining_seconds <= 300 ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700' }}">
                                    <i class="fa-solid fa-clock"></i>
                                    <span class="text-2xl font-extrabold">{{ $student->remaining_label }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-3 gap-4 mb-5">
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 px-4 py-4">
                                <div class="text-xs font-bold uppercase tracking-wide text-slate-500">Sesi</div>
                                <div class="font-extrabold text-slate-900 mt-2">{{ $student->session_name }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ \Carbon\Carbon::parse($student->test_date)->format('d M Y') }}</div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 px-4 py-4">
                                <div class="text-xs font-bold uppercase tracking-wide text-slate-500">Jam Sesi</div>
                                <div class="font-extrabold text-slate-900 mt-2">
                                    {{ \Illuminate\Support\Str::of($student->start_time)->substr(0, 5) }} -
                                    {{ \Illuminate\Support\Str::of($student->end_time)->substr(0, 5) }}
                                </div>
                            </div>

                            <div class="rounded-2xl {{ $student->violation_count > 0 ? 'bg-red-50 border-red-100' : 'bg-slate-50 border-slate-200' }} border px-4 py-4">
                                <div class="text-xs font-bold uppercase tracking-wide {{ $student->violation_count > 0 ? 'text-red-600' : 'text-slate-500' }}">Pelanggaran</div>
                                <div class="font-extrabold {{ $student->violation_count > 0 ? 'text-red-700' : 'text-slate-900' }} text-2xl mt-2">
                                    {{ $student->violation_count }}
                                </div>
                                <div class="text-xs {{ $student->violation_count > 0 ? 'text-red-500' : 'text-slate-500' }} mt-1">
                                    {{ $student->last_violation_at ? 'Terakhir: ' . \Carbon\Carbon::parse($student->last_violation_at)->format('H:i:s') : 'Belum ada catatan' }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm font-bold text-slate-700">Progress Waktu Ujian</div>
                                <div class="text-sm font-extrabold text-blue-600">{{ $student->progress_percent }}%</div>
                            </div>

                            <div class="w-full h-3 rounded-full bg-slate-200 overflow-hidden">
                                <div class="h-3 rounded-full bg-gradient-to-r from-blue-600 to-blue-400" style="width: {{ $student->progress_percent }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        setTimeout(() => window.location.reload(), 15000);
    </script>
@endpush
