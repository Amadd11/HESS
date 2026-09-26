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

    <!-- Skor Indikator Survei Kepuasan Pegawai (Full Width) -->
    <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
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

    <!-- Baris 2: Komparasi Peringkat Kepuasan Pegawai (Full Width & Spacious) -->
    <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
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
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Satker Drill-down (muncul di tab Direktorat saat memilih direktorat) -->
                    <div x-show="chartTab === 'directorate' && selectedDirectorate" x-cloak class="flex flex-wrap items-center gap-2">
                        <div class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-100/70 px-2.5 py-1 rounded-xl border border-purple-200/60">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span x-text="currentFilteredCount + ' Satker'"></span>
                        </div>
                    </div>

                    <!-- Tab Toggle Button: Direktorat & Profesi -->
                    <div class="inline-flex p-1 rounded-xl bg-gray-100 text-xs font-bold shrink-0">
                        @if($directorateScores->count() > 1)
                        <button type="button" @click="setChartTab('directorate')"
                            :class="chartTab === 'directorate' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                            class="px-3 py-1 rounded-lg transition cursor-pointer flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Direktorat ({{ $directorateScores->count() }})</span>
                        </button>
                        @endif
                        <button type="button" @click="setChartTab('profession')"
                            :class="chartTab === 'profession' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                            class="px-3 py-1 rounded-lg transition cursor-pointer flex items-center gap-1.5">
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

            <div class="relative min-h-[300px] flex items-center justify-center">
                @if(($unitScores->count() > 0 || $professionScores->count() > 0 || $directorateScores->count() > 0) && $totalResponses > 0)
                <div class="w-full transition-all" :class="chartTab === 'directorate' && selectedDirectorate ? 'max-h-[460px] overflow-y-auto pr-2' : ''">
                    <div id="comparisonBarChart" class="w-full"></div>
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
        <div class="pt-3 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px] text-gray-500">
            <div class="flex items-center gap-4">
                {{-- Highlight Direktorat (overview mode, tanpa drill-down) --}}
                <div x-show="chartTab === 'directorate' && !selectedDirectorate" class="flex items-center gap-3">
                    @if($directorateScores->count() > 0)
                    <span class="flex items-center gap-1.5">
                        <span class="text-sky-600 font-bold">🏆 Direktorat Tertinggi:</span>
                        <strong class="text-gray-800">{{ $directorateScores->first()?->directorate }} ({{ $directorateScores->first()?->avg_score }}%)</strong>
                    </span>
                    <span class="text-gray-300 hidden sm:inline">|</span>
                    <span class="flex items-center gap-1.5">
                        <span class="text-amber-600 font-bold">⚠️ Perhatian:</span>
                        <strong class="text-gray-800">{{ $directorateScores->last()?->directorate }} ({{ $directorateScores->last()?->avg_score }}%)</strong>
                    </span>
                    @endif
                </div>

                {{-- Highlight Satker (drill-down mode di tab Direktorat) --}}
                <div x-show="chartTab === 'directorate' && selectedDirectorate" x-cloak class="flex items-center gap-3">
                    <span class="flex items-center gap-1.5">
                        <span class="text-purple-600 font-bold">📋 Drill-down Satuan Kerja:</span>
                        <strong class="text-gray-800" x-text="selectedDirectorate"></strong>
                    </span>
                </div>

                {{-- Highlight Profesi --}}
                <div x-show="chartTab === 'profession'" x-cloak class="flex items-center gap-3">
                    @if($professionScores->count() > 0)
                    <span class="flex items-center gap-1.5">
                        <span class="text-teal-600 font-bold">🏆 Profesi Tertinggi:</span>
                        <strong class="text-gray-800">{{ $professionScores->first()?->profession }} ({{ $professionScores->first()?->avg_score }}%)</strong>
                    </span>
                    <span class="text-gray-300 hidden sm:inline">|</span>
                    <span class="flex items-center gap-1.5">
                        <span class="text-amber-600 font-bold">⚠️ Perhatian:</span>
                        <strong class="text-gray-800">{{ $professionScores->last()?->profession }} ({{ $professionScores->last()?->avg_score }}%)</strong>
                    </span>
                    @endif
                </div>
            </div>
            <span class="font-bold text-gray-700">Total Basis: {{ number_format($totalResponses) }} Jawaban Terkumpul</span>
        </div>
    </div>

</div>