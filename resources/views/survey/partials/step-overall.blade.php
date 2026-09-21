<!-- STEP 3: OVERALL SATISFACTION & eNPS -->
<section x-show="step === 'overall'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200/75 space-y-6">
        <div>
            <h2 class="text-lg md:text-xl font-bold text-gray-900">Penilaian Keseluruhan</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1">
                Bagian akhir ini mengukur penilaian kepuasan global serta kesediaan merekomendasikan rumah sakit.
            </p>
        </div>

        <!-- Kepuasan Global (1-5) -->
        <div class="space-y-2.5">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                Secara keseluruhan, seberapa puas Anda dengan pekerjaan Anda di rumah sakit ini? <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-2">
                <template x-for="(label, idx) in ['Sangat Tidak Puas', 'Tidak Puas', 'Netral', 'Puas', 'Sangat Puas']" :key="idx">
                    <label class="cursor-pointer select-none">
                        <input type="radio" name="overall_score" :value="idx + 1" x-model.number="overall.overall_score" class="sr-only">
                        <div class="flex md:flex-col items-center justify-start md:justify-center p-3 rounded-xl border transition-all text-left md:text-center min-h-[48px]"
                            :class="overall.overall_score === (idx + 1)
                                 ? 'border-primary-600 bg-primary-50 text-primary-800 ring-2 ring-primary-600/30'
                                 : 'border-gray-200 hover:border-gray-300 bg-white text-gray-700'">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs mr-2 md:mr-0 md:mb-1"
                                :class="overall.overall_score === (idx + 1) ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600'"
                                x-text="idx + 1"></span>
                            <span class="text-xs font-medium" x-text="label"></span>
                        </div>
                    </label>
                </template>
            </div>
        </div>

        <!-- Rekomendasi Tempat Kerja (eNPS 0 - 10) -->
        <div class="space-y-2.5 pt-2">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                Seberapa besar kemungkinan Anda merekomendasikan rumah sakit ini sebagai tempat kerja yang baik? (Skala 0–10) <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-6 sm:grid-cols-11 gap-1.5">
                <template x-for="n in 11" :key="n - 1">
                    <label class="cursor-pointer select-none">
                        <input type="radio" name="nps_score" :value="n - 1" x-model.number="overall.nps_score" class="sr-only">
                        <div class="h-11 rounded-xl border flex items-center justify-center font-bold text-sm transition cursor-pointer"
                            :class="overall.nps_score === (n - 1)
                                 ? 'border-primary-600 bg-primary-600 text-white shadow-sm'
                                 : 'border-gray-200 hover:border-gray-300 bg-white text-gray-700'"
                            x-text="n - 1"></div>
                    </label>
                </template>
            </div>
            <div class="flex justify-between text-[11px] text-gray-400 px-0.5">
                <span>0: Sangat Tidak Mungkin</span>
                <span>10: Sangat Mungkin</span>
            </div>
        </div>

        <!-- Pertanyaan Terbuka: Perlu Diperbaiki -->
        <div class="space-y-1.5 pt-2">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                Apa satu hal yang paling perlu diperbaiki untuk meningkatkan kepuasan kerja pegawai?
            </label>
            <textarea x-model="overall.improve_text" rows="3"
                placeholder="Tuliskan masukan singkat Anda (opsional)..."
                class="w-full p-3.5 rounded-xl border border-gray-300 bg-white text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition"></textarea>
        </div>

        <!-- Pertanyaan Terbuka: Paling Disukai -->
        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                Apa hal yang paling Anda sukai dari bekerja di rumah sakit ini?
            </label>
            <textarea x-model="overall.like_text" rows="3"
                placeholder="Tuliskan hal yang Anda sukai (opsional)..."
                class="w-full p-3.5 rounded-xl border border-gray-300 bg-white text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition"></textarea>
        </div>
    </div>
</section>
