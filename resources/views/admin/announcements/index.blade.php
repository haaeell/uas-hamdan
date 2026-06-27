@extends('layouts.admin')

@section('title', 'Pengumuman Final')

@section('content')
@php
    $isOpen = $announcement?->published_at && $announcement->published_at->lte(now());
@endphp

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">Pengumuman Final</h1>
        <p class="text-slate-500 mt-2">
            Kelola satu pengumuman final untuk ditampilkan kepada siswa.
        </p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-[30px] p-6 shadow-sm">
        <div class="mb-6">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <i class="fa-solid fa-bullhorn"></i>
            </div>

            <h2 class="text-xl font-extrabold text-slate-900">
                {{ $announcement ? 'Edit Pengumuman Final' : 'Buat Pengumuman Final' }}
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Tanggal buka menjadi target countdown di halaman siswa.
            </p>
        </div>

        <form method="POST"
            action="{{ $announcement ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}"
            class="space-y-4">
            @csrf
            @if($announcement)
                @method('PUT')
            @endif

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Judul</label>
                <input name="title"
                    value="{{ old('title', $announcement?->title) }}"
                    placeholder="Contoh: Pengumuman Hasil Final"
                    class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">

                @error('title')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Buka Pengumuman</label>
                <input type="datetime-local" name="published_at"
                    value="{{ old('published_at', $announcement?->published_at?->format('Y-m-d\TH:i')) }}"
                    class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">

                @error('published_at')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Isi Pengumuman</label>
                <textarea name="content" rows="8"
                    placeholder="Tulis isi pengumuman final..."
                    class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">{{ old('content', $announcement?->content) }}</textarea>

                @error('content')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button
                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-extrabold shadow-lg shadow-blue-100 transition">
                <i class="fa-solid fa-save"></i>
                {{ $announcement ? 'Simpan Perubahan' : 'Simpan Pengumuman Final' }}
            </button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-[30px] p-6 shadow-sm">
        <div class="flex items-center justify-between gap-3 mb-5">
            <h2 class="text-xl font-extrabold text-slate-900">Status</h2>

            @if(!$announcement)
                <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-extrabold">
                    Belum dibuat
                </span>
            @elseif($isOpen)
                <span class="inline-flex px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-extrabold">
                    Sudah dibuka
                </span>
            @else
                <span class="inline-flex px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-extrabold">
                    Terjadwal
                </span>
            @endif
        </div>

        @if($announcement)
            <div class="rounded-[26px] border border-blue-100 bg-blue-50 p-5 mb-4">
                <p class="text-xs font-extrabold text-blue-600 uppercase tracking-wide">Final</p>
                <h3 class="text-lg font-extrabold text-slate-900 mt-2">{{ $announcement->title }}</h3>
                <p class="text-sm text-slate-600 mt-2 line-clamp-3">
                    {{ $announcement->content ?: 'Isi pengumuman belum diisi.' }}
                </p>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex items-start justify-between gap-4 rounded-2xl bg-slate-50 border border-slate-200 p-4">
                    <span class="font-bold text-slate-500">Tanggal buka</span>
                    <span class="font-extrabold text-slate-900 text-right">
                        {{ $announcement->published_at?->translatedFormat('d F Y H:i') ?? '-' }}
                    </span>
                </div>

                <div class="flex items-start justify-between gap-4 rounded-2xl bg-slate-50 border border-slate-200 p-4">
                    <span class="font-bold text-slate-500">Diperbarui</span>
                    <span class="font-extrabold text-slate-900 text-right">
                        {{ $announcement->updated_at?->translatedFormat('d F Y H:i') ?? '-' }}
                    </span>
                </div>
            </div>

            <div class="grid gap-3 mt-5">
                <form id="deleteForm{{ $announcement->id }}" method="POST"
                    action="{{ route('admin.announcements.destroy', $announcement) }}">
                    @csrf
                    @method('DELETE')

                    <button type="button"
                        onclick="confirmAction('deleteForm{{ $announcement->id }}', 'Hapus pengumuman final?', 'Data pengumuman final akan dihapus permanen.')"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-red-50 text-red-600 hover:bg-red-100 font-extrabold transition">
                        <i class="fa-solid fa-trash"></i>
                        Hapus Pengumuman
                    </button>
                </form>
            </div>
        @else
            <div class="text-center py-10">
                <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-clock text-2xl"></i>
                </div>

                <h3 class="text-lg font-extrabold text-slate-900">
                    Pengumuman final belum dibuat
                </h3>

                <p class="text-sm text-slate-500 mt-2">
                    Setelah disimpan, siswa akan melihat countdown menuju tanggal buka.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmAction(formId, title, text) {
    Swal.fire({
        icon: 'question',
        title: title,
        text: text,
        showCancelButton: true,
        confirmButtonText: 'Ya, lanjutkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
</script>
@endpush
