{{-- Sentiment Trend Line Chart --}}
<div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
        <div>
            <h3 class="text-sm md:text-base font-black text-gray-900">Tren Sentimen Antar Periode</h3>
            <p class="text-xs text-gray-400">Dinamika fluktuasi sentimen positif, netral, dan negatif antar siklus survei kepuasan.</p>
        </div>
        <div class="flex items-center gap-4 text-xs">
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                <span class="font-bold text-gray-700">Positif</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                <span class="font-bold text-gray-700">Netral</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full bg-rose-600"></span>
                <span class="font-bold text-gray-700">Negatif</span>
            </div>
        </div>
    </div>

    <div class="w-full min-h-[300px] flex items-center justify-center">
        <div id="sentimentTrendChart" class="w-full"></div>
    </div>
</div>
