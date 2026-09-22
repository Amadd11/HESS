@php
$promoterPct = $totalResponses > 0 ? round(($promoters / $totalResponses) * 100, 1) : 0;
$detractorPct = $totalResponses > 0 ? round(($detractors / $totalResponses) * 100, 1) : 0;
$passivePct = $totalResponses > 0 ? round(($passives / $totalResponses) * 100, 1) : 0;

if ($npsScore >= 50) {
$npsGrade = 'A+';
$npsPredicate = 'Istimewa';
$npsBadgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300';
} elseif ($npsScore >= 20) {
$npsGrade = 'A';
$npsPredicate = 'Sangat Baik';
$npsBadgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300';
} elseif ($npsScore >= 0) {
$npsGrade = 'B';
$npsPredicate = 'Baik / Sehat';
$npsBadgeClass = 'bg-purple-100 text-purple-800 border-purple-300';
} elseif ($npsScore >= -20) {
$npsGrade = 'C';
$npsPredicate = 'Perlu Evaluasi';
$npsBadgeClass = 'bg-amber-100 text-amber-800 border-amber-300';
} else {
$npsGrade = 'D';
$npsPredicate = 'Kritis';
$npsBadgeClass = 'bg-rose-100 text-rose-800 border-rose-300';
}
@endphp

<div x-data="dashboardCharts({
    radarSeries: {{ Js::from($hospitalCategoryScores->pluck('percentage_score')) }},
    radarCategories: {{ Js::from($hospitalCategoryScores->pluck('name')) }},
    rawScores: {{ Js::from($hospitalCategoryScores->pluck('avg_raw_score')) }},
    radarCounts: {{ Js::from($hospitalCategoryScores->pluck('respondents_count')) }},
    unitLabels: {{ Js::from($unitScores->pluck('unit')) }},
    unitData: {{ Js::from($unitScores->pluck('avg_score')) }},
    unitCounts: {{ Js::from($unitScores->pluck('total')) }},
    profLabels: {{ Js::from($professionScores->pluck('profession')) }},
    profData: {{ Js::from($professionScores->pluck('avg_score')) }},
    profCounts: {{ Js::from($professionScores->pluck('total')) }},
    promoters: {{ $promoters }},
    passives: {{ $passives }},
    detractors: {{ $detractors }},
    npsScore: {{ $npsScore }},
    totalResponses: {{ $totalResponses }}
})" x-init="initCharts()" class="space-y-6" id="radar-section">

    <!-- Section Title & Context -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3">
        <div>
            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-primary-600 inline-block"></span>
                <span>Visualisasi & Peta Analitik Kepuasan Pegawai</span>
            </h2>
            <p class="text-xs text-gray-500 mt-0.5">Pemetaan grafis faktor rumah sakit, loyalitas responden, dan komparasi skor antar unit kerja.</p>
        </div>
        <div class="inline-flex items-center gap-2 text-xs font-semibold text-gray-600 bg-gray-50/80 px-3 py-1.5 rounded-xl border border-gray-200/70 shrink-0">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Data Teragregasi Real-Time ({{ number_format($totalResponses) }} Responden)</span>
        </div>
    </div>

    <!-- Baris 1: Radar 8 Dimensi RS (7 Kolom) + eNPS Donut & Rincian Loyalitas (5 Kolom) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

        <!-- Card 1: Grafik 8 Dimensi RS (Horizontal Bar Chart) -->
        <div class="lg:col-span-7 bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-gray-900">Skor 8 Dimensi Rumah Sakit</h3>
                            <p class="text-[11px] text-gray-400">Peringkat kepuasan per faktor lingkungan & budaya kerja RS</p>
                        </div>
                    </div>
                    <x-badge color="blue" size="xs">Skala 0–100%</x-badge>
                </div>

                <div class="relative min-h-[350px] flex items-center justify-center">
                    @if($hospitalCategoryScores->count() > 0 && $totalResponses > 0)
                    <div id="hospitalRadarChart" class="w-full"></div>
                    @else
                    <div class="text-center py-12 text-gray-400">
                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-xs font-semibold">Belum cukup data respon untuk memetakan dimensi.</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
                <span>Menampilkan 8 faktor lingkungan & budaya kerja RS</span>
                <a href="#hospital-dimensions" class="text-primary-700 hover:underline font-bold flex items-center gap-1">
                    <span>Lihat Rincian 8 Dimensi</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Card 2: Distribusi eNPS & Loyalitas Pegawai dengan Predikat -->
        <div class="lg:col-span-5 bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
            <div>
                <!-- Header Card dengan Predikat Grade -->
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2m-7 0H4a2 2 0 00-2 2v6a2 2 0 002 2h3" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-gray-900">Tingkat Loyalitas Pegawai (eNPS)</h3>
                            <p class="text-[11px] text-gray-400">Tingkat kesediaan merekomendasikan RS</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-black border shadow-2xs {{ $npsBadgeClass }}">
                        <span>🏆</span>
                        <span>Predikat {{ $npsGrade }}</span>
                    </span>
                </div>

                <!-- 1. Meteran Predikat 5 Tahap Visual -->
                <div class="grid grid-cols-5 gap-1 text-center p-1 bg-gray-50/90 rounded-xl border border-gray-200/70 text-[10px] font-bold mb-3">
                    <div class="py-1 px-0.5 rounded-lg transition-all {{ $npsGrade === 'D' ? 'bg-rose-600 text-white shadow-xs font-black' : 'text-gray-400 bg-white/70' }}">
                        D: Kritis
                    </div>
                    <div class="py-1 px-0.5 rounded-lg transition-all {{ $npsGrade === 'C' ? 'bg-amber-500 text-white shadow-xs font-black' : 'text-gray-400 bg-white/70' }}">
                        C: Evaluasi
                    </div>
                    <div class="py-1 px-0.5 rounded-lg transition-all {{ $npsGrade === 'B' ? 'bg-purple-600 text-white shadow-xs font-black' : 'text-gray-400 bg-white/70' }}">
                        B: Baik
                    </div>
                    <div class="py-1 px-0.5 rounded-lg transition-all {{ $npsGrade === 'A' ? 'bg-emerald-600 text-white shadow-xs font-black ring-2 ring-emerald-400' : 'text-gray-400 bg-white/70' }}">
                        A: Sangat Baik
                    </div>
                    <div class="py-1 px-0.5 rounded-lg transition-all {{ $npsGrade === 'A+' ? 'bg-emerald-700 text-white shadow-xs font-black ring-2 ring-emerald-400' : 'text-gray-400 bg-white/70' }}">
                        A+: Unggul
                    </div>
                </div>

                <!-- 2. Grafik Donut dengan Center Predikat -->
                <div class="relative min-h-[230px] flex items-center justify-center">
                    @if($totalResponses > 0)
                    <div id="npsDonutChart" class="w-full"></div>
                    @else
                    <div class="text-center py-12 text-gray-400">
                        <p class="text-xs font-semibold">Belum ada respon responden.</p>
                    </div>
                    @endif
                </div>

                <!-- 3. Kotak Rumus Visual Pengurangan (+30) -->
                <div class="p-2.5 rounded-xl bg-gradient-to-r from-emerald-50/90 via-gray-50 to-rose-50/90 border border-gray-200/80 flex items-center justify-between text-xs font-bold shadow-2xs mb-3">
                    <div class="flex items-center gap-1.5 text-emerald-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                        <span>{{ $promoterPct }}% Suka</span>
                    </div>
                    <span class="text-gray-400 text-sm font-black">−</span>
                    <div class="flex items-center gap-1.5 text-rose-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
                        <span>{{ $detractorPct }}% Kecewa</span>
                    </div>
                    <span class="text-gray-400 text-sm font-black">=</span>
                    <div class="px-2.5 py-1 rounded-lg bg-white border border-gray-200/90 shadow-xs flex items-center gap-1 text-emerald-700 font-extrabold text-xs">
                        <span>⭐</span>
                        <span>{{ ($npsScore > 0 ? '+' : '') . $npsScore }} Poin ({{ $npsPredicate }})</span>
                    </div>
                </div>

                <!-- 4. Rincian Komposisi Pegawai (Bahasa Ramah Manusiawi) -->
                <div class="space-y-2 pt-1 border-t border-gray-100 text-xs">
                    <!-- Promoter -->
                    <div class="p-2 rounded-xl bg-emerald-50/40 border border-emerald-100/60 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                            <div>
                                <span class="font-bold text-gray-900">Pegawai Loyal & Merekomendasikan</span>
                                <span class="text-[10px] text-gray-400 block">Promoter (Skor 9–10)</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-extrabold text-emerald-700">{{ $promoters }} Orang</span>
                            <span class="text-[10px] text-gray-400 block font-medium">{{ $promoterPct }}%</span>
                        </div>
                    </div>

                    <!-- Pasif -->
                    <div class="p-2 rounded-xl bg-amber-50/40 border border-amber-100/60 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shrink-0"></span>
                            <div>
                                <span class="font-bold text-gray-900">Pegawai Cukup Puas (Netral)</span>
                                <span class="text-[10px] text-gray-400 block">Pasif (Skor 7–8)</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-extrabold text-amber-700">{{ $passives }} Orang</span>
                            <span class="text-[10px] text-gray-400 block font-medium">{{ $passivePct }}%</span>
                        </div>
                    </div>

                    <!-- Detractor -->
                    <div class="p-2 rounded-xl bg-rose-50/40 border border-rose-100/60 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
                            <div>
                                <span class="font-bold text-gray-900">Pegawai Butuh Perhatian & Solusi</span>
                                <span class="text-[10px] text-gray-400 block">Detractor (Skor 0–6)</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-extrabold text-rose-700">{{ $detractors }} Orang</span>
                            <span class="text-[10px] text-gray-400 block font-medium">{{ $detractorPct }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100 text-[10px] text-gray-400 text-center">
                * Kelompok Netral ({{ $passivePct }}%) tidak mengurangi nilai loyalitas.
            </div>
        </div>

    </div>

    <!-- Baris 2: Komparasi Peringkat Kepuasan Pegawai (Full Width & Spacious) -->
    <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-gray-900">Komparasi Peringkat Kepuasan Pegawai</h3>
                        <p class="text-[11px] text-gray-400">Peringkat rata-rata kepuasan per unit operasional & rumpun profesi RS</p>
                    </div>
                </div>

                <!-- Tab Toggle Button -->
                <div class="inline-flex p-1 rounded-xl bg-gray-100 text-xs font-bold shrink-0">
                    <button type="button" @click="setChartTab('unit')"
                        :class="chartTab === 'unit' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                        class="px-3.5 py-1.5 rounded-lg transition cursor-pointer flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Unit Kerja ({{ $unitScores->count() }})</span>
                    </button>
                    <button type="button" @click="setChartTab('profession')"
                        :class="chartTab === 'profession' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                        class="px-3.5 py-1.5 rounded-lg transition cursor-pointer flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Kelompok Profesi ({{ $professionScores->count() }})</span>
                    </button>
                </div>
            </div>

            <div class="relative min-h-[350px] flex items-center justify-center">
                @if(($unitScores->count() > 0 || $professionScores->count() > 0) && $totalResponses > 0)
                <div id="comparisonBarChart" class="w-full"></div>
                @else
                <div class="text-center py-12 text-gray-400">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-xs font-semibold">Belum ada respon responden pada segmen ini.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Bar Footer with Quick Highlights -->
        <div class="pt-3 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px] text-gray-500">
            <div class="flex items-center gap-4">
                @if($unitScores->count() > 0)
                <span class="flex items-center gap-1.5">
                    <span class="text-emerald-600 font-bold">🏆 Skor Tertinggi:</span>
                    <strong class="text-gray-800">{{ $unitScores->first()?->unit }} ({{ $unitScores->first()?->avg_score }}%)</strong>
                </span>
                <span class="text-gray-300 hidden sm:inline">|</span>
                <span class="flex items-center gap-1.5">
                    <span class="text-amber-600 font-bold">⚠️ Perhatian:</span>
                    <strong class="text-gray-800">{{ $unitScores->last()?->unit }} ({{ $unitScores->last()?->avg_score }}%)</strong>
                </span>
                @endif
            </div>
            <span class="font-bold text-gray-700">Total Basis: {{ number_format($totalResponses) }} Jawaban Terkumpul</span>
        </div>
    </div>

</div>