<div x-data="dashboardFeedback()" class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs space-y-4">

    <!-- Header & Search Toolbar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100 pb-4">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-primary-50 text-primary-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <h3 class="text-xs font-bold text-gray-900">Masukan Kualitatif Pegawai (Anonim)</h3>
                <x-badge color="primary" size="xs">
                    {{ $totalFeedbacksCount }} Aspirasi Tercatat
                </x-badge>
            </div>
            <p class="text-[11px] text-gray-400 mt-1">Aspirasi jujur pegawai mengenai hal yang disukai dan hal yang paling perlu diperbaiki.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
            <!-- Tab Filter -->
            <div class="inline-flex p-0.5 rounded-xl bg-gray-100 text-xs font-bold shrink-0">
                <button type="button" @click="tab = 'all'"
                        :class="tab === 'all' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                        class="px-2.5 py-1.5 rounded-lg transition cursor-pointer">
                    Semua
                </button>
                <button type="button" @click="tab = 'like'"
                        :class="tab === 'like' ? 'bg-emerald-600 text-white shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                        class="px-2.5 py-1.5 rounded-lg transition cursor-pointer">
                    Disukai
                </button>
                <button type="button" @click="tab = 'improve'"
                        :class="tab === 'improve' ? 'bg-rose-600 text-white shadow-xs' : 'text-gray-500 hover:text-gray-900'"
                        class="px-2.5 py-1.5 rounded-lg transition cursor-pointer">
                    Perlu Diperbaiki
                </button>
            </div>

            <!-- Search Input -->
            <div class="relative min-w-[220px]">
                <input type="text" x-model="search" placeholder="Cari kata kunci (cth: shift, insentif)..."
                       class="w-full h-8 pl-8 pr-3 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Feedbacks List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($recentFeedbacks as $fb)
            @php
                $hasLike = !empty(trim($fb->like_text ?? ''));
                $hasImprove = !empty(trim($fb->improve_text ?? ''));
                $jsLike = addslashes($fb->like_text ?? '');
                $jsImprove = addslashes($fb->improve_text ?? '');
                $jsProf = addslashes($fb->profession ?? '');
                $jsUnit = addslashes($fb->unit ?? '');
            @endphp
            <div x-show="matches({{ $hasLike ? 'true' : 'false' }}, {{ $hasImprove ? 'true' : 'false' }}, '{{ $jsLike }}', '{{ $jsImprove }}', '{{ $jsProf }}', '{{ $jsUnit }}')"
                 class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-white hover:border-primary-200 hover:shadow-xs transition space-y-3 text-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between text-[11px] text-gray-500 mb-2">
                        <x-badge color="primary" size="xs">
                            {{ $fb->profession }} &bull; Unit {{ $fb->unit }}
                        </x-badge>
                        <span class="text-[10px] text-gray-400">{{ $fb->completed_at ? $fb->completed_at->diffForHumans() : '-' }}</span>
                    </div>

                    @if($hasLike)
                        <div class="space-y-0.5 mt-2" x-show="tab === 'all' || tab === 'like'">
                            <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                <span>Hal yang Disukai:</span>
                            </span>
                            <p class="text-gray-700 leading-relaxed font-medium pl-4 border-l-2 border-emerald-400 italic">
                                "{{ $fb->like_text }}"
                            </p>
                        </div>
                    @endif

                    @if($hasImprove)
                        <div class="space-y-0.5 mt-2.5" x-show="tab === 'all' || tab === 'improve'">
                            <span class="text-[10px] font-extrabold text-rose-600 uppercase tracking-wider flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>Perlu Diperbaiki:</span>
                            </span>
                            <p class="text-gray-700 leading-relaxed font-medium pl-4 border-l-2 border-rose-400 italic">
                                "{{ $fb->improve_text }}"
                            </p>
                        </div>
                    @endif
                </div>

                <div class="pt-2 border-t border-gray-100/70 text-[10px] text-gray-400 flex items-center justify-between">
                    <span>Status: {{ $fb->status ?? 'Pegawai' }}</span>
                    <span>Masa Kerja: {{ $fb->tenure ?? '-' }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-2 py-10 text-center text-gray-400 text-xs">
                Belum ada masukan kualitatif yang terekam pada periode ini.
            </div>
        @endforelse
    </div>

</div>
