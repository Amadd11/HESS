{{-- Executive Demographic Header Banner --}}
<div class="bg-gradient-to-r from-teal-900 via-primary-900 to-indigo-900 rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-7 text-white shadow-sm relative z-20">
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-5">
        <div>
            <div class="flex items-center gap-2 mb-1.5 sm:mb-2 flex-wrap">
                <x-badge :color="$selectedPeriod?->is_active ? 'emerald' : 'gray'" size="xs" :dot="$selectedPeriod?->is_active ? true : false">
                    {{ $selectedPeriod?->is_active ? 'Periode Aktif' : 'Arsip Periode' }}
                </x-badge>
                <span class="text-xs text-primary-200/90 font-medium">Modul Khusus Analisis Demografi</span>
            </div>
            <h1 class="text-lg sm:text-xl md:text-2xl font-black tracking-tight flex items-center gap-2.5">
                <span>Analisis Karakteristik Demografi Pegawai</span>
            </h1>
            <p class="text-xs sm:text-sm text-primary-100/90 mt-1 max-w-2xl leading-relaxed">
                Evaluasi komposisi profil responden berdasarkan kelompok <strong>Usia</strong>, <strong>Jenis Kelamin</strong>, <strong>Tingkat Pendapatan</strong>, <strong>Status Kepegawaian</strong>, serta <strong>Latar Belakang Pendidikan</strong>.
            </p>
            <div class="flex flex-wrap items-center gap-y-1 gap-x-2 sm:gap-x-3 text-xs text-primary-200/80 mt-2">
                <span>Periode: <strong>{{ $selectedPeriod?->name ?? 'Semua Periode' }}</strong></span>
                @if($hasFilters)
                <span>&bull;</span>
                <span class="font-bold text-amber-300">Segmentasi Terfilter Aktif</span>
                @endif
            </div>
        </div>

        <!-- Header Quick Action Buttons -->
        <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 shrink-0 print:hidden">
            <a href="{{ route('admin.dashboard', request()->query()) }}"
                class="px-3 sm:px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard Analytics</span>
            </a>

            <a href="{{ $allResponsesUrl }}"
                class="px-3 sm:px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Data Respon</span>
            </a>
        </div>
    </div>
</div>