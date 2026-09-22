@extends('layouts.admin', ['title' => 'Data Respon Survei — HESS Admin'])

@section('header-title', 'Data Respon Survei Pegawai')

@section('content')
@php
    $hasActiveFilters = request()->anyFilled(['search', 'period_id', 'unit', 'profession', 'nps_category']);
@endphp

<div x-data="responsesManager()" class="space-y-6">

    <!-- Top Action Bar Header -->
    <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-20">
        <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0 border border-primary-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base md:text-lg font-bold text-gray-900 tracking-tight">Daftar Mentah Respon Kuesioner Anonim</h2>
                <p class="text-xs text-gray-500 mt-0.5">Pantau dan telusuri setiap pengisian kuesioner, skor rincian dimensi, serta masukan kualitatif responden.</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
            <x-button variant="secondary" size="md" :href="route('admin.dashboard.export', request()->all())">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Unduh Excel</span>
            </x-button>

            <x-button variant="primary" size="md" :href="route('admin.dashboard')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Lihat Analitik</span>
            </x-button>
        </div>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Respon -->
        <a href="{{ route('admin.responses.index') }}"
           class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs hover:border-primary-300 transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Respon</span>
                <x-badge color="gray" size="xs">Semua</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-gray-900 group-hover:text-primary-700 transition">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-semibold text-gray-400">Pengisian</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                <span>Akumulasi kuesioner masuk</span>
            </div>
        </a>

        <!-- Promoters -->
        <a href="{{ route('admin.responses.index', array_merge(request()->except('nps_category'), ['nps_category' => 'promoter'])) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ request('nps_category') === 'promoter' ? 'border-emerald-400 ring-2 ring-emerald-100 bg-emerald-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-emerald-300 transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Promoters (9-10)</span>
                <x-badge color="emerald" size="xs">Loyal</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-emerald-700">{{ number_format($stats['promoters']) }}</span>
                <span class="text-[11px] font-semibold text-gray-400">Responden</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Puas & merekomendasikan RS</span>
            </div>
        </a>

        <!-- Passives -->
        <a href="{{ route('admin.responses.index', array_merge(request()->except('nps_category'), ['nps_category' => 'passive'])) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ request('nps_category') === 'passive' ? 'border-amber-400 ring-2 ring-amber-100 bg-amber-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-amber-300 transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Passives (7-8)</span>
                <x-badge color="amber" size="xs">Netral</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-amber-700">{{ number_format($stats['passives']) }}</span>
                <span class="text-[11px] font-semibold text-gray-400">Responden</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>Cukup puas tapi rentan</span>
            </div>
        </a>

        <!-- Detractors -->
        <a href="{{ route('admin.responses.index', array_merge(request()->except('nps_category'), ['nps_category' => 'detractor'])) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ request('nps_category') === 'detractor' ? 'border-red-400 ring-2 ring-red-100 bg-red-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-red-300 transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-red-700 uppercase tracking-wider">Detractors (0-6)</span>
                <x-badge color="red" size="xs">Kritis</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-red-700">{{ number_format($stats['detractors']) }}</span>
                <span class="text-[11px] font-semibold text-gray-400">Responden</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                <span>Perlu perhatian khusus</span>
            </div>
        </a>
    </div>

    <!-- Quick Filter Tabs & Search Bar Container -->
    <div class="bg-white rounded-2xl border border-gray-200/90 shadow-xs p-5 space-y-4">
        <!-- Quick Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 border-b border-gray-100 scrollbar-none text-xs">
            <span class="text-[11px] font-extrabold text-gray-400 uppercase tracking-wider shrink-0 mr-1">Filter NPS:</span>

            <a href="{{ route('admin.responses.index', request()->except('nps_category')) }}"
               class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ !request()->filled('nps_category') ? 'bg-primary-700 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua Kategori ({{ $stats['total'] }})
            </a>

            <a href="{{ route('admin.responses.index', array_merge(request()->except('nps_category'), ['nps_category' => 'promoter'])) }}"
               class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ request('nps_category') === 'promoter' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                Promoters ({{ $stats['promoters'] }})
            </a>

            <a href="{{ route('admin.responses.index', array_merge(request()->except('nps_category'), ['nps_category' => 'passive'])) }}"
               class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ request('nps_category') === 'passive' ? 'bg-amber-700 text-white shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                Passives ({{ $stats['passives'] }})
            </a>

            <a href="{{ route('admin.responses.index', array_merge(request()->except('nps_category'), ['nps_category' => 'detractor'])) }}"
               class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ request('nps_category') === 'detractor' ? 'bg-red-700 text-white shadow-xs' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                Detractors ({{ $stats['detractors'] }})
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.responses.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            @if(request('nps_category'))
                <input type="hidden" name="nps_category" value="{{ request('nps_category') }}">
            @endif

            <!-- Search Bar -->
            <div class="lg:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari ID respon, saran, atau kata kunci..."
                       class="w-full h-10 pl-10 pr-9 rounded-xl border border-gray-200 text-xs text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white">
                @if(request('search'))
                    <a href="{{ route('admin.responses.index', request()->except('search')) }}"
                       class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition" title="Hapus pencarian">
                        ✕
                    </a>
                @endif
            </div>

            <!-- Periode Dropdown -->
            <div class="lg:col-span-3">
                <select name="period_id"
                        class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer">
                    <option value="">Semua Periode</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                            {{ $period->name }} {{ $period->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Unit Kerja Dropdown -->
            <div class="lg:col-span-2">
                <select name="unit"
                        class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer">
                    <option value="">Semua Unit</option>
                    @foreach($demographics['units'] ?? [] as $u)
                        <option value="{{ $u }}" {{ request('unit') === $u ? 'selected' : '' }}>{{ $u }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Profesi Dropdown -->
            <div class="lg:col-span-2">
                <select name="profession"
                        class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer">
                    <option value="">Semua Profesi</option>
                    @foreach($demographics['professions'] ?? [] as $p)
                        <option value="{{ $p }}" {{ request('profession') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="lg:col-span-1 flex items-center gap-1.5">
                <x-button type="submit" variant="primary" size="md" class="w-full justify-center">
                    <span>Filter</span>
                </x-button>
                @if($hasActiveFilters)
                    <a href="{{ route('admin.responses.index') }}"
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
                        <a href="{{ route('admin.responses.index', request()->except('search')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
                    </span>
                @endif

                @if(request('period_id'))
                    @php $activeP = $periods->firstWhere('id', request('period_id')); @endphp
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-primary-50 text-primary-800 font-semibold text-xs border border-primary-200">
                        <span>Periode: <strong>{{ $activeP?->name ?? request('period_id') }}</strong></span>
                        <a href="{{ route('admin.responses.index', request()->except('period_id')) }}" class="text-primary-400 hover:text-red-500 font-bold ml-0.5">✕</a>
                    </span>
                @endif

                @if(request('unit'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
                        <span>Unit: <strong>{{ request('unit') }}</strong></span>
                        <a href="{{ route('admin.responses.index', request()->except('unit')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
                    </span>
                @endif

                @if(request('profession'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
                        <span>Profesi: <strong>{{ request('profession') }}</strong></span>
                        <a href="{{ route('admin.responses.index', request()->except('profession')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
                    </span>
                @endif

                @if(request('nps_category'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-semibold text-xs border border-emerald-200">
                        <span>Kategori: <strong>{{ ucfirst(request('nps_category')) }}</strong></span>
                        <a href="{{ route('admin.responses.index', request()->except('nps_category')) }}" class="text-emerald-400 hover:text-red-500 font-bold ml-0.5">✕</a>
                    </span>
                @endif

                <a href="{{ route('admin.responses.index') }}" class="text-xs font-bold text-red-600 hover:text-red-800 ml-1 transition">
                    Hapus Semua
                </a>
            </div>
        @endif
    </div>

    <!-- Responses Table Card -->
    <x-table-container class="border-gray-200/90 shadow-xs">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-gray-50/90 border-b border-gray-200 text-gray-500 font-extrabold text-[11px] uppercase tracking-wider select-none">
                    <th class="py-3.5 px-4 w-28">ID & Tanggal</th>
                    <th class="py-3.5 px-4 min-w-[200px]">Periode Survei</th>
                    <th class="py-3.5 px-4 min-w-[220px]">Demografi Responden</th>
                    <th class="py-3.5 px-4 min-w-[170px]">Skor Indeks</th>
                    <th class="py-3.5 px-4 text-center w-36">NPS</th>
                    <th class="py-3.5 px-4 text-right w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($responses as $resp)
                    <tr class="hover:bg-primary-50/20 transition-colors bg-white">
                        <!-- ID & Tanggal -->
                        <td class="py-4 px-4">
                            <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-700 block w-fit">
                                #{{ $resp->id }}
                            </span>
                            <span class="text-[11px] text-gray-400 block mt-1">
                                {{ $resp->completed_at?->format('d/m/Y H:i') }}
                            </span>
                        </td>

                        <!-- Periode Survei -->
                        <td class="py-4 px-4">
                            <div class="space-y-0.5">
                                <span class="font-bold text-gray-900 block truncate max-w-[220px]" title="{{ $resp->period?->name }}">
                                    {{ $resp->period?->name ?? 'Periode Tidak Diketahui' }}
                                </span>
                                @if($resp->period?->is_active)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Periode Aktif
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Demografi Responden -->
                        <td class="py-4 px-4">
                            <div class="space-y-0.5">
                                <div class="font-bold text-gray-900 text-xs flex items-center gap-1.5">
                                    <span>{{ $resp->unit }}</span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-gray-600">{{ $resp->profession }}</span>
                                </div>
                                <div class="text-[11px] text-gray-400">
                                    {{ $resp->status }} &mdash; {{ $resp->tenure }}
                                </div>
                            </div>
                        </td>

                        <!-- Skor Indeks -->
                        <td class="py-4 px-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="text-gray-500 text-[10px]">Kepuasan:</span>
                                    <span class="font-bold text-gray-900 font-mono">{{ $resp->overall_score }}/5</span>
                                </div>
                                <div class="flex items-center gap-2 text-[11px]">
                                    <span class="text-purple-700 bg-purple-50 px-1.5 py-0.2 rounded font-bold font-mono">MSQ: {{ number_format($resp->general_score, 1) }}</span>
                                    <span class="text-sky-700 bg-sky-50 px-1.5 py-0.2 rounded font-bold font-mono">RS: {{ number_format($resp->hospital_score, 1) }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- NPS & Kategori -->
                        <td class="py-4 px-4 text-center">
                            <div class="space-y-1">
                                <span class="font-black text-sm text-gray-900 font-mono block">
                                    {{ $resp->nps_score }} <span class="text-[10px] text-gray-400 font-normal">/ 10</span>
                                </span>
                                @if($resp->nps_category === 'promoter')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                        Promoter
                                    </span>
                                @elseif($resp->nps_category === 'passive')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800">
                                        Passive
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-red-100 text-red-800">
                                        Detractor
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Aksi -->
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Detail Button (triggers Alpine modal) -->
                                <button type="button" @click="openDetail({{ $resp->id }})"
                                        class="p-2 text-gray-500 hover:text-primary-700 hover:bg-primary-50 rounded-xl transition cursor-pointer"
                                        title="Lihat Detail Jawaban">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.responses.destroy', $resp->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus respon #{{ $resp->id }} ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer"
                                            title="Hapus Respon">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-bold text-gray-800">Tidak ada respon survei yang ditemukan</h4>
                                    <p class="text-xs text-gray-500">Coba sesuaikan kata kunci pencarian atau bersihkan filter yang aktif.</p>
                                </div>
                                @if($hasActiveFilters)
                                    <a href="{{ route('admin.responses.index') }}"
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

        <!-- Table Pagination Footer -->
        <div class="p-4 md:px-6 border-t border-gray-100 bg-gray-50/50">
            {{ $responses->links() }}
        </div>
    </x-table-container>

    <!-- Detail Modal -->
    @include('admin.responses.partials.detail-modal')

</div>
@endsection
