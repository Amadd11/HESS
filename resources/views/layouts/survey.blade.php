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

<body class="min-h-full bg-[#f8f6f9] text-[#263238] font-sans antialiased flex flex-col selection:bg-primary-500 selection:text-white pb-28 md:pb-12">
    <!-- Header Brand HESS -->
    <header class="bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700 text-white pt-6 pb-10 md:pt-7 md:pb-12 px-4 md:px-8 shadow-sm">
        <div class="max-w-4xl mx-auto flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo PT. MRSTC Indonesia" class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-white p-1.5 object-contain shadow-md shrink-0 border border-white/30">
                <div>
                    <div class="flex items-center gap-2 text-xs text-primary-200 font-bold uppercase tracking-wider">
                        <span>HESS</span>
                        <span>•</span>
                        <span>PT. MRSTC INDONESIA</span>
                    </div>
                    <h1 class="font-extrabold text-base md:text-xl text-white tracking-tight leading-tight mt-0.5">
                        Survei Kepuasan Kerja Pegawai
                    </h1>
                    <div class="text-xs text-primary-100/90 font-medium mt-0.5">
                        RSUP Dr. Sardjito Yogyakarta
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/15 backdrop-blur-xs text-white border border-white/20 shadow-xs">
                    <svg class="w-3.5 h-3.5 text-emerald-300 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                    <span>100% Anonim & Rahasia</span>
                </span>
            </div>
        </div>
    </header>

    <!-- Content Container -->
    <main class="max-w-4xl w-full mx-auto px-4 md:px-6 -mt-6 md:-mt-7 flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-6 text-center text-xs text-gray-400 hidden md:block">
        &copy; {{ date('Y') }} Hospital Employee Satisfaction Survey (HESS). Pengisian instrumen kuesioner terstandarisasi.
    </footer>
</body>

</html>