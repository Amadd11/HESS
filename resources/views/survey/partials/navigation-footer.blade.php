<!-- FLOATING BOTTOM NAVIGATION -->
<nav x-show="step !== 'profile'" x-cloak
    class="fixed left-0 right-0 bottom-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200/80 px-4 py-3 md:py-4 shadow-lg md:static md:bg-transparent md:border-0 md:shadow-none md:p-0">
    <div class="max-w-2xl mx-auto flex items-center gap-3">
        <!-- Tombol Kembali -->
        <button type="button"
            @click="step === 'overall' ? backToQuestions() : prevQuestion()"
            class="h-12 px-5 rounded-xl border border-gray-200 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-700 font-bold text-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="hidden sm:inline">Kembali</span>
        </button>

        <!-- Tombol Lanjut (di Step Kuesioner) -->
        <template x-if="step === 'questionnaire'">
            <button type="button"
                @click="nextQuestion()"
                :disabled="!isCurrentAnswered"
                class="flex-1 h-12 bg-primary-600 hover:bg-primary-700 active:scale-[0.99] text-white font-bold rounded-xl shadow-md shadow-primary-600/20 disabled:opacity-40 disabled:cursor-not-allowed transition flex items-center justify-center gap-2 text-sm md:text-base cursor-pointer">
                <span>Lanjut</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </template>

        <!-- Tombol Submit Akhir (di Step Overall) -->
        <template x-if="step === 'overall'">
            <button type="button"
                @click="submitSurvey()"
                :disabled="isSubmitting"
                class="flex-1 h-12 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white font-bold rounded-xl shadow-md shadow-emerald-600/20 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center gap-2 text-sm md:text-base cursor-pointer">
                <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="isSubmitting ? 'Mengirim Data...' : 'Kirim Survei ✓'"></span>
            </button>
        </template>
    </div>
</nav>
