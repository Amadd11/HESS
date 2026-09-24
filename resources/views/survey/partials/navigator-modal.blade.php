<!-- QUESTION NAVIGATOR MODAL (GRID 1 - 44) -->
<div x-show="questionListModalOpen" x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="questionListModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/60 transition-opacity"
        @click="questionListModalOpen = false"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="questionListModalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-lg p-5 sm:p-6 border border-gray-100">

            <!-- Header -->
            <div class="flex items-start justify-between pb-3 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-gray-900" id="modal-title">
                        Navigator Soal Kuesioner
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Klik nomor soal untuk berpindah dan memeriksa jawaban Anda.
                    </p>
                </div>
                <button type="button" @click="questionListModalOpen = false"
                    class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Progress Summary -->
            <div class="my-3.5 p-3 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-800">Status Progres:</span>
                    <span class="font-mono font-black text-primary-700" x-text="answeredCount + ' / ' + questions.length + ' Terjawab'"></span>
                </div>
                <!-- Legend Indicators -->
                <div class="flex items-center gap-3 text-[10px] text-gray-500">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Terisi</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-primary-600 ring-2 ring-primary-300"></span> Aktif</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-gray-300"></span> Belum</span>
                </div>
            </div>

            <!-- 8 Dimensions with Question Chips and Feedback Badges -->
            <div class="space-y-2 max-h-[360px] overflow-y-auto p-1">
                <template x-for="(aspect, aIdx) in aspectList" :key="aIdx">
                    <div class="p-2.5 rounded-xl border border-gray-200 bg-gray-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="text-xs font-bold text-gray-800 truncate" x-text="(aIdx + 1) + '. ' + aspect"></div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <!-- 3 Soal Tertutup -->
                            <template x-for="qOffset in [0, 1, 2]" :key="qOffset">
                                <button type="button"
                                    @click="jumpToQuestion((aIdx * 3) + qOffset)"
                                    class="w-8 h-8 rounded-lg text-xs font-bold transition flex items-center justify-center cursor-pointer"
                                    :class="currentIndex === ((aIdx * 3) + qOffset) && step === 'questionnaire'
                                        ? 'bg-primary-700 text-white ring-2 ring-primary-400 shadow-xs'
                                        : (isQuestionAnswered((aIdx * 3) + qOffset)
                                            ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200'
                                            : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-100')">
                                    <span x-text="(aIdx * 3) + qOffset + 1"></span>
                                </button>
                            </template>
                            <!-- Tombol Umpan Balik Aspek -->
                            <button type="button"
                                @click="jumpToFeedback(aIdx)"
                                class="px-2 h-8 rounded-lg text-[11px] font-bold transition flex items-center gap-1 cursor-pointer"
                                :class="currentAspectIndex === aIdx && step === 'feedback'
                                    ? 'bg-primary-700 text-white ring-2 ring-primary-400 shadow-xs'
                                    : (isFeedbackFilled(aspect)
                                        ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200'
                                        : 'bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100')">
                                <span>💬</span>
                                <span class="hidden sm:inline" x-text="isFeedbackFilled(aspect) ? 'Saran ✓' : 'Saran'"></span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modal Actions -->
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                <button type="button" @click="questionListModalOpen = false"
                    class="px-3.5 py-2 rounded-xl text-gray-600 hover:bg-gray-100 font-bold transition cursor-pointer">
                    Tutup
                </button>
                <template x-if="answeredCount === questions.length">
                    <button type="button" @click="step = 'overall'; questionListModalOpen = false; window.scrollTo({ top: 0, behavior: 'smooth' });"
                        class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition shadow-xs cursor-pointer">
                        Lanjut ke Konfirmasi
                    </button>
                </template>
            </div>

        </div>
    </div>
</div>
