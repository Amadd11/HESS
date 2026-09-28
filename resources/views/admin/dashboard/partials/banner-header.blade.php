@php
    $hasFilters = request()->anyFilled(['profession', 'unit', 'status', 'tenure']);
@endphp

<div class="bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700 rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-7 text-white shadow-sm relative z-20">
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-5">
        <div>
            <div class="flex items-center gap-2 mb-1.5 sm:mb-2 flex-wrap">
                <x-badge :color="$selectedPeriod?->is_active ? 'emerald' : 'gray'" size="xs" :dot="$selectedPeriod?->is_active ? true : false">
                    {{ $selectedPeriod?->is_active ? 'Periode Aktif' : 'Arsip Periode' }}
                </x-badge>
                <span class="text-xs text-primary-200/90 font-medium">Siklus Evaluasi RS</span>
            </div>
            <h2 class="text-lg sm:text-xl md:text-2xl font-black tracking-tight flex items-center gap-2.5">
                <span>{{ $selectedPeriod?->name ?? 'Belum Ada Periode Terpilih' }}</span>
            </h2>
            <div class="flex flex-wrap items-center gap-y-1 gap-x-2 sm:gap-x-3 text-xs sm:text-sm text-primary-100/90 mt-1">
                <span>Rentang: {{ $selectedPeriod ? $selectedPeriod->start_date->format('d M Y') . ' — ' . $selectedPeriod->end_date->format('d M Y') : '-' }}</span>
                @if($hasFilters)
                    <span>&bull;</span>
                    <span class="font-bold text-amber-300">Hasil Segmentasi Terfilter</span>
                @endif
            </div>
        </div>

        <!-- Header Action Buttons -->
        <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 shrink-0 print:hidden">
            <!-- Dropdown Unduh Laporan (2 Pilihan: PDF & Excel) -->
            <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                <button @click="open = !open" type="button"
                        class="px-3 sm:px-3.5 py-2 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs border border-white/25 transition flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Unduh Laporan</span>
                    <svg class="w-3.5 h-3.5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 text-gray-800">
                    <div class="px-3.5 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 border-b border-gray-100">
                        Pilihan Format Unduh
                    </div>

                    <!-- 1. Pilihan PDF -->
                    <a href="{{ route('admin.dashboard.report', request()->query()) }}" target="_blank" @click="open = false"
                       class="flex items-center gap-2.5 px-3.5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition">
                        <span class="w-7 h-7 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0 font-black text-[10px] border border-red-100">
                            PDF
                        </span>
                        <div>
                            <div class="font-bold text-gray-900 leading-tight">Laporan Eksekutif (PDF)</div>
                            <div class="text-[10px] text-gray-400">Format siap cetak A4 / PDF</div>
                        </div>
                    </a>

                    <!-- 2. Pilihan Excel -->
                    <a href="{{ route('admin.dashboard.export', array_merge(request()->query(), ['from_dashboard' => 1, 'period_id' => $selectedPeriod?->id])) }}" @click="open = false"
                       class="flex items-center gap-2.5 px-3.5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 font-black text-[10px] border border-emerald-100">
                            XLS
                        </span>
                        <div>
                            <div class="font-bold text-gray-900 leading-tight">Data Survei (Excel)</div>
                            <div class="text-[10px] text-gray-400">File spreadsheet .xlsx</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Manage Period Link -->
            <a href="{{ route('admin.periods.index') }}"
               class="px-3 sm:px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Kelola Periode</span>
            </a>
        </div>
    </div>
</div>
