{{-- 4 NPS KPI Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Total Respon -->
    <a href="{{ route('admin.responses.index') }}"
        class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs hover:border-primary-300 transition group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Respon</span>
            <x-badge color="gray" size="xs">Semua</x-badge>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-2xl md:text-3xl font-black text-gray-900 group-hover:text-primary-700 transition">{{ number_format($stats['total']) }}</span>
            <span class="text-[11px] font-semibold text-gray-400">Pengisian</span>
        </div>
        <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
            <span>Akumulasi kuesioner masuk</span>
        </div>
    </a>

    <!-- Promoters -->
    <a href="{{ route('admin.responses.index', array_merge(request()->except('nps_category'), ['nps_category' => 'promoter'])) }}"
        class="bg-white p-4 md:p-5 rounded-2xl border {{ request('nps_category') === 'promoter' ? 'border-emerald-400 ring-2 ring-emerald-100 bg-emerald-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-emerald-300 transition group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Promoters (9-10)</span>
            <x-badge color="emerald" size="xs">Loyal</x-badge>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-2xl md:text-3xl font-black text-emerald-700">{{ number_format($stats['promoters']) }}</span>
            <span class="text-[11px] font-semibold text-gray-400">Responden</span>
        </div>
        <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            <span>Puas & merekomendasikan RS</span>
        </div>
    </a>

    <!-- Passives -->
    <a href="{{ route('admin.responses.index', array_merge(request()->except('nps_category'), ['nps_category' => 'passive'])) }}"
        class="bg-white p-4 md:p-5 rounded-2xl border {{ request('nps_category') === 'passive' ? 'border-amber-400 ring-2 ring-amber-100 bg-amber-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-amber-300 transition group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Passives (7-8)</span>
            <x-badge color="amber" size="xs">Netral</x-badge>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-2xl md:text-3xl font-black text-amber-700">{{ number_format($stats['passives']) }}</span>
            <span class="text-[11px] font-semibold text-gray-400">Responden</span>
        </div>
        <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
            <span>Cukup puas tapi rentan</span>
        </div>
    </a>

    <!-- Detractors -->
    <a href="{{ route('admin.responses.index', array_merge(request()->except('nps_category'), ['nps_category' => 'detractor'])) }}"
        class="bg-white p-4 md:p-5 rounded-2xl border {{ request('nps_category') === 'detractor' ? 'border-red-400 ring-2 ring-red-100 bg-red-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-red-300 transition group">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-red-700 uppercase tracking-wider">Detractors (0-6)</span>
            <x-badge color="red" size="xs">Kritis</x-badge>
        </div>
        <div class="flex items-baseline justify-between">
            <span class="text-2xl md:text-3xl font-black text-red-700">{{ number_format($stats['detractors']) }}</span>
            <span class="text-[11px] font-semibold text-gray-400">Responden</span>
        </div>
        <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
            <span>Perlu perhatian khusus</span>
        </div>
    </a>
</div>
