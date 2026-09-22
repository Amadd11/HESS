<!-- STEP 2: KUESIONER KARTU PERTANYAAN (1 SOAL PER LAYAR) -->
<section x-show="step === 'questionnaire'" x-cloak x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <template x-if="currentQuestion">
        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200/75 space-y-6">
            <!-- Meta Info Butir Soal -->
            <div class="flex items-center justify-between">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-primary-100 text-primary-700 tracking-wide uppercase"
                    x-text="currentQuestion.code + ' • ' + currentCategoryName"></span>
                <button type="button" @click="questionListModalOpen = true"
                    class="text-xs font-bold text-gray-500 hover:text-primary-700 transition flex items-center gap-1 cursor-pointer">
                    <span x-text="'Soal ' + (currentIndex + 1) + ' dari ' + questions.length"></span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <!-- Pernyataan Soal -->
            <div class="min-h-[70px] flex items-center">
                <h3 class="text-lg md:text-xl font-bold text-gray-800 leading-snug"
                    x-text="currentQuestion.text"></h3>
            </div>

            <!-- Pilihan Jawaban Skala 1 - 5 -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-2.5">
                <template x-for="(label, idx) in currentQuestion.labels" :key="idx">
                    <label class="cursor-pointer select-none">
                        <input type="radio"
                            :name="'q_' + currentQuestion.id"
                            :value="idx + 1"
                            @change="setAnswer(idx + 1)"
                            :checked="answers[currentQuestion.id] === (idx + 1)"
                            class="sr-only">
                        <div class="flex md:flex-col items-center justify-start md:justify-center p-3 md:p-4 rounded-xl border text-left md:text-center transition-all min-h-[52px] md:min-h-[95px] relative group"
                            :class="answers[currentQuestion.id] === (idx + 1)
                                 ? 'border-primary-600 bg-primary-50 text-primary-800 ring-2 ring-primary-600/30 shadow-sm'
                                 : 'border-gray-200 hover:border-gray-300 bg-white text-gray-700'">
                            <div class="flex items-center gap-2 mr-3 md:mr-0 md:mb-1.5">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center font-extrabold text-sm"
                                    :class="answers[currentQuestion.id] === (idx + 1) ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600'"
                                    x-text="idx + 1"></span>
                            </div>
                            <span class="text-xs md:text-[11px] font-medium leading-tight" x-text="label"></span>
                        </div>
                    </label>
                </template>
            </div>
        </div>
    </template>
</section>