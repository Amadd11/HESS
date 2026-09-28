{{-- Top Action Bar Header & Export/Navigation Actions --}}
<div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-20">
    <div class="flex items-start gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0 border border-primary-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div>
            <h2 class="text-base md:text-lg font-bold text-gray-900 tracking-tight">Daftar Mentah Respon Kuesioner Anonim</h2>
            <p class="text-xs text-gray-500 mt-0.5">Pantau dan telusuri setiap pengisian kuesioner, skor rincian dimensi, serta masukan kualitatif responden.</p>
        </div>
    </div>

    <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
        <x-button variant="secondary" size="md" :href="route('admin.dashboard.export', request()->all())">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Unduh Excel</span>
        </x-button>

        <x-button variant="primary" size="md" :href="route('admin.dashboard')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span>Lihat Analytics</span>
        </x-button>
    </div>
</div>
