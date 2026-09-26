<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- 1. Total Responden & Rate -->
    <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Responden</span>
            <x-badge color="emerald" size="xs">
                {{ $responseRate }}% Target
            </x-badge>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-3xl font-black text-gray-900">{{ number_format($totalResponses) }}</span>
            <span class="text-xs font-semibold text-gray-400">Target: {{ number_format($target) }}</span>
        </div>
        <div class="mt-3">
            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 {{ $responseRate >= 100 ? 'bg-emerald-500' : 'bg-primary-600' }}"
                     style="width: {{ min($responseRate, 100) }}%"></div>
            </div>
        </div>
        <div class="mt-2 text-[11px] text-gray-500 flex items-center justify-between">
            <span>{{ $professionScores->count() }} kelompok profesi</span>
            <span>{{ $unitScores->count() }} unit terdata</span>
        </div>
    </div>

    @php
        $totalActiveCategories = $hospitalCategoryScores->count() ?: 8;
        $totalActiveQuestions = (int) ($hospitalCategoryScores->sum('questions_count') ?: 24);
    @endphp

    <!-- 2. Indeks Kepuasan Pegawai (Pertanyaan Tertutup Dinamis Skala 1-4) -->
    <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Indeks Kepuasan Pegawai</span>
            <div class="flex items-center gap-1.5">
                <x-badge color="blue" size="xs">
                    {{ $totalActiveCategories }} Unsur • {{ $totalActiveQuestions }} Butir
                </x-badge>
                <x-badge :color="$avgHospital >= 81 ? 'emerald' : ($avgHospital >= 61 ? 'primary' : 'amber')" size="xs">
                    {{ $avgHospital >= 81 ? 'Sangat Setuju' : ($avgHospital >= 61 ? 'Setuju' : ($avgHospital >= 41 ? 'Tidak Setuju' : 'Sangat Tidak Setuju')) }}
                </x-badge>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-black text-blue-700">{{ number_format($avgHospital, 2, ',', '.') }}</span>
            <span class="text-sm font-bold text-gray-400">/ 100</span>
            <span class="ml-auto text-xs font-semibold text-gray-400">Skala 1–4 Forced Choice</span>
        </div>
        <div class="mt-3">
            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-blue-600 transition-all duration-500" style="width: {{ min($avgHospital, 100) }}%"></div>
            </div>
        </div>
        <div class="mt-2 text-[11px] text-gray-500 flex items-center justify-between">
            <span>{{ $totalActiveQuestions }} Pertanyaan Tertutup</span>
            <a href="#radar-section" class="text-blue-600 hover:underline font-bold">Lihat Rincian {{ $totalActiveCategories }} Unsur &darr;</a>
        </div>
    </div>

    <!-- 3. Tingkat Loyalitas Pegawai (eNPS) - Compact -->
    @php
        $promoterPct = $totalResponses > 0 ? round(($promoters / $totalResponses) * 100, 1) : 0;
        $detractorPct = $totalResponses > 0 ? round(($detractors / $totalResponses) * 100, 1) : 0;
        $passivePct = $totalResponses > 0 ? round(($passives / $totalResponses) * 100, 1) : 0;

        if ($npsScore >= 50) {
            $npsGrade = 'A+'; $npsPredicate = 'Istimewa';
            $npsBadgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300';
        } elseif ($npsScore >= 20) {
            $npsGrade = 'A'; $npsPredicate = 'Sangat Baik';
            $npsBadgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300';
        } elseif ($npsScore >= 0) {
            $npsGrade = 'B'; $npsPredicate = 'Baik / Sehat';
            $npsBadgeClass = 'bg-purple-100 text-purple-800 border-purple-300';
        } elseif ($npsScore >= -20) {
            $npsGrade = 'C'; $npsPredicate = 'Perlu Evaluasi';
            $npsBadgeClass = 'bg-amber-100 text-amber-800 border-amber-300';
        } else {
            $npsGrade = 'D'; $npsPredicate = 'Kritis';
            $npsBadgeClass = 'bg-rose-100 text-rose-800 border-rose-300';
        }
    @endphp
    <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Loyalitas eNPS</span>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-xl text-[11px] font-black border shadow-2xs {{ $npsBadgeClass }}">
                🏆 Predikat {{ $npsGrade }}
            </span>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-black {{ $npsScore >= 20 ? 'text-emerald-700' : ($npsScore >= 0 ? 'text-purple-700' : 'text-rose-700') }}">{{ ($npsScore > 0 ? '+' : '') . $npsScore }}</span>
            <span class="text-sm font-bold text-gray-400">Poin</span>
            <span class="ml-auto text-xs font-semibold text-gray-400">{{ $npsPredicate }}</span>
        </div>

        <!-- Compact Grade Meter -->
        <div class="grid grid-cols-5 gap-0.5 text-center p-0.5 bg-gray-50/90 rounded-lg border border-gray-200/70 text-[9px] font-bold mt-3">
            <div class="py-0.5 rounded {{ $npsGrade === 'D' ? 'bg-rose-600 text-white font-black' : 'text-gray-400 bg-white/70' }}">D</div>
            <div class="py-0.5 rounded {{ $npsGrade === 'C' ? 'bg-amber-500 text-white font-black' : 'text-gray-400 bg-white/70' }}">C</div>
            <div class="py-0.5 rounded {{ $npsGrade === 'B' ? 'bg-purple-600 text-white font-black' : 'text-gray-400 bg-white/70' }}">B</div>
            <div class="py-0.5 rounded {{ $npsGrade === 'A' ? 'bg-emerald-600 text-white font-black' : 'text-gray-400 bg-white/70' }}">A</div>
            <div class="py-0.5 rounded {{ $npsGrade === 'A+' ? 'bg-emerald-700 text-white font-black' : 'text-gray-400 bg-white/70' }}">A+</div>
        </div>

        <!-- Compact Formula -->
        <div class="mt-3 p-2 rounded-xl bg-gradient-to-r from-emerald-50/90 via-gray-50 to-rose-50/90 border border-gray-200/80 flex items-center justify-between text-[11px] font-bold">
            <div class="flex items-center gap-1 text-emerald-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                <span>{{ $promoterPct }}%</span>
            </div>
            <span class="text-gray-400 font-black">−</span>
            <div class="flex items-center gap-1 text-rose-700">
                <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                <span>{{ $detractorPct }}%</span>
            </div>
            <span class="text-gray-400 font-black">=</span>
            <span class="text-emerald-700 font-extrabold">⭐ {{ ($npsScore > 0 ? '+' : '') . $npsScore }}</span>
        </div>

        <!-- Compact Composition -->
        <div class="mt-2 space-y-1 text-[11px]">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                    <span class="font-semibold text-gray-700">Promoter (9–10)</span>
                </div>
                <span class="font-extrabold text-emerald-700">{{ $promoters }} <span class="text-gray-400 font-medium">({{ $promoterPct }}%)</span></span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
                    <span class="font-semibold text-gray-700">Pasif (7–8)</span>
                </div>
                <span class="font-extrabold text-amber-700">{{ $passives }} <span class="text-gray-400 font-medium">({{ $passivePct }}%)</span></span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                    <span class="font-semibold text-gray-700">Detractor (0–6)</span>
                </div>
                <span class="font-extrabold text-rose-700">{{ $detractors }} <span class="text-gray-400 font-medium">({{ $detractorPct }}%)</span></span>
            </div>
        </div>
    </div>
</div>

