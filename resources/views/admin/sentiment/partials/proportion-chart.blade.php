{{-- Donut Proporsi Sentimen --}}
<div class="bg-white rounded-3xl p-5 md:p-6 border border-gray-200/90 shadow-xs flex flex-col justify-between h-full space-y-4">
    <!-- Header Card -->
    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
        <div>
            <h3 class="text-sm md:text-base font-black text-gray-900">Proporsi Sentimen</h3>
            <p class="text-xs text-gray-400">Komposisi kata respon positif, netral, & negatif</p>
        </div>
        <span class="text-[10px] font-bold bg-gray-100 px-2 py-0.5 rounded-md text-gray-600">Donut Chart</span>
    </div>

    <!-- Donut Chart & Legend Container -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-6 my-auto py-2">
        <!-- 1. Donut dengan Overlay Center Total -->
        <div class="relative w-[210px] h-[210px] flex items-center justify-center shrink-0">
            <div id="sentimentPropDonutChart" class="w-[210px] h-[210px]"></div>

            {{-- Text di Lubang Tengah Donut (Total X kata) --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center select-none">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">TOTAL</span>
                <span class="text-2xl md:text-3xl font-black text-gray-900 leading-tight font-mono my-0.5">
                    {{ number_format($sentimentProportion['total']) }}
                </span>
                <span class="text-[11px] font-semibold text-gray-400 leading-none">
                    {{ $sentimentProportion['unit'] }}
                </span>
            </div>
        </div>

        <!-- 2. Legend List di Sisi Kanan (Persis Gambar Mockup) -->
        <div class="space-y-4 sm:pl-5 sm:border-l sm:border-gray-100 w-full sm:w-auto flex flex-row sm:flex-col justify-around sm:justify-center">
            <!-- Positif -->
            <div class="flex items-center gap-3">
                <span class="w-4 h-4 rounded-full bg-[#22C55E] shrink-0 shadow-2xs"></span>
                <div>
                    <div class="text-xs sm:text-sm font-bold text-gray-900 leading-tight">Positif</div>
                    <div class="text-[11px] sm:text-xs text-gray-500 font-medium mt-0.5">
                        {{ number_format($sentimentProportion['positive']['count']) }} {{ $sentimentProportion['unit'] }}
                    </div>
                </div>
            </div>

            <!-- Netral -->
            <div class="flex items-center gap-3">
                <span class="w-4 h-4 rounded-full bg-[#F59E0B] shrink-0 shadow-2xs"></span>
                <div>
                    <div class="text-xs sm:text-sm font-bold text-gray-900 leading-tight">Netral</div>
                    <div class="text-[11px] sm:text-xs text-gray-500 font-medium mt-0.5">
                        {{ number_format($sentimentProportion['neutral']['count']) }} {{ $sentimentProportion['unit'] }}
                    </div>
                </div>
            </div>

            <!-- Negatif -->
            <div class="flex items-center gap-3">
                <span class="w-4 h-4 rounded-full bg-[#EF4444] shrink-0 shadow-2xs"></span>
                <div>
                    <div class="text-xs sm:text-sm font-bold text-gray-900 leading-tight">Negatif</div>
                    <div class="text-[11px] sm:text-xs text-gray-500 font-medium mt-0.5">
                        {{ number_format($sentimentProportion['negative']['count']) }} {{ $sentimentProportion['unit'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Note -->
    <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400">
        <span>Rasio kata sentimen</span>
        <span class="font-bold text-emerald-700 font-mono shrink-0">{{ $sentimentProportion['positive']['percent'] }}% Positif</span>
    </div>
</div>
