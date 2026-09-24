@extends('layouts.admin', ['title' => 'Periode Survei — HESS Admin'])

@section('header-title', 'Manajemen Periode Survei')

@section('content')
@php
$activePeriod = $stats['active_period'] ?? null;
$hasActiveFilters = request()->anyFilled(['search', 'status']);
$publicSurveyUrl = route('survey.index');
@endphp

<div x-data="periodsManager({
    activePeriodName: '{{ addslashes($activePeriod?->name ?? 'Survei Kepuasan Pegawai HESS') }}',
    publicSurveyUrl: '{{ $publicSurveyUrl }}',
    qrUrl: 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&margin=10&data={{ urlencode($publicSurveyUrl) }}'
})" class="space-y-6">

    <!-- Top Action Bar Header -->
    <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0 border border-primary-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base md:text-lg font-bold text-gray-900 tracking-tight">Siklus & Periode Pelaksanaan Survei</h2>
                    @if($activePeriod)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Survei Aktif Berjalan
                    </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Kelola jadwal pelaksanaan, target responden, aktivasi survei, serta bagikan tautan kuesioner ke seluruh pegawai.</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
            <x-button type="button" @click="openShare()" variant="secondary" size="md" class="border-gray-200 text-gray-700 hover:text-primary-700">
                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
                <span>Bagikan Tautan & QR</span>
            </x-button>
            <x-button type="button" @click="createModalOpen = true" variant="primary" size="md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Buka Periode Baru</span>
            </x-button>
        </div>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Periode Berjalan LIVE -->
        <div class="bg-white p-4 md:p-5 rounded-2xl border {{ $activePeriod ? 'border-emerald-300 ring-2 ring-emerald-50 bg-emerald-50/10' : 'border-gray-200/90' }} shadow-xs group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Periode Berjalan</span>
                @if($activePeriod)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-ping"></span>
                    LIVE
                </span>
                @else
                <x-badge color="gray" size="xs">Belum Ada</x-badge>
                @endif
            </div>
            <div class="truncate">
                <div class="text-base md:text-lg font-black text-gray-900 truncate" title="{{ $activePeriod?->name ?? 'Tidak Ada Periode Aktif' }}">
                    {{ $activePeriod?->name ?? 'Tidak Ada Aktif' }}
                </div>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center justify-between">
                <span>Target: <strong>{{ $activePeriod?->target ?? 0 }}</strong> responden</span>
                @if($activePeriod)
                <button type="button" @click="openShare('{{ addslashes($activePeriod->name) }}')" class="text-primary-700 font-bold hover:underline cursor-pointer">
                    Share & QR
                </button>
                @endif
            </div>
        </div>

        <!-- Total Responden Terkumpul -->
        <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Responden</span>
                <x-badge color="blue" size="xs">Akumulasi</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-gray-900 group-hover:text-blue-700 transition">{{ number_format($stats['total_responses'] ?? 0) }}</span>
                <span class="text-[11px] font-semibold text-gray-400">Pegawai Terlibat</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                <span>Data kuesioner terisi lengkap</span>
            </div>
        </div>

        <!-- Capaian Target Periode Aktif -->
        <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Capaian Periode Ini</span>
                <span class="text-xs font-black {{ ($stats['active_rate'] ?? 0) >= 100 ? 'text-emerald-600' : 'text-primary-700' }}">
                    {{ $stats['active_rate'] ?? 0 }}%
                </span>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-gray-900">
                    {{ $activePeriod?->responses_count ?? 0 }}
                    <span class="text-xs font-bold text-gray-400">/ {{ $activePeriod?->target ?? 0 }}</span>
                </span>
                <span class="text-[11px] font-semibold text-gray-400">Tercapai</span>
            </div>
            <div class="mt-2.5">
                <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 {{ ($stats['active_rate'] ?? 0) >= 100 ? 'bg-emerald-500' : 'bg-primary-600' }}"
                        style="width: {{ min($stats['active_rate'] ?? 0, 100) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Total Periode / Siklus -->
        <a href="{{ route('admin.periods.index') }}"
            class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs hover:border-amber-300 transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Periode</span>
                <x-badge color="amber" size="xs">Siklus</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-gray-900 group-hover:text-amber-700 transition">{{ $stats['total'] ?? 0 }}</span>
                <span class="text-[11px] font-semibold text-gray-400">{{ $stats['archived'] ?? 0 }} Diarsipkan</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>Arsip & riwayat survei tahunan</span>
            </div>
        </a>
    </div>

    <!-- Quick Filter Tabs & Search Bar -->
    <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-gray-100 pb-3">
            <!-- Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 scrollbar-none text-xs">
                <a href="{{ route('admin.periods.index', request()->only('search')) }}"
                    class="px-3.5 py-1.5 rounded-xl font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ !request()->filled('status') ? 'bg-primary-700 text-white shadow-xs' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span>Semua Periode</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ !request()->filled('status') ? 'bg-primary-800 text-white' : 'bg-gray-200 text-gray-700' }}">
                        {{ $stats['total'] ?? 0 }}
                    </span>
                </a>

                <a href="{{ route('admin.periods.index', array_merge(request()->only('search'), ['status' => 'active'])) }}"
                    class="px-3.5 py-1.5 rounded-xl font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ request('status') === 'active' ? 'bg-emerald-600 text-white shadow-xs' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>Periode Aktif</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ request('status') === 'active' ? 'bg-emerald-700 text-white' : 'bg-emerald-50 text-emerald-700' }}">
                        {{ $activePeriod ? 1 : 0 }}
                    </span>
                </a>

                <a href="{{ route('admin.periods.index', array_merge(request()->only('search'), ['status' => 'inactive'])) }}"
                    class="px-3.5 py-1.5 rounded-xl font-bold transition flex items-center gap-1.5 whitespace-nowrap {{ request('status') === 'inactive' ? 'bg-gray-700 text-white shadow-xs' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span>Arsip / Riwayat</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ request('status') === 'inactive' ? 'bg-gray-800 text-white' : 'bg-gray-200 text-gray-700' }}">
                        {{ $stats['archived'] ?? 0 }}
                    </span>
                </a>
            </div>

            <!-- Active Filter Status -->
            @if($hasActiveFilters)
            <div class="flex items-center gap-2 text-xs">
                <span class="text-gray-400 text-[11px]">Filter aktif:</span>
                <a href="{{ route('admin.periods.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-red-600 hover:text-red-700 bg-red-50 px-2 py-0.5 rounded-md">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Reset Filter</span>
                </a>
            </div>
            @endif
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.periods.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            @if(request()->filled('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="md:col-span-10 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama periode survei (contoh: 2026, Periode 1)..."
                    class="w-full pl-10 pr-10 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                @if(request()->filled('search'))
                <a href="{{ route('admin.periods.index', request()->only('status')) }}"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                    title="Hapus pencarian">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
                @endif
            </div>

            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2.5 text-xs font-bold text-white bg-primary-700 hover:bg-primary-800 rounded-xl transition shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Cari</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Periods Table Card -->
    <x-table-container>
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-500 font-extrabold uppercase tracking-wider text-[11px]">
                    <th class="py-3.5 px-4">Nama Periode Survei</th>
                    <th class="py-3.5 px-4 w-52">Capaian Responden</th>
                    <th class="py-3.5 px-4 w-52">Rentang Pelaksanaan</th>
                    <th class="py-3.5 px-4 w-32 text-center">Status</th>
                    <th class="py-3.5 px-4 text-right w-52">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($periods as $period)
                @php
                $rate = $period->target > 0 ? round(($period->responses_count / $period->target) * 100, 1) : 0;
                $periodData = [
                'id' => $period->id,
                'name' => $period->name,
                'target' => $period->target,
                'start_date_formatted' => $period->start_date?->format('Y-m-d'),
                'end_date_formatted' => $period->end_date?->format('Y-m-d'),
                'is_active' => $period->is_active,
                ];

                $isOngoing = $period->start_date && $period->end_date && !$period->end_date->isPast() && !$period->start_date->isFuture();
                $isUpcoming = $period->start_date && $period->start_date->isFuture();
                $isPast = $period->end_date && $period->end_date->isPast();
                @endphp
                <tr class="hover:bg-gray-50/70 transition-colors {{ $period->is_active ? 'bg-primary-50/20' : '' }}">
                    <td class="py-4 px-4">
                        <div class="flex items-start gap-2.5">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 {{ $period->is_active ? 'bg-emerald-100 text-emerald-800 ring-2 ring-emerald-200' : 'bg-gray-100 text-gray-600' }}">
                                @if($period->is_active)
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                @else
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-gray-900 text-sm flex items-center gap-2 flex-wrap">
                                    <span class="hover:text-primary-700 transition">{{ $period->name }}</span>
                                    @if($period->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                        LIVE
                                    </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mt-1 text-[11px] text-gray-400">
                                    <span>Dibuat: {{ $period->created_at?->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="py-4 px-4">
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-gray-800 font-mono">{{ $period->responses_count }} <span class="text-gray-400 font-normal">/ {{ $period->target }}</span></span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold {{ $rate >= 100 ? 'bg-emerald-100 text-emerald-800' : 'bg-primary-100 text-primary-800' }}">
                                {{ $rate }}%
                            </span>
                        </div>
                        <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $rate >= 100 ? 'bg-emerald-500' : 'bg-primary-600' }}" style="width: {{ min($rate, 100) }}%"></div>
                        </div>
                        <div class="mt-1 text-[10px] text-gray-400">
                            @if($rate >= 100)
                            <span class="text-emerald-600 font-semibold">Target tercapai penuh</span>
                            @else
                            <span>Kurang {{ max(0, $period->target - $period->responses_count) }} responden</span>
                            @endif
                        </div>
                    </td>

                    <td class="py-4 px-4 text-gray-600">
                        <div class="font-semibold text-xs text-gray-800">
                            {{ $period->start_date?->format('d M Y') }} &mdash; {{ $period->end_date?->format('d M Y') }}
                        </div>
                        <div class="mt-1">
                            @if($isPast)
                            <span class="inline-flex items-center gap-1 text-[10px] font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Telah berakhir
                            </span>
                            @elseif($isUpcoming)
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Akan datang
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Sedang Berlangsung
                            </span>
                            @endif
                        </div>
                    </td>

                    <td class="py-4 px-4 text-center">
                        @if($period->is_active)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600">
                            Arsip
                        </span>
                        @endif
                    </td>

                    <td class="py-4 px-4 text-right">
                        <div class="inline-flex items-center gap-1">
                            <!-- Tombol Bagikan Link & QR Modal -->
                            <button type="button" @click="openShare('{{ addslashes($period->name) }}')"
                                class="p-1.5 text-gray-500 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition cursor-pointer"
                                title="Bagikan Tautan & QR Code">
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                            </button>

                            <!-- Lihat Analitik -->
                            <a href="{{ route('admin.dashboard', ['period_id' => $period->id]) }}"
                                class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block cursor-pointer"
                                title="Lihat Dashboard Analitik Periode Ini">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </a>

                            <!-- Edit Button -->
                            <button type="button" @click="openEdit({{ Js::from($periodData) }})"
                                class="p-1.5 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition cursor-pointer"
                                title="Edit Periode">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>

                            @if(!$period->is_active)
                            <!-- Tombol Jadikan Aktif -->
                            <form action="{{ route('admin.periods.activate', $period->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Jadikan Periode Aktif"
                                    class="px-2.5 py-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition cursor-pointer">
                                    Aktifkan
                                </button>
                            </form>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.periods.destroy', $period->id) }}" method="POST" class="inline"
                                data-title="Hapus Periode Survei?"
                                data-confirm="Apakah Anda yakin ingin menghapus periode '{{ $period->name }}'?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer"
                                    title="Hapus Periode">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-gray-400">
                        <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3 text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="font-bold text-gray-700 text-sm">Tidak ada periode survei yang sesuai.</p>
                        <p class="text-xs text-gray-400 mt-1">Coba sesuaikan kata kunci pencarian atau buka periode baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-table-container>

    {{-- Modals --}}
    @include('admin.periods.partials.share-modal')
    @include('admin.periods.partials.create-modal')
    @include('admin.periods.partials.edit-modal')

</div>
@endsection