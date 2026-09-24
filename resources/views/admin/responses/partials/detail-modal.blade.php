<!-- Response Detail Modal -->
<div x-show="detailModalOpen" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
     role="dialog" aria-modal="true" aria-labelledby="modal-headline">

    <!-- Backdrop -->
    <div x-show="detailModalOpen"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeDetail()"
         class="fixed inset-0 bg-gray-900/60 transition-opacity"></div>

    <!-- Modal Content Box -->
    <div x-show="detailModalOpen"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-4xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden z-10 my-8 flex flex-col max-h-[90vh]">

        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/70 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-bold text-gray-900">Detail Respon Survei Pegawai</h3>
                        <template x-if="selectedResponse">
                            <span class="font-mono text-xs px-2 py-0.5 rounded bg-gray-200 text-gray-700 font-bold" x-text="'#' + selectedResponse.id"></span>
                        </template>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5" x-text="selectedResponse ? ('Terkirim pada: ' + new Date(selectedResponse.completed_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' })) : 'Memuat data...'"></p>
                </div>
            </div>

            <button type="button" @click="closeDetail()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="py-20 flex flex-col items-center justify-center text-center space-y-3">
            <div class="w-8 h-8 border-3 border-primary-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-xs font-semibold text-gray-500">Memuat rincian butir respon survei...</p>
        </div>

        <!-- Loaded Content -->
        <div x-show="!loading && selectedResponse" class="flex-1 overflow-y-auto p-6 space-y-6">

            <!-- Sub Tabs -->
            <div class="flex items-center gap-2 border-b border-gray-100 pb-2 text-xs font-bold">
                <button type="button" @click="activeTab = 'summary'"
                        :class="activeTab === 'summary' ? 'bg-primary-700 text-white shadow-xs' : 'text-gray-600 hover:bg-gray-100'"
                        class="px-3.5 py-1.5 rounded-xl transition cursor-pointer flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Profil & Kualitatif</span>
                </button>

                <button type="button" @click="activeTab = 'msq'"
                        :class="activeTab === 'msq' ? 'bg-purple-700 text-white shadow-xs' : 'text-gray-600 hover:bg-gray-100'"
                        class="px-3.5 py-1.5 rounded-xl transition cursor-pointer flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                    <span>Butir MSQ-20 (<span x-text="msqAnswers.length"></span>)</span>
                </button>

                <button type="button" @click="activeTab = 'hospital'"
                        :class="activeTab === 'hospital' ? 'bg-blue-700 text-white shadow-xs' : 'text-gray-600 hover:bg-gray-100'"
                        class="px-3.5 py-1.5 rounded-xl transition cursor-pointer flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    <span>Faktor Lingkungan RS (<span x-text="hospitalAnswers.length"></span>)</span>
                </button>
            </div>

            <!-- Tab 1: Ringkasan & Kualitatif -->
            <div x-show="activeTab === 'summary'" class="space-y-5">
                <!-- Demographic & Overall Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200">
                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Satuan Kerja</span>
                        <span class="font-bold text-gray-900 text-xs mt-1 block" x-text="selectedResponse ? selectedResponse.unit : '-'"></span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200">
                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Profesi Pegawai</span>
                        <span class="font-bold text-gray-900 text-xs mt-1 block" x-text="selectedResponse ? selectedResponse.profession : '-'"></span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200">
                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Status Kepegawaian</span>
                        <span class="font-bold text-gray-900 text-xs mt-1 block" x-text="selectedResponse ? selectedResponse.status : '-'"></span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200">
                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Masa Kerja</span>
                        <span class="font-bold text-gray-900 text-xs mt-1 block" x-text="selectedResponse ? selectedResponse.tenure : '-'"></span>
                    </div>
                </div>

                <!-- Scores Breakdown -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="p-3.5 rounded-xl bg-primary-50/50 border border-primary-100">
                        <span class="text-[10px] uppercase font-bold text-primary-600 tracking-wider block">Kepuasan Menyeluruh</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-2xl font-black text-primary-900" x-text="selectedResponse ? selectedResponse.overall_score : 0"></span>
                            <span class="text-xs text-primary-600 font-bold">/ 5</span>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-emerald-50/50 border border-emerald-100">
                        <span class="text-[10px] uppercase font-bold text-emerald-600 tracking-wider block">Net Promoter Score (NPS)</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-2xl font-black text-emerald-900" x-text="selectedResponse ? selectedResponse.nps_score : 0"></span>
                            <span class="text-xs text-emerald-600 font-bold">/ 10</span>
                        </div>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-extrabold uppercase"
                              :class="{
                                  'bg-emerald-100 text-emerald-800': selectedResponse && selectedResponse.nps_category === 'promoter',
                                  'bg-amber-100 text-amber-800': selectedResponse && selectedResponse.nps_category === 'passive',
                                  'bg-red-100 text-red-800': selectedResponse && selectedResponse.nps_category === 'detractor'
                              }"
                              x-text="selectedResponse ? selectedResponse.nps_category : ''">
                        </span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-purple-50/50 border border-purple-100">
                        <span class="text-[10px] uppercase font-bold text-purple-600 tracking-wider block">Indeks MSQ-20</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-2xl font-black text-purple-900" x-text="selectedResponse ? Number(selectedResponse.general_score).toFixed(1) + '%' : '0%'"></span>
                        </div>
                        <div class="text-[11px] text-purple-600 font-semibold mt-0.5 flex items-center justify-between">
                            <span>Rata-rata:</span>
                            <span class="font-bold font-mono" x-text="selectedResponse ? (((selectedResponse.general_score / 100) * 5).toFixed(2) + ' / 5.0') : '-'"></span>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-sky-50/50 border border-sky-100">
                        <span class="text-[10px] uppercase font-bold text-sky-600 tracking-wider block">Indeks Faktor RS</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-2xl font-black text-sky-900" x-text="selectedResponse ? Number(selectedResponse.hospital_score).toFixed(1) + '%' : '0%'"></span>
                        </div>
                        <div class="text-[11px] text-sky-600 font-semibold mt-0.5 flex items-center justify-between">
                            <span>Rata-rata:</span>
                            <span class="font-bold font-mono" x-text="selectedResponse ? (((selectedResponse.hospital_score / 100) * 5).toFixed(2) + ' / 5.0') : '-'"></span>
                        </div>
                    </div>
                </div>

                <!-- Qualitative Text Feedback -->
                <div class="space-y-3 pt-2">
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Umpan Balik Kualitatif Responden</h4>
                    
                    <div class="p-4 rounded-xl bg-emerald-50/40 border border-emerald-200/80 space-y-1">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-800">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                            <span>Hal yang Paling Disukai dari Bekerja di RS:</span>
                        </div>
                        <p class="text-xs text-gray-700 leading-relaxed italic pl-5.5" x-text="selectedResponse && selectedResponse.like_text ? selectedResponse.like_text : '(Tidak diisi / kosong)'"></p>
                    </div>

                    <div class="p-4 rounded-xl bg-amber-50/40 border border-amber-200/80 space-y-1">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-amber-800">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Saran Perbaikan / Hal yang Perlu Ditingkatkan:</span>
                        </div>
                        <p class="text-xs text-gray-700 leading-relaxed italic pl-5.5" x-text="selectedResponse && selectedResponse.improve_text ? selectedResponse.improve_text : '(Tidak diisi / kosong)'"></p>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Butir MSQ-20 Answers -->
            <div x-show="activeTab === 'msq'" class="space-y-3">
                <div class="p-3 bg-purple-50 rounded-xl border border-purple-100 flex items-center justify-between text-xs">
                    <span class="font-bold text-purple-800">Instrumen Baku Minnesota Satisfaction Questionnaire (MSQ-20)</span>
                    <span class="text-purple-600 font-semibold">Skala 1 - 5</span>
                </div>

                <div class="border border-gray-200 rounded-xl overflow-hidden divide-y divide-gray-100">
                    <template x-for="(ans, idx) in msqAnswers" :key="ans.id">
                        <div class="p-3.5 flex items-start justify-between gap-4 hover:bg-gray-50/80 transition">
                            <div class="flex items-start gap-3 min-w-0">
                                <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-purple-100 text-purple-800 shrink-0" x-text="ans.question_code"></span>
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-gray-800" x-text="ans.question_text"></p>
                                    <span class="text-[10px] text-gray-400 mt-0.5 block" x-text="ans.category_name"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs"
                                      :class="{
                                          'bg-emerald-100 text-emerald-800': ans.score >= 4,
                                          'bg-amber-100 text-amber-800': ans.score === 3,
                                          'bg-red-100 text-red-800': ans.score <= 2
                                      }"
                                      x-text="ans.score"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Tab 3: Butir Faktor RS Answers -->
            <div x-show="activeTab === 'hospital'" class="space-y-3">
                <div class="p-3 bg-blue-50 rounded-xl border border-blue-100 flex items-center justify-between text-xs">
                    <span class="font-bold text-blue-800">Faktor Lingkungan & Kondisi Operasional Rumah Sakit</span>
                    <span class="text-blue-600 font-semibold">Skala 1 - 5</span>
                </div>

                <div class="border border-gray-200 rounded-xl overflow-hidden divide-y divide-gray-100">
                    <template x-for="(ans, idx) in hospitalAnswers" :key="ans.id">
                        <div class="p-3.5 flex items-start justify-between gap-4 hover:bg-gray-50/80 transition">
                            <div class="flex items-start gap-3 min-w-0">
                                <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-sky-100 text-sky-800 shrink-0" x-text="ans.question_code"></span>
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-gray-800" x-text="ans.question_text"></p>
                                    <span class="text-[10px] text-gray-400 mt-0.5 block" x-text="ans.category_name"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs"
                                      :class="{
                                          'bg-emerald-100 text-emerald-800': ans.score >= 4,
                                          'bg-amber-100 text-amber-800': ans.score === 3,
                                          'bg-red-100 text-red-800': ans.score <= 2
                                      }"
                                      x-text="ans.score"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50/70 flex items-center justify-between shrink-0">
            <template x-if="selectedResponse">
                <form :action="'/admin/responses/' + selectedResponse.id" method="POST"
                      data-title="Hapus Respon Survei?"
                      data-confirm="Apakah Anda yakin ingin menghapus data respon survei ini secara permanen?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition cursor-pointer">
                        Hapus Respon Ini
                    </button>
                </form>
            </template>
            <x-button type="button" @click="closeDetail()" variant="secondary" size="sm">
                Tutup
            </x-button>
        </div>

    </div>
</div>
