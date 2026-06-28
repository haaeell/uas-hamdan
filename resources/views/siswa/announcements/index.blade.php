@extends('layouts.siswa')

@section('content')
    <div class="min-h-screen bg-slate-100 px-4 py-8">
        <div class="max-w-2xl mx-auto">

            <div class="bg-white border border-slate-200 rounded-[34px] p-6 md:p-8 shadow-sm">

                <div class="flex items-start gap-4 mb-8">
                    <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-bullhorn text-2xl"></i>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-blue-600 uppercase tracking-wide">
                            Informasi Siswa
                        </p>

                        <h1 class="text-3xl font-extrabold text-slate-900 mt-1">
                            Pengumuman Hasil
                        </h1>

                        <p class="text-slate-500 mt-2">
                            Lihat hasil penempatan jurusan dan kelas Anda, lalu baca informasi lanjutannya dengan teliti.
                        </p>
                    </div>
                </div>

                @if(!$announcement)
                    <div class="text-center py-10">
                        <div
                            class="w-20 h-20 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-5">
                            <i class="fa-solid fa-clock text-4xl"></i>
                        </div>

                        <h2 class="text-2xl font-extrabold text-slate-900">
                            Belum Ada Pengumuman
                        </h2>

                        <p class="text-slate-500 mt-3">
                            Pengumuman hasil belum dibuat. Silakan cek kembali secara berkala melalui akun ini karena hasil dan surat keterangan akan ditampilkan di sini setelah dirilis oleh admin sekolah.
                        </p>

                        <div class="mt-6 rounded-[28px] border border-blue-100 bg-blue-50 p-5 text-left text-sm text-blue-700">
                            <p class="font-bold mb-2">Sambil menunggu pengumuman:</p>
                            <div>1. Pastikan Anda tetap bisa login menggunakan akun yang sama.</div>
                            <div>2. Silakan cek halaman ini secara berkala untuk informasi terbaru.</div>
                            <div>3. Jika ada kendala akses atau data belum sesuai, hubungi admin sekolah.</div>
                        </div>
                    </div>
                @elseif(!$announcementIsOpen)
                    <div class="text-center py-10">
                        <div
                            class="w-20 h-20 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-5">
                            <i class="fa-solid fa-hourglass-half text-4xl"></i>
                        </div>

                        <p class="text-sm font-bold text-blue-600 uppercase tracking-wide">
                            Pengumuman Final
                        </p>

                        <h2 class="text-2xl font-extrabold text-slate-900 mt-2">
                            {{ $announcement->title }}
                        </h2>

                        <p class="text-slate-500 mt-3">
                            Pengumuman final sudah dibuat dan akan dibuka sesuai jadwal berikut.
                        </p>

                        <div class="mt-6 rounded-[28px] border border-blue-100 bg-blue-50 p-5">
                            <p class="text-sm font-bold text-blue-700 mb-4">
                                {{ $announcement->published_at?->translatedFormat('l, d F Y H:i') }}
                            </p>

                            @if($announcement->published_at)
                                <div id="announcementCountdown"
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
                @else
                    <div class="bg-blue-50 border border-blue-100 rounded-[28px] p-5 md:p-6 mb-6">
                        <p class="text-sm font-bold text-blue-600 uppercase tracking-wide">
                            FINAL
                        </p>

                        <h2 class="text-2xl font-extrabold text-slate-900 mt-2">
                            {{ $announcement->title }}
                        </h2>

                        <p class="text-slate-600 mt-3 leading-relaxed">
                            {{ $announcement->content }}
                        </p>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-[28px] p-5 md:p-6 mb-6">
                        <h3 class="text-lg font-extrabold text-slate-900">Petunjuk Setelah Pengumuman</h3>
                        <div class="mt-3 space-y-2 text-sm text-slate-600 leading-relaxed">
                            <div>1. Baca hasil penempatan Anda dengan teliti hingga selesai.</div>
                            <div>2. Pastikan data diri, jurusan, dan kelas sudah sesuai.</div>
                            <div>3. Jika ada pertanyaan atau data perlu dikonfirmasi, hubungi owner melalui WhatsApp.</div>
                            <div>4. Simpan akun ini untuk mengakses kembali hasil pengumuman.</div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-[28px] p-5 md:p-6 mb-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="fa-solid fa-id-card"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900">Data Diri</h3>
                                <p class="text-sm text-slate-500">Pastikan identitas berikut sudah sesuai.</p>
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-3 text-sm">
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="font-semibold text-slate-500">Nama</p>
                                <p class="font-extrabold text-slate-900 mt-1">{{ $student->name }}</p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="font-semibold text-slate-500">NISN / NIS</p>
                                <p class="font-extrabold text-slate-900 mt-1">{{ $student->nisn }} / {{ $student->nis ?: '-' }}</p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="font-semibold text-slate-500">Kelas Asal</p>
                                <p class="font-extrabold text-slate-900 mt-1">{{ $student->origin_class ?: '-' }}</p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="font-semibold text-slate-500">Jenis Kelamin</p>
                                <p class="font-extrabold text-slate-900 mt-1">
                                    {{ $student->biodata?->gender === 'L' ? 'Laki-laki' : ($student->biodata?->gender === 'P' ? 'Perempuan' : '-') }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="font-semibold text-slate-500">Tempat, Tanggal Lahir</p>
                                <p class="font-extrabold text-slate-900 mt-1">
                                    {{ $student->biodata?->birth_place ?: '-' }},
                                    {{ $student->biodata?->birth_date?->translatedFormat('d F Y') ?: '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="font-semibold text-slate-500">No HP Siswa</p>
                                <p class="font-extrabold text-slate-900 mt-1">{{ $student->biodata?->phone ?: '-' }}</p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 sm:col-span-2">
                                <p class="font-semibold text-slate-500">Alamat</p>
                                <p class="font-extrabold text-slate-900 mt-1">{{ $student->biodata?->address ?: '-' }}</p>
                            </div>

                            <div class="rounded-2xl bg-blue-50 border border-blue-100 p-4">
                                <p class="font-semibold text-blue-600">Pilihan Jurusan 1</p>
                                <p class="font-extrabold text-slate-900 mt-1">{{ $student->packageChoice?->firstPackage?->name ?: '-' }}</p>
                            </div>

                            <div class="rounded-2xl bg-blue-50 border border-blue-100 p-4">
                                <p class="font-semibold text-blue-600">Pilihan Jurusan 2</p>
                                <p class="font-extrabold text-slate-900 mt-1">{{ $student->packageChoice?->secondPackage?->name ?: '-' }}</p>
                            </div>

                            <div class="rounded-2xl bg-blue-50 border border-blue-100 p-4 sm:col-span-2">
                                <p class="font-semibold text-blue-600">Rencana Setelah Lulus</p>
                                <p class="font-extrabold text-slate-900 mt-1">{{ $student->packageChoice?->post_graduation_plan ?: '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-[28px] p-5 md:p-6 mb-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="fa-solid fa-chart-simple"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900">Ringkasan Hasil Tes</h3>
                                <p class="text-sm text-slate-500">Nilai dan rekomendasi yang menjadi dasar penempatan.</p>
                            </div>
                        </div>

                        @if($testResult)
                            @if($testResult->recommendedPackage)
                                <div class="rounded-2xl p-5 mb-4" style="background:#1a2d6b">
                                    <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:#c9a227">Hasil Rekomendasi</p>
                                    <p class="text-lg font-extrabold leading-snug text-white">
                                        Berdasarkan hasil tes kamu, kamu cocok di
                                        <span class="underline underline-offset-2" style="color:#c9a227">{{ $testResult->recommendedPackage->name }}</span>
                                    </p>
                                    @if($testResult->recommendedPackage->description)
                                        <p class="mt-3 text-sm leading-relaxed" style="color:#d1d9f0">
                                            {{ $testResult->recommendedPackage->description }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <div class="grid sm:grid-cols-2 gap-3 text-sm mb-4">
                                <div class="rounded-2xl bg-blue-600 text-white p-4">
                                    <p class="font-semibold text-blue-100">Status Tes</p>
                                    <p class="text-3xl font-extrabold mt-1">Selesai</p>
                                </div>

                                <div class="rounded-2xl bg-blue-50 border border-blue-100 p-4">
                                    <p class="font-semibold text-blue-600">Rekomendasi Jurusan</p>
                                    <p class="font-extrabold text-slate-900 mt-1">{{ $testResult->recommendedPackage?->name ?: '-' }}</p>
                                </div>

                                {{-- <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                    <p class="font-semibold text-slate-500">Jurusan Final</p>
                                    <p class="font-extrabold text-slate-900 mt-1">{{ $testResult->finalPackage?->name ?: ($classStudent?->package?->name ?: '-') }}</p>
                                </div>

                                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                    <p class="font-semibold text-slate-500">Status Hasil</p>
                                    <p class="font-extrabold text-slate-900 mt-1">{{ $testResult->is_locked ? 'Sudah dikunci' : 'Belum dikunci' }}</p>
                                </div> --}}
                            </div>

                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="font-bold text-slate-900 mb-3">Nilai Instrumen Peminatan per Jurusan</p>

                                @if($psychologyScores->isNotEmpty())
                                    <div class="space-y-3">
                                        @foreach($psychologyScores as $score)
                                            @php
                                                $scoreValue = (float) $score['score'];
                                                $maxScore = max((float) $psychologyScores->max('score'), 1);
                                                $percentage = min(100, round(($scoreValue / $maxScore) * 100));
                                            @endphp

                                            <div>
                                                <div class="flex items-center justify-between gap-3 text-sm mb-1">
                                                    <span class="font-bold text-slate-700">{{ $score['package'] }}</span>
                                                    <span class="font-extrabold text-blue-600">{{ $score['score'] }}</span>
                                                </div>
                                                <div class="h-2.5 rounded-full bg-white border border-slate-200 overflow-hidden">
                                                    <div class="h-full rounded-full bg-blue-600" style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500">Nilai instrumen peminatan belum tersedia.</p>
                                @endif
                            </div>
                        @else
                            <div class="rounded-2xl bg-blue-50 border border-blue-100 text-blue-700 p-4">
                                <p class="font-bold">Hasil tes belum tersedia.</p>
                                <p class="text-sm mt-1">Silakan cek kembali secara berkala atau hubungi admin sekolah.</p>
                            </div>
                        @endif
                    </div>

                    @if($classStudent)
                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-white border border-slate-200 rounded-[26px] p-5 shadow-sm">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-layer-group"></i>
                                </div>

                                <p class="text-sm font-semibold text-slate-500">Jurusan</p>
                                <h3 class="text-xl font-extrabold text-slate-900 mt-1">
                                    {{ $classStudent->package->name }}
                                </h3>
                            </div>

                            <div class="bg-white border border-slate-200 rounded-[26px] p-5 shadow-sm">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-users"></i>
                                </div>

                                <p class="text-sm font-semibold text-slate-500">Kelas</p>
                                <h3 class="text-xl font-extrabold text-slate-900 mt-1">
                                    {{ $classStudent->classGroup->name }}
                                </h3>
                            </div>
                        </div>
                    @else
                        <div class="bg-blue-50 border border-blue-100 text-blue-700 rounded-[28px] p-5 mb-6">
                            <p class="font-bold">Anda belum mendapatkan kelas.</p>
                            <p class="text-sm mt-1">Silakan tunggu proses distribusi dari admin.</p>
                        </div>
                    @endif

                    @if($classStudent)
                        <div class="space-y-4">
                            <div class="bg-blue-50 border border-blue-100 text-blue-700 rounded-[28px] p-5">
                                <p class="font-bold">Pengumuman final sudah tersedia.</p>

                                @if($announcement->published_at)
                                    <p class="text-sm mt-1">Dibuka pada {{ $announcement->published_at->translatedFormat('d F Y H:i') }}</p>
                                @endif
                            </div>

                            @if($whatsappUrl)
                                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-white hover:bg-blue-50 text-blue-700 border border-blue-100 py-4 rounded-2xl font-extrabold transition">
                                    <i class="fa-brands fa-whatsapp"></i>
                                    Hubungi WhatsApp
                                </a>
                            @endif
                        </div>
                    @elseif($whatsappUrl)
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                            class="w-full inline-flex items-center justify-center gap-2 bg-white hover:bg-blue-50 text-blue-700 border border-blue-100 py-4 rounded-2xl font-extrabold transition">
                            <i class="fa-brands fa-whatsapp"></i>
                            Hubungi WhatsApp
                        </a>
                    @endif
                @endif

            </div>
        </div>

        {{-- Navigation --}}
        <div class="max-w-2xl mx-auto grid sm:grid-cols-2 gap-3 mt-8">

            {{-- Back To Wizard --}}
            <a href="{{ route('siswa.wizard.index') }}" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-blue-50
            text-blue-700 border border-blue-100 py-4 rounded-2xl font-extrabold transition">

                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Wizard
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700
                text-white py-4 rounded-2xl font-extrabold shadow-lg shadow-blue-200 transition">

                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </button>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const countdown = document.getElementById('announcementCountdown');

    if (!countdown) {
        return;
    }

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
});
</script>
@endpush
