{{-- 4 KPI Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Card 1: Sentimen Positif -->
    <div class="bg-white rounded-3xl p-5 border border-emerald-200/90 shadow-xs flex flex-col justify-between relative overflow-hidden group hover:border-emerald-300 transition">
        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-50 rounded-full blur-xl pointer-events-none"></div>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-800">Positif</span>
                <div class="w-9 h-9 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M8 14s1.5 2 4 2 4-2 4-2" stroke-linecap="round" />
                        <line x1="9" y1="9" x2="9.01" y2="9" stroke-width="2.5" stroke-linecap="round" />
                        <line x1="15" y1="9" x2="15.01" y2="9" stroke-width="2.5" stroke-linecap="round" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-black tracking-tight text-emerald-950 flex items-baseline gap-2 flex-wrap">
                    <span>{{ $sentimentProportion['positive']['percent'] ?? $kpis['positive']['percent'] }}%</span>
                    @if(isset($sentimentProportion['positive']['count']))
                    <span class="text-xs font-semibold text-emerald-700/80 font-mono">({{ number_format($sentimentProportion['positive']['count']) }} {{ $sentimentProportion['unit'] }})</span>
                    @endif
                </div>
                <div class="text-xs font-semibold text-emerald-700/90 mt-0.5">
                    {{ $kpis['positive']['count'] }} Masukan Responden
                </div>
            </div>
        </div>
        <div class="mt-3 pt-2.5 border-t border-emerald-100 text-[11px] text-emerald-600 font-medium flex items-center gap-1">
            <span>Apresiasi & Kepuasan</span>
        </div>
    </div>

    <!-- Card 2: Sentimen Netral -->
    <div class="bg-white rounded-3xl p-5 border border-amber-200/90 shadow-xs flex flex-col justify-between relative overflow-hidden group hover:border-amber-300 transition">
        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-amber-50 rounded-full blur-xl pointer-events-none"></div>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-amber-800">Netral</span>
                <div class="w-9 h-9 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="8" y1="15" x2="16" y2="15" stroke-linecap="round" stroke-width="2" />
                        <line x1="9" y1="9" x2="9.01" y2="9" stroke-width="2.5" stroke-linecap="round" />
                        <line x1="15" y1="9" x2="15.01" y2="9" stroke-width="2.5" stroke-linecap="round" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-black tracking-tight text-amber-950 flex items-baseline gap-2 flex-wrap">
                    <span>{{ $sentimentProportion['neutral']['percent'] ?? $kpis['neutral']['percent'] }}%</span>
                    @if(isset($sentimentProportion['neutral']['count']))
                    <span class="text-xs font-semibold text-amber-700/80 font-mono">({{ number_format($sentimentProportion['neutral']['count']) }} {{ $sentimentProportion['unit'] }})</span>
                    @endif
                </div>
                <div class="text-xs font-semibold text-amber-700/90 mt-0.5">
                    {{ $kpis['neutral']['count'] }} Masukan Responden
                </div>
            </div>
        </div>
        <div class="mt-3 pt-2.5 border-t border-amber-100 text-[11px] text-amber-600 font-medium flex items-center gap-1">
            <span>SOP & Alur Operasional</span>
        </div>
    </div>

    <!-- Card 3: Sentimen Negatif -->
    <div class="bg-white rounded-3xl p-5 border border-rose-200/90 shadow-xs flex flex-col justify-between relative overflow-hidden group hover:border-rose-300 transition">
        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-rose-50 rounded-full blur-xl pointer-events-none"></div>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-rose-800">Negatif</span>
                <div class="w-9 h-9 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M16 16s-1.5-2-4-2-4 2-4 2" stroke-linecap="round" />
                        <line x1="9" y1="9" x2="9.01" y2="9" stroke-width="2.5" stroke-linecap="round" />
                        <line x1="15" y1="9" x2="15.01" y2="9" stroke-width="2.5" stroke-linecap="round" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-black tracking-tight text-rose-950 flex items-baseline gap-2 flex-wrap">
                    <span>{{ $sentimentProportion['negative']['percent'] ?? $kpis['negative']['percent'] }}%</span>
                    @if(isset($sentimentProportion['negative']['count']))
                    <span class="text-xs font-semibold text-rose-700/80 font-mono">({{ number_format($sentimentProportion['negative']['count']) }} {{ $sentimentProportion['unit'] }})</span>
                    @endif
                </div>
                <div class="text-xs font-semibold text-rose-700/90 mt-0.5">
                    {{ $kpis['negative']['count'] }} Masukan Responden
                </div>
            </div>
        </div>
        <div class="mt-3 pt-2.5 border-t border-rose-100 text-[11px] text-rose-600 font-medium flex items-center gap-1">
            <span>Keluhan & Hambatan</span>
        </div>
    </div>

    <!-- Card 4: Total Feedback -->
    <div class="bg-white rounded-3xl p-5 border border-gray-200/90 shadow-xs flex flex-col justify-between relative overflow-hidden group hover:border-gray-300 transition">
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-gray-500">Total Feedback</span>
                <div class="w-9 h-9 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-black tracking-tight text-gray-900">
                    {{ $kpis['total_feedback'] }}
                </div>
                <div class="text-xs font-semibold text-gray-500 mt-0.5">
                    {{ $kpis['total_words'] }} Total Kata Teranalisis
                </div>
            </div>
        </div>
        <div class="mt-3 pt-2.5 border-t border-gray-100 text-[11px] text-gray-500 font-medium flex items-center justify-between">
            <span>Respon Masukan</span>
            <span class="font-bold text-gray-700">{{ $totalFeedbackCount }} Responden</span>
        </div>
    </div>
</div>
