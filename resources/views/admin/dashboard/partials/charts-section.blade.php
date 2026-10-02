<div x-data="dashboardCharts({
    radarSeries: {{ Js::from($hospitalCategoryScores->pluck('percentage_score')) }},
    radarCategories: {{ Js::from($hospitalCategoryScores->pluck('name')) }},
    rawScores: {{ Js::from($hospitalCategoryScores->pluck('avg_raw_score')) }},
    radarCounts: {{ Js::from($hospitalCategoryScores->pluck('respondents_count')) }},
    unitLabels: {{ Js::from($unitScores->pluck('unit')) }},
    unitData: {{ Js::from($unitScores->pluck('avg_score')) }},
    unitCounts: {{ Js::from($unitScores->pluck('total')) }},
    unitDirectorates: {{ Js::from($unitScores->pluck('directorate')) }},
    profLabels: {{ Js::from($professionScores->pluck('profession')) }},
    profData: {{ Js::from($professionScores->pluck('avg_score')) }},
    profCounts: {{ Js::from($professionScores->pluck('total')) }},
    directorateLabels: {{ Js::from($directorateScores->pluck('directorate')) }},
    directorateData: {{ Js::from($directorateScores->pluck('avg_score')) }},
    directorateCounts: {{ Js::from($directorateScores->pluck('total')) }},
    totalResponses: {{ $totalResponses }},
    activeDirectorate: '{{ request('directorate', '') }}'
})" x-init="initCharts()" class="space-y-6" id="radar-section">

    <!-- Section Title & Context -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3">
        <div>
            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-primary-600 inline-block"></span>
                <span>Visualisasi & Peta Analitik Kepuasan Pegawai</span>
            </h2>
            <p class="text-xs text-gray-500 mt-0.5">Pemetaan grafis faktor rumah sakit, loyalitas responden, dan komparasi skor antar satuan kerja.</p>
        </div>
        <div class="inline-flex items-center gap-2 text-xs font-semibold text-gray-600 bg-gray-50/80 px-3 py-1.5 rounded-xl border border-gray-200/70 shrink-0">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Data Teragregasi Real-Time ({{ number_format($totalResponses) }} Responden)</span>
        </div>
    </div>

    @php
    $sortedCats = $hospitalCategoryScores->sortByDesc('percentage_score')->values();
    $highestCat = $sortedCats->first();
    $lowestCat = $sortedCats->last();
    $avgCatScore = $hospitalCategoryScores->count() > 0 ? round($hospitalCategoryScores->avg('percentage_score'), 1) : 0;
    @endphp

    <!-- Skor Indikator Survei Kepuasan Pegawai (2-Kolom Layout Proporsional) -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 mb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-gray-900">Skor Indikator Survei Kepuasan Pegawai</h3>
                        <p class="text-[11px] text-gray-400">Peringkat kepuasan per indikator lingkungan & budaya kerja pegawai</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold text-gray-400 hidden sm:inline">{{ $hospitalCategoryScores->count() }} Indikator</span>
                    <x-badge color="blue" size="xs">Skala 0–100%</x-badge>
                </div>
            </div>

            <!-- Konten 2-Kolom di Layar Lebar: Grafik di Kiri, Ringkasan di Kanan -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-6 items-stretch">
                <!-- Kolom Kiri: Visual Grafik Bar (~70% lebar) -->
                <div class="lg:col-span-8 xl:col-span-9 min-w-0 flex flex-col justify-center">
                    <div class="relative min-h-[260px] sm:min-h-[275px] flex items-center justify-center">
                        @if($hospitalCategoryScores->count() > 0 && $totalResponses > 0)
                        <div id="hospitalRadarChart" class="w-full min-w-0"></div>
                        @else
                        <div class="text-center py-10 text-gray-400">
                            <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <p class="text-xs font-semibold">Belum cukup data respon untuk memetakan dimensi.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Kolom Kanan: Panel Ringkasan Eksekutif (~30% lebar) -->
                <div class="lg:col-span-4 xl:col-span-3 flex flex-col justify-between border-t lg:border-t-0 lg:border-l border-gray-100 pt-3.5 lg:pt-0 lg:pl-5 space-y-3">
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Ringkasan Dimensi</span>
                            <span class="text-[10px] font-semibold text-primary-700 bg-primary-50 px-2 py-0.5 rounded-md">8 Dimensi RS</span>
                        </div>

                        <!-- Mini Card: Rata-Rata Indikator -->
                        <div class="p-3 rounded-xl bg-gray-50/80 border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-medium text-gray-500">Rata-rata Indikator</p>
                                <p class="text-base font-black text-gray-900 leading-tight">{{ $avgCatScore }}%</p>
                            </div>
                            <x-badge :color="$avgCatScore >= 81 ? 'emerald' : ($avgCatScore >= 61 ? 'primary' : 'amber')" size="xs">
                                {{ $avgCatScore >= 81 ? 'Optimal' : ($avgCatScore >= 61 ? 'Baik' : 'Perhatian') }}
                            </x-badge>
                        </div>

                        @if($highestCat)
                        <!-- Mini Card: Indikator Tertinggi -->
                        <div class="p-3 rounded-xl bg-emerald-50/40 border border-emerald-100/70 hover:bg-emerald-50/70 transition space-y-1">
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="font-bold text-emerald-800 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Indikator Tertinggi
                                </span>
                                <span class="font-black text-emerald-700">{{ $highestCat->percentage_score }}%</span>
                            </div>
                            <p class="text-xs font-bold text-gray-900 truncate" title="{{ $highestCat->name }}">
                                {{ $highestCat->name }}
                            </p>
                            <p class="text-[10px] text-gray-500 flex items-center justify-between pt-0.5">
                                <span>Kode: <strong class="text-gray-700">{{ $highestCat->code }}</strong></span>
                                <span>{{ $highestCat->avg_raw_score }} / 4.0</span>
                            </p>
                        </div>
                        @endif

                        @if($lowestCat && $lowestCat->id !== ($highestCat->id ?? null))
                        <!-- Mini Card: Indikator Terendah (Fokus RTL) -->
                        <div class="p-3 rounded-xl bg-amber-50/40 border border-amber-100/70 hover:bg-amber-50/70 transition space-y-1">
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="font-bold text-amber-800 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Fokus Perbaikan (RTL)
                                </span>
                                <span class="font-black text-amber-700">{{ $lowestCat->percentage_score }}%</span>
                            </div>
                            <p class="text-xs font-bold text-gray-900 truncate" title="{{ $lowestCat->name }}">
                                {{ $lowestCat->name }}
                            </p>
                            <p class="text-[10px] text-gray-500 flex items-center justify-between pt-0.5">
                                <span>Kode: <strong class="text-gray-700">{{ $lowestCat->code }}</strong></span>
                                <span>{{ $lowestCat->avg_raw_score }} / 4.0</span>
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- Shortcut ke Detail Dimensi -->
                    <a href="#hospital-dimensions" class="inline-flex items-center justify-center gap-1.5 w-full py-2 px-3 rounded-xl bg-gray-50 hover:bg-primary-50 text-gray-700 hover:text-primary-700 text-xs font-bold border border-gray-200/80 hover:border-primary-200 transition text-center print:hidden">
                        <span>Lihat Rincian 8 Dimensi</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="pt-2.5 mt-3 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 text-[11px] text-gray-500">
            <span>Menampilkan 8 faktor lingkungan & budaya kerja RS terstandarisasi</span>
            <span class="text-gray-400">Skor teragregasi dari skala likert forced-choice (1–4)</span>
        </div>
    </div>

    <!-- Baris 2: Komparasi Peringkat Kepuasan Pegawai (Full Width & Spacious) -->
    <div class="bg-white p-4 sm:p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 border-b border-gray-100 pb-3 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-gray-900">Komparasi Peringkat Kepuasan Pegawai</h3>
                        <p class="text-[11px] text-gray-400">Peringkat rata-rata kepuasan per satuan kerja & rumpun profesi RS</p>
                    </div>
                </div>

                <!-- Controls: Filter Scope / Drilldown & Tab Kategori -->
                <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto justify-between sm:justify-start lg:justify-end print:hidden">
                    <!-- Satker Drill-down (muncul di tab Direktorat saat memilih direktorat) -->
                    <div x-show="chartTab === 'directorate' && selectedDirectorate" x-cloak class="flex flex-wrap items-center gap-2">
                        <div class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/70 px-2.5 py-1 rounded-xl border border-purple-200/60">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span x-text="currentFilteredCount + ' Satker'"></span>
                        </div>
                    </div>

                    <!-- Tab Toggle Button: Direktorat & Profesi -->
                    <div class="inline-flex p-1 rounded-xl bg-gray-100 text-xs font-bold shrink-0">
                        @if($directorateScores->count() > 1)
                        <button type="button" @click="setChartTab('directorate')"
                            :class="chartTab === 'directorate' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                            class="px-2.5 sm:px-3 py-1 rounded-lg transition cursor-pointer flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Direktorat ({{ $directorateScores->count() }})</span>
                        </button>
                        @endif
                        <button type="button" @click="setChartTab('profession')"
                            :class="chartTab === 'profession' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                            class="px-2.5 sm:px-3 py-1 rounded-lg transition cursor-pointer flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profesi ({{ $professionScores->count() }})</span>
                        </button>
                    </div>

                    <!-- Direktorat Dropdown (muncul di tab Direktorat untuk drill-down ke Satker) -->
                    <div x-show="chartTab === 'directorate'" x-cloak class="flex flex-wrap items-center gap-2">
                        @if(request('directorate'))
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-700 bg-primary-50 px-2.5 py-1 rounded-xl border border-primary-200">
                            <span>Direktorat: <strong>{{ request('directorate') }}</strong></span>
                            <span class="text-primary-500 font-semibold">({{ $unitScores->count() }} Satker)</span>
                        </div>
                        @else
                        <div class="relative">
                            <select x-model="selectedDirectorate" @change="setDirectorateFilter($event.target.value)"
                                class="h-8 pl-2.5 pr-7 text-xs font-semibold bg-white border border-sky-200 text-sky-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-400 cursor-pointer shadow-2xs">
                                <option value="">Semua Direktorat</option>
                                @foreach($demographics['directorates'] ?? [] as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="relative min-h-[260px] sm:min-h-[300px] flex items-center justify-center">
                @if(($unitScores->count() > 0 || $professionScores->count() > 0 || $directorateScores->count() > 0) && $totalResponses > 0)
                <div class="w-full min-w-0 transition-all overflow-x-hidden" :class="chartTab === 'directorate' && selectedDirectorate ? 'max-h-[460px] overflow-y-auto pr-2' : ''">
                    <div id="comparisonBarChart" class="w-full min-w-0"></div>
                </div>
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
        <div class="pt-3 border-t border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-2 text-[11px] text-gray-500">
            <div class="flex flex-col sm:flex-row sm:items-center flex-wrap gap-2 sm:gap-3">
                {{-- Highlight Direktorat (overview mode, tanpa drill-down) --}}
                <div x-show="chartTab === 'directorate' && !selectedDirectorate" class="flex flex-col sm:flex-row sm:items-center flex-wrap gap-1.5 sm:gap-3">
                    @if($directorateScores->count() > 0)
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-sky-600 font-bold shrink-0">🏆 Tertinggi:</span>
                        <strong class="text-gray-800 truncate max-w-[240px] sm:max-w-none">{{ $directorateScores->first()?->directorate }} ({{ $directorateScores->first()?->avg_score }}%)</strong>
                    </div>
                    <span class="text-gray-300 hidden sm:inline">|</span>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-amber-600 font-bold shrink-0">⚠️ Perhatian:</span>
                        <strong class="text-gray-800 truncate max-w-[240px] sm:max-w-none">{{ $directorateScores->last()?->directorate }} ({{ $directorateScores->last()?->avg_score }}%)</strong>
                    </div>
                    @endif
                </div>

                {{-- Highlight Satker (drill-down mode di tab Direktorat) --}}
                <div x-show="chartTab === 'directorate' && selectedDirectorate" x-cloak class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-purple-600 font-bold shrink-0">📋 Drill-down:</span>
                    <strong class="text-gray-800 break-words" x-text="selectedDirectorate"></strong>
                </div>

                {{-- Highlight Profesi --}}
                <div x-show="chartTab === 'profession'" x-cloak class="flex flex-col sm:flex-row sm:items-center flex-wrap gap-1.5 sm:gap-3">
                    @if($professionScores->count() > 0)
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-teal-600 font-bold shrink-0">🏆 Tertinggi:</span>
                        <strong class="text-gray-800">{{ $professionScores->first()?->profession }} ({{ $professionScores->first()?->avg_score }}%)</strong>
                    </div>
                    <span class="text-gray-300 hidden sm:inline">|</span>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-amber-600 font-bold shrink-0">⚠️ Perhatian:</span>
                        <strong class="text-gray-800">{{ $professionScores->last()?->profession }} ({{ $professionScores->last()?->avg_score }}%)</strong>
                    </div>
                    @endif
                </div>
            </div>
            <span class="font-bold text-gray-700 shrink-0">Total Basis: {{ number_format($totalResponses) }} Jawaban Terkumpul</span>
        </div>
    </div>

</div>