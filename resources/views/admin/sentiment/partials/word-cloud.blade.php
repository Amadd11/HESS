{{-- Word Cloud Section — Modern Vector Typographic Cloud with Dual View (Tab & 3-Kolom) --}}
<div x-data="{ cloudLayout: 'tab' }" class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-4">
    <!-- Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0 border border-primary-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 00-9.78 2.096A4.001 4.001 0 003 15z" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm md:text-base font-black text-gray-900 tracking-tight">Word Cloud Sentimen Pegawai</h3>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Interaktif
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Visualisasi proporsional frekuensi kata masukan pegawai. Klik kata apa pun untuk memfilter respon.</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
            <!-- View Mode Switcher: Tab Fokus vs 3 Kolom Bersanding -->
            <div class="inline-flex p-1 bg-gray-100 rounded-xl">
                <button type="button"
                        @click="cloudLayout = 'tab'"
                        :class="cloudLayout === 'tab' ? 'bg-white text-gray-900 shadow-xs font-bold' : 'text-gray-500 hover:text-gray-800 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span>Tab Fokus</span>
                </button>
                <button type="button"
                        @click="cloudLayout = 'grid'"
                        :class="cloudLayout === 'grid' ? 'bg-white text-gray-900 shadow-xs font-bold' : 'text-gray-500 hover:text-gray-800 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>3 Kolom</span>
                </button>
            </div>

            <!-- Link Master Data -->
            <a href="{{ route('admin.sentiment-words.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:text-primary-700 hover:bg-primary-50 hover:border-primary-200 transition"
               title="Kelola kamus kosakata sentimen">
                <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span class="hidden md:inline">Master Kata</span>
            </a>
        </div>
    </div>

    <!-- 1. TAMPILAN MODE TAB FOKUS (DEFAULT LEBIH LEGA & MODERN) -->
    <div x-show="cloudLayout === 'tab'" x-transition class="space-y-4">
        <!-- Tab Selectors -->
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div class="inline-flex p-1 bg-gray-100 rounded-2xl">
                <button type="button"
                        @click="activeCloudTab = 'positive'"
                        :class="activeCloudTab === 'positive' ? 'bg-emerald-600 text-white shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900 font-semibold'"
                        class="px-4 py-2 rounded-xl text-xs transition flex items-center gap-2 cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                    <span>Kata Positif</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black transition-colors"
                          :class="activeCloudTab === 'positive' ? 'bg-emerald-700 text-white' : 'bg-gray-200 text-gray-700'">
                        {{ count($wordClouds['positive']) }}
                    </span>
                </button>
                <button type="button"
                        @click="activeCloudTab = 'neutral'"
                        :class="activeCloudTab === 'neutral' ? 'bg-amber-500 text-white shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900 font-semibold'"
                        class="px-4 py-2 rounded-xl text-xs transition flex items-center gap-2 cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-amber-200"></span>
                    <span>Netral / SOP</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black transition-colors"
                          :class="activeCloudTab === 'neutral' ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700'">
                        {{ count($wordClouds['neutral']) }}
                    </span>
                </button>
                <button type="button"
                        @click="activeCloudTab = 'negative'"
                        :class="activeCloudTab === 'negative' ? 'bg-rose-600 text-white shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900 font-semibold'"
                        class="px-4 py-2 rounded-xl text-xs transition flex items-center gap-2 cursor-pointer">
                    <span class="w-2 h-2 rounded-full bg-rose-300"></span>
                    <span>Kata Negatif</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black transition-colors"
                          :class="activeCloudTab === 'negative' ? 'bg-rose-700 text-white' : 'bg-gray-200 text-gray-700'">
                        {{ count($wordClouds['negative']) }}
                    </span>
                </button>
            </div>

            <div class="text-[11px] text-gray-400 hidden sm:block">
                Ukuran kata merepresentasikan volume kemunculan pada masukan responden
            </div>
        </div>

        <!-- Tab Content Card -->
        <div class="min-h-[260px] rounded-3xl p-6 md:p-8 border transition-all duration-300 flex items-center justify-center"
             :class="{
                'bg-gradient-to-br from-[#f2fbf5] via-[#f7fdf9] to-white border-emerald-200/90 shadow-2xs': activeCloudTab === 'positive',
                'bg-gradient-to-br from-[#fefce8] via-[#fffdf0] to-white border-amber-200/90 shadow-2xs': activeCloudTab === 'neutral',
                'bg-gradient-to-br from-[#fef2f2] via-[#fff5f5] to-white border-rose-200/90 shadow-2xs': activeCloudTab === 'negative',
             }">

            <!-- Tab 1: Kata Positif -->
            <div x-show="activeCloudTab === 'positive'" x-transition.opacity.duration.200ms class="w-full flex flex-wrap items-center justify-center gap-2.5 md:gap-3 select-none">
                @forelse($wordClouds['positive'] as $item)
                    @php
                        $pillClass = match($item['scale']) {
                            5 => 'text-sm md:text-base font-black px-4 py-2 rounded-2xl bg-emerald-600 text-white shadow-xs hover:bg-emerald-700 hover:scale-110',
                            4 => 'text-xs md:text-sm font-extrabold px-3.5 py-1.5 rounded-xl bg-emerald-100 text-emerald-900 border border-emerald-300/80 hover:bg-emerald-200 hover:scale-105',
                            3 => 'text-xs font-bold px-3 py-1 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 hover:scale-105',
                            2 => 'text-xs font-semibold px-2.5 py-1 rounded-lg bg-white text-emerald-700 border border-emerald-100 hover:bg-emerald-50 hover:scale-105',
                            default => 'text-[11px] font-medium px-2 py-0.5 rounded-lg text-emerald-600 bg-white/80 border border-emerald-100/60 hover:text-emerald-900 hover:bg-emerald-50',
                        };
                    @endphp
                    <button type="button"
                            @click="filterKeyword('{{ $item['word'] }}')"
                            class="{{ $pillClass }} inline-flex items-center gap-1.5 transition-all duration-150 active:scale-95 cursor-pointer group"
                            title="Klik untuk filter responden dengan kata '{{ $item['word'] }}' ({{ $item['count'] }}x)">
                        <span>{{ $item['word'] }}</span>
                        <span class="text-[10px] font-mono opacity-60 group-hover:opacity-100 transition-opacity">
                            {{ $item['count'] }}
                        </span>
                    </button>
                @empty
                    <span class="text-xs text-emerald-600 italic">Belum ada kata positif yang teridentifikasi</span>
                @endforelse
            </div>

            <!-- Tab 2: Kata Netral -->
            <div x-show="activeCloudTab === 'neutral'" x-cloak x-transition.opacity.duration.200ms class="w-full flex flex-wrap items-center justify-center gap-2.5 md:gap-3 select-none">
                @forelse($wordClouds['neutral'] as $item)
                    @php
                        $pillClass = match($item['scale']) {
                            5 => 'text-sm md:text-base font-black px-4 py-2 rounded-2xl bg-amber-500 text-white shadow-xs hover:bg-amber-600 hover:scale-110',
                            4 => 'text-xs md:text-sm font-extrabold px-3.5 py-1.5 rounded-xl bg-amber-100 text-amber-900 border border-amber-300/80 hover:bg-amber-200 hover:scale-105',
                            3 => 'text-xs font-bold px-3 py-1 rounded-xl bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100 hover:scale-105',
                            2 => 'text-xs font-semibold px-2.5 py-1 rounded-lg bg-white text-amber-700 border border-amber-100 hover:bg-amber-50 hover:scale-105',
                            default => 'text-[11px] font-medium px-2 py-0.5 rounded-lg text-amber-600 bg-white/80 border border-amber-100/60 hover:text-amber-900 hover:bg-amber-50',
                        };
                    @endphp
                    <button type="button"
                            @click="filterKeyword('{{ $item['word'] }}')"
                            class="{{ $pillClass }} inline-flex items-center gap-1.5 transition-all duration-150 active:scale-95 cursor-pointer group"
                            title="Klik untuk filter responden dengan kata '{{ $item['word'] }}' ({{ $item['count'] }}x)">
                        <span>{{ $item['word'] }}</span>
                        <span class="text-[10px] font-mono opacity-60 group-hover:opacity-100 transition-opacity">
                            {{ $item['count'] }}
                        </span>
                    </button>
                @empty
                    <span class="text-xs text-amber-600 italic">Belum ada kata netral / istilah operasional</span>
                @endforelse
            </div>

            <!-- Tab 3: Kata Negatif -->
            <div x-show="activeCloudTab === 'negative'" x-cloak x-transition.opacity.duration.200ms class="w-full flex flex-wrap items-center justify-center gap-2.5 md:gap-3 select-none">
                @forelse($wordClouds['negative'] as $item)
                    @php
                        $pillClass = match($item['scale']) {
                            5 => 'text-sm md:text-base font-black px-4 py-2 rounded-2xl bg-rose-600 text-white shadow-xs hover:bg-rose-700 hover:scale-110',
                            4 => 'text-xs md:text-sm font-extrabold px-3.5 py-1.5 rounded-xl bg-rose-100 text-rose-900 border border-rose-300/80 hover:bg-rose-200 hover:scale-105',
                            3 => 'text-xs font-bold px-3 py-1 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 hover:bg-rose-100 hover:scale-105',
                            2 => 'text-xs font-semibold px-2.5 py-1 rounded-lg bg-white text-rose-700 border border-rose-100 hover:bg-rose-50 hover:scale-105',
                            default => 'text-[11px] font-medium px-2 py-0.5 rounded-lg text-rose-600 bg-white/80 border border-rose-100/60 hover:text-rose-900 hover:bg-rose-50',
                        };
                    @endphp
                    <button type="button"
                            @click="filterKeyword('{{ $item['word'] }}')"
                            class="{{ $pillClass }} inline-flex items-center gap-1.5 transition-all duration-150 active:scale-95 cursor-pointer group"
                            title="Klik untuk filter responden dengan kata '{{ $item['word'] }}' ({{ $item['count'] }}x)">
                        <span>{{ $item['word'] }}</span>
                        <span class="text-[10px] font-mono opacity-60 group-hover:opacity-100 transition-opacity">
                            {{ $item['count'] }}
                        </span>
                    </button>
                @empty
                    <span class="text-xs text-rose-600 italic">Belum ada kata keluhan atau kendala kerja</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 2. TAMPILAN MODE 3 KOLOM BERSANDING -->
    <div x-show="cloudLayout === 'grid'" x-cloak x-transition class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">
        <!-- Kolom Positif -->
        <div class="bg-gradient-to-b from-[#f2fbf5] to-white rounded-3xl border border-emerald-200/90 p-5 flex flex-col justify-between shadow-2xs">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-black text-emerald-900 uppercase tracking-wider">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Kata Positif
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-white text-emerald-700 border border-emerald-200 shadow-2xs">
                        {{ count($wordClouds['positive']) }} kata
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                    @forelse($wordClouds['positive'] as $item)
                        @php
                            $gridPill = match($item['scale']) {
                                5 => 'text-xs md:text-sm font-black px-2.5 py-1 bg-emerald-600 text-white rounded-xl shadow-2xs',
                                4 => 'text-xs font-bold px-2.5 py-1 bg-emerald-100 text-emerald-900 rounded-lg border border-emerald-200',
                                3 => 'text-xs font-semibold px-2 py-0.5 bg-emerald-50 text-emerald-800 rounded-md border border-emerald-200/70',
                                default => 'text-[11px] font-medium px-2 py-0.5 bg-white text-emerald-700 rounded-md border border-emerald-100',
                            };
                        @endphp
                        <button type="button"
                                @click="filterKeyword('{{ $item['word'] }}')"
                                class="{{ $gridPill }} transition-transform hover:scale-105 active:scale-95 cursor-pointer">
                            {{ $item['word'] }} <span class="opacity-60 text-[9px]">({{ $item['count'] }})</span>
                        </button>
                    @empty
                        <span class="text-xs text-emerald-500 italic">Belum ada kata positif</span>
                    @endforelse
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-emerald-100 text-[11px] text-emerald-700 flex items-center justify-between font-medium">
                <span>Aspek paling diapresiasi</span>
                <span class="font-mono font-bold">{{ $wordClouds['positive'][0]['word'] ?? '—' }} (teratas)</span>
            </div>
        </div>

        <!-- Kolom Netral / SOP -->
        <div class="bg-gradient-to-b from-[#fefce8] to-white rounded-3xl border border-amber-200/90 p-5 flex flex-col justify-between shadow-2xs">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-black text-amber-900 uppercase tracking-wider">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        Netral / SOP
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-white text-amber-700 border border-amber-200 shadow-2xs">
                        {{ count($wordClouds['neutral']) }} kata
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                    @forelse($wordClouds['neutral'] as $item)
                        @php
                            $gridPill = match($item['scale']) {
                                5 => 'text-xs md:text-sm font-black px-2.5 py-1 bg-amber-500 text-white rounded-xl shadow-2xs',
                                4 => 'text-xs font-bold px-2.5 py-1 bg-amber-100 text-amber-900 rounded-lg border border-amber-200',
                                3 => 'text-xs font-semibold px-2 py-0.5 bg-amber-50 text-amber-800 rounded-md border border-amber-200/70',
                                default => 'text-[11px] font-medium px-2 py-0.5 bg-white text-amber-700 rounded-md border border-amber-100',
                            };
                        @endphp
                        <button type="button"
                                @click="filterKeyword('{{ $item['word'] }}')"
                                class="{{ $gridPill }} transition-transform hover:scale-105 active:scale-95 cursor-pointer">
                            {{ $item['word'] }} <span class="opacity-60 text-[9px]">({{ $item['count'] }})</span>
                        </button>
                    @empty
                        <span class="text-xs text-amber-500 italic">Belum ada kata netral</span>
                    @endforelse
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-amber-100 text-[11px] text-amber-700 flex items-center justify-between font-medium">
                <span>Alur & prosedur RS</span>
                <span class="font-mono font-bold">{{ $wordClouds['neutral'][0]['word'] ?? '—' }} (teratas)</span>
            </div>
        </div>

        <!-- Kolom Negatif -->
        <div class="bg-gradient-to-b from-[#fef2f2] to-white rounded-3xl border border-rose-200/90 p-5 flex flex-col justify-between shadow-2xs">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-black text-rose-900 uppercase tracking-wider">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                        Kata Negatif
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-white text-rose-700 border border-rose-200 shadow-2xs">
                        {{ count($wordClouds['negative']) }} kata
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                    @forelse($wordClouds['negative'] as $item)
                        @php
                            $gridPill = match($item['scale']) {
                                5 => 'text-xs md:text-sm font-black px-2.5 py-1 bg-rose-600 text-white rounded-xl shadow-2xs',
                                4 => 'text-xs font-bold px-2.5 py-1 bg-rose-100 text-rose-900 rounded-lg border border-rose-200',
                                3 => 'text-xs font-semibold px-2 py-0.5 bg-rose-50 text-rose-800 rounded-md border border-rose-200/70',
                                default => 'text-[11px] font-medium px-2 py-0.5 bg-white text-rose-700 rounded-md border border-rose-100',
                            };
                        @endphp
                        <button type="button"
                                @click="filterKeyword('{{ $item['word'] }}')"
                                class="{{ $gridPill }} transition-transform hover:scale-105 active:scale-95 cursor-pointer">
                            {{ $item['word'] }} <span class="opacity-60 text-[9px]">({{ $item['count'] }})</span>
                        </button>
                    @empty
                        <span class="text-xs text-rose-500 italic">Belum ada keluhan kerja</span>
                    @endforelse
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-rose-100 text-[11px] text-rose-700 flex items-center justify-between font-medium">
                <span>Fokus keluhan perbaikan</span>
                <span class="font-mono font-bold">{{ $wordClouds['negative'][0]['word'] ?? '—' }} (teratas)</span>
            </div>
        </div>
    </div>

    <!-- Footnote helper -->
    <div class="flex items-center justify-between pt-2 border-t border-gray-100 text-[11px] text-gray-400">
        <span class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><strong>Tips:</strong> Klik langsung pada kata di atas untuk memfilter respon masukan pegawai di tabel detail bawah.</span>
        </span>
        <span class="font-semibold text-gray-500">Desain Tipografi Vektor Responsif</span>
    </div>
</div>
