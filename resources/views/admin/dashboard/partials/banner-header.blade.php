@php
    $hasFilters = request()->anyFilled(['profession', 'unit', 'status', 'tenure']);
@endphp

<div class="bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700 rounded-3xl p-6 md:p-7 text-white shadow-sm relative overflow-hidden">
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <x-badge :color="$selectedPeriod?->is_active ? 'emerald' : 'gray'" size="xs" :dot="$selectedPeriod?->is_active ? true : false">
                    {{ $selectedPeriod?->is_active ? 'Periode Aktif' : 'Arsip Periode' }}
                </x-badge>
                <span class="text-xs text-primary-200/90 font-medium">Siklus Evaluasi RS</span>
            </div>
            <h2 class="text-xl md:text-2xl font-black tracking-tight flex items-center gap-2.5">
                <span>{{ $selectedPeriod?->name ?? 'Belum Ada Periode Terpilih' }}</span>
            </h2>
            <div class="flex flex-wrap items-center gap-y-1 gap-x-3 text-xs md:text-sm text-primary-100/90 mt-1">
                <span>Rentang Pelaksanaan: {{ $selectedPeriod ? $selectedPeriod->start_date->format('d M Y') . ' — ' . $selectedPeriod->end_date->format('d M Y') : '-' }}</span>
                @if($hasFilters)
                    <span>&bull;</span>
                    <span class="font-bold text-amber-300">Menampilkan Hasil Segmentasi Terfilter</span>
                @endif
            </div>
        </div>

        <!-- Header Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <!-- Print Report Button -->
            <button type="button" onclick="window.print()"
                    class="px-3.5 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs border border-white/20 backdrop-blur-sm transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Laporan</span>
            </button>

            <!-- Export Filtered CSV -->
            <a href="{{ route('admin.dashboard.export', request()->query()) }}"
               class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Unduh CSV</span>
            </a>

            <!-- Manage Period Link -->
            <a href="{{ route('admin.periods.index') }}"
               class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Kelola Periode</span>
            </a>
        </div>
    </div>
</div>
