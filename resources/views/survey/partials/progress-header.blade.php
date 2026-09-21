<!-- Progress & Utility Header Bar -->
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/75 space-y-2.5">
    <div class="flex items-center justify-between text-xs">
        <!-- Left: Category / Step Name -->
        <div class="flex items-center gap-2 min-w-0">
            <span class="inline-block w-2 h-2 rounded-full bg-primary-600 animate-pulse shrink-0"></span>
            <span class="text-primary-700 font-extrabold truncate"
                x-text="step === 'profile' ? 'Langkah 1: Profil Pegawai' : (step === 'overall' ? 'Langkah 3: Evaluasi Akhir' : currentCategoryName)"></span>
        </div>

        <!-- Right: Quick Controls (Auto-Advance Toggle) -->
        <div class="flex items-center gap-2 shrink-0">
            <!-- Auto-Advance Toggle -->
            <button type="button" @click="toggleAutoAdvance()"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[11px] font-semibold transition cursor-pointer"
                :class="autoAdvance ? 'bg-primary-50 text-primary-700 border border-primary-200' : 'bg-gray-100 text-gray-500 border border-gray-200'"
                title="Transisi otomatis ke soal berikutnya setelah memilih jawaban skala 1-5">
                <span class="w-1.5 h-1.5 rounded-full" :class="autoAdvance ? 'bg-primary-600 animate-pulse' : 'bg-gray-400'"></span>
                <span class="hidden sm:inline">Auto-Lanjut:</span>
                <span x-text="autoAdvance ? 'ON' : 'OFF'" class="font-bold"></span>
            </button>
        </div>
    </div>

    <!-- Progress Bar Indicator -->
    <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r from-primary-600 to-primary-500 rounded-full transition-all duration-300 ease-out"
            :style="'width: ' + progressPercentage + '%'"></div>
    </div>
</div>

<!-- Alert Pesan Error jika ada -->
<div x-show="errorMessage" x-cloak
    class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-xs text-red-700 flex items-start gap-2.5 animate-shake shadow-xs">
    <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span x-text="errorMessage" class="font-medium"></span>
</div>
