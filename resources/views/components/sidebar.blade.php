<!-- Mobile Sidebar Backdrop -->
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden transition-opacity"></div>

<!-- Sidebar Navigation -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 flex flex-col transition-transform duration-200 lg:static lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <!-- Logo & Brand -->
    <div class="py-4 px-5 border-b border-gray-100 flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 min-w-0">
            <div class="w-12 h-12 rounded-xl bg-white border border-gray-200/90 p-1 flex items-center justify-center shadow-xs shrink-0">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo PT. MRSTC Indonesia" class="w-full h-full object-contain">
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-1.5">
                    <span class="font-black text-lg tracking-tight text-gray-900 leading-tight">HESS</span>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-primary-50 text-primary-700 border border-primary-100">RS</span>
                </div>
                <span class="block text-xs font-bold text-gray-700 truncate mt-0.5">PT. MRSTC Indonesia</span>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600 p-1 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="p-4 space-y-1.5 flex-1 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs md:text-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard Analytics</span>
        </a>

        <a href="{{ route('admin.sentiment.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs md:text-sm transition {{ request()->routeIs('admin.sentiment.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.sentiment.*') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Analisis Sentimen</span>
        </a>

        <a href="{{ route('admin.methodology.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs md:text-sm transition {{ request()->routeIs('admin.methodology.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.methodology.*') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span>Metodologi & Rumus</span>
        </a>

        <a href="{{ route('admin.responses.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs md:text-sm transition {{ request()->routeIs('admin.responses.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.responses.*') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Data Respon</span>
        </a>

        <a href="{{ route('admin.periods.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs md:text-sm transition {{ request()->routeIs('admin.periods.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.periods.*') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Periode Survei</span>
        </a>

        <div class="pt-3 pb-1">
            <span class="px-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Master Data</span>
        </div>

        <a href="{{ route('admin.categories.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs md:text-sm transition {{ request()->routeIs('admin.categories.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.categories.*') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            <span>Master Kategori</span>
        </a>

        <a href="{{ route('admin.questions.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs md:text-sm transition {{ request()->routeIs('admin.questions.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.questions.*') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Master Pertanyaan</span>
        </a>

        <a href="{{ route('admin.demographics.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs md:text-sm transition {{ request()->routeIs('admin.demographics.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.demographics.*') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span>Master Demografi</span>
        </a>

        <a href="{{ route('admin.sentiment-words.index') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs md:text-sm transition {{ request()->routeIs('admin.sentiment-words.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.sentiment-words.*') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            <span>Kosakata Sentimen</span>
        </a>

        <div class="pt-3 pb-1">
            <span class="px-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Akses Cepat</span>
        </div>

        <a href="{{ route('survey.index') }}" target="_blank"
            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Form Kuesioner Publik</span>
            </span>
            <x-badge color="emerald" size="xs">
                Aktif
            </x-badge>
        </a>
    </nav>

    <!-- User Profile & Logout -->
    <div class="p-3.5 border-t border-gray-100 bg-gray-50/50">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.profile.edit') }}"
                class="flex items-center gap-2.5 truncate p-1 rounded-xl hover:bg-white transition group flex-1 mr-1"
                title="Buka Pengaturan Akun">
                <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 font-bold flex items-center justify-center text-xs shrink-0 group-hover:ring-2 group-hover:ring-primary-400 transition">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="truncate">
                    <div class="text-xs font-bold text-gray-900 truncate group-hover:text-primary-700 transition">{{ auth()->user()->name ?? 'Admin HESS' }}</div>
                    <div class="text-[10px] text-gray-500 truncate">{{ auth()->user()->email ?? '' }}</div>
                </div>
            </a>
            <div class="flex items-center shrink-0">
                <a href="{{ route('admin.profile.edit') }}"
                    class="p-1.5 text-gray-400 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition"
                    title="Pengaturan Akun">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout"
                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>