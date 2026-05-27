@extends('layouts.admin')

@section('title', 'Distribusi Kelas')
@push('styles')
    <style>
        .sortable-chosen {
            box-shadow: 0 0 0 4px rgba(191, 219, 254, 1);
        }

        .sortable-ghost {
            opacity: 0.5;
        }
    </style>
@endpush
@section('content')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Distribusi Kelas</h1>
            <p class="text-slate-500 mt-2">
                Auto distribusi siswa berdasarkan rekomendasi jurusan dan nilai. Admin bisa menyesuaikan dengan drag & drop.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <form id="autoDistributeForm" method="POST" action="{{ route('admin.class-distribution.run') }}">
                @csrf
                <button type="button"
                    onclick="confirmAction('autoDistributeForm', 'Jalankan auto distribusi?', 'Sistem akan membuat kelas otomatis dan membagi siswa berdasarkan jurusan serta nilai.')"
                    class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-2xl font-extrabold shadow-lg shadow-blue-200 transition">
                    <i class="fa-solid fa-random"></i>
                    Auto Distribusi
                </button>
            </form>

            <form id="lockFinalForm" method="POST" action="{{ route('admin.class-distribution.lock') }}">
                @csrf
                <button type="button"
                    onclick="confirmAction('lockFinalForm', 'Kunci data final?', 'Data final akan dikunci dan tidak bisa diubah lagi.')"
                    class="inline-flex items-center justify-center gap-2 bg-white hover:bg-blue-50 text-blue-700 border border-blue-100 px-5 py-3 rounded-2xl font-extrabold transition">
                    <i class="fa-solid fa-lock"></i>
                    Lock Final
                </button>
            </form>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-slate-200 rounded-[26px] p-5 shadow-sm">
            <p class="text-sm font-bold text-slate-500">Total Kelas</p>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $classGroups->count() }}</h2>
        </div>

        <div class="bg-white border border-slate-200 rounded-[26px] p-5 shadow-sm">
            <p class="text-sm font-bold text-slate-500">Belum Terbagi</p>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $unassignedStudents->count() }}</h2>
        </div>

        <div class="bg-white border border-slate-200 rounded-[26px] p-5 shadow-sm">
            <p class="text-sm font-bold text-slate-500">Total Terisi</p>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">
                {{ $classGroups->sum(fn($group) => $group->students->count()) }}
            </h2>
        </div>
    </div>

    @if($unassignedStudents->count())
        <div class="bg-white border border-amber-200 rounded-[30px] p-6 shadow-sm mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">Siswa Belum Terbagi</h2>
                    <p class="text-sm text-slate-500">Drag siswa dari sini ke kelas tujuan.</p>
                </div>

                <span class="bg-amber-50 text-amber-700 px-3 py-1.5 rounded-full text-sm font-extrabold">
                    {{ $unassignedStudents->count() }} siswa
                </span>
            </div>

            <div class="student-list grid md:grid-cols-3 gap-3 min-h-[100px]" data-class-id="">
                @foreach($unassignedStudents as $student)
                    <div class="student-card bg-amber-50 border border-amber-200 rounded-2xl p-3 cursor-move"
                        data-student-id="{{ $student->id }}">
                        <div class="font-extrabold text-slate-900">{{ $student->name }}</div>
                        <div class="text-xs text-slate-500 mt-1">
                            {{ $student->origin_class }}
                            @if($student->result)
                                · Nilai: {{ $student->result->academic_score }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid xl:grid-cols-3 md:grid-cols-2 gap-6">
        @forelse($classGroups as $group)
            @php
                $filled = $group->students->count();
                $capacity = max((int) $group->capacity, 1);
                $percent = min(100, round(($filled / $capacity) * 100));
            @endphp

            <div
                class="bg-white border border-slate-200 rounded-[30px] p-6 shadow-sm hover:shadow-xl hover:shadow-blue-100 transition-all duration-300">
                <div class="flex justify-between items-start gap-4 mb-5">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>

                        <h2 class="text-xl font-extrabold text-slate-900">{{ $group->name }}</h2>
                        <p class="text-sm text-slate-500 mt-1">{{ $group->package->name }}</p>
                    </div>

                    <span
                        class="class-counter inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-1.5 rounded-full text-sm font-extrabold"
                        data-capacity="{{ $group->capacity }}">
                        {{ $filled }}/{{ $group->capacity }}
                    </span>
                </div>

                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Kapasitas</p>
                        <p class="text-xs font-extrabold text-blue-600">{{ $percent }}%</p>
                    </div>

                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-3 rounded-full bg-gradient-to-r from-blue-600 to-blue-400" style="width: {{ $percent }}%">
                        </div>
                    </div>
                </div>

                <div class="student-list divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden min-h-[120px] max-h-[360px] overflow-y-auto"
                    data-class-id="{{ $group->id }}">
                    @forelse($group->students->sortByDesc(fn($item) => $item->student->result->academic_score ?? 0) as $index => $item)
                        <div class="student-card flex items-center gap-3 px-3 py-2 bg-white hover:bg-blue-50 cursor-move"
                            data-student-id="{{ $item->student->id }}">

                            <div class="w-7 text-xs font-extrabold text-slate-400">
                                #{{ $index + 1 }}
                            </div>

                            <div
                                class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xs font-extrabold">
                                {{ strtoupper(substr($item->student->name ?? 'S', 0, 1)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-extrabold text-slate-900 truncate">
                                    {{ $item->student->name }}
                                </div>

                                <div class="text-xs text-slate-500 truncate">
                                    {{ $item->student->origin_class }}
                                    @if($item->student->result)
                                        · Nilai: {{ $item->student->result->academic_score }}
                                    @endif
                                </div>
                            </div>

                            @if($item->is_manual_override)
                                <span class="shrink-0 text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold">
                                    Manual
                                </span>
                            @endif

                            <i class="fa-solid fa-grip-vertical text-slate-300 text-xs"></i>
                        </div>
                    @empty
                        <div class="empty-state text-center bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-6">
                            <p class="text-sm font-bold text-slate-600">Drop siswa ke sini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="xl:col-span-3 md:col-span-2 bg-white border border-slate-200 rounded-[30px] p-10 text-center shadow-sm">
                <div class="w-20 h-20 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-5">
                    <i class="fa-solid fa-school text-3xl"></i>
                </div>

                <h2 class="text-2xl font-extrabold text-slate-900">Belum Ada Kelas</h2>
                <p class="text-slate-500 mt-3">
                    Klik Auto Distribusi untuk membuat kelas otomatis berdasarkan hasil tes siswa.
                </p>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

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

        document.querySelectorAll('.student-list').forEach(function (list) {
            new Sortable(list, {
                group: 'students',
                animation: 150,
                chosenClass: 'sortable-chosen',
                ghostClass: 'sortable-ghost',

                onAdd: function (event) {
                    const studentId = event.item.dataset.studentId;
                    const classGroupId = event.to.dataset.classId;

                    if (!classGroupId) {
                        location.reload();
                        return;
                    }

                    const emptyState = event.to.querySelector('.empty-state');
                    if (emptyState) emptyState.remove();

                    $.post('{{ route("admin.class-distribution.manual-move") }}', {
                        _token: '{{ csrf_token() }}',
                        student_id: studentId,
                        class_group_id: classGroupId
                    })
                        .done(function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message ?? 'Siswa berhasil dipindahkan.',
                                timer: 900,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        })
                        .fail(function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON?.message ?? 'Gagal memindahkan siswa.',
                                confirmButtonColor: '#2563eb'
                            }).then(() => location.reload());
                        });
                }
            });
        });
    </script>
@endpush