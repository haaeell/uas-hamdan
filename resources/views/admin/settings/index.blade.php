@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    @php
        $groupMeta = [
            'general' => [
                'title' => 'Identitas Aplikasi',
                'description' => 'Atur nama aplikasi, nama sekolah, logo, dan informasi bantuan yang tampil di area penting.',
                'icon' => 'fa-solid fa-school',
            ],
            'cbt' => [
                'title' => 'Aturan CBT',
                'description' => 'Atur durasi ujian, batas pelanggaran, dan perilaku pengawasan selama tes.',
                'icon' => 'fa-solid fa-stopwatch',
            ],
            'student' => [
                'title' => 'Informasi Siswa',
                'description' => 'Catatan umum yang bisa dipakai untuk kebutuhan operasional siswa.',
                'icon' => 'fa-solid fa-circle-info',
            ],
        ];
    @endphp

    <div class="space-y-8">
        <div class="grid xl:grid-cols-[0.95fr_1.65fr] gap-6 items-start">
            <div class="bg-white border border-slate-200 rounded-[30px] p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900">Pengaturan Sistem</h1>
                        <p class="text-sm text-slate-500 mt-1">Semua aturan utama aplikasi dikumpulkan di satu tempat.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                        <div class="text-xs font-bold uppercase tracking-wide text-slate-500">Link Ujian Owner</div>
                        <div class="text-sm font-extrabold text-slate-900 mt-1 break-all">{{ $examLink ?: '-' }}</div>
                    </div>

                    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-4">
                        <div class="text-xs font-bold uppercase tracking-wide text-blue-600">Durasi Ujian</div>
                        <div class="text-2xl font-extrabold text-blue-700 mt-1">{{ $values['psychology_duration_minutes'] }} menit</div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                        <div class="text-xs font-bold uppercase tracking-wide text-slate-500">Auto Submit</div>
                        <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $values['cbt_auto_submit_violation_limit'] }} pelanggaran</div>
                    </div>
                </div>

                <div class="mt-5 rounded-[24px] border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                    <div class="font-bold text-slate-800 mb-2">Yang langsung terhubung</div>
                    <div class="space-y-2">
                        <div>1. Timer ujian psikologi siswa.</div>
                        <div>2. Auto-submit saat batas pelanggaran tercapai.</div>
                        <div>3. Branding aplikasi, warna tema, dan WhatsApp owner.</div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-700">
                        <p class="font-bold mb-2">Periksa kembali input:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @foreach ($definitions as $group => $items)
                    <div class="bg-white border border-slate-200 rounded-[30px] p-6 shadow-sm">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="{{ $groupMeta[$group]['icon'] ?? 'fa-solid fa-gear' }}"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-extrabold text-slate-900">{{ $groupMeta[$group]['title'] ?? ucfirst($group) }}</h2>
                                <p class="text-sm text-slate-500 mt-1">{{ $groupMeta[$group]['description'] ?? 'Kelola pengaturan grup ini.' }}</p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-5">
                            @foreach ($items as $key => $definition)
                                <div class="{{ ($definition['type'] ?? 'text') === 'textarea' ? 'md:col-span-2' : '' }}">
                                    @if (($definition['type'] ?? 'text') === 'checkbox')
                                        <label class="flex items-start gap-3 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-4">
                                            <input type="checkbox" name="{{ $key }}" value="1"
                                                {{ old($key, $values[$key]) ? 'checked' : '' }}
                                                class="w-5 h-5 mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                            <div>
                                                <div class="font-bold text-slate-900">{{ $definition['label'] }}</div>
                                                <div class="text-sm text-slate-500 mt-1">{{ $definition['help'] ?? '' }}</div>
                                            </div>
                                        </label>
                                    @else
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            {{ $definition['label'] }}
                                        </label>

                                        @if (($definition['type'] ?? 'text') === 'textarea')
                                            <textarea name="{{ $key }}" rows="4"
                                                class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">{{ old($key, $values[$key]) }}</textarea>
                                        @elseif (($definition['type'] ?? 'text') === 'file')
                                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4">
                                                <input type="file" name="{{ $key }}" accept="image/*"
                                                    class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-blue-700">

                                                <div class="mt-4 flex items-center gap-4">
                                                    <img src="{{ \App\Models\Setting::logoUrl() }}" alt="Logo aplikasi"
                                                        class="w-16 h-16 rounded-2xl object-contain bg-white border border-slate-200 p-2">
                                                    <div class="text-xs text-slate-500">
                                                        <div class="font-semibold text-slate-700">Logo aktif saat ini</div>
                                                        <div>Upload file baru untuk mengganti logo di seluruh aplikasi.</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="relative">
                                                <input type="{{ $definition['type'] ?? 'text' }}" name="{{ $key }}"
                                                    value="{{ old($key, $values[$key]) }}"
                                                    class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">

                                                @if (!empty($definition['suffix']))
                                                    <div class="absolute inset-y-0 right-4 flex items-center text-sm font-semibold text-slate-400">
                                                        {{ $definition['suffix'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <p class="text-xs text-slate-500 mt-2">{{ $definition['help'] ?? '' }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end">
                    <button
                        class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-200 transition">
                        <i class="fa-solid fa-save"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
