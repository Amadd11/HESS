<!-- STEP: ASPECT FEEDBACK (ALASAN & SARAN PERBAIKAN PER UNSUR) -->
<section x-show="step === 'feedback'" x-cloak x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200/75 space-y-6">
        <!-- Header Info Unsur -->
        <div class="border-b border-gray-100 pb-4">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-primary-100 text-primary-700 tracking-wide uppercase mb-2"
                x-text="'UNSUR ' + (currentAspectIndex + 1) + ' DARI 8'"></span>
            <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight"
                x-text="currentAspectTitle"></h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1 leading-relaxed">
                Setelah memberikan penilaian pada 3 pertanyaan unsur ini, mohon berikan alasan dan saran perbaikan konstruktif Anda.
            </p>
        </div>

        <!-- 2 Isian Terbuka -->
        <div class="space-y-5">
            <!-- 1. Alasan Penilaian -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider leading-relaxed">
                    1. Jelaskan alasan yang mendasari penilaian Anda <span class="text-red-500">*</span>
                </label>
                <textarea x-model="currentFeedback.reason" rows="3"
                    placeholder="Jelaskan alasan yang mendasari penilaian Anda..."
                    class="w-full p-3.5 rounded-xl border border-gray-300 bg-white text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition resize-none"></textarea>
            </div>

            <!-- 2. Saran Perbaikan -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider leading-relaxed">
                    2. Saran perbaikan yang dapat dilakukan <span class="text-red-500">*</span>
                </label>
                <textarea x-model="currentFeedback.suggestion" rows="3"
                    placeholder="Tuliskan saran perbaikan yang dapat dilakukan..."
                    class="w-full p-3.5 rounded-xl border border-gray-300 bg-white text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition resize-none"></textarea>
            </div>
        </div>

        <div class="text-[11px] text-gray-400 italic">
            * Isian kualitatif Anda disimpan secara otomatis dan anonim untuk perumusan Rencana Tindak Lanjut (RTL).
        </div>
    </div>
</section>
