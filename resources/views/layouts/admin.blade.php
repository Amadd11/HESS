<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Dashboard — HESS' }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpeg') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full font-sans antialiased text-gray-800 flex" x-data="{ sidebarOpen: false }">

    <!-- Sidebar Navigation Component -->
    <x-sidebar />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 px-4 md:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo" class="w-9 h-9 rounded-xl object-contain bg-white border border-gray-200/90 p-1 lg:hidden shrink-0 shadow-xs">
                <h1 class="text-base md:text-lg font-bold text-gray-900 truncate">
                    @yield('header-title', 'Dashboard')
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <x-badge color="emerald" size="sm" :dot="true">
                    Sistem Online
                </x-badge>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <!-- Flash Message Alerts -->
            @if(session('success'))
            <x-alert type="success" :message="session('success')" />
            @endif

            @if(session('error'))
            <x-alert type="error" :message="session('error')" />
            @endif

            @yield('content')
        </main>
    </div>

</body>

</html>