{{-- Top 10 Positive & Negative Keywords --}}
<div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-4">
    <div class="border-b border-gray-100 pb-3">
        <h3 class="text-sm md:text-base font-black text-gray-900">Analisis Kata Kunci (Top 10 Positif vs Negatif)</h3>
        <p class="text-xs text-gray-400">Peringkat frekuensi kata paling sering diulang dalam apresiasi positif dan saran perbaikan.</p>
    </div>

    @php
        $maxPos = ! empty($topPositive) ? max($topPositive) : 1;
        $maxNeg = ! empty($topNegative) ? max($topNegative) : 1;
        $posKeys = array_keys($topPositive);
        $posVals = array_values($topPositive);
        $negKeys = array_keys($topNegative);
        $negVals = array_values($topNegative);
        $maxRows = max(count($posKeys), count($negKeys));
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- 1. Kolom Kata Positif -->
        <div class="space-y-3 bg-[#f2fbf5]/60 p-4 rounded-2xl border border-emerald-100">
            <div class="flex items-center justify-between pb-2 border-b border-emerald-200/60">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-black uppercase tracking-wider text-emerald-900">Top Kata Positif</span>
                </div>
                <span class="text-[11px] font-bold text-emerald-700">Frekuensi</span>
            </div>

            <div class="space-y-2">
                @forelse($topPositive as $word => $count)
                    @php
                        $width = max(15, round(($count / $maxPos) * 100));
                    @endphp
                    <div class="flex items-center justify-between gap-3 text-xs">
                        <button type="button"
                                @click="filterKeyword('{{ $word }}')"
                                class="font-bold text-gray-800 hover:text-emerald-700 truncate max-w-[140px] text-left cursor-pointer transition"
                                title="Klik untuk memfilter: {{ $word }}">
                            {{ $word }}
                        </button>
                        <div class="flex items-center gap-2 flex-1 justify-end">
                            <div class="w-28 sm:w-36 bg-gray-100 rounded-full h-2.5 overflow-hidden flex justify-start">
                                <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $width }}%"></div>
                            </div>
                            <span class="font-extrabold text-gray-900 w-7 text-right text-xs">{{ $count }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-xs text-emerald-600 italic py-4 text-center">Belum ada kata positif terkumpul</div>
                @endforelse
            </div>
        </div>

        <!-- 2. Kolom Kata Negatif -->
        <div class="space-y-3 bg-[#fef2f2]/60 p-4 rounded-2xl border border-rose-100">
            <div class="flex items-center justify-between pb-2 border-b border-rose-200/60">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <span class="text-xs font-black uppercase tracking-wider text-rose-900">Top Kata Negatif</span>
                </div>
                <span class="text-[11px] font-bold text-rose-700">Frekuensi</span>
            </div>

            <div class="space-y-2">
                @forelse($topNegative as $word => $count)
                    @php
                        $width = max(15, round(($count / $maxNeg) * 100));
                    @endphp
                    <div class="flex items-center justify-between gap-3 text-xs">
                        <button type="button"
                                @click="filterKeyword('{{ $word }}')"
                                class="font-bold text-gray-800 hover:text-rose-700 truncate max-w-[140px] text-left cursor-pointer transition"
                                title="Klik untuk memfilter: {{ $word }}">
                            {{ $word }}
                        </button>
                        <div class="flex items-center gap-2 flex-1 justify-end">
                            <div class="w-28 sm:w-36 bg-gray-100 rounded-full h-2.5 overflow-hidden flex justify-start">
                                <div class="bg-rose-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $width }}%"></div>
                            </div>
                            <span class="font-extrabold text-gray-900 w-7 text-right text-xs">{{ $count }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-xs text-rose-600 italic py-4 text-center">Belum ada kata keluhan negatif terkumpul</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
