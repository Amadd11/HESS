<div x-data="dashboardCharts({
    radarSeries: {{ Js::from($hospitalCategoryScores->pluck('percentage_score')) }},
    radarCategories: {{ Js::from($hospitalCategoryScores->pluck('name')) }},
    unitLabels: {{ Js::from($unitScores->pluck('unit')) }},
    unitData: {{ Js::from($unitScores->pluck('avg_score')) }},
    profLabels: {{ Js::from($professionScores->pluck('profession')) }},
    profData: {{ Js::from($professionScores->pluck('avg_score')) }}
})" x-init="initCharts()" class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="radar-section">

    <!-- Card 1: Radar Chart 8 Dimensi RS -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-gray-900">Peta Radar 8 Dimensi Rumah Sakit</h3>
                        <p class="text-[11px] text-gray-400">Distribusi keseimbangan faktor lingkungan & budaya kerja RS</p>
                    </div>
                </div>
                <x-badge color="primary" size="xs">Skala 0–100%</x-badge>
            </div>
            
            <div class="relative min-h-[340px] flex items-center justify-center">
                @if($hospitalCategoryScores->count() > 0 && $totalResponses > 0)
                    <div id="hospitalRadarChart" class="w-full"></div>
                @else
                    <div class="text-center py-12 text-gray-400">
                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <p class="text-xs font-semibold">Belum cukup data respon untuk memetakan radar.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-primary-600 inline-block"></span>
                <span>Standar Optimal Target: &ge; 75%</span>
            </span>
            <a href="#hospital-dimensions" class="text-primary-700 hover:underline font-bold">Rincian 8 Dimensi &darr;</a>
        </div>
    </div>

    <!-- Card 2: Bar Chart Komparasi Unit Kerja & Profesi -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3 mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-gray-900">Komparasi Tingkat Kepuasan</h3>
                        <p class="text-[11px] text-gray-400">Peringkat kepuasan per unit kerja atau kelompok profesi</p>
                    </div>
                </div>

                <!-- Tab Toggle Button -->
                <div class="inline-flex p-0.5 rounded-xl bg-gray-100 text-xs font-bold shrink-0">
                    <button type="button" @click="setChartTab('unit')"
                            :class="chartTab === 'unit' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                            class="px-2.5 py-1 rounded-lg transition cursor-pointer">
                        Unit Kerja ({{ $unitScores->count() }})
                    </button>
                    <button type="button" @click="setChartTab('profession')"
                            :class="chartTab === 'profession' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                            class="px-2.5 py-1 rounded-lg transition cursor-pointer">
                        Kelompok Profesi ({{ $professionScores->count() }})
                    </button>
                </div>
            </div>

            <div class="relative min-h-[340px] flex items-center justify-center">
                @if(($unitScores->count() > 0 || $professionScores->count() > 0) && $totalResponses > 0)
                    <div id="comparisonBarChart" class="w-full"></div>
                @else
                    <div class="text-center py-12 text-gray-400">
                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <p class="text-xs font-semibold">Belum ada respon responden pada segmen ini.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
            <span x-show="chartTab === 'unit'">Menampilkan seluruh unit yang telah berpartisipasi</span>
            <span x-show="chartTab === 'profession'" style="display: none;">Menampilkan seluruh rumpun profesi RS</span>
            <span class="font-bold text-gray-700">Total: {{ number_format($totalResponses) }} Jawaban</span>
        </div>
    </div>
</div>
