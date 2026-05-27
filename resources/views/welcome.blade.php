<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pengumuman Kelulusan Siswa</title>
    <meta name="description" content="Cek pengumuman kelulusan siswa secara online dengan cepat dan mudah.">
    <meta name="keywords" content="kelulusan, siswa, pengumuman, sekolah">
    <meta name="author" content="Sekolah">
    <link rel="icon" type="image/png" href="{{ \App\Models\Setting::logoUrl() }}">
    <meta property="og:title" content="Pengumuman Kelulusan Siswa">
    <meta property="og:description" content="Cek hasil kelulusanmu sekarang juga secara online.">
    <meta property="og:image" content="{{ \App\Models\Setting::logoUrl() }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Pengumuman Kelulusan Siswa">
    <meta name="twitter:description" content="Cek hasil kelulusanmu sekarang juga.">
    <meta name="twitter:image" content="{{ \App\Models\Setting::logoUrl() }}">
    <meta name="theme-color" content="#4f46e5">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            transition: all 0.2s;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
        }

        .btn-gradient:active {
            transform: translateY(0);
        }

        .input-focus:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .result-section {
            display: none;
        }

        .result-section.show {
            display: block;
            animation: fadeUp 0.45s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pulse-dot {
            animation: pulseDot 1.5s ease infinite;
        }

        @keyframes pulseDot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.4;
                transform: scale(0.7);
            }
        }

        .cd-num {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @media (max-width: 640px) {
            .cd-num {
                font-size: 1.6rem;
            }
        }

        .lulus-glow {
            animation: glowPulse 1.5s ease-in-out infinite;
        }

        @keyframes glowPulse {

            0%,
            100% {
                box-shadow: 0 0 0 rgba(16, 185, 129, 0);
            }

            50% {
                box-shadow: 0 0 25px rgba(16, 185, 129, 0.6);
            }
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-14 sm:h-16 flex items-center gap-3">
            <img src="{{ \App\Models\Setting::logoUrl() }}"
                class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg object-cover flex-shrink-0" alt="Logo">
            <span class="text-xs sm:text-sm font-semibold text-gray-800 truncate">SMA Plus Asthahannas — Pengumuman
                Kelulusan</span>
            <span class="ml-auto text-[10px] sm:text-xs text-gray-400 flex-shrink-0">TA
                {{ date('Y') - 1 }}/{{ date('Y') }}</span>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10 sm:py-14">

        <div class="text-center mb-10 sm:mb-14" id="heroSection">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-3" id="heroTitle">
                Halo! 👋<br>
                <span class="gradient-text italic">Siap Lihat Hasilmu? 🎓</span>
            </h1>
            <p class="text-gray-500 text-sm sm:text-[15px] leading-relaxed max-w-md mx-auto" id="heroSubtitle">
                Yuk cek hasil kelulusanmu di sini. Masukkan NIS dan password yang sudah diberikan ya.
            </p>
        </div>
        @php
            $announcement = \App\Models\AnnouncementDate::where('is_active', true)->first();
            $isOpen = $announcement && now()->gte($announcement->announcement_date);
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start" id="mainGrid">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">

                @if ($announcement && !$isOpen)
                    <p
                        class="text-[10px] sm:text-xs font-semibold tracking-widest uppercase text-gray-400 text-center mb-5">
                        <i class="fa-regular fa-clock mr-1"></i> Pengumuman dibuka dalam
                    </p>
                    <div class="flex items-end justify-center gap-2 sm:gap-3 mb-5">
                        <div
                            class="text-center bg-slate-50 border border-gray-100 rounded-xl px-3 sm:px-5 py-3 sm:py-4 flex-1 sm:flex-none sm:min-w-[68px]">
                            <span class="cd-num block" id="cd-days">00</span>
                            <span
                                class="text-[9px] sm:text-[10px] font-semibold tracking-widest uppercase text-gray-400 mt-1 block">Hari</span>
                        </div>
                        <span class="text-xl font-light text-gray-300 mb-3">:</span>
                        <div
                            class="text-center bg-slate-50 border border-gray-100 rounded-xl px-3 sm:px-5 py-3 sm:py-4 flex-1 sm:flex-none sm:min-w-[68px]">
                            <span class="cd-num block" id="cd-hours">00</span>
                            <span
                                class="text-[9px] sm:text-[10px] font-semibold tracking-widest uppercase text-gray-400 mt-1 block">Jam</span>
                        </div>
                        <span class="text-xl font-light text-gray-300 mb-3">:</span>
                        <div
                            class="text-center bg-slate-50 border border-gray-100 rounded-xl px-3 sm:px-5 py-3 sm:py-4 flex-1 sm:flex-none sm:min-w-[68px]">
                            <span class="cd-num block" id="cd-minutes">00</span>
                            <span
                                class="text-[9px] sm:text-[10px] font-semibold tracking-widest uppercase text-gray-400 mt-1 block">Menit</span>
                        </div>
                        <span class="text-xl font-light text-gray-300 mb-3">:</span>
                        <div
                            class="text-center bg-slate-50 border border-gray-100 rounded-xl px-3 sm:px-5 py-3 sm:py-4 flex-1 sm:flex-none sm:min-w-[68px]">
                            <span class="cd-num block" id="cd-seconds">00</span>
                            <span
                                class="text-[9px] sm:text-[10px] font-semibold tracking-widest uppercase text-gray-400 mt-1 block">Detik</span>
                        </div>
                    </div>
                    <p class="text-center text-xs text-gray-400">
                        Dibuka pada <span
                            class="font-semibold text-gray-700">{{ $announcement->announcement_date->translatedFormat('l, d F Y — H:i') }}
                            WIB</span>
                    </p>
                    @if ($announcement->description)
                        <p class="text-center text-xs text-gray-400 italic mt-2">{{ $announcement->description }}</p>
                    @endif
                    <script>
                        const targetDate = new Date("{{ $announcement->announcement_date->toISOString() }}");
                        function updateCountdown() {
                            const now = new Date();
                            const diff = targetDate - now;
                            if (diff <= 0) { location.reload(); return; }
                            const d = Math.floor(diff / 86400000);
                            const h = Math.floor((diff % 86400000) / 3600000);
                            const m = Math.floor((diff % 3600000) / 60000);
                            const s = Math.floor((diff % 60000) / 1000);
                            document.getElementById('cd-days').textContent = String(d).padStart(2, '0');
                            document.getElementById('cd-hours').textContent = String(h).padStart(2, '0');
                            document.getElementById('cd-minutes').textContent = String(m).padStart(2, '0');
                            document.getElementById('cd-seconds').textContent = String(s).padStart(2, '0');
                        }
                        updateCountdown();
                        setInterval(updateCountdown, 1000);
                    </script>

                @elseif ($isOpen)
                    <div class="text-center py-2">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-600 text-xs font-bold tracking-widest uppercase mb-3">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-dot inline-block"></span>
                            Pengumuman Sudah Dibuka
                        </div>
                        <p class="text-sm text-gray-400 mt-1">
                            Resmi dibuka sejak <span
                                class="font-semibold text-gray-700">{{ $announcement->announcement_date->translatedFormat('d F Y, H:i') }}
                                WIB</span>
                        </p>
                    </div>

                @else
                    <div class="text-center py-4">
                        <div
                            class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-400 text-xl mx-auto mb-4">
                            <i class="fa-solid fa-calendar-xmark"></i>
                        </div>
                        <p class="font-semibold text-gray-700 mb-1">Tanggal Belum Diatur</p>
                        <p class="text-sm text-gray-400">Administrator belum mengatur tanggal pengumuman.</p>
                    </div>
                @endif

            </div>

            @if (!$announcement || !$isOpen)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-8 text-center">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-indigo-400 text-xl mx-auto mb-4">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <p class="font-semibold text-gray-800 mb-1">Belum Dapat Diakses</p>
                    <p class="text-sm text-gray-400 leading-relaxed">Tunggu hingga tanggal pengumuman tiba.<br>Pantau terus
                        halaman ini.</p>
                </div>

            @else
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" id="loginSection">
                    <div class="px-5 sm:px-7 pt-5 sm:pt-7 pb-2">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1">Cek Kelulusanmu</h3>
                        <p class="text-xs sm:text-sm text-gray-400 mb-5">Masukkan NIS dan password yang diberikan sekolah
                        </p>

                        <div id="errorBar" style="display:none;"
                            class="flex items-center gap-2 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-red-500 text-sm font-medium mb-5">
                            <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
                            <span id="errorText">NIS atau password salah.</span>
                        </div>

                        <form id="checkForm">
                            @csrf
                            <div class="mb-4">
                                <label
                                    class="block text-[10px] sm:text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-2">NIS
                                    / Username</label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-id-badge absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300 text-xs pointer-events-none"></i>
                                    <input type="text" id="inputNis" placeholder="Masukkan NIS kamu"
                                        class="input-focus w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 bg-slate-50 text-base text-gray-800 placeholder-gray-300 transition-all"
                                        required autocomplete="off">
                                </div>
                            </div>
                            <div class="mb-5">
                                <label
                                    class="block text-[10px] sm:text-[11px] font-semibold tracking-widest uppercase text-gray-400 mb-2">Password</label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-key absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-300 text-xs pointer-events-none"></i>
                                    <input type="password" id="inputPassword" placeholder="Masukkan password"
                                        class="input-focus w-full pl-9 pr-10 py-2.5 rounded-xl border border-gray-200 bg-slate-50 text-base text-gray-800 placeholder-gray-300 transition-all"
                                        required>
                                    <button type="button" onclick="togglePw()"
                                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500 text-xs transition-colors bg-transparent border-none cursor-pointer">
                                        <i class="fa-regular fa-eye" id="pwIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" id="submitBtn"
                                class="btn-gradient w-full py-3 rounded-xl text-white text-sm font-semibold flex items-center justify-center gap-2">
                                <div class="spinner" id="spinner"></div>
                                <i class="fa-solid fa-magnifying-glass" id="searchIcon"></i>
                                <span id="btnText">Cek Kelulusan</span>
                            </button>
                        </form>
                    </div>
                    <div class="px-5 sm:px-7 py-4 bg-slate-50 border-t border-gray-100 mt-5">
                        <p class="text-center text-xs text-gray-400 leading-relaxed">Hubungi Tim Pengajaran jika ada
                            kendala.</p>
                    </div>
                </div>
            @endif

        </div>

        <div class="result-section mt-5" id="resultSection">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div id="resAvatar"
                            class="w-11 h-11 rounded-full btn-gradient flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                            —</div>
                        <div class="min-w-0">
                            <p id="resNama" class="font-semibold text-gray-900 text-sm truncate">—</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                NIS <code id="resNis"
                                    class="bg-slate-100 px-1.5 py-0.5 rounded font-mono text-[10px] text-gray-500">—</code>
                                · <span id="resKelas">—</span>
                            </p>
                        </div>
                    </div>
                    <div id="resBadge" class="flex-shrink-0"></div>
                </div>

                <div class="grid grid-cols-3 divide-x divide-gray-100 border-b border-gray-100">
                    <div class="px-4 py-3">
                        <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400 mb-1">Total Nilai
                        </p>
                        <p id="resTotalScore" class="text-2xl font-bold gradient-text">—</p>
                    </div>
                    <div class="px-4 py-3">
                        <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400 mb-1">Rata-rata</p>
                        <p id="resAvgScore" class="text-2xl font-bold gradient-text">—</p>
                    </div>
                    <div class="px-4 py-3">
                        <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400 mb-1">Peringkat</p>
                        <p id="resRanking" class="text-2xl font-bold gradient-text">—</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-0 px-5 py-3">
                    <div class="py-2 pr-4">
                        <p class="text-[10px] text-gray-400 mb-0.5">NISN</p>
                        <p id="infoNisn" class="text-sm font-semibold text-gray-800">—</p>
                    </div>
                    <div class="py-2">
                        <p class="text-[10px] text-gray-400 mb-0.5">Tempat, Tanggal Lahir</p>
                        <p id="infoTTL" class="text-sm font-semibold text-gray-800">—</p>
                    </div>
                    <div class="py-2 pr-4">
                        <p class="text-[10px] text-gray-400 mb-0.5">Orang Tua</p>
                        <p id="infoOrtu" class="text-sm font-semibold text-gray-800">—</p>
                    </div>
                    <div class="py-2">
                        <p class="text-[10px] text-gray-400 mb-0.5">Mapel Pilihan</p>
                        <p id="infoMapel" class="text-sm font-semibold text-gray-800">—</p>
                    </div>
                    <div class="py-2 col-span-2">
                        <p class="text-[10px] text-gray-400 mb-0.5">Status Kelulusan</p>
                        <p id="infoStatus" class="text-sm font-bold">—</p>
                    </div>
                </div>

                <div class="px-5 py-3 bg-slate-50 border-t border-gray-100 flex items-center justify-between gap-3">
                    <button onclick="backToForm()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-xs font-medium text-gray-500 hover:text-gray-800 hover:border-gray-300 transition-all cursor-pointer">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
                    </button>
                    <a href="#" id="downloadBtn" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl btn-gradient text-white text-xs font-semibold">
                        <i class="fa-solid fa-file-arrow-down"></i> Download Surat
                    </a>
                </div>

            </div>
            <p class="text-center text-xs text-gray-400 mt-3">Dokumen ini bersifat resmi. Simpan dengan baik dan jangan
                disebarluaskan.</p>
        </div>

    </main>

    <footer class="border-t border-gray-100 bg-white py-5 text-center text-xs text-gray-400">
        © {{ date('Y') }} · Sistem Pengumuman Kelulusan
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <script>
        function togglePw() {
            const inp = document.getElementById('inputPassword');
            const icon = document.getElementById('pwIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.className = 'fa-regular fa-eye-slash';
            } else {
                inp.type = 'password';
                icon.className = 'fa-regular fa-eye';
            }
        }

        function showError(msg) {
            const el = document.getElementById('errorBar');
            document.getElementById('errorText').textContent = msg;
            el.style.display = 'flex';
            setTimeout(() => { el.style.display = 'none'; }, 5000);
        }

        function setLoading(on) {
            const btn = document.getElementById('submitBtn');
            const spin = document.getElementById('spinner');
            const icon = document.getElementById('searchIcon');
            const text = document.getElementById('btnText');
            btn.disabled = on;
            spin.style.display = on ? 'block' : 'none';
            icon.style.display = on ? 'none' : 'inline';
            text.textContent = on ? 'Memuat...' : 'Cek Kelulusan';
        }

        function backToForm() {
            document.getElementById('resultSection').classList.remove('show', 'lulus-glow');
            document.getElementById('mainGrid').style.display = '';
            document.getElementById('loginSection').style.display = '';
            document.getElementById('inputNis').value = '';
            document.getElementById('inputPassword').value = '';

            // Reset hero
            document.getElementById('heroTitle').innerHTML = `Halo! 👋<br><span class="gradient-text italic">Siap Lihat Hasilmu? 🎓</span>`;
            document.getElementById('heroSubtitle').textContent = 'Yuk cek hasil kelulusanmu di sini. Masukkan NIS dan password yang sudah diberikan ya.';

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function playConfetti() {
            const end = Date.now() + 3000;
            (function frame() {
                confetti({ particleCount: 5, angle: 60, spread: 70, origin: { x: 0 } });
                confetti({ particleCount: 5, angle: 120, spread: 70, origin: { x: 1 } });
                if (Date.now() < end) requestAnimationFrame(frame);
            })();
        }

        document.getElementById('checkForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();
            setLoading(true);
            document.getElementById('errorBar').style.display = 'none';

            const nis = document.getElementById('inputNis').value.trim();
            const password = document.getElementById('inputPassword').value.trim();

            try {
                const res = await fetch('{{ route("check.result") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ nis, password })
                });

                const data = await res.json();

                if (!res.ok || !data.success) {
                    showError(data.message ?? 'NIS atau password salah.');
                    setLoading(false);
                    return;
                }

                const s = data.student;
                const dlBtn = document.getElementById('downloadBtn');

                document.getElementById('resAvatar').textContent = (s.nama || '?').charAt(0).toUpperCase();
                document.getElementById('resNama').textContent = s.nama;
                document.getElementById('resNis').textContent = s.nis;
                document.getElementById('resKelas').textContent = s.kelas;
                document.getElementById('resTotalScore').textContent = s.total_score ?? '—';
                document.getElementById('resAvgScore').textContent = s.average_score ? parseFloat(s.average_score).toFixed(2) : '—';
                document.getElementById('resRanking').textContent = s.ranking ? '#' + s.ranking : '—';
                document.getElementById('infoNisn').textContent = s.nisn || '-';

                const tempat = s.tempat_lahir || '-';
                const tgl = s.tanggal_lahir
                    ? new Date(s.tanggal_lahir).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
                    : '-';
                document.getElementById('infoTTL').textContent = tempat + ', ' + tgl;
                document.getElementById('infoOrtu').textContent = s.nama_orang_tua || '-';
                document.getElementById('infoMapel').textContent = s.mapel || '-';

                const lulus = s.status === 'lulus';

                const heroTitle = document.getElementById('heroTitle');
                const heroSubtitle = document.getElementById('heroSubtitle');

                if (lulus) {
                    heroTitle.innerHTML = `Selamat! 🎉<br><span class="gradient-text italic">Kamu Dinyatakan Lulus! 🎓</span>`;
                    heroSubtitle.textContent = ` Semangat untuk langkah berikutnya, ${s.nama.split(' ')[0]}!`;
                } else {
                    heroTitle.innerHTML = `Hai, ${s.nama.split(' ')[0]} 👋<br><span style="-webkit-text-fill-color:#dc2626;background:none">Belum Berhasil Kali Ini</span>`;
                    heroSubtitle.textContent = 'Jangan menyerah. Tetap semangat dan terus berjuang untuk masa depanmu!';
                }

                document.getElementById('infoStatus').textContent = lulus ? 'LULUS' : 'TIDAK LULUS';
                document.getElementById('infoStatus').style.color = lulus ? '#059669' : '#dc2626';

                document.getElementById('resBadge').innerHTML = lulus
                    ? `<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold tracking-widest uppercase"><i class="fa-solid fa-circle-check text-[10px]"></i> LULUS</span>`
                    : `<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 border border-red-200 text-red-600 text-[10px] font-bold tracking-widest uppercase"><i class="fa-solid fa-circle-xmark text-[10px]"></i> TIDAK LULUS</span>`;

                if (lulus) {
                    dlBtn.href = '#';
                    dlBtn.onclick = async (ev) => {
                        ev.preventDefault();

                        if (!s.file_surat) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Surat Belum Digenerate',
                                text: 'Surat kelulusan belum dibuat. Hubungi admin untuk generate surat terlebih dahulu.',
                                confirmButtonColor: '#6366f1',
                                customClass: { popup: 'rounded-2xl' }
                            });
                            return;
                        }

                        const fileUrl = `/storage/${s.file_surat}`;
                        const a = document.createElement('a');
                        a.href = fileUrl;
                        a.download = `Surat_Kelulusan_${s.nis}.pdf`;
                        a.target = '_blank';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    };
                } else {
                    dlBtn.href = '#';
                    dlBtn.onclick = (ev) => {
                        ev.preventDefault();
                        Swal.fire({
                            icon: 'info',
                            title: 'Tidak Tersedia',
                            text: 'Surat kelulusan hanya tersedia untuk siswa yang dinyatakan LULUS.',
                            confirmButtonColor: '#6366f1',
                            customClass: { popup: 'rounded-2xl' }
                        });
                    };
                }
                if (lulus) {
                    playConfetti();
                    document.getElementById('resultSection').classList.add('lulus-glow');
                }

                document.getElementById('mainGrid').style.display = 'none';
                document.getElementById('resultSection').classList.add('show');
                window.scrollTo({ top: 0, behavior: 'smooth' });

            } catch (err) {
                showError('Terjadi kesalahan. Coba lagi nanti.');
            }

            setLoading(false);
        });
    </script>

</body>

</html>
