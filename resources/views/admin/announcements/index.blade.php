@extends('layouts.admin')

@section('title', 'Pengumuman')

@section('content')
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">Pengumuman</h1>
        <p class="text-slate-500 mt-2">
            Kelola pengumuman sementara dan final untuk siswa.
        </p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Form --}}
    <div class="bg-white border border-slate-200 rounded-[30px] p-6 shadow-sm">
        <div class="mb-6">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                <i class="fa-solid fa-bullhorn"></i>
            </div>

            <h2 class="text-xl font-extrabold text-slate-900">
                Buat Pengumuman
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Tulis pengumuman baru sebagai draft.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tipe</label>
                <select name="type"
                    class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">
                    <option value="temporary">Sementara</option>
                    <option value="final">Final</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Judul</label>
                <input name="title"
                    value="{{ old('title') }}"
                    placeholder="Contoh: Pengumuman Hasil Sementara"
                    class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">

                @error('title')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Isi Pengumuman</label>
                <textarea name="content" rows="6"
                    placeholder="Tulis isi pengumuman..."
                    class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">{{ old('content') }}</textarea>

                @error('content')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button
                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-extrabold shadow-lg shadow-blue-100 transition">
                <i class="fa-solid fa-save"></i>
                Simpan Draft
            </button>
        </form>
    </div>

    {{-- List --}}
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-[30px] p-6 shadow-sm">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">
                    Daftar Pengumuman
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Publish atau hapus pengumuman yang sudah dibuat.
                </p>
            </div>

            <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-full text-sm font-extrabold">
                {{ $announcements->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-left">
                        <th class="py-3 px-3 font-extrabold text-slate-500">Judul</th>
                        <th class="py-3 px-3 font-extrabold text-slate-500">Tipe</th>
                        <th class="py-3 px-3 font-extrabold text-slate-500">Status</th>
                        <th class="py-3 px-3 font-extrabold text-slate-500">Publish</th>
                        <th class="py-3 px-3 font-extrabold text-slate-500 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($announcements as $announcement)
                        <tr class="hover:bg-blue-50/50 transition">
                            <td class="py-4 px-3">
                                <div class="font-extrabold text-slate-900">
                                    {{ $announcement->title }}
                                </div>
                                <div class="text-xs text-slate-500 mt-1 line-clamp-1">
                                    {{ Str::limit(strip_tags($announcement->content), 80) }}
                                </div>
                            </td>

                            <td class="py-4 px-3">
                                @if($announcement->type === 'final')
                                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-extrabold">
                                        Final
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-extrabold">
                                        Sementara
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-3">
                                @if($announcement->is_published)
                                    <span class="inline-flex px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-extrabold">
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-extrabold">
                                        Draft
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-3 text-slate-600">
                                {{ $announcement->published_at?->format('d M Y H:i') ?? '-' }}
                            </td>

                            <td class="py-4 px-3">
                                <div class="flex justify-end gap-2">
                                    @if(!$announcement->is_published)
                                        <form id="publishForm{{ $announcement->id }}" method="POST"
                                            action="{{ route('admin.announcements.publish', $announcement) }}">
                                            @csrf

                                            <button type="button"
                                                onclick="confirmAction('publishForm{{ $announcement->id }}', 'Publish pengumuman?', 'Pengumuman akan tampil untuk siswa.')"
                                                class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-green-50 text-green-700 hover:bg-green-100 font-extrabold transition">
                                                <i class="fa-solid fa-paper-plane"></i>
                                                Publish
                                            </button>
                                        </form>
                                    @endif

                                    <form id="deleteForm{{ $announcement->id }}" method="POST"
                                        action="{{ route('admin.announcements.destroy', $announcement) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                            onclick="confirmAction('deleteForm{{ $announcement->id }}', 'Hapus pengumuman?', 'Data pengumuman akan dihapus permanen.')"
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 font-extrabold transition">
                                            <i class="fa-solid fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center">
                                <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-bullhorn text-2xl"></i>
                                </div>

                                <h3 class="text-lg font-extrabold text-slate-900">
                                    Belum ada pengumuman
                                </h3>

                                <p class="text-slate-500 mt-1">
                                    Buat pengumuman pertama dari form di sebelah kiri.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
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