@extends('layouts.siswa')

@section('content')
    @php
        $steps = [
            'onboarding' => 10,
            'biodata' => 25,
            'package_choice' => 45,
            'waiting_session' => 80,
            'psychology_test' => 95,
            'completed' => 100,
        ];

        $progress = $steps[$student->status] ?? 10;
    @endphp

    <div class="min-h-screen bg-slate-100 pb-32">

        {{-- Header --}}
        <div class="sticky top-0 z-30 bg-white/90 backdrop-blur-xl border-b border-slate-200">
            <div class="max-w-4xl mx-auto px-4 py-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-200">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>

                        <div>
                            <div class="text-sm text-slate-500">Halo,</div>
                            <div class="font-extrabold text-slate-900">{{ $student->name }}</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-blue-50 text-slate-500 hover:text-blue-600 transition">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm font-bold text-slate-700">Progress Pendaftaran</div>
                        <div class="text-sm font-extrabold text-blue-600">{{ $progress }}%</div>
                    </div>

                    <div class="w-full h-3 rounded-full bg-slate-200 overflow-hidden">
                        <div class="h-3 rounded-full  transition-all duration-500"
                            style="width: {{ $progress }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="max-w-4xl mx-auto p-4 md:p-6">

            @if($student->status === 'onboarding')
                <div class="max-w-3xl mx-auto bg-white border border-slate-200 rounded-[32px] p-7 md:p-8 shadow-sm">
                    <div class="w-20 h-20 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-graduation-cap text-4xl"></i>
                    </div>

                    <div class="text-center max-w-2xl mx-auto">
                        <h1 class="text-3xl font-extrabold text-slate-900 mb-4">Selamat Datang</h1>

                        <p class="text-slate-500 leading-relaxed mb-8">
                            Ikuti seluruh tahapan pemilihan jurusan dengan lengkap dan benar agar proses penempatan berjalan
                            lancar.
                        </p>

                        <button id="startBtn" type="button" class="btn-primary">
                            <i class="fa-solid fa-arrow-right"></i>
                            Mulai Sekarang
                        </button>
                    </div>
                </div>
            @endif

            @if($student->status === 'biodata' || $student->status === 'onboarding')
                <form id="biodataForm"
                    class="max-w-3xl mx-auto bg-white border border-slate-200 rounded-[32px] p-6 md:p-8 shadow-sm space-y-5 {{ $student->status === 'onboarding' ? 'hidden mt-6' : '' }}">
                    @csrf

                    <div class="mb-4">
                        <h2 class="text-2xl font-extrabold text-slate-900">Biodata Siswa</h2>
                        <p class="text-slate-500 mt-2">Lengkapi data diri dan informasi orang tua.</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <input name="birth_place" placeholder="Tempat lahir" class="input"
                            value="{{ old('birth_place', $student->biodata?->birth_place) }}" required>
                        <input type="date" name="birth_date" class="input"
                            value="{{ old('birth_date', optional($student->biodata?->birth_date)->format('Y-m-d') ?? $student->biodata?->birth_date) }}"
                            required>
                    </div>

                    <select name="gender" class="input" required>
                        <option value="">Jenis Kelamin</option>
                        <option value="L" {{ old('gender', $student->biodata?->gender) === 'L' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="P" {{ old('gender', $student->biodata?->gender) === 'P' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>

                    <textarea name="address" rows="4" placeholder="Alamat lengkap" class="input"
                        required>{{ old('address', $student->biodata?->address) }}</textarea>

                    <div class="grid md:grid-cols-2 gap-4">
                        <input name="phone" placeholder="No HP siswa" class="input"
                            value="{{ old('phone', $student->biodata?->phone) }}">
                        <input name="parent_phone" placeholder="No HP orang tua" class="input"
                            value="{{ old('parent_phone', $student->biodata?->parent_phone) }}" required>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <input name="father_name" placeholder="Nama ayah" class="input"
                            value="{{ old('father_name', $student->biodata?->father_name) }}" required>
                        <input name="mother_name" placeholder="Nama ibu" class="input"
                            value="{{ old('mother_name', $student->biodata?->mother_name) }}" required>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-save"></i>
                        Simpan Biodata
                    </button>
                </form>
            @endif

            @if($student->status === 'package_choice')
                <form id="packageForm" class="space-y-6">
                    @csrf

                    <div class="bg-white border border-slate-200 rounded-[32px] p-6 md:p-7 shadow-sm">
                        <h2 class="text-2xl font-extrabold text-slate-900">Pilih Jurusan</h2>
                        <p class="text-slate-500 mt-2">Pelajari jurusan yang tersedia, lalu tentukan pilihan pertama dan kedua.
                        </p>

                        <div class="mt-4 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                            Pilihan 1 dan Pilihan 2 harus berbeda. Pilih sesuai minat utama dan cadangan Anda.
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mt-6">
                            @foreach($packages as $package)
                                <div
                                    class="group rounded-[28px] p-6 border border-slate-200 bg-white hover:border-blue-300 hover:shadow-xl hover:shadow-blue-100 transition-all duration-300">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-2xl shadow-sm shrink-0"
                                            style="background: {{ $package->color ?? '#2563eb' }}">
                                        </div>

                                        <div>
                                            <div class="font-extrabold text-slate-900">{{ $package->name }}</div>
                                            <div class="text-sm text-slate-500 mt-1">{{ $package->description }}</div>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach($package->subjects as $subject)
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
                                                {{ $subject->subject_name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="max-w-2xl mx-auto bg-white border border-slate-200 rounded-[32px] p-6 shadow-sm space-y-4">
                        <select name="first_package_id" class="input" required>
                            <option value="">Pilihan 1</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" {{ (string) old('first_package_id', $student->packageChoice?->first_package_id) === (string) $package->id ? 'selected' : '' }}>
                                    {{ $package->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="second_package_id" class="input" required>
                            <option value="">Pilihan 2</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" {{ (string) old('second_package_id', $student->packageChoice?->second_package_id) === (string) $package->id ? 'selected' : '' }}>
                                    {{ $package->name }}
                                </option>
                            @endforeach
                        </select>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Setelah lulus, Anda ingin melanjutkan ke
                                mana?</label>
                            <input name="post_graduation_plan"
                                value="{{ old('post_graduation_plan', $student->packageChoice?->post_graduation_plan) }}"
                                placeholder="Contoh: Kuliah Teknik Informatika di UI, kerja, wirausaha, atau rencana lainnya"
                                class="input" required>
                        </div>

                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-check"></i>
                            Simpan Pilihan
                        </button>
                    </div>
                </form>
            @endif

            @if($student->status === 'waiting_session')
                <div
                    class="max-w-3xl mx-auto text-center bg-white border border-slate-200 rounded-[32px] p-8 md:p-10 shadow-sm">
                    <div class="w-20 h-20 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-clock text-4xl"></i>
                    </div>

                    <h2 class="text-2xl font-extrabold text-slate-900">Menunggu Sesi Tes</h2>

                    <p class="text-slate-500 mt-3">
                        Data Anda sudah lengkap. Silakan tunggu sampai admin membuka sesi tes sesuai jadwal untuk kelas Anda.
                    </p>

                    <div class="mt-6 rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 text-left text-sm text-blue-700">
                        <p class="font-bold mb-2">Yang perlu Anda lakukan sambil menunggu:</p>
                        <div>1. Cek halaman ini secara berkala untuk melihat jadwal sesi yang aktif.</div>
                        <div>2. Pastikan perangkat dan jaringan internet siap digunakan.</div>
                        <div>3. Gunakan akun Anda sendiri dan jangan membagikan akses ke orang lain.</div>
                    </div>

                    <a href="{{ route('siswa.waiting-session') }}" class="btn-primary mt-6">
                        <i class="fa-solid fa-calendar-days"></i>
                        Lihat Jadwal Sesi
                    </a>
                </div>
            @endif

            @if($student->status === 'completed' && !$announcement)
                <div
                    class="max-w-3xl mx-auto text-center bg-white border border-slate-200 rounded-[32px] p-8 md:p-10 shadow-sm">
                    <div class="w-20 h-20 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-bullhorn text-4xl"></i>
                    </div>

                    <h2 class="text-2xl font-extrabold text-slate-900">Tes Selesai, Menunggu Pengumuman</h2>

                    <p class="text-slate-500 mt-3">
                        Seluruh tahapan tes Anda sudah selesai. Hasil penempatan jurusan dan kelas akan ditampilkan setelah
                        pengumuman resmi dipublikasikan oleh admin sekolah.
                    </p>

                    <div class="mt-6 rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 text-left text-sm text-blue-700">
                        <p class="font-bold mb-2">Informasi penting:</p>
                        <div>1. Silakan cek halaman ini secara berkala untuk melihat pengumuman terbaru.</div>
                        <div>2. Simpan akun Anda dengan baik karena hasil dan surat keterangan akan diakses dari akun ini.</div>
                        <div>3. Jika ada kendala atau informasi yang belum jelas, segera hubungi admin sekolah.</div>
                    </div>
                </div>
            @endif

            @if($student->status === 'completed' && $announcement && !$announcementIsOpen)
                <div
                    class="max-w-3xl mx-auto text-center bg-white border border-slate-200 rounded-[32px] p-8 md:p-10 shadow-sm">
                    <div class="w-20 h-20 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-hourglass-half text-4xl"></i>
                    </div>

                    <p class="text-sm font-bold text-blue-600 uppercase tracking-wide">Pengumuman Final</p>
                    <h2 class="text-2xl font-extrabold text-slate-900 mt-2">{{ $announcement->title }}</h2>

                    <p class="text-slate-500 mt-3">
                        Pengumuman final sudah dibuat dan akan dibuka sesuai jadwal.
                    </p>

                    <div class="mt-6 rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4">
                        <p class="text-sm font-bold text-blue-700 mb-4">
                            {{ $announcement->published_at?->translatedFormat('l, d F Y H:i') }}
                        </p>

                        @if($announcement->published_at)
                            <div id="wizardAnnouncementCountdown"
                                data-target="{{ $announcement->published_at->toISOString() }}"
                                class="grid grid-cols-4 gap-3 text-center">
                                <div class="rounded-2xl bg-white border border-blue-100 p-3">
                                    <div data-countdown-days class="text-2xl font-extrabold text-slate-900">00</div>
                                    <div class="text-xs font-bold text-slate-500 mt-1">Hari</div>
                                </div>

                                <div class="rounded-2xl bg-white border border-blue-100 p-3">
                                    <div data-countdown-hours class="text-2xl font-extrabold text-slate-900">00</div>
                                    <div class="text-xs font-bold text-slate-500 mt-1">Jam</div>
                                </div>

                                <div class="rounded-2xl bg-white border border-blue-100 p-3">
                                    <div data-countdown-minutes class="text-2xl font-extrabold text-slate-900">00</div>
                                    <div class="text-xs font-bold text-slate-500 mt-1">Menit</div>
                                </div>

                                <div class="rounded-2xl bg-white border border-blue-100 p-3">
                                    <div data-countdown-seconds class="text-2xl font-extrabold text-slate-900">00</div>
                                    <div class="text-xs font-bold text-slate-500 mt-1">Detik</div>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-blue-700">Jadwal buka belum ditentukan oleh admin.</p>
                        @endif
                    </div>
                </div>
            @endif

            @if($announcement && $announcementIsOpen)
                <div class="max-w-3xl mx-auto mt-4">
                    <a href="{{ route('siswa.announcements.index') }}" class="flex items-center justify-between gap-4 bg-blue-600 hover:bg-blue-700
                            text-white rounded-2xl px-5 py-4 shadow-lg shadow-blue-200 transition-all duration-300">

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center">
                                <i class="fa-solid fa-bullhorn text-xl"></i>
                            </div>

                            <div>
                                <div class="text-sm font-bold uppercase tracking-wide text-blue-100">
                                    Pengumuman Baru
                                </div>

                                <div class="font-extrabold text-lg">
                                    {{ $announcement->title }}
                                </div>
                            </div>
                        </div>

                        <div class="w-11 h-11 rounded-2xl bg-white/20 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        .input {
            width: 100%;
            border-radius: 1.25rem;
            background: white;
            border: 1px solid #e2e8f0;
            color: #0f172a;
            padding: 1rem;
            outline: none;
            transition: all .25s ease;
        }

        .input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px #dbeafe;
        }

        .btn-primary {
            width: 100%;
            min-height: 56px;
            border-radius: 1.25rem;
            background: #2563eb;
            color: white;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            transition: all .25s ease;
            box-shadow: 0 10px 24px rgba(37, 99, 235, .2);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-secondary {
            width: 100%;
            min-height: 56px;
            border-radius: 1.25rem;
            background: #e2e8f0;
            color: #334155;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $('#startBtn').on('click', function () {
            $('#biodataForm').removeClass('hidden');
            $(this).closest('.bg-white').hide();
        });

        $('#biodataForm').on('submit', function (e) {
            e.preventDefault();

            const button = $(this).find('button[type="submit"]');
            button.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Memproses...');

            $.post('{{ route("siswa.wizard.biodata") }}', $(this).serialize())
                .done(function (response) {
                    window.location.replace(response.redirect_url || '{{ route("siswa.wizard.index") }}');
                })
                .fail(function (xhr) {
                    button.prop('disabled', false).html('<i class="fa-solid fa-save"></i> Simpan Biodata');
                    Swal.fire('Gagal', xhr.responseJSON?.message ?? 'Validasi gagal.', 'error');
                });
        });

        $('#packageForm').on('submit', function (e) {
            e.preventDefault();

            const first = $('[name="first_package_id"]').val();
            const second = $('[name="second_package_id"]').val();

            if (first && second && first === second) {
                Swal.fire('Periksa Pilihan', 'Pilihan 1 dan Pilihan 2 tidak boleh sama.', 'warning');
                return;
            }

            const button = $(this).find('button[type="submit"]');
            button.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Memproses...');

            $.post('{{ route("siswa.wizard.package-choice") }}', $(this).serialize())
                .done(function (response) {
                    window.location.replace(response.redirect_url || '{{ route("siswa.wizard.index") }}');
                })
                .fail(function (xhr) {
                    button.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Simpan Pilihan');
                    Swal.fire('Gagal', xhr.responseJSON?.message ?? 'Validasi gagal.', 'error');
                });
        });

        const countdown = document.getElementById('wizardAnnouncementCountdown');

        if (countdown) {
            const targetDate = new Date(countdown.dataset.target);
            const days = countdown.querySelector('[data-countdown-days]');
            const hours = countdown.querySelector('[data-countdown-hours]');
            const minutes = countdown.querySelector('[data-countdown-minutes]');
            const seconds = countdown.querySelector('[data-countdown-seconds]');
            const pad = (value) => String(value).padStart(2, '0');

            function updateCountdown() {
                const distance = targetDate.getTime() - Date.now();

                if (distance <= 0) {
                    days.textContent = '00';
                    hours.textContent = '00';
                    minutes.textContent = '00';
                    seconds.textContent = '00';
                    window.location.reload();
                    return;
                }

                days.textContent = pad(Math.floor(distance / (1000 * 60 * 60 * 24)));
                hours.textContent = pad(Math.floor((distance / (1000 * 60 * 60)) % 24));
                minutes.textContent = pad(Math.floor((distance / (1000 * 60)) % 60));
                seconds.textContent = pad(Math.floor((distance / 1000) % 60));
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        }

    </script>
@endpush
