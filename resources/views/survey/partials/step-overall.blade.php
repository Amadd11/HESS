<!-- STEP 3: KONFIRMASI PENGISIAN SURVEI (OVERALL) -->
<section x-show="step === 'overall'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200/75 space-y-6">
        <div class="border-b border-gray-100 pb-4">
            <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">Konfirmasi Pengisian</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1 leading-relaxed">
                Seluruh 8 unsur telah dinilai. Pastikan seluruh alasan penilaian dan saran perbaikan sudah lengkap sebelum mengirim survei.
            </p>
        </div>

        <!-- Ringkasan Instrumen Box -->
        <div class="bg-primary-50/70 border border-primary-200/70 rounded-2xl p-4 md:p-5 text-primary-950 space-y-2 text-xs md:text-sm leading-relaxed">
            <div class="font-extrabold flex items-center gap-2 text-primary-900">
                <span class="text-base">📋</span>
                <span>Ringkasan Instrumen HESS RSUP Dr. Sardjito:</span>
            </div>
            <div class="pl-6 space-y-1 text-gray-700 text-xs">
                <div>• 8 unsur × 3 pertanyaan penilaian = <strong>24 pertanyaan tertutup</strong> (skala 1–4)</div>
                <div>• 8 unsur × 2 pertanyaan terbuka = <strong>16 isian kualitatif</strong> (alasan & saran)</div>
                <div class="font-bold text-primary-900 pt-1">• Total = 40 isian per responden.</div>
            </div>
        </div>

        <!-- Status Pengisian 8 Unsur -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">
                Status Kelengkapan 8 Unsur & Umpan Balik:
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <template x-for="(aspect, aIdx) in aspectList" :key="aIdx">
                    <div class="p-3.5 rounded-xl border border-gray-200 bg-gray-50/60 flex items-center justify-between text-xs transition hover:bg-gray-50">
                        <div class="space-y-0.5 min-w-0 pr-2">
                            <div class="font-bold text-gray-900 truncate" x-text="(aIdx + 1) + '. ' + aspect"></div>
                            <div class="text-[11px] text-gray-500 flex items-center gap-2">
                                <span :class="isAspectAnswered(aIdx) ? 'text-emerald-700 font-semibold' : 'text-amber-600'">
                                    <span x-text="getAspectScoreCount(aIdx) + '/3 soal'"></span>
                                </span>
                                <span>•</span>
                                <span :class="isFeedbackFilled(aspect) ? 'text-emerald-700 font-semibold' : 'text-amber-600'"
                                    x-text="isFeedbackFilled(aspect) ? 'Alasan & Saran: Lengkap' : 'Belum lengkap'"></span>
                            </div>
                        </div>
                        <button type="button" @click="editAspect(aIdx)"
                            class="px-2.5 py-1 text-[11px] font-bold text-primary-700 hover:text-primary-800 bg-white border border-gray-200 rounded-lg shadow-2xs hover:bg-primary-50 transition shrink-0 cursor-pointer">
                            Periksa
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Rekomendasi Tempat Kerja (eNPS 0 - 10) - Opsional Pelengkap -->
        <div class="space-y-3 pt-2 border-t border-gray-100">
            <div class="flex items-center justify-between">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Seberapa besar kemungkinan Anda merekomendasikan rumah sakit ini sebagai tempat kerja? (0–10)
                </label>
                <span class="text-[11px] text-gray-400 font-medium">Opsional</span>
            </div>
            <div class="grid grid-cols-6 sm:grid-cols-11 gap-1.5 md:gap-2">
                <template x-for="n in 11" :key="n - 1">
                    <label class="cursor-pointer select-none">
                        <input type="radio" name="nps_score" :value="n - 1" x-model.number="overall.nps_score" class="sr-only">
                        <div class="h-10 md:h-11 rounded-xl border flex items-center justify-center font-bold text-xs md:text-sm transition cursor-pointer"
                            :class="overall.nps_score === (n - 1)
                                 ? 'border-primary-600 bg-primary-600 text-white shadow-sm'
                                 : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50 bg-white text-gray-700'"
                            x-text="n - 1"></div>
                    </label>
                </template>
            </div>
            <div class="flex justify-between text-[10px] font-medium text-gray-400 px-1">
                <span>0: Sangat Tidak Mungkin</span>
                <span>10: Sangat Mungkin</span>
            </div>
        </div>
    </div>
</section>
