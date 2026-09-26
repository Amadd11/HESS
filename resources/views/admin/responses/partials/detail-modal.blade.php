<!-- Response Detail Modal -->
<div x-show="detailModalOpen" x-cloak
     @keydown.escape.window="closeDetail()"
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
                    <p class="text-xs text-gray-500 mt-0.5" x-text="selectedResponse ? ('Terkirim pada: ' + (selectedResponse.completed_at ? new Date(selectedResponse.completed_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' }) : (selectedResponse.created_at ? new Date(selectedResponse.created_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' }) : '-'))) : 'Memuat data...'"></p>
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
                    <span>Profil & Ringkasan</span>
                </button>

                <button type="button" @click="activeTab = 'hospital'"
                        :class="activeTab === 'hospital' ? 'bg-blue-700 text-white shadow-xs' : 'text-gray-600 hover:bg-gray-100'"
                        class="px-3.5 py-1.5 rounded-xl transition cursor-pointer flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    <span>Indikator Survei Kepuasan Pegawai (<span x-text="hospitalAnswers.length"></span>)</span>
                </button>
            </div>

            <!-- Tab 1: Ringkasan & Kualitatif -->
            <div x-show="activeTab === 'summary'" class="space-y-5">
                <!-- Demographic & Overall Cards (3x3 Grid) -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200">
                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Direktorat</span>
                        <span class="font-bold text-gray-900 text-xs mt-1 block" x-text="selectedResponse && selectedResponse.directorate ? selectedResponse.directorate : '-'"></span>
                    </div>
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
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200">
                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Usia</span>
                        <span class="font-bold text-gray-900 text-xs mt-1 block" x-text="selectedResponse && selectedResponse.age ? selectedResponse.age : '-'"></span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200">
                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Jenis Kelamin</span>
                        <span class="font-bold text-gray-900 text-xs mt-1 block" x-text="selectedResponse && selectedResponse.gender ? selectedResponse.gender : '-'"></span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200">
                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Pendidikan</span>
                        <span class="font-bold text-gray-900 text-xs mt-1 block" x-text="selectedResponse && selectedResponse.education ? selectedResponse.education : '-'"></span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200">
                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Jumlah Pendapatan</span>
                        <span class="font-bold text-gray-900 text-xs mt-1 block" x-text="selectedResponse && selectedResponse.income ? selectedResponse.income : '-'"></span>
                    </div>
                </div>

                <!-- Scores Breakdown -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="p-3.5 rounded-xl bg-primary-50/50 border border-primary-100">
                        <span class="text-[10px] uppercase font-bold text-primary-600 tracking-wider block">Indeks Kepuasan Pegawai</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-2xl font-black text-primary-900" x-text="selectedResponse ? Number(selectedResponse.general_score).toFixed(1) + '%' : '0%'"></span>
                        </div>
                        <span class="text-[10px] text-primary-500 font-semibold mt-1 block">Skala 0–100%</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-blue-50/50 border border-blue-100">
                        <span class="text-[10px] uppercase font-bold text-blue-600 tracking-wider block">Rata-rata Skor</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-2xl font-black text-blue-900" x-text="selectedResponse ? (((selectedResponse.general_score / 100) * 4).toFixed(2)) : '0.00'"></span>
                            <span class="text-xs text-blue-600 font-bold">/ 4.0</span>
                        </div>
                        <span class="text-[10px] text-blue-500 font-semibold mt-1 block">Skala 1–4 Forced Choice</span>
                    </div>
                    <div class="p-3.5 rounded-xl border flex flex-col justify-between" :class="satisfactionPredicate.class">
                        <span class="text-[10px] uppercase font-bold tracking-wider block opacity-80">Predikat Kepuasan</span>
                        <div class="mt-1">
                            <span class="text-xs sm:text-sm font-black block leading-snug" x-text="satisfactionPredicate.label"></span>
                        </div>
                        <div class="text-[10px] font-semibold mt-1 flex items-center justify-between opacity-80">
                            <span>Status:</span>
                            <span class="font-bold font-mono" x-text="selectedResponse ? Number(selectedResponse.general_score).toFixed(1) + '%' : '-'"></span>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-emerald-50/50 border border-emerald-100">
                        <span class="text-[10px] uppercase font-bold text-emerald-600 tracking-wider block">eNPS Rekomendasi</span>
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
                </div>

            </div>
            <!-- Tab 3: Butir Indikator Survei Kepuasan Pegawai -->
            <div x-show="activeTab === 'hospital'" class="space-y-4">
                <div class="p-3.5 bg-blue-50/70 rounded-2xl border border-blue-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span class="font-extrabold text-blue-900">Rincian Jawaban per Indikator Survei Kepuasan</span>
                    </div>
                    <div class="flex items-center gap-2 text-blue-700 font-semibold text-[11px]">
                        <span x-text="indicatorGroups.length + ' Indikator'"></span>
                        <span>•</span>
                        <span x-text="hospitalAnswers.length + ' Butir Pertanyaan (Skala 1 - 4)'"></span>
                    </div>
                </div>

                <!-- Indicator Group Cards -->
                <div class="space-y-3.5">
                    <template x-for="group in indicatorGroups" :key="group.name">
                        <div class="border border-gray-200/90 rounded-2xl bg-white shadow-2xs overflow-hidden transition hover:border-gray-300">
                            <!-- Group Header -->
                            <div class="p-3.5 sm:px-4 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-6 h-6 rounded-lg bg-primary-100 text-primary-800 text-xs font-black flex items-center justify-center shrink-0" x-text="group.index"></span>
                                    <div class="min-w-0">
                                        <h4 class="text-xs sm:text-sm font-bold text-gray-900 truncate" x-text="group.name"></h4>
                                        <span class="text-[10px] text-gray-400 font-medium" x-text="group.questions.length + ' butir penilaian'"></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-mono font-bold border"
                                          :class="group.badgeClass"
                                          x-text="group.pct + '% • ' + group.predicate"></span>
                                </div>
                            </div>

                            <!-- Questions List -->
                            <div class="divide-y divide-gray-100">
                                <template x-for="ans in group.questions" :key="ans.id">
                                    <div class="p-3 sm:px-4 flex items-start justify-between gap-3 hover:bg-gray-50/50 transition">
                                        <div class="flex items-start gap-2.5 min-w-0">
                                            <span class="font-mono text-[11px] font-bold px-1.5 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200 shrink-0" x-text="ans.question_code"></span>
                                            <p class="text-xs font-medium text-gray-800 leading-relaxed" x-text="ans.question_text"></p>
                                        </div>
                                        <div class="shrink-0">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold inline-flex items-center gap-1"
                                                  :class="{
                                                      'bg-emerald-100 text-emerald-800 border border-emerald-200': Number(ans.score) === 4,
                                                      'bg-sky-100 text-sky-800 border border-sky-200': Number(ans.score) === 3,
                                                      'bg-amber-100 text-amber-800 border border-amber-200': Number(ans.score) === 2,
                                                      'bg-red-100 text-red-800 border border-red-200': Number(ans.score) <= 1
                                                  }">
                                                <span class="font-mono font-black" x-text="ans.score"></span>
                                                <span class="hidden sm:inline text-[11px]" x-text="Number(ans.score) === 4 ? '• Sangat Setuju' : (Number(ans.score) === 3 ? '• Setuju' : (Number(ans.score) === 2 ? '• Tidak Setuju' : '• Sangat Tidak Setuju'))"></span>
                                            </span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Indicator Qualitative Feedback (Reason & Suggestion) -->
                            <template x-if="group.reason || group.suggestion">
                                <div class="p-3 sm:px-4 bg-gray-50/60 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                    <div class="p-2.5 rounded-xl bg-white border border-gray-200/80 space-y-1">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block">Alasan Penilaian:</span>
                                        <p class="text-[11px] text-gray-800 italic leading-relaxed" x-text="group.reason || '-'"></p>
                                    </div>
                                    <div class="p-2.5 rounded-xl bg-white border border-gray-200/80 space-y-1">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block">Saran Perbaikan:</span>
                                        <p class="text-[11px] text-gray-800 italic leading-relaxed" x-text="group.suggestion || '-'"></p>
                                    </div>
                                </div>
                            </template>
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
