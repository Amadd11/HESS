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

    @vite(['resources/css/app.css', 'resources/js/admin.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full font-sans antialiased text-gray-800 flex" x-data="{
    sidebarOpen: false,
    logoutModalOpen: false,
    deleteModal: {
        open: false,
        title: 'Konfirmasi Hapus Data',
        message: 'Apakah Anda yakin ingin menghapus data ini?',
        targetForm: null,
    },
    confirmLogout() {
        this.logoutModalOpen = true;
    },
    confirmDelete(detail) {
        this.deleteModal.title = detail.title || 'Konfirmasi Hapus Data';
        this.deleteModal.message = detail.message || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
        this.deleteModal.targetForm = detail.form || null;
        this.deleteModal.open = true;
    },
    executeDelete() {
        if (this.deleteModal.targetForm) {
            this.deleteModal.targetForm.dataset.confirmed = 'true';
            this.deleteModal.targetForm.submit();
        }
        this.deleteModal.open = false;
    }
}"
@open-logout-modal.window="confirmLogout()"
@open-delete-modal.window="confirmDelete($event.detail)">

    <!-- Sidebar Navigation Component -->
    <x-sidebar />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-y-auto">
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

    <!-- Modal Konfirmasi Logout -->
    <div x-show="logoutModalOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div @click.away="logoutModalOpen = false"
             x-show="logoutModalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-gray-100 space-y-4">
            
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center mx-auto shadow-xs">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>

            <div class="space-y-1">
                <h3 class="text-base font-extrabold text-gray-900 tracking-tight">Keluar dari Sistem?</h3>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Sesi Anda sebagai Administrator HESS akan diakhiri. Anda harus login kembali untuk mengakses panel.
                </p>
            </div>

            <div class="flex items-center gap-2.5 pt-2">
                <button type="button" @click="logoutModalOpen = false"
                    class="flex-1 px-4 py-2.5 text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition cursor-pointer">
                    Batal
                </button>
                <form action="{{ route('admin.logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition shadow-xs cursor-pointer">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Universal Konfirmasi Hapus Data -->
    <div x-show="deleteModal.open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs">
        <div @click.away="deleteModal.open = false"
             x-show="deleteModal.open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-gray-100 space-y-4">
            
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center mx-auto shadow-xs">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>

            <div class="space-y-1.5">
                <h3 class="text-base font-extrabold text-gray-900 tracking-tight" x-text="deleteModal.title">
                    Konfirmasi Hapus Data
                </h3>
                <p class="text-xs text-gray-500 leading-relaxed" x-text="deleteModal.message">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <div class="flex items-center gap-2.5 pt-2">
                <button type="button" @click="deleteModal.open = false"
                    class="flex-1 px-4 py-2.5 text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition cursor-pointer">
                    Batal
                </button>
                <button type="button" @click="executeDelete()"
                    class="flex-1 px-4 py-2.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow-xs cursor-pointer">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

</body>

</html>