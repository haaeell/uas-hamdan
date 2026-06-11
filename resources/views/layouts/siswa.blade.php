<!DOCTYPE html>
<html lang="id">

<head>
    @php
        $appName = \App\Models\Setting::getSetting('app_name', 'Sistem Pemilihan Jurusan');
        $logoUrl = \App\Models\Setting::logoUrl();
        $themeColor = \App\Models\Setting::getSetting('theme_color', '#2563eb');
    @endphp
    <meta charset="UTF-8">
    <title>Siswa - {{ $appName }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ $logoUrl }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/contrib/auto-render.min.js"></script>
    <style>
        :root {
            --theme-color: {{ $themeColor }};
        }

        .bg-blue-600,
        .bg-blue-700 {
            background: var(--theme-color) !important;
        }

        .text-blue-600,
        .text-blue-700 {
            color: var(--theme-color) !important;
        }

        .border-blue-100,
        .border-blue-600 {
            border-color: color-mix(in srgb, var(--theme-color) 30%, white) !important;
        }
    </style>
</head>

<body class="bg-slate-950 text-white">
    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sesi Belum Dibuka',
                    text: @json(session('warning')),
                    confirmButtonColor: @json($themeColor)
                });
            });
        </script>
    @endif

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonColor: @json($themeColor)
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: @json(session('error')),
                    confirmButtonColor: @json($themeColor)
                });
            });
        </script>
    @endif
    @yield('content')

    <script>
        window.renderMathBlocks = function (root = document) {
            if (typeof renderMathInElement !== 'function') {
                return;
            }

            const targets = [];

            if (root.classList && root.classList.contains('math-render')) {
                targets.push(root);
            }

            if (root.querySelectorAll) {
                targets.push(...root.querySelectorAll('.math-render'));
            }

            [...new Set(targets)].forEach((element) => {
                renderMathInElement(element, {
                    delimiters: [
                        {left: '$$', right: '$$', display: true},
                        {left: '$', right: '$', display: false},
                        {left: '\\(', right: '\\)', display: false},
                        {left: '\\[', right: '\\]', display: true},
                    ],
                    throwOnError: false,
                });
            });
        };

        document.addEventListener('DOMContentLoaded', function () {
            window.renderMathBlocks();
        });
    </script>

    @stack('scripts')
</body>

</html>
