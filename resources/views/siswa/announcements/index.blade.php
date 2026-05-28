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
                            Pengumuman hasil belum dipublikasikan. Silakan cek kembali secara berkala melalui akun ini karena hasil, respons, dan surat keterangan akan ditampilkan di sini setelah dirilis oleh admin sekolah.
                        </p>

                        <div class="mt-6 rounded-[28px] border border-blue-100 bg-blue-50 p-5 text-left text-sm text-blue-700">
                            <p class="font-bold mb-2">Sambil menunggu pengumuman:</p>
                            <div>1. Pastikan Anda tetap bisa login menggunakan akun yang sama.</div>
                            <div>2. Silakan cek halaman ini secara berkala untuk informasi terbaru.</div>
                            <div>3. Jika ada kendala akses atau data belum sesuai, hubungi admin sekolah.</div>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-100 rounded-[28px] p-5 md:p-6 mb-6">
                        <p class="text-sm font-bold text-blue-600 uppercase tracking-wide">
                            {{ strtoupper($announcement->type) }}
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
                            <div>2. Jika setuju, gunakan tombol penerimaan agar status Anda tercatat di sistem.</div>
                            <div>3. Jika belum setuju, tuliskan alasan keberatan secara jelas dan lengkap agar admin mudah menindaklanjuti.</div>
                            <div>4. Tetap cek halaman ini secara berkala untuk pembaruan status, keputusan keberatan, atau surat keterangan final.</div>
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
                            <div class="grid sm:grid-cols-2 gap-3 text-sm mb-4">
                                <div class="rounded-2xl bg-blue-600 text-white p-4">
                                    <p class="font-semibold text-blue-100">Nilai Akademik</p>
                                    <p class="text-3xl font-extrabold mt-1">{{ $testResult->academic_score }}</p>
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
                                <p class="font-bold text-slate-900 mb-3">Nilai Psikotes per Jurusan</p>

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
                                    <p class="text-sm text-slate-500">Nilai psikotes belum tersedia.</p>
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

                    @if(!$response && $announcement->type === 'temporary')
                        <form method="POST" action="{{ route('siswa.announcements.accept', $announcement) }}" class="mb-4">
                            @csrf

                            <button
                                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-extrabold shadow-lg shadow-blue-200 transition">
                                <i class="fa-solid fa-check"></i>
                                Saya Terima
                            </button>
                        </form>

                        <form method="POST" action="{{ route('siswa.announcements.object', $announcement) }}" class="space-y-3">
                            @csrf

                            <textarea name="reason" rows="4" placeholder="Tulis alasan keberatan Anda secara jelas, misalnya alasan akademik, minat jurusan, atau data yang perlu ditinjau ulang"
                                class="w-full p-4 rounded-2xl bg-white border border-slate-200 text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition"></textarea>

                            <button
                                class="w-full inline-flex items-center justify-center gap-2 bg-white hover:bg-blue-50 text-blue-700 border border-blue-100 py-4 rounded-2xl font-extrabold transition">
                                <i class="fa-solid fa-message"></i>
                                Ajukan Keberatan
                            </button>
                        </form>
                    @elseif($response || ($announcement->type === 'final' && $classStudent))
                        <div class="space-y-4">
                            <div class="bg-blue-50 border border-blue-100 text-blue-700 rounded-[28px] p-5">
                                @if($response)
                                    <p class="font-bold">Respons Anda: {{ $response->response === 'accepted' ? 'Diterima' : 'Mengajukan keberatan' }}</p>
                                @else
                                    <p class="font-bold">Pengumuman final sudah tersedia.</p>
                                @endif

                                @if($response?->responded_at)
                                    <p class="text-sm mt-1">Dikirim pada {{ $response->responded_at->translatedFormat('d F Y H:i') }}</p>
                                @elseif($announcement->type === 'final' && $announcement->published_at)
                                    <p class="text-sm mt-1">Dipublikasikan pada {{ $announcement->published_at->translatedFormat('d F Y H:i') }}</p>
                                @endif
                            </div>

                            @if($objection && $announcement->type === 'final')
                                <div class="rounded-[28px] p-5 border {{ $objection->status === 'rejected' ? 'bg-red-50 border-red-100 text-red-700' : 'bg-blue-50 border-blue-100 text-blue-700' }}">
                                    <p class="font-bold">
                                        Status Keberatan:
                                        {{ $objection->status === 'approved' ? 'Disetujui' : ($objection->status === 'rejected' ? 'Ditolak' : 'Menunggu review') }}
                                    </p>

                                    <p class="text-sm mt-2">
                                        <span class="font-bold">Alasan Anda:</span>
                                        {{ $objection->reason }}
                                    </p>

                                    @if($objection->admin_note)
                                        <p class="text-sm mt-2">
                                            <span class="font-bold">Catatan Admin:</span>
                                            {{ $objection->admin_note }}
                                        </p>
                                    @elseif($objection->status === 'rejected')
                                        <p class="text-sm mt-2">Admin belum menambahkan catatan penolakan.</p>
                                    @endif

                                    @if($objection->reviewed_at)
                                        <p class="text-xs mt-3 opacity-80">
                                            Ditinjau pada {{ $objection->reviewed_at->translatedFormat('d F Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            @if((($response && $response->response === 'accepted') || $announcement->type === 'final') && $classStudent)
                                <a href="{{ route('siswa.announcements.letter', $announcement) }}"
                                    target="_blank" rel="noopener"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-extrabold shadow-lg shadow-blue-200 transition">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    Lihat Surat Keterangan PDF
                                </a>
                            @endif
                        </div>
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
