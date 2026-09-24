<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

    <!-- 2. Indikator Survei Kepuasan Pegawai -->
    <div class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Indikator Survei Kepuasan Pegawai</span>
            <div class="flex items-center gap-1.5">
                <x-badge color="blue" size="xs">
                    {{ $hospitalCategoryScores->count() }} Indikator
                </x-badge>
                <x-badge :color="$avgHospital >= 81 ? 'emerald' : ($avgHospital >= 61 ? 'primary' : 'amber')" size="xs">
                    {{ $avgHospital >= 81 ? 'Sangat Setuju' : ($avgHospital >= 61 ? 'Setuju' : ($avgHospital >= 41 ? 'Tidak Setuju' : 'Sangat Tidak Setuju')) }}
                </x-badge>
            </div>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-3xl font-black text-blue-700">{{ $avgHospital }}%</span>
            <span class="text-xs font-semibold text-gray-400">{{ $hospitalCategoryScores->sum('questions_count') ?: '24' }} Butir Soal Terdata</span>
        </div>
        <div class="mt-3">
            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-blue-600 transition-all duration-500" style="width: {{ min($avgHospital, 100) }}%"></div>
            </div>
        </div>
        <div class="mt-2 text-[11px] text-gray-500 flex items-center justify-between">
            <span>Skala 1–4 Forced Choice</span>
            <a href="#radar-section" class="text-blue-600 hover:underline font-bold">Lihat Rincian &darr;</a>
        </div>
    </div>
</div>
