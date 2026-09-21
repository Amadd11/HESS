<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'HESS — Hospital Employee Satisfaction Survey' }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpeg') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-full bg-[#f7f4f8] text-[#263238] font-sans antialiased flex flex-col selection:bg-primary-500 selection:text-white pb-28 md:pb-12">
    <!-- Header Brand HESS -->
    <header class="bg-gradient-to-r from-primary-700 via-primary-600 to-primary-500 text-white pt-6 pb-9 px-4 md:px-8 shadow-sm">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center justify-between text-xs tracking-wider uppercase font-extrabold text-primary-100/90 mb-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Logo PT. MRSTC Indonesia" class="w-10 h-10 rounded-xl bg-white p-1 object-contain shadow-xs shrink-0 border border-white/40">
                    <div>
                        <span class="block font-black text-sm text-white tracking-wide leading-tight">HESS - HOSPITAL EMPLOYEE SATISFACTION SURVEY</span>
                        <span class="block text-[10px] text-primary-100 tracking-wider font-semibold">PT. MRSTC Indonesia</span>
                    </div>
                </div>
            </div>
            <h1 class="text-xl md:text-2xl font-bold tracking-tight text-white">Survei Kepuasan Kerja Pegawai</h1>
            <p class="text-xs md:text-sm text-primary-100/85 mt-1">RSUP Dr. Sardjito</p>
        </div>
    </header>

    <!-- Content Container -->
    <main class="max-w-2xl w-full mx-auto px-4 -mt-4 flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-6 text-center text-xs text-gray-400 hidden md:block">
        &copy; {{ date('Y') }} Hospital Employee Satisfaction Survey (HESS). Pengisian instrumen kuesioner terstandarisasi.
    </footer>
</body>

</html>