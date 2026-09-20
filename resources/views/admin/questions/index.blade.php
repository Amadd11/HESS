@extends('layouts.admin', ['title' => 'Master Pertanyaan — HESS Admin'])

@section('header-title', 'Master Pertanyaan Kuesioner')

@section('content')
@php
    $stats = $stats ?? [
        'total' => $questions->total(),
        'active' => 0,
        'inactive' => 0,
        'msq' => 0,
        'hospital' => 0,
    ];

    $activeCategory = request('category_id') ? $categories->firstWhere('id', request('category_id')) : null;
    $hasActiveFilters = request()->anyFilled(['search', 'category_id', 'type', 'scale', 'status']);
@endphp

<div x-data="{
    createModalOpen: false,
    editModalOpen: false,
    editQuestion: {
        id: '',
        category_id: '',
        code: '',
        text: '',
        scale: 'satisfaction',
        subscale: '',
        order: 1,
        is_active: true
    },
    openEdit(q) {
        this.editQuestion = {
            id: q.id,
            category_id: q.category_id,
            code: q.code,
            text: q.text,
            scale: q.scale,
            subscale: q.subscale || '',
            order: q.order,
            is_active: Boolean(q.is_active)
        };
        this.editModalOpen = true;
    }
}" class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0 border border-primary-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base md:text-lg font-bold text-gray-900 tracking-tight">Manajemen Butir Instrumen Kuesioner</h2>
                <p class="text-xs text-gray-500 mt-0.5">Kelola penuh 44 butir instrumen evaluasi kepuasan kerja (20 butir MSQ-20 & 24 butir faktor lingkungan rumah sakit).</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <x-button variant="secondary" size="md" :href="route('admin.categories.index')">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Kelola Kategori</span>
            </x-button>
            <x-button type="button" @click="createModalOpen = true" variant="primary" size="md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Pertanyaan</span>
            </x-button>
        </div>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Soal -->
        <a href="{{ route('admin.questions.index') }}"
           class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs hover:border-primary-300 hover:shadow-sm transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Soal</span>
                <x-badge color="gray" size="xs">Instrumen</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-gray-900 group-hover:text-primary-700 transition">{{ $stats['total'] }}</span>
                <span class="text-[11px] font-semibold text-gray-400">Butir Terdaftar</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                <span>Seluruh instrumen kuesioner</span>
            </div>
        </a>

        <!-- MSQ-20 -->
        <a href="{{ route('admin.questions.index', ['type' => 'msq']) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ request('type') === 'msq' ? 'border-purple-400 ring-2 ring-purple-100 bg-purple-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-purple-300 hover:shadow-sm transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-purple-700 uppercase tracking-wider">Instrumen MSQ-20</span>
                <x-badge color="purple" size="xs">Baku</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-purple-900 group-hover:text-purple-700 transition">{{ $stats['msq'] }}</span>
                <span class="text-[11px] font-semibold text-purple-500">Soal Kepuasan</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                <span>Skala Likert Puas (1–5)</span>
            </div>
        </a>

        <!-- Faktor RS -->
        <a href="{{ route('admin.questions.index', ['type' => 'hospital']) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ request('type') === 'hospital' ? 'border-blue-400 ring-2 ring-blue-100 bg-blue-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-blue-300 hover:shadow-sm transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Faktor Lingkungan RS</span>
                <x-badge color="blue" size="xs">8 Dimensi</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-blue-900 group-hover:text-blue-700 transition">{{ $stats['hospital'] }}</span>
                <span class="text-[11px] font-semibold text-blue-500">Soal Persetujuan</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                <span>Skala Likert Setuju (1–5)</span>
            </div>
        </a>

        <!-- Status Operasional -->
        <a href="{{ route('admin.questions.index', ['status' => 'inactive']) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ request('status') === 'inactive' ? 'border-amber-400 ring-2 ring-amber-100 bg-amber-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-emerald-300 hover:shadow-sm transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Operasional</span>
                <x-badge :color="$stats['inactive'] === 0 ? 'emerald' : 'amber'" size="xs">
                    {{ $stats['inactive'] === 0 ? '100% Aktif' : $stats['inactive'] . ' Nonaktif' }}
                </x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-emerald-600">{{ $stats['active'] }}</span>
                <span class="text-[11px] font-semibold text-gray-400">Aktif Disurveikan</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full {{ $stats['inactive'] === 0 ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                <span>{{ $stats['inactive'] === 0 ? 'Semua butir aktif di survei' : $stats['inactive'] . ' butir dinonaktifkan' }}</span>
            </div>
        </a>
    </div>

    <!-- Quick Filter Tabs & Search Bar Container -->
    <div class="bg-white rounded-2xl border border-gray-200/90 shadow-xs p-5 space-y-4">
        <!-- Quick Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 border-b border-gray-100 scrollbar-none text-xs">
            <span class="text-[11px] font-extrabold text-gray-400 uppercase tracking-wider shrink-0 mr-1">Filter Cepat:</span>

            <a href="{{ route('admin.questions.index') }}"
               class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ (!request()->has('type') && !request()->has('status') && !request()->has('category_id') && !request()->has('scale')) ? 'bg-primary-700 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua Butir ({{ $stats['total'] }})
            </a>

            <a href="{{ route('admin.questions.index', ['type' => 'msq']) }}"
               class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ request('type') === 'msq' ? 'bg-purple-700 text-white shadow-xs' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }}">
                MSQ-20 ({{ $stats['msq'] }})
            </a>

            <a href="{{ route('admin.questions.index', ['type' => 'hospital']) }}"
               class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ request('type') === 'hospital' ? 'bg-blue-700 text-white shadow-xs' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                Faktor RS ({{ $stats['hospital'] }})
            </a>

            <a href="{{ route('admin.questions.index', ['status' => 'active']) }}"
               class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ request('status') === 'active' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                Aktif ({{ $stats['active'] }})
            </a>

            @if($stats['inactive'] > 0)
                <a href="{{ route('admin.questions.index', ['status' => 'inactive']) }}"
                   class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ request('status') === 'inactive' ? 'bg-amber-700 text-white shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                    Nonaktif ({{ $stats['inactive'] }})
                </a>
            @endif
        </div>

        <!-- Detailed Filter Form -->
        <form method="GET" action="{{ route('admin.questions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            @if(request('type'))
                <input type="hidden" name="type" value="{{ request('type') }}">
            @endif

            <!-- Search Input (Span 3) -->
            <div class="sm:col-span-2 lg:col-span-3 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kode atau teks..."
                       class="w-full h-10 pl-10 pr-9 rounded-xl border border-gray-200 text-xs text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white">
                @if(request('search'))
                    <a href="{{ route('admin.questions.index', request()->except('search')) }}"
                       class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition" title="Hapus pencarian">
                        ✕
                    </a>
                @endif
            </div>

            <!-- Category Filter (Span 3) -->
            <div class="lg:col-span-3">
                <select name="category_id"
                        class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer">
                    <option value="">-- Semua Kategori / Dimensi --</option>
                    <optgroup label="Instrumen Baku MSQ-20">
                        @foreach($categories->where('type', 'msq') as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                [{{ $cat->code }}] {{ $cat->name }}
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Faktor Kerja Rumah Sakit">
                        @foreach($categories->where('type', 'hospital') as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                [{{ $cat->code }}] {{ $cat->name }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <!-- Scale Filter (Span 2) -->
            <div class="lg:col-span-2">
                <select name="scale"
                        class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer">
                    <option value="">-- Tipe Skala --</option>
                    <option value="satisfaction" {{ request('scale') === 'satisfaction' ? 'selected' : '' }}>Puas (1-5)</option>
                    <option value="agreement" {{ request('scale') === 'agreement' ? 'selected' : '' }}>Setuju (1-5)</option>
                </select>
            </div>

            <!-- Status Filter (Span 2) -->
            <div class="lg:col-span-2">
                <select name="status"
                        class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer">
                    <option value="">-- Semua Status --</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hanya Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Hanya Nonaktif</option>
                </select>
            </div>

            <!-- Submit & Reset Action (Span 2) -->
            <div class="lg:col-span-2 flex items-center gap-2">
                <x-button type="submit" variant="primary" size="md" class="flex-1">
                    <span>Terapkan</span>
                </x-button>
                @if($hasActiveFilters)
                    <a href="{{ route('admin.questions.index') }}"
                       class="h-10 px-3 rounded-xl border border-gray-200 hover:border-gray-300 text-gray-500 hover:text-gray-800 text-xs font-bold flex items-center justify-center transition bg-white shrink-0"
                       title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>

        <!-- Active Filter Badges Bar -->
        @if($hasActiveFilters)
            <div class="pt-2 border-t border-gray-100 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                <span class="font-bold text-gray-400 text-[11px] uppercase tracking-wider">Filter Aktif:</span>

                @if(request('search'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
                        <span>Pencarian: "<strong>{{ request('search') }}</strong>"</span>
                        <a href="{{ route('admin.questions.index', request()->except('search')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
                    </span>
                @endif

                @if($activeCategory)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-primary-50 text-primary-800 font-semibold text-xs border border-primary-200">
                        <span>Kategori: <strong>{{ $activeCategory->name }}</strong></span>
                        <a href="{{ route('admin.questions.index', request()->except('category_id')) }}" class="text-primary-400 hover:text-red-500 font-bold ml-0.5">✕</a>
                    </span>
                @endif

                @if(request('type'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 text-purple-800 font-semibold text-xs border border-purple-200">
                        <span>Kelompok: <strong>{{ request('type') === 'msq' ? 'MSQ-20' : 'Faktor Lingkungan RS' }}</strong></span>
                        <a href="{{ route('admin.questions.index', request()->except('type')) }}" class="text-purple-400 hover:text-red-500 font-bold ml-0.5">✕</a>
                    </span>
                @endif

                @if(request('scale'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 font-semibold text-xs border border-blue-200">
                        <span>Skala: <strong>{{ request('scale') === 'satisfaction' ? 'Kepuasan' : 'Persetujuan' }}</strong></span>
                        <a href="{{ route('admin.questions.index', request()->except('scale')) }}" class="text-blue-400 hover:text-red-500 font-bold ml-0.5">✕</a>
                    </span>
                @endif

                @if(request('status'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-semibold text-xs border border-emerald-200">
                        <span>Status: <strong>{{ request('status') === 'active' ? 'Hanya Aktif' : 'Hanya Nonaktif' }}</strong></span>
                        <a href="{{ route('admin.questions.index', request()->except('status')) }}" class="text-emerald-400 hover:text-red-500 font-bold ml-0.5">✕</a>
                    </span>
                @endif

                <a href="{{ route('admin.questions.index') }}" class="text-xs font-bold text-red-600 hover:text-red-800 ml-1 transition">
                    Hapus Semua
                </a>
            </div>
        @endif
    </div>

    <!-- Questions Table Card -->
    <x-table-container class="border-gray-200/90 shadow-xs">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/90 border-b border-gray-200 text-gray-500 font-extrabold text-[11px] uppercase tracking-wider select-none">
                    <th class="py-3.5 px-4 text-center w-16">No</th>
                    <th class="py-3.5 px-4 w-28">Kode Soal</th>
                    <th class="py-3.5 px-4 min-w-[340px]">Pernyataan Instrumen Kuesioner</th>
                    <th class="py-3.5 px-4 min-w-[220px]">Kategori & Dimensi</th>
                    <th class="py-3.5 px-4 min-w-[150px]">Skala Respon</th>
                    <th class="py-3.5 px-4 text-center w-28">Status</th>
                    <th class="py-3.5 px-4 text-right w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($questions as $q)
                    <tr class="hover:bg-primary-50/20 transition-colors {{ !$q->is_active ? 'bg-gray-50/50 opacity-70' : 'bg-white' }}">
                        <!-- No / Urut -->
                        <td class="py-4 px-4 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100/90 text-gray-500 font-mono text-xs font-bold">
                                {{ sprintf('%02d', $q->order) }}
                            </span>
                        </td>

                        <!-- Kode Soal -->
                        <td class="py-4 px-4">
                            @if($q->category?->type === 'msq')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black tracking-wide bg-purple-50 text-purple-700 border border-purple-200/80">
                                    {{ $q->code }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black tracking-wide bg-sky-50 text-sky-700 border border-sky-200/80">
                                    {{ $q->code }}
                                </span>
                            @endif
                        </td>

                        <!-- Pernyataan Soal -->
                        <td class="py-4 px-4">
                            <div class="space-y-1.5">
                                <p class="text-xs md:text-sm font-semibold text-gray-900 leading-snug">
                                    {{ $q->text }}
                                </p>
                                <div class="flex items-center gap-2 text-[11px] text-gray-400">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Jangkar Respon:</span>
                                    </span>
                                    @if($q->scale === 'satisfaction')
                                        <span class="font-medium text-gray-600 bg-gray-100/80 px-2 py-0.5 rounded text-[10px]">
                                            1: Sangat Tidak Puas ↔ 5: Sangat Puas
                                        </span>
                                    @else
                                        <span class="font-medium text-gray-600 bg-gray-100/80 px-2 py-0.5 rounded text-[10px]">
                                            1: Sangat Tidak Setuju ↔ 5: Sangat Setuju
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Kategori & Subskala -->
                        <td class="py-4 px-4">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-black {{ $q->category?->type === 'msq' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                        {{ $q->category?->code ?? 'DIM' }}
                                    </span>
                                    <span class="font-bold text-xs text-gray-800">
                                        {{ $q->category?->name ?? 'MSQ-20' }}
                                    </span>
                                </div>

                                <div>
                                    @if($q->subscale === 'intrinsic')
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Kepuasan Intrinsik
                                        </span>
                                    @elseif($q->subscale === 'extrinsic')
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200/80 px-2 py-0.5 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            Kepuasan Ekstrinsik
                                        </span>
                                    @elseif($q->subscale === 'general')
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-700 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            Kepuasan Umum
                                        </span>
                                    @elseif($q->category?->type === 'hospital')
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200/80 px-2 py-0.5 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            Faktor Rumah Sakit
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Skala Likert -->
                        <td class="py-4 px-4">
                            @if($q->scale === 'satisfaction')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/80">
                                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Puas (1–5)</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200/80">
                                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Setuju (1–5)</span>
                                </span>
                            @endif
                        </td>

                        <!-- Status Toggle Button -->
                        <td class="py-4 px-4 text-center">
                            <form action="{{ route('admin.questions.toggle', $q->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="group inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 border cursor-pointer {{ $q->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100 hover:border-emerald-300' : 'bg-gray-100 text-gray-500 border-gray-200 hover:bg-gray-200 hover:text-gray-700' }}"
                                        title="Klik untuk {{ $q->is_active ? 'menonaktifkan' : 'mengaktifkan' }} butir soal ini">
                                    <span class="w-2 h-2 rounded-full transition-transform duration-200 {{ $q->is_active ? 'bg-emerald-500 group-hover:scale-125' : 'bg-gray-400' }}"></span>
                                    <span>{{ $q->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </button>
                            </form>
                        </td>

                        <!-- Aksi (Edit & Hapus) -->
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Tombol Edit Modal -->
                                <button type="button" @click="openEdit({{ Js::from($q) }})"
                                        class="p-2 text-gray-500 hover:text-primary-700 hover:bg-primary-50 rounded-xl transition border border-transparent hover:border-primary-100 cursor-pointer"
                                        title="Edit Pertanyaan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                <!-- Tombol Hapus (SoftDelete) -->
                                <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus butir pertanyaan {{ $q->code }}? Data histori survei yang lampau akan tetap aman.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition border border-transparent hover:border-red-100 cursor-pointer"
                                            title="Hapus Pertanyaan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 px-4 text-center">
                            <div class="max-w-sm mx-auto space-y-3">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-bold text-gray-800">Tidak ada butir pertanyaan yang sesuai</h4>
                                    <p class="text-xs text-gray-500">Coba ubah kata kunci pencarian atau sesuaikan opsi filter instrumen yang dipilih.</p>
                                </div>
                                @if($hasActiveFilters)
                                    <a href="{{ route('admin.questions.index') }}"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span>Reset Semua Filter</span>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Table Pagination & Count Footer -->
        <div class="p-4 md:px-6 md:py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-gray-500">
            <div>
                Menampilkan <span class="font-bold text-gray-800">{{ $questions->firstItem() ?? 0 }}</span> s/d <span class="font-bold text-gray-800">{{ $questions->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-800">{{ $questions->total() }}</span> butir pertanyaan
            </div>
            @if($questions->hasPages())
                <div>
                    {{ $questions->links() }}
                </div>
            @endif
        </div>
    </x-table-container>

    <!-- MODAL CREATE PERTANYAAN -->
    <x-modal show="createModalOpen" title="Tambah Butir Pertanyaan Baru" maxWidth="max-w-lg">
        <form action="{{ route('admin.questions.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <x-select label="Kategori Instrumen" name="category_id" :required="true">
                <option value="">-- Pilih Kategori --</option>
                <optgroup label="Instrumen Baku MSQ-20">
                    @foreach($categories->where('type', 'msq') as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                    @endforeach
                </optgroup>
                <optgroup label="Faktor Kerja Rumah Sakit">
                    @foreach($categories->where('type', 'hospital') as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                    @endforeach
                </optgroup>
            </x-select>

            <div class="grid grid-cols-2 gap-3">
                <x-input
                    label="Kode Soal"
                    name="code"
                    placeholder="Contoh: MSQ21 atau H25"
                    :required="true"
                />
                <x-input
                    label="Nomor Urutan"
                    name="order"
                    type="number"
                    :value="\App\Models\Question::max('order') + 1"
                />
            </div>

            <x-textarea
                label="Teks Pernyataan"
                name="text"
                rows="3"
                placeholder="Tuliskan butir pernyataan kuesioner..."
                :required="true"
            />

            <div class="grid grid-cols-2 gap-3">
                <x-select label="Tipe Skala Pilihan" name="scale" :required="true">
                    <option value="satisfaction">Puas (Sangat Tidak Puas - Sangat Puas)</option>
                    <option value="agreement">Setuju (Sangat Tidak Setuju - Sangat Setuju)</option>
                </x-select>

                <x-select label="Subskala (Khusus MSQ)" name="subscale">
                    <option value="">-- Tidak Ada / Faktor RS --</option>
                    <option value="intrinsic">Kepuasan Intrinsik</option>
                    <option value="extrinsic">Kepuasan Ekstrinsik</option>
                    <option value="general">Kepuasan Umum</option>
                </x-select>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="is_active" value="1" checked id="create_active" class="w-4 h-4 rounded text-primary-600 border-gray-300 focus:ring-primary-500 cursor-pointer">
                <label for="create_active" class="text-gray-700 font-semibold cursor-pointer">Langsung Aktifkan Pertanyaan di Kuesioner</label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <x-button type="button" @click="createModalOpen = false" variant="secondary">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary">
                    Simpan Pertanyaan
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- MODAL EDIT PERTANYAAN -->
    <x-modal show="editModalOpen" title="Edit Butir Pertanyaan" maxWidth="max-w-lg">
        <form :action="'{{ url('admin/questions') }}/' + editQuestion.id" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <x-select label="Kategori Instrumen" name="category_id" x-model="editQuestion.category_id" :required="true">
                <optgroup label="Instrumen Baku MSQ-20">
                    @foreach($categories->where('type', 'msq') as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                    @endforeach
                </optgroup>
                <optgroup label="Faktor Kerja Rumah Sakit">
                    @foreach($categories->where('type', 'hospital') as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                    @endforeach
                </optgroup>
            </x-select>

            <div class="grid grid-cols-2 gap-3">
                <x-input
                    label="Kode Soal"
                    name="code"
                    x-model="editQuestion.code"
                    :required="true"
                />
                <x-input
                    label="Nomor Urutan"
                    name="order"
                    type="number"
                    x-model="editQuestion.order"
                />
            </div>

            <x-textarea
                label="Teks Pernyataan"
                name="text"
                rows="3"
                x-model="editQuestion.text"
                :required="true"
            />

            <div class="grid grid-cols-2 gap-3">
                <x-select label="Tipe Skala Pilihan" name="scale" x-model="editQuestion.scale" :required="true">
                    <option value="satisfaction">Puas (Sangat Tidak Puas - Sangat Puas)</option>
                    <option value="agreement">Setuju (Sangat Tidak Setuju - Sangat Setuju)</option>
                </x-select>

                <x-select label="Subskala (Khusus MSQ)" name="subscale" x-model="editQuestion.subscale">
                    <option value="">-- Tidak Ada / Faktor RS --</option>
                    <option value="intrinsic">Kepuasan Intrinsik</option>
                    <option value="extrinsic">Kepuasan Ekstrinsik</option>
                    <option value="general">Kepuasan Umum</option>
                </x-select>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="is_active" value="1" :checked="editQuestion.is_active" id="edit_active" class="w-4 h-4 rounded text-primary-600 border-gray-300 focus:ring-primary-500 cursor-pointer">
                <label for="edit_active" class="text-gray-700 font-semibold cursor-pointer">Pertanyaan Aktif di Kuesioner</label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <x-button type="button" @click="editModalOpen = false" variant="secondary">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary">
                    Perbarui Pertanyaan
                </x-button>
            </div>
        </form>
    </x-modal>

</div>
@endsection
