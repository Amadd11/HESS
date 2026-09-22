<!-- Progress & Utility Header Bar -->
<div class="bg-white rounded-2xl p-4 md:px-6 md:py-4 shadow-sm border border-gray-200/75 space-y-3">
    <div class="flex items-center justify-between text-xs md:text-sm">
        <!-- Left: Category / Step Name -->
        <div class="flex items-center gap-2.5 min-w-0">
            <span class="inline-block w-2.5 h-2.5 rounded-full bg-primary-600 animate-pulse shrink-0"></span>
            <span class="text-primary-800 font-extrabold truncate tracking-tight"
                x-text="step === 'profile' ? 'Langkah 1: Profil Pegawai' : (step === 'overall' ? 'Langkah 3: Evaluasi Akhir & eNPS' : currentCategoryName)"></span>
        </div>

        <!-- Right: Quick Controls (Question Counter & Auto-Advance Toggle) -->
        <div class="flex items-center gap-2.5 shrink-0">
            <!-- Question counter on desktop during questionnaire -->
            <template x-if="step === 'questionnaire'">
                <button type="button" @click="questionListModalOpen = true"
                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-gray-600 hover:text-primary-700 bg-gray-50 hover:bg-primary-50 border border-gray-200 transition cursor-pointer">
                    <span x-text="'Soal ' + (currentIndex + 1) + ' / ' + questions.length"></span>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </template>

            <!-- Auto-Advance Toggle -->
            <button type="button" @click="toggleAutoAdvance()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition cursor-pointer"
                :class="autoAdvance ? 'bg-primary-50 text-primary-700 border border-primary-200 shadow-xs' : 'bg-gray-100 text-gray-500 border border-gray-200'"
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
