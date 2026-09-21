<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Kolom 1: 5 Kekuatan Utama (Top Strengths) -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-gray-900">5 Kekuatan Utama RS (Top Strengths)</h3>
                        <p class="text-[11px] text-gray-400">Butir dengan kepuasan tertinggi — pertahankan dan jadikan budaya unggul</p>
                    </div>
                </div>
                <x-badge color="emerald" size="xs">Apresiasi</x-badge>
            </div>

            <div class="space-y-3">
                @forelse($topStrengths as $index => $item)
                    <div class="p-3.5 rounded-xl border border-emerald-100 bg-emerald-50/30 hover:bg-emerald-50/60 transition flex items-start gap-3 group">
                        <div class="shrink-0 w-6 h-6 rounded-lg bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-xs">
                            #{{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <span class="text-[10px] font-bold text-emerald-800 bg-emerald-100/70 px-1.5 py-0.5 rounded">
                                    {{ $item->question_code ?? $item->code }}
                                </span>
                                <span class="text-[11px] font-semibold text-gray-500 truncate max-w-[200px]">
                                    {{ $item->category_name }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-800 font-medium leading-snug">
                                "{{ $item->text }}"
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="text-sm font-black text-emerald-700">{{ $item->percentage_score }}%</div>
                            <div class="text-[10px] font-bold text-gray-400">{{ $item->avg_score }} / 5.0</div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400 text-xs">
                        Belum ada cukup data respon untuk memetakan kekuatan utama.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="pt-3 mt-4 border-t border-gray-100 text-[11px] text-gray-400 flex items-center justify-between">
            <span>Dianalisis dari seluruh instrumen survei aktif</span>
            <span class="font-semibold text-emerald-600">Pertahankan & Berikan Apresiasi</span>
        </div>
    </div>

    <!-- Kolom 2: 5 Area Perbaikan Prioritas (Priority Areas RTL) -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-700 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-gray-900">5 Area Perbaikan Prioritas (Action Plan RTL)</h3>
                        <p class="text-[11px] text-gray-400">Butir dengan kepuasan terendah — prioritas tindak lanjut manajemen</p>
                    </div>
                </div>
                <x-badge color="rose" size="xs">Prioritas RTL</x-badge>
            </div>

            <div class="space-y-3">
                @forelse($topImprovements as $index => $item)
                    <div class="p-3.5 rounded-xl border border-rose-100 bg-rose-50/30 hover:bg-rose-50/60 transition flex items-start gap-3 group">
                        <div class="shrink-0 w-6 h-6 rounded-lg bg-rose-600 text-white font-black text-xs flex items-center justify-center shadow-xs">
                            !{{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <span class="text-[10px] font-bold text-rose-800 bg-rose-100/70 px-1.5 py-0.5 rounded">
                                    {{ $item->question_code ?? $item->code }}
                                </span>
                                <span class="text-[11px] font-semibold text-gray-500 truncate max-w-[200px]">
                                    {{ $item->category_name }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-800 font-medium leading-snug">
                                "{{ $item->text }}"
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="text-sm font-black text-rose-700">{{ $item->percentage_score }}%</div>
                            <div class="text-[10px] font-bold text-gray-400">{{ $item->avg_score }} / 5.0</div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400 text-xs">
                        Belum ada cukup data respon untuk memetakan area perbaikan.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="pt-3 mt-4 border-t border-gray-100 text-[11px] text-gray-400 flex items-center justify-between">
            <span>Rekomendasi tindak lanjut program kerja RS</span>
            <span class="font-semibold text-rose-600">Segera Rumuskan Intervensi</span>
        </div>
    </div>

</div>
