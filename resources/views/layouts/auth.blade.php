<!DOCTYPE html>
<html lang="id">

<head>
    @php
        $appName = \App\Models\Setting::getSetting('app_name', 'Sistem Pemilihan Jurusan');
        $themeColor = '#dc2626';
    @endphp
    <meta charset="UTF-8">
    <title>@yield('title', $appName)</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Tailwind Config --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },

                    colors: {
                        primary: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        }
                    },

                    boxShadow: {
                        soft: '0 10px 30px rgba(220, 38, 38, 0.08)',
                    }
                }
            }
        }
    </script>

    {{-- Global Style --}}
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #f8fafc;
            color: #0f172a;
            overflow-x: hidden;
        }

        :root {
            --theme-color: {{ $themeColor }};
        }

        .bg-blue-600,
        .bg-blue-700,
        .swal2-confirm {
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

        ::selection {
            background: var(--theme-color);
            color: white;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #e2e8f0;
        }

        ::-webkit-scrollbar-thumb {
            background: #fca5a5;
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #dc2626;
        }

        /* Autofill */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        textarea:-webkit-autofill,
        select:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important;
            -webkit-text-fill-color: #0f172a !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* SweetAlert Custom */
        .swal2-popup {
            border-radius: 28px !important;
            padding: 1.5rem !important;
        }

        .swal2-confirm {
            background: var(--theme-color) !important;
            border-radius: 14px !important;
            font-weight: 700 !important;
            padding: 12px 24px !important;
        }

        .swal2-cancel {
            border-radius: 14px !important;
            font-weight: 700 !important;
            padding: 12px 24px !important;
        }

        /* Pagination Laravel */
        nav[role="navigation"] div:first-child {
            display: none;
        }

        nav[role="navigation"] span,
        nav[role="navigation"] a {
            border-radius: 14px !important;
            margin: 0 2px;
        }

        /* Datatable */
        table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0 10px !important;
        }

        table.dataTable tbody tr {
            background: white;
            transition: all .25s ease;
        }

        table.dataTable tbody tr:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.08);
        }

        table.dataTable thead th {
            color: #64748b !important;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 0 !important;
        }

        table.dataTable tbody td {
            padding-top: 18px !important;
            padding-bottom: 18px !important;
            border-top: 1px solid #e2e8f0 !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        table.dataTable tbody td:first-child {
            border-left: 1px solid #e2e8f0 !important;
            border-top-left-radius: 18px;
            border-bottom-left-radius: 18px;
        }

        table.dataTable tbody td:last-child {
            border-right: 1px solid #e2e8f0 !important;
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
        }

        /* Glass Effect */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }
    </style>

    @stack('styles')
</head>

<body class="antialiased">

    {{-- Flash Message --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonColor: '#dc2626'
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: @json(session('error')),
                    confirmButtonColor: '#dc2626'
                });
            });
        </script>
    @endif

    {{-- Main Content --}}
    @yield('content')

    {{-- Global Confirm Delete --}}
    <script>
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Hapus data?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    @stack('scripts')

</body>

</html>
