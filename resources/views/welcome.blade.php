<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>jejakcita.id - Platform Deteksi Potensi Karir Siswa</title>
    <meta name="description" content="jejakcita.id membantu sekolah mendeteksi potensi karir siswa, memetakan minat dan bakat, serta mempersiapkan mereka untuk masa depan setelah lulus.">
    <meta name="theme-color" content="#1a2d6b">
    <link rel="icon" href="{{ asset('jejakcitafaviconfix.png') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --navy:        #1a2d6b;
            --navy-dark:   #111e4a;
            --gold:        #c9a227;
            --gold-light:  #fdf6e3;
            --navy-light:  #edf0f8;
        }

        *, *::before, *::after {
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        body {
            background: #f6f8fb;
            color: #0f1f3d;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .float-slow { animation: floatSlow 7s ease-in-out infinite; }
        .float-delay { animation: floatSlow 8s ease-in-out infinite; animation-delay: -2.5s; }

        @media (max-width: 767px) {
            .float-slow, .float-delay { animation: none; }
        }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-9px); }
        }

        .label-tag {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            color: var(--gold);
        }

        .btn-primary {
            background: var(--navy);
            color: #fff;
            transition: background .2s;
        }
        .btn-primary:hover { background: var(--navy-dark); }

        .accent { color: var(--gold); }
        .bg-gold-light { background: var(--gold-light); }
        .bg-navy-light { background: var(--navy-light); }
        .text-navy { color: var(--navy); }
        .text-gold { color: var(--gold); }
    </style>
</head>

<body>

    {{-- ─── NAVBAR ─────────────────────────────────────────────── --}}
    <header class="fixed inset-x-0 top-0 z-50 border-b border-gray-200/70 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-5">
            <a href="/" class="inline-flex items-center gap-2.5">
                <img src="{{ asset('logo.png') }}" alt="jejakcita.id" class="h-11 w-auto">
            </a>

            <nav class="hidden items-center gap-7 text-sm font-medium text-gray-500 md:flex">
                <a href="#fitur" class="transition hover:text-navy" style="--tw-text-opacity:1">Fitur</a>
                <a href="#alur" class="transition hover:text-navy">Alur</a>
                <a href="#laporan" class="transition hover:text-navy">Laporan</a>
            </nav>

            <div class="flex items-center gap-1.5">
                <a href="{{ route('login') }}"
                    class="rounded-lg px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-100">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                    class="btn-primary hidden rounded-lg px-4 py-2 text-sm font-semibold sm:inline-flex">
                    Daftar sekarang
                </a>
            </div>
        </div>
    </header>

    <main>

        {{-- ─── HERO ───────────────────────────────────────────────── --}}
        <section class="relative overflow-hidden pt-24" style="background:#f6f8fb">
            <div class="mx-auto grid max-w-7xl gap-12 px-5 pb-16 pt-10 sm:pt-12 lg:grid-cols-[1fr_1.1fr] lg:items-center">

                <div class="relative z-10 max-w-xl">

                    <h1 class="mt-4 text-4xl font-bold leading-[1.1] text-[#0f1f3d] sm:mt-5 sm:text-5xl md:text-[3.6rem]">
                        Temukan Arah <span class="text-gold">Wujudkan Masa Depan.</span>
                    </h1>

                    <p class="mt-5 max-w-lg text-[0.975rem] leading-[1.75] text-gray-500 sm:mt-6">
                        Petakan minat, bakat, dan potensi setiap siswa secara terstruktur — dari tes online hingga rekomendasi jalur karir — dalam satu panel yang mudah dikelola sekolah.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}"
                            class="btn-primary inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3.5 text-sm font-bold shadow-sm">
                            Mulai gunakan gratis
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3.5 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-300 hover:bg-gray-50">
                            <i class="bi bi-box-arrow-in-right text-gray-400"></i>
                            Masuk panel
                        </a>
                    </div>

                    <div class="mt-9 grid max-w-sm grid-cols-3 divide-x divide-gray-100 rounded-xl border border-gray-100 bg-white text-center shadow-sm sm:mt-11">
                        <div class="px-3 py-4">
                            <p class="text-2xl font-bold text-[#0f1f3d]">100+</p>
                            <p class="mt-0.5 text-[0.7rem] font-medium text-gray-400">Jalur karir</p>
                        </div>
                        <div class="px-3 py-4">
                            <p class="text-2xl font-bold text-[#0f1f3d]">4</p>
                            <p class="mt-0.5 text-[0.7rem] font-medium text-gray-400">Laporan potensi</p>
                        </div>
                        <div class="px-3 py-4">
                            <p class="text-2xl font-bold text-[#0f1f3d]">24/7</p>
                            <p class="mt-0.5 text-[0.7rem] font-medium text-gray-400">Akses online</p>
                        </div>
                    </div>
                </div>

                {{-- Mock dashboard --}}
                <div class="relative grid gap-4 md:min-h-[500px] lg:min-h-[580px]">

                    <div class="float-slow w-full rounded-2xl border border-gray-200 bg-white shadow-xl shadow-[#1e3a6e]/10 md:absolute md:left-0 md:top-6 md:w-[87%]">
                        <div class="flex h-11 items-center justify-between border-b border-gray-100 px-4">
                            <div class="flex items-center gap-1.5">
                                <span class="h-2.5 w-2.5 rounded-full bg-red-300"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-300"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                            </div>
                            <span class="text-[0.68rem] font-medium text-gray-400">Panel Potensi Karir Siswa</span>
                        </div>

                        <div class="grid gap-4 p-4 sm:p-5">
                            <div class="grid grid-cols-3 gap-2.5">
                                <div class="rounded-xl border border-gray-100 bg-gray-50 p-3 sm:p-4">
                                    <p class="text-[0.68rem] font-medium text-gray-400">Siswa</p>
                                    <p class="mt-2 text-2xl font-bold text-[#0f1f3d]">426</p>
                                </div>
                                <div class="rounded-xl p-3 sm:p-4" style="background:var(--gold-light); border:1px solid #e8d58a">
                                    <p class="text-[0.68rem] font-medium text-gold">Tes selesai</p>
                                    <p class="mt-2 text-2xl font-bold text-[#0f1f3d]">318</p>
                                </div>
                                <div class="rounded-xl p-3 sm:p-4" style="background:var(--navy-light); border:1px solid #c8d1e8">
                                    <p class="text-[0.68rem] font-medium text-navy">Karir dipetakan</p>
                                    <p class="mt-2 text-2xl font-bold text-[#0f1f3d]">12</p>
                                </div>
                            </div>

                            <div class="rounded-xl border border-gray-100 p-4">
                                <div class="mb-4 flex items-center justify-between">
                                    <p class="text-sm font-semibold text-[#0f1f3d]">Distribusi potensi karir</p>
                                    <span class="rounded-full px-2.5 py-0.5 text-[0.65rem] font-medium text-gold" style="background:var(--gold-light)">Live</span>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <div class="mb-1.5 flex justify-between text-[0.7rem] font-medium text-gray-400">
                                            <span>Sains & Teknologi</span><span>84%</span>
                                        </div>
                                        <div class="h-1.5 rounded-full bg-gray-100"><div class="h-1.5 w-[84%] rounded-full" style="background:var(--navy)"></div></div>
                                    </div>
                                    <div>
                                        <div class="mb-1.5 flex justify-between text-[0.7rem] font-medium text-gray-400">
                                            <span>Sosial & Humaniora</span><span>72%</span>
                                        </div>
                                        <div class="h-1.5 rounded-full bg-gray-100"><div class="h-1.5 w-[72%] rounded-full" style="background:var(--gold)"></div></div>
                                    </div>
                                    <div>
                                        <div class="mb-1.5 flex justify-between text-[0.7rem] font-medium text-gray-400">
                                            <span>Bisnis & Manajemen</span><span>61%</span>
                                        </div>
                                        <div class="h-1.5 rounded-full bg-gray-100"><div class="h-1.5 w-[61%] rounded-full bg-amber-400"></div></div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-2.5 sm:grid-cols-2">
                                <div class="rounded-xl border border-gray-100 p-3.5">
                                    <p class="text-[0.68rem] font-medium text-gray-400">Hasil rekomendasi</p>
                                    <p class="mt-1.5 text-sm font-semibold text-[#0f1f3d]">Siap dibagikan ke siswa</p>
                                </div>
                                <div class="rounded-xl border border-gray-100 p-3.5">
                                    <p class="text-[0.68rem] font-medium text-gray-400">Laporan karir</p>
                                    <p class="mt-1.5 text-sm font-semibold text-[#0f1f3d]">Excel dan PDF siap</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="float-delay w-full rounded-2xl border border-gray-200 bg-white p-4 shadow-lg shadow-[#1e3a6e]/10 md:absolute md:bottom-8 md:right-0 md:w-[52%]">
                        <div class="mb-3 flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl text-sm text-white" style="background:var(--navy)">
                                <i class="bi bi-mortarboard"></i>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-[#0f1f3d]">Portal siswa</p>
                                <p class="text-[0.68rem] font-medium text-gray-400">Hasil potensi karir kamu</p>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between rounded-lg px-3 py-2" style="background:var(--navy-light)">
                                <span class="text-[0.72rem] font-medium text-navy">Rekayasa Perangkat Lunak</span>
                                <span class="text-[0.72rem] font-bold text-navy">92%</span>
                            </div>
                            <div class="flex items-center justify-between rounded-lg px-3 py-2" style="background:var(--gold-light)">
                                <span class="text-[0.72rem] font-medium text-gold">Data Science</span>
                                <span class="text-[0.72rem] font-bold text-gold">87%</span>
                            </div>
                            <div class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                                <span class="text-[0.72rem] font-medium text-gray-500">Desain Grafis</span>
                                <span class="text-[0.72rem] font-semibold text-gray-400">74%</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- ─── FITUR ──────────────────────────────────────────────── --}}
        <section id="fitur" class="border-y border-gray-100 bg-white py-16">
            <div class="mx-auto max-w-7xl px-5">
                <div class="max-w-xl">
                    <span class="label-tag">Fitur utama</span>
                    <h2 class="mt-3 text-3xl font-bold text-[#0f1f3d] md:text-4xl">
                        Semua yang dibutuhkan untuk memetakan masa depan siswa.
                    </h2>
                </div>

                <div class="mt-10 grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                    <article class="rounded-xl border border-gray-100 bg-white p-5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg text-white text-sm" style="background:var(--navy)">
                            <i class="bi bi-people"></i>
                        </span>
                        <h3 class="mt-4 text-sm font-bold text-[#0f1f3d]">Data siswa</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-500">Import, aktivasi akun, dan kelola profil lengkap setiap siswa per sekolah.</p>
                    </article>

                    <article class="rounded-xl border border-gray-100 bg-white p-5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg text-white text-sm" style="background:var(--gold)">
                            <i class="bi bi-lightbulb"></i>
                        </span>
                        <h3 class="mt-4 text-sm font-bold text-[#0f1f3d]">Tes minat & bakat</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-500">Instrumen terstruktur, timer, pengawasan CBT, dan analisis potensi otomatis setelah selesai.</p>
                    </article>

                    <article class="rounded-xl border border-gray-100 bg-white p-5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg text-white text-sm" style="background:var(--gold)">
                            <i class="bi bi-compass"></i>
                        </span>
                        <h3 class="mt-4 text-sm font-bold text-[#0f1f3d]">Rekomendasi karir</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-500">Setiap siswa mendapat rekomendasi jalur karir berdasarkan hasil tes dan pilihan mereka.</p>
                    </article>

                    <article class="rounded-xl border border-gray-100 bg-white p-5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg text-white text-sm" style="background:var(--navy)">
                            <i class="bi bi-file-earmark-text"></i>
                        </span>
                        <h3 class="mt-4 text-sm font-bold text-[#0f1f3d]">Laporan potensi</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-500">Laporan PDF dan Excel siap cetak untuk siswa, orang tua, maupun arsip sekolah.</p>
                    </article>
                </div>
            </div>
        </section>

        {{-- ─── ALUR ───────────────────────────────────────────────── --}}
        <section id="alur" class="py-16" style="background:#f6f8fb">
            <div class="mx-auto grid max-w-7xl gap-12 px-5 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                <div>
                    <span class="label-tag">Alur kerja</span>
                    <h2 class="mt-3 text-3xl font-bold text-[#0f1f3d] md:text-4xl">
                        Dari tes hingga rekomendasi karir siap dibagikan.
                    </h2>
                    <p class="mt-4 max-w-md text-[0.975rem] leading-[1.75] text-gray-500">
                        Dirancang agar sekolah bisa menjalankan proses deteksi potensi siswa setiap tahun — dengan data yang terpisah dan laporan yang rapi.
                    </p>
                </div>

                <div class="grid gap-3">
                    <div class="flex gap-4 rounded-xl border border-gray-100 bg-white p-5">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs font-semibold text-white" style="background:var(--navy)">1</span>
                        <div>
                            <h3 class="text-sm font-bold text-[#0f1f3d]">Sekolah menyiapkan ruang tes</h3>
                            <p class="mt-1 text-sm leading-6 text-gray-500">Setiap sekolah punya panel sendiri — data siswa, instrumen tes, dan laporan tidak bercampur.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 rounded-xl border border-gray-100 bg-white p-5">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs font-semibold text-white" style="background:var(--gold)">2</span>
                        <div>
                            <h3 class="text-sm font-bold text-[#0f1f3d]">Siswa mengikuti tes minat & bakat</h3>
                            <p class="mt-1 text-sm leading-6 text-gray-500">Biodata, pilihan minat, sesi tes online — semua bisa dipantau secara real-time dari panel sekolah.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 rounded-xl border border-gray-100 bg-white p-5">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs font-semibold text-white" style="background:var(--navy)">3</span>
                        <div>
                            <h3 class="text-sm font-bold text-[#0f1f3d]">Hasil potensi karir siap dibagikan</h3>
                            <p class="mt-1 text-sm leading-6 text-gray-500">Rekomendasi jalur karir, distribusi kelompok siswa, laporan PDF, dan surat hasil — selesai di satu tempat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ─── CTA ────────────────────────────────────────────────── --}}
        <section id="laporan" class="py-16 text-white" style="background:var(--navy)">
            <div class="mx-auto grid max-w-7xl gap-12 px-5 lg:grid-cols-[1fr_0.75fr] lg:items-center">
                <div>
                    <span class="label-tag" style="color:var(--gold)">Siap dipakai</span>
                    <h2 class="mt-3 max-w-2xl text-3xl font-bold leading-[1.1] text-white md:text-5xl">
                        Laporan potensi karir dan branding sekolah — semua di satu panel.
                    </h2>
                    <p class="mt-5 max-w-xl text-[0.975rem] leading-[1.75] text-blue-100/80">
                        Cocok untuk sekolah, konsultan pendidikan, atau guru BK yang ingin membantu siswa menentukan arah karir masa depan dengan data yang valid.
                    </p>
                </div>

                <div class="rounded-2xl p-5" style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.12)">
                    <div style="border-bottom:1px solid rgba(255,255,255,0.1)">
                        <div class="flex items-center justify-between px-1 py-3.5">
                            <span class="text-sm font-medium text-white/80">Laporan potensi karir (PDF)</span>
                            <i class="bi bi-check-lg" style="color:var(--gold)"></i>
                        </div>
                    </div>
                    <div style="border-bottom:1px solid rgba(255,255,255,0.1)">
                        <div class="flex items-center justify-between px-1 py-3.5">
                            <span class="text-sm font-medium text-white/80">Export rekap siswa (Excel)</span>
                            <i class="bi bi-check-lg" style="color:var(--gold)"></i>
                        </div>
                    </div>
                    <div style="border-bottom:1px solid rgba(255,255,255,0.1)">
                        <div class="flex items-center justify-between px-1 py-3.5">
                            <span class="text-sm font-medium text-white/80">Rekomendasi jalur karir otomatis</span>
                            <i class="bi bi-check-lg" style="color:var(--gold)"></i>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between px-1 py-3.5">
                            <span class="text-sm font-medium text-white/80">Logo dan tema per sekolah</span>
                            <i class="bi bi-check-lg" style="color:var(--gold)"></i>
                        </div>
                    </div>

                    <a href="{{ route('register') }}"
                        class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white px-5 py-3.5 text-sm font-bold transition hover:bg-gray-50"
                        style="color:var(--navy)">
                        Mulai deteksi potensi siswa
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>

    </main>

    <footer class="border-t border-gray-100 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-6 text-sm text-gray-400 flex-row">
            <img src="{{ asset('logo.png') }}" alt="jejakcita.id" class="h-9 w-auto flex-shrink-0">
            <span class="text-right leading-snug">Platform deteksi potensi karir siswa untuk sekolah modern.</span>
        </div>
    </footer>

</body>
</html>
