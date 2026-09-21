<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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

    <!-- 2. Kepuasan Umum (MSQ-20) -->
    <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kepuasan Kerja (MSQ-20)</span>
            <x-badge :color="$avgGeneral >= 75 ? 'emerald' : ($avgGeneral >= 65 ? 'primary' : 'amber')" size="xs">
                {{ $avgGeneral >= 75 ? 'Sangat Puas' : ($avgGeneral >= 65 ? 'Puas' : 'Perhatian') }}
            </x-badge>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-3xl font-black text-primary-700">{{ $avgGeneral }}%</span>
            <span class="text-xs font-semibold text-gray-400">Baku MSQ-20</span>
        </div>
        <div class="mt-3">
            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-primary-600 transition-all duration-500" style="width: {{ min($avgGeneral, 100) }}%"></div>
            </div>
        </div>
        <div class="mt-2 text-[11px] text-gray-500 flex items-center justify-between">
            <span>Intrinsik: <strong>{{ $avgIntrinsic }}%</strong></span>
            <span>Ekstrinsik: <strong>{{ $avgExtrinsic }}%</strong></span>
        </div>
    </div>

    <!-- 3. Faktor Lingkungan RS -->
    <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Faktor Lingkungan RS</span>
            <x-badge color="blue" size="xs">
                {{ $hospitalCategoryScores->count() }} Dimensi
            </x-badge>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-3xl font-black text-blue-700">{{ $avgHospital }}%</span>
            <span class="text-xs font-semibold text-gray-400">24 Butir RS</span>
        </div>
        <div class="mt-3">
            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-blue-600 transition-all duration-500" style="width: {{ min($avgHospital, 100) }}%"></div>
            </div>
        </div>
        <div class="mt-2 text-[11px] text-gray-500 flex items-center justify-between">
            <span>Skor Budaya & Fasilitas Kerja</span>
            <a href="#radar-section" class="text-blue-600 hover:underline font-bold">Lihat Radar &darr;</a>
        </div>
    </div>

    <!-- 4. Employee Net Promoter Score (eNPS) -->
    <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Employee NPS (eNPS)</span>
            <x-badge :color="$npsScore >= 20 ? 'emerald' : ($npsScore >= 0 ? 'primary' : 'rose')" size="xs">
                {{ $npsScore >= 20 ? 'Sangat Baik' : ($npsScore >= 0 ? 'Baik' : 'Perlu Evaluasi') }}
            </x-badge>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-3xl font-black {{ $npsScore >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                {{ ($npsScore > 0 ? '+' : '') . $npsScore }}
            </span>
            <span class="text-[11px] font-semibold text-gray-400">Skala -100 s/d +100</span>
        </div>
        <div class="mt-3">
            <div class="h-2 w-full bg-gray-100 rounded-full flex overflow-hidden">
                @if($totalResponses > 0)
                    <div class="bg-emerald-500 h-full" style="width: {{ ($promoters / $totalResponses) * 100 }}%" title="Promoter: {{ $promoters }}"></div>
                    <div class="bg-amber-400 h-full" style="width: {{ ($passives / $totalResponses) * 100 }}%" title="Pasif: {{ $passives }}"></div>
                    <div class="bg-rose-500 h-full" style="width: {{ ($detractors / $totalResponses) * 100 }}%" title="Detractor: {{ $detractors }}"></div>
                @endif
            </div>
        </div>
        <div class="mt-2 text-[10px] text-gray-400 flex items-center justify-between">
            <span class="text-emerald-700 font-bold">{{ $promoters }} Promoter</span>
            <span class="text-amber-700 font-bold">{{ $passives }} Pasif</span>
            <span class="text-rose-700 font-bold">{{ $detractors }} Detractor</span>
        </div>
    </div>
</div>
