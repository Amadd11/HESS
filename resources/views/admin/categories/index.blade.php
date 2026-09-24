@extends('layouts.admin', ['title' => 'Master Kategori — HESS Admin'])

@section('header-title', 'Master Kategori Kuesioner')

@section('content')
@php
    $stats = $stats ?? [
        'total' => $categories->count(),
        'msq' => $categories->where('type', 'msq')->count(),
        'hospital' => $categories->where('type', 'hospital')->count(),
        'total_questions' => 44,
    ];

    $hasActiveFilters = request()->anyFilled(['search', 'type']);
@endphp

<div x-data="categoriesManager()" class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0 border border-primary-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <h2 class="text-base md:text-lg font-bold text-gray-900 tracking-tight">Pengelompokan Dimensi & Faktor Kuesioner</h2>
                <p class="text-xs text-gray-500 mt-0.5">Kelola kategori baku instrumen kepuasan kerja MSQ-20 dan dimensi faktor lingkungan rumah sakit.</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <x-button variant="secondary" size="md" :href="route('admin.questions.index')">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Lihat Pertanyaan</span>
            </x-button>
            <x-button type="button" @click="createModalOpen = true" variant="primary" size="md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Kategori</span>
            </x-button>
        </div>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Kategori -->
        <a href="{{ route('admin.categories.index') }}"
           class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs hover:border-primary-300 hover:shadow-sm transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Kategori</span>
                <x-badge color="gray" size="xs">Dimensi</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-gray-900 group-hover:text-primary-700 transition">{{ $stats['total'] }}</span>
                <span class="text-[11px] font-semibold text-gray-400">Kelompok</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                <span>Seluruh kategori kuesioner</span>
            </div>
        </a>

        <!-- MSQ-20 -->
        <a href="{{ route('admin.categories.index', ['type' => 'msq']) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ request('type') === 'msq' ? 'border-purple-400 ring-2 ring-purple-100 bg-purple-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-purple-300 hover:shadow-sm transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-purple-700 uppercase tracking-wider">Instrumen MSQ-20</span>
                <x-badge color="purple" size="xs">Baku</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-purple-900 group-hover:text-purple-700 transition">{{ $stats['msq'] }}</span>
                <span class="text-[11px] font-semibold text-purple-500">Kategori Utama</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                <span>20 Butir Minnesota Satisfaction</span>
            </div>
        </a>

        <!-- Faktor RS -->
        <a href="{{ route('admin.categories.index', ['type' => 'hospital']) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ request('type') === 'hospital' ? 'border-blue-400 ring-2 ring-blue-100 bg-blue-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-blue-300 hover:shadow-sm transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Faktor Lingkungan RS</span>
                <x-badge color="blue" size="xs">Faktor Kerja</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-blue-900 group-hover:text-blue-700 transition">{{ $stats['hospital'] }}</span>
                <span class="text-[11px] font-semibold text-blue-500">Dimensi Kerja</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                <span>24 Butir Hospital Work Factors</span>
            </div>
        </a>

        <!-- Total Butir Soal -->
        <a href="{{ route('admin.questions.index') }}"
           class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs hover:border-emerald-300 hover:shadow-sm transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Butir Soal</span>
                <x-badge color="emerald" size="xs">100% Terpetakan</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-emerald-600">{{ $stats['total_questions'] }}</span>
                <span class="text-[11px] font-semibold text-gray-400">Butir Terhubung</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Semua butir terdistribusi di kategori</span>
            </div>
        </a>
    </div>

    <!-- Quick Filter Tabs & Search Bar Container -->
    <div class="bg-white rounded-2xl border border-gray-200/90 shadow-xs p-5 space-y-4">
        <!-- Quick Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 border-b border-gray-100 scrollbar-none text-xs">
            <span class="text-[11px] font-extrabold text-gray-400 uppercase tracking-wider shrink-0 mr-1">Filter Cepat:</span>

            <a href="{{ route('admin.categories.index') }}"
               class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ (!request()->has('type') && !request()->has('search')) ? 'bg-primary-700 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua Kategori ({{ $stats['total'] }})
            </a>

            <a href="{{ route('admin.categories.index', ['type' => 'msq']) }}"
               class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ request('type') === 'msq' ? 'bg-purple-700 text-white shadow-xs' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }}">
                MSQ-20 ({{ $stats['msq'] }})
            </a>

            <a href="{{ route('admin.categories.index', ['type' => 'hospital']) }}"
               class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ request('type') === 'hospital' ? 'bg-blue-700 text-white shadow-xs' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                Faktor RS ({{ $stats['hospital'] }})
            </a>
        </div>

        <!-- Filter & Search Form -->
        <form method="GET" action="{{ route('admin.categories.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            @if(request('type'))
                <input type="hidden" name="type" value="{{ request('type') }}">
            @endif

            <!-- Search Input -->
            <div class="sm:col-span-8 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kode dimensi (LS, WS, CR...) atau nama kategori..."
                       class="w-full h-10 pl-10 pr-9 rounded-xl border border-gray-200 text-xs text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white">
                @if(request('search'))
                    <a href="{{ route('admin.categories.index', request()->except('search')) }}"
                       class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition" title="Hapus pencarian">
                        ✕
                    </a>
                @endif
            </div>

            <!-- Group Filter Dropdown -->
            <div class="sm:col-span-2">
                <select name="type"
                        class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer">
                    <option value="">Semua Kelompok</option>
                    <option value="msq" {{ request('type') === 'msq' ? 'selected' : '' }}>MSQ-20</option>
                    <option value="hospital" {{ request('type') === 'hospital' ? 'selected' : '' }}>Faktor RS</option>
                </select>
            </div>

            <!-- Submit & Reset Action -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <x-button type="submit" variant="primary" size="md" class="flex-1">
                    <span>Terapkan</span>
                </x-button>
                @if($hasActiveFilters)
                    <a href="{{ route('admin.categories.index') }}"
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
                        <a href="{{ route('admin.categories.index', request()->except('search')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
                    </span>
                @endif

                @if(request('type'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 text-purple-800 font-semibold text-xs border border-purple-200">
                        <span>Kelompok: <strong>{{ request('type') === 'msq' ? 'MSQ-20' : 'Faktor Lingkungan RS' }}</strong></span>
                        <a href="{{ route('admin.categories.index', request()->except('type')) }}" class="text-purple-400 hover:text-red-500 font-bold ml-0.5">✕</a>
                    </span>
                @endif

                <a href="{{ route('admin.categories.index') }}" class="text-xs font-bold text-red-600 hover:text-red-800 ml-1 transition">
                    Hapus Semua
                </a>
            </div>
        @endif
    </div>

    <!-- Categories Table Card -->
    <x-table-container class="border-gray-200/90 shadow-xs">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/90 border-b border-gray-200 text-gray-500 font-extrabold text-[11px] uppercase tracking-wider select-none">
                    <th class="py-3.5 px-4 text-center w-16">No</th>
                    <th class="py-3.5 px-4 w-28">Kode</th>
                    <th class="py-3.5 px-4 min-w-[280px]">Nama Kategori / Dimensi Kerja</th>
                    <th class="py-3.5 px-4 min-w-[200px]">Kelompok Instrumen</th>
                    <th class="py-3.5 px-4 text-center w-40">Jumlah Soal</th>
                    <th class="py-3.5 px-4 text-right w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $cat)
                    <tr class="hover:bg-primary-50/20 transition-colors bg-white">
                        <!-- No / Urutan -->
                        <td class="py-4 px-4 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100/90 text-gray-500 font-mono text-xs font-bold">
                                {{ sprintf('%02d', $cat->order) }}
                            </span>
                        </td>

                        <!-- Kode -->
                        <td class="py-4 px-4">
                            @if($cat->type === 'msq')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black tracking-wide bg-purple-50 text-purple-700 border border-purple-200/80">
                                    {{ $cat->code }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black tracking-wide bg-sky-50 text-sky-700 border border-sky-200/80">
                                    {{ $cat->code }}
                                </span>
                            @endif
                        </td>

                        <!-- Nama Kategori -->
                        <td class="py-4 px-4">
                            <div class="space-y-0.5">
                                <span class="font-bold text-gray-900 text-sm block">
                                    {{ $cat->name }}
                                </span>
                                <span class="text-[11px] text-gray-400 block">
                                    {{ $cat->type === 'msq' ? 'Instrumen standar Minnesota Satisfaction Questionnaire' : 'Dimensi operasional lingkungan kerja rumah sakit' }}
                                </span>
                            </div>
                        </td>

                        <!-- Kelompok Instrumen -->
                        <td class="py-4 px-4">
                            @if($cat->type === 'msq')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                    <span>MSQ-20 Instrument</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                    <span>Hospital Work Factor</span>
                                </span>
                            @endif
                        </td>

                        <!-- Jumlah Soal Terdaftar -->
                        <td class="py-4 px-4 text-center">
                            <a href="{{ route('admin.questions.index', ['category_id' => $cat->id]) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all border border-gray-200 bg-gray-50 hover:bg-primary-50 hover:text-primary-700 hover:border-primary-200 group"
                               title="Lihat seluruh butir soal di dimensi ini">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary-600"></span>
                                <span>{{ $cat->questions_count }} Butir Soal</span>
                                <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-primary-600 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>

                        <!-- Aksi -->
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Edit Button -->
                                <button type="button" @click="openEdit({{ Js::from($cat) }})"
                                        class="p-2 text-gray-500 hover:text-primary-700 hover:bg-primary-50 rounded-xl transition border border-transparent hover:border-primary-100 cursor-pointer"
                                        title="Edit Kategori">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline"
                                      data-title="Hapus Kategori?"
                                      data-confirm="Apakah Anda yakin ingin menghapus kategori '{{ $cat->name }}'? Seluruh butir kuesioner terkait akan terdampak.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition border border-transparent hover:border-red-100 cursor-pointer"
                                            title="Hapus Kategori">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 px-4 text-center">
                            <div class="max-w-sm mx-auto space-y-3">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-bold text-gray-800">Tidak ada kategori yang sesuai</h4>
                                    <p class="text-xs text-gray-500">Coba ubah kata kunci pencarian atau sesuaikan opsi filter kelompok instrumen.</p>
                                </div>
                                @if($hasActiveFilters)
                                    <a href="{{ route('admin.categories.index') }}"
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

        <!-- Table Count Footer -->
        <div class="p-4 md:px-6 md:py-3.5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between text-xs text-gray-500">
            <div>
                Total terdaftar: <span class="font-bold text-gray-800">{{ $categories->count() }}</span> dimensi instrumen
            </div>
            <div class="text-[11px] text-gray-400">
                Diurutkan berdasarkan urutan tampilan kuesioner
            </div>
        </div>
    </x-table-container>

    {{-- Modals --}}
    @include('admin.categories.partials.create-modal')
    @include('admin.categories.partials.edit-modal')

</div>
@endsection
