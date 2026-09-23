{{-- Multi-Dimensional Sentiment Breakdown --}}
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base md:text-lg font-black text-gray-900">Breakdown Sentimen Multi-Dimensi</h3>
            <p class="text-xs text-gray-400">Pemetaan persepsi dan kepuasan berdasarkan struktur demografi kepegawaian rumah sakit.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- 1. Breakdown by Unit Kerja (Stacked Bar) -->
        <div class="bg-white rounded-3xl p-5 border border-gray-200/90 shadow-xs space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-gray-800">Distribusi per Satuan Kerja</h4>
                    <span class="text-[11px] text-gray-400">Komposisi sentimen direktorat / satuan kerja</span>
                </div>
                <span class="text-[10px] font-bold bg-gray-100 px-2 py-0.5 rounded-md text-gray-600">Stacked Bar</span>
            </div>
            <div class="w-full min-h-[260px] flex items-center justify-center">
                <div id="breakdownUnitChart" class="w-full"></div>
            </div>
        </div>

        <!-- 2. Breakdown by Profesi (Horizontal Bar) -->
        <div class="bg-white rounded-3xl p-5 border border-gray-200/90 shadow-xs space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-gray-800">Distribusi per Profesi Medis/Staf</h4>
                    <span class="text-[11px] text-gray-400">Komposisi sentimen kelompok tenaga kerja</span>
                </div>
                <span class="text-[10px] font-bold bg-gray-100 px-2 py-0.5 rounded-md text-gray-600">Grouped Bar</span>
            </div>
            <div class="w-full min-h-[260px] flex items-center justify-center">
                <div id="breakdownProfChart" class="w-full"></div>
            </div>
        </div>

        <!-- 3. Breakdown by Status Pegawai (Pie / Donut) -->
        <div class="bg-white rounded-3xl p-5 border border-gray-200/90 shadow-xs space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-gray-800">Proporsi Status Kepegawaian</h4>
                    <span class="text-[11px] text-gray-400">PNS, Tetap, Kontrak, & Honorer</span>
                </div>
                <span class="text-[10px] font-bold bg-gray-100 px-2 py-0.5 rounded-md text-gray-600">Donut Chart</span>
            </div>
            <div class="w-full min-h-[260px] flex items-center justify-center">
                <div id="breakdownStatusChart" class="w-full"></div>
            </div>
        </div>

        <!-- 4. Breakdown by Masa Kerja (Tenure Stacked Bar) -->
        <div class="bg-white rounded-3xl p-5 border border-gray-200/90 shadow-xs space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-gray-800">Distribusi Berdasarkan Masa Kerja</h4>
                    <span class="text-[11px] text-gray-400">Pengaruh lama pengabdian terhadap sentimen</span>
                </div>
                <span class="text-[10px] font-bold bg-gray-100 px-2 py-0.5 rounded-md text-gray-600">Stacked Bar</span>
            </div>
            <div class="w-full min-h-[260px] flex items-center justify-center">
                <div id="breakdownTenureChart" class="w-full"></div>
            </div>
        </div>
    </div>
</div>
