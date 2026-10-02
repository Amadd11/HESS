{{-- 4 Executive KPI Metric Cards (Drill-Down Highlights) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
    <!-- KPI 1: Total Responden Sampel -->
    <a href="{{ $allResponsesUrl }}" class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200/90 shadow-xs flex items-center gap-3.5 group hover:border-teal-300 hover:shadow-md transition cursor-pointer" title="Klik untuk membuka data respon seluruh pegawai">
        <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100 group-hover:scale-105 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <div class="min-w-0">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block truncate">Total Responden</span>
            <span class="text-xl sm:text-2xl font-black text-gray-900 block leading-tight">{{ number_format($totalResponses) }} <span class="text-xs font-bold text-gray-500">Pegawai</span></span>
            <span class="text-[11px] text-teal-600 font-semibold truncate flex items-center gap-1">
                <span>Lihat respon</span>
                <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </span>
        </div>
    </a>

    <!-- KPI 2: Kelompok Usia Dominan -->
    <a @if(!empty($dominantAge['drill_url'])) href="{{ $dominantAge['drill_url'] }}" @endif class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200/90 shadow-xs flex items-center gap-3.5 group hover:border-indigo-300 hover:shadow-md transition {{ !empty($dominantAge['drill_url']) ? 'cursor-pointer' : '' }}" title="{{ !empty($dominantAge['drill_url']) ? 'Klik untuk melihat data respon rentang usia ' . $dominantAge['label'] : '' }}">
        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100 group-hover:scale-105 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="min-w-0">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block truncate">Usia Terbanyak</span>
            <span class="text-base sm:text-lg font-black text-gray-900 block leading-tight truncate" title="{{ $dominantAge['label'] ?? '-' }}">
                {{ $dominantAge['label'] ?? 'Tidak ada data' }}
            </span>
            <span class="text-[11px] text-indigo-600 font-semibold flex items-center gap-1 truncate">
                @if($dominantAge)
                    <span>{{ number_format($dominantAge['count']) }} org ({{ number_format($dominantAge['percentage'], 1) }}%)</span>
                    <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                @else
                    -
                @endif
            </span>
        </div>
    </a>

    <!-- KPI 3: Mayoritas Jenis Kelamin -->
    <a @if(!empty($dominantGender['drill_url'])) href="{{ $dominantGender['drill_url'] }}" @endif class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200/90 shadow-xs flex items-center gap-3.5 group hover:border-rose-300 hover:shadow-md transition {{ !empty($dominantGender['drill_url']) ? 'cursor-pointer' : '' }}" title="{{ !empty($dominantGender['drill_url']) ? 'Klik untuk melihat data respon gender ' . $dominantGender['label'] : '' }}">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100 group-hover:scale-105 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <div class="min-w-0">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block truncate">Mayoritas Gender</span>
            <span class="text-base sm:text-lg font-black text-gray-900 block leading-tight truncate">
                {{ $dominantGender['label'] ?? 'Tidak ada data' }}
            </span>
            <span class="text-[11px] text-rose-600 font-semibold flex items-center gap-1 truncate">
                @if($dominantGender)
                    <span>{{ number_format($dominantGender['count']) }} org ({{ number_format($dominantGender['percentage'], 1) }}%)</span>
                    <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                @else
                    -
                @endif
            </span>
        </div>
    </a>

    <!-- KPI 4: Tingkat Pendapatan Dominan -->
    <a @if(!empty($dominantIncome['drill_url'])) href="{{ $dominantIncome['drill_url'] }}" @endif class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200/90 shadow-xs flex items-center gap-3.5 group hover:border-emerald-300 hover:shadow-md transition {{ !empty($dominantIncome['drill_url']) ? 'cursor-pointer' : '' }}" title="{{ !empty($dominantIncome['drill_url']) ? 'Klik untuk melihat data respon pendapatan ' . $dominantIncome['label'] : '' }}">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 group-hover:scale-105 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="min-w-0">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block truncate">Pendapatan Terbanyak</span>
            <span class="text-base sm:text-lg font-black text-gray-900 block leading-tight truncate" title="{{ $dominantIncome['label'] ?? '-' }}">
                {{ $dominantIncome['label'] ?? 'Tidak ada data' }}
            </span>
            <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1 truncate">
                @if($dominantIncome)
                    <span>{{ number_format($dominantIncome['count']) }} org ({{ number_format($dominantIncome['percentage'], 1) }}%)</span>
                    <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                @else
                    -
                @endif
            </span>
        </div>
    </a>
</div>
