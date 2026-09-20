<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'HESS — Hospital Employee Satisfaction Survey' }}</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-full bg-[#f7f4f8] text-[#263238] font-sans antialiased flex flex-col selection:bg-primary-500 selection:text-white pb-28 md:pb-12">
    <!-- Header Brand HESS -->
    <header class="bg-gradient-to-r from-primary-700 via-primary-600 to-primary-500 text-white pt-6 pb-9 px-4 md:px-8 shadow-sm">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center justify-between text-xs tracking-wider uppercase font-extrabold text-primary-100/90 mb-1.5">
                <span>HESS • Hospital Survey</span>
                <span class="bg-white/15 px-2.5 py-0.5 rounded-full text-[11px] font-semibold tracking-normal normal-case">Anonim & Rahasia</span>
            </div>
            <h1 class="text-xl md:text-2xl font-bold tracking-tight text-white">Survei Kepuasan Kerja Pegawai</h1>
            <p class="text-xs md:text-sm text-primary-100/85 mt-1">Pengisian anonim • ± 8–10 menit • dapat diakses melalui ponsel</p>
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