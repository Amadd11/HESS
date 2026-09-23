{{-- Top 10 Positive & Negative Keywords --}}
<div class="bg-white rounded-3xl p-5 md:p-6 border border-gray-200/90 shadow-xs flex flex-col justify-between h-full space-y-4">
    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
        <div>
            <h3 class="text-sm md:text-base font-black text-gray-900">Top 10 Kata Positif dan Negatif</h3>
            <p class="text-xs text-gray-400">Peringkat frekuensi kata paling sering diulang dalam masukan</p>
        </div>
        <span class="text-[10px] font-bold bg-gray-100 px-2 py-0.5 rounded-md text-gray-600">Frekuensi</span>
    </div>

    @php
        $maxPos = ! empty($topPositive) ? max($topPositive) : 1;
        $maxNeg = ! empty($topNegative) ? max($topNegative) : 1;
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1">
        <!-- 1. Kolom Kata Positif -->
        <div class="space-y-2.5 bg-[#f2fbf5]/60 p-3.5 rounded-2xl border border-emerald-100 flex flex-col justify-between">
            <div class="flex items-center justify-between pb-1.5 border-b border-emerald-200/60">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                    <span class="text-[11px] font-black uppercase tracking-wider text-emerald-900">Kata Positif</span>
                </div>
                <span class="text-[10px] font-bold text-emerald-700">Frekuensi</span>
            </div>

            <div class="space-y-1.5 flex-1 flex flex-col justify-around">
                @forelse($topPositive as $word => $count)
                    @php
                        $width = max(15, round(($count / $maxPos) * 100));
                    @endphp
                    <div class="flex items-center justify-between gap-2 text-xs">
                        <button type="button"
                                @click="filterKeyword('{{ $word }}')"
                                class="font-bold text-gray-800 hover:text-emerald-700 text-left cursor-pointer transition whitespace-nowrap overflow-hidden text-ellipsis max-w-[105px] shrink-0"
                                title="Klik untuk memfilter: {{ $word }}">
                            {{ $word }}
                        </button>
                        <div class="flex items-center gap-2 flex-1 justify-end min-w-0">
                            <div class="w-full max-w-[85px] bg-emerald-100/60 rounded-full h-2 overflow-hidden flex justify-start">
                                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $width }}%"></div>
                            </div>
                            <span class="font-extrabold text-gray-900 w-5 text-right text-xs shrink-0 font-mono">{{ $count }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-xs text-emerald-600 italic py-4 text-center">Belum ada kata positif</div>
                @endforelse
            </div>
        </div>

        <!-- 2. Kolom Kata Negatif -->
        <div class="space-y-2.5 bg-[#fef2f2]/60 p-3.5 rounded-2xl border border-rose-100 flex flex-col justify-between">
            <div class="flex items-center justify-between pb-1.5 border-b border-rose-200/60">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
                    <span class="text-[11px] font-black uppercase tracking-wider text-rose-900">Kata Negatif</span>
                </div>
                <span class="text-[10px] font-bold text-rose-700">Frekuensi</span>
            </div>

            <div class="space-y-1.5 flex-1 flex flex-col justify-around">
                @forelse($topNegative as $word => $count)
                    @php
                        $width = max(15, round(($count / $maxNeg) * 100));
                    @endphp
                    <div class="flex items-center justify-between gap-2 text-xs">
                        <button type="button"
                                @click="filterKeyword('{{ $word }}')"
                                class="font-bold text-gray-800 hover:text-rose-700 text-left cursor-pointer transition whitespace-nowrap overflow-hidden text-ellipsis max-w-[105px] shrink-0"
                                title="Klik untuk memfilter: {{ $word }}">
                            {{ $word }}
                        </button>
                        <div class="flex items-center gap-2 flex-1 justify-end min-w-0">
                            <div class="w-full max-w-[85px] bg-rose-100/60 rounded-full h-2 overflow-hidden flex justify-start">
                                <div class="bg-rose-500 h-2 rounded-full transition-all duration-500" style="width: {{ $width }}%"></div>
                            </div>
                            <span class="font-extrabold text-gray-900 w-5 text-right text-xs shrink-0 font-mono">{{ $count }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-xs text-rose-600 italic py-4 text-center">Belum ada kata keluhan</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Footer Note -->
    <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400">
        <span>Klik kata untuk memfilter tabel</span>
        <span class="font-bold text-gray-600 font-mono">Top 10 Per Kategori</span>
    </div>
</div>
