{{-- Word Cloud Section with Tabs --}}
<div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
        <div>
            <h3 class="text-sm md:text-base font-black text-gray-900">Word Cloud Sentimen</h3>
            <p class="text-xs text-gray-400">Visualisasi frekuensi kata yang paling sering diutarakan pegawai berdasarkan kelompok sentimen.</p>
        </div>

        <!-- Tabs Positif / Netral / Negatif -->
        <div class="inline-flex p-1 bg-gray-100 rounded-2xl shrink-0 self-start sm:self-auto">
            <button type="button"
                    @click="activeCloudTab = 'positive'"
                    :class="activeCloudTab === 'positive' ? 'bg-emerald-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900'"
                    class="px-4 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                <span>Positif</span>
            </button>
            <button type="button"
                    @click="activeCloudTab = 'neutral'"
                    :class="activeCloudTab === 'neutral' ? 'bg-amber-500 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900'"
                    class="px-4 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                <span class="w-2 h-2 rounded-full bg-amber-200"></span>
                <span>Netral</span>
            </button>
            <button type="button"
                    @click="activeCloudTab = 'negative'"
                    :class="activeCloudTab === 'negative' ? 'bg-rose-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900'"
                    class="px-4 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                <span class="w-2 h-2 rounded-full bg-rose-300"></span>
                <span>Negatif</span>
            </button>
        </div>
    </div>

    <!-- Word Cloud Content Panels -->
    <div class="min-h-[220px] rounded-2xl p-6 flex items-center justify-center border transition"
         :class="{
            'bg-[#f2fbf5] border-emerald-100': activeCloudTab === 'positive',
            'bg-[#fefbe8] border-amber-100': activeCloudTab === 'neutral',
            'bg-[#fef2f2] border-rose-100': activeCloudTab === 'negative',
         }">

        <!-- 1. Tab Positif -->
        <div x-show="activeCloudTab === 'positive'" x-transition.opacity.duration.200ms class="w-full flex flex-wrap items-center justify-center gap-x-4 gap-y-3 select-none">
            @forelse($wordClouds['positive'] as $item)
                @php
                    $scaleClasses = match($item['scale']) {
                        5 => 'text-2xl md:text-3xl font-black text-emerald-800',
                        4 => 'text-lg md:text-xl font-extrabold text-emerald-700',
                        3 => 'text-base md:text-lg font-bold text-teal-700',
                        2 => 'text-sm md:text-base font-semibold text-emerald-600',
                        default => 'text-xs md:text-sm font-medium text-emerald-500',
                    };
                @endphp
                <button type="button"
                        @click="filterKeyword('{{ $item['word'] }}')"
                        class="{{ $scaleClasses }} hover:scale-125 transition-transform duration-150 cursor-pointer"
                        title="Klik untuk filter feedback dengan kata: {{ $item['word'] }} ({{ $item['count'] }}x)">
                    {{ $item['word'] }}
                </button>
            @empty
                <span class="text-xs text-emerald-500 italic">Belum ada kata positif</span>
            @endforelse
        </div>

        <!-- 2. Tab Netral -->
        <div x-show="activeCloudTab === 'neutral'" x-cloak x-transition.opacity.duration.200ms class="w-full flex flex-wrap items-center justify-center gap-x-4 gap-y-3 select-none">
            @forelse($wordClouds['neutral'] as $item)
                @php
                    $scaleClasses = match($item['scale']) {
                        5 => 'text-2xl md:text-3xl font-black text-amber-800',
                        4 => 'text-lg md:text-xl font-extrabold text-amber-700',
                        3 => 'text-base md:text-lg font-bold text-yellow-800',
                        2 => 'text-sm md:text-base font-semibold text-amber-600',
                        default => 'text-xs md:text-sm font-medium text-amber-500',
                    };
                @endphp
                <button type="button"
                        @click="filterKeyword('{{ $item['word'] }}')"
                        class="{{ $scaleClasses }} hover:scale-125 transition-transform duration-150 cursor-pointer"
                        title="Klik untuk filter feedback dengan kata: {{ $item['word'] }} ({{ $item['count'] }}x)">
                    {{ $item['word'] }}
                </button>
            @empty
                <span class="text-xs text-amber-500 italic">Belum ada kata netral</span>
            @endforelse
        </div>

        <!-- 3. Tab Negatif -->
        <div x-show="activeCloudTab === 'negative'" x-cloak x-transition.opacity.duration.200ms class="w-full flex flex-wrap items-center justify-center gap-x-4 gap-y-3 select-none">
            @forelse($wordClouds['negative'] as $item)
                @php
                    $scaleClasses = match($item['scale']) {
                        5 => 'text-2xl md:text-3xl font-black text-rose-800',
                        4 => 'text-lg md:text-xl font-extrabold text-rose-700',
                        3 => 'text-base md:text-lg font-bold text-red-700',
                        2 => 'text-sm md:text-base font-semibold text-rose-600',
                        default => 'text-xs md:text-sm font-medium text-rose-500',
                    };
                @endphp
                <button type="button"
                        @click="filterKeyword('{{ $item['word'] }}')"
                        class="{{ $scaleClasses }} hover:scale-125 transition-transform duration-150 cursor-pointer"
                        title="Klik untuk filter feedback dengan kata: {{ $item['word'] }} ({{ $item['count'] }}x)">
                    {{ $item['word'] }}
                </button>
            @empty
                <span class="text-xs text-rose-500 italic">Belum ada kata negatif</span>
            @endforelse
        </div>
    </div>

    <div class="flex items-center justify-between text-[11px] text-gray-400">
        <span>* Tips: Klik pada salah satu kata di atas untuk memfilter tabel masukan secara otomatis.</span>
        <span class="font-semibold text-gray-500">Skala ukuran proporsional frekuensi kemunculan</span>
    </div>
</div>
