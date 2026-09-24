{{-- Feedback Explorer Table --}}
<div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-5" id="feedback-explorer">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-gray-100 pb-4">
        <div>
            <div class="flex items-center gap-2">
                <h3 class="text-sm md:text-base font-black text-gray-900">Feedback Explorer</h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-primary-50 text-primary-700 border border-primary-100">
                    {{ number_format($feedbackPaginator->total()) }} Masukan
                </span>
            </div>
            <p class="text-xs text-gray-400 mt-0.5">Eksplorasi alasan penilaian dan saran perbaikan per indikator kualitatif.</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5">
            <!-- Sentiment Quick Filter Tabs -->
            <div class="flex items-center p-1 bg-gray-100/80 rounded-2xl border border-gray-200/70 text-xs font-bold shrink-0">
                <a href="{{ route('admin.sentiment.index', array_merge(request()->except(['sentiment', 'page']), ['sentiment' => 'all'])) }}"
                   class="px-2.5 py-1 rounded-xl transition {{ (!request('sentiment') || request('sentiment') === 'all') ? 'bg-white text-gray-900 shadow-2xs' : 'text-gray-500 hover:text-gray-900' }}">
                    Semua
                </a>
                <a href="{{ route('admin.sentiment.index', array_merge(request()->except(['sentiment', 'page']), ['sentiment' => 'positive'])) }}"
                   class="px-2.5 py-1 rounded-xl transition {{ request('sentiment') === 'positive' ? 'bg-emerald-600 text-white shadow-2xs' : 'text-gray-500 hover:text-emerald-700' }}">
                    Positif
                </a>
                <a href="{{ route('admin.sentiment.index', array_merge(request()->except(['sentiment', 'page']), ['sentiment' => 'neutral'])) }}"
                   class="px-2.5 py-1 rounded-xl transition {{ request('sentiment') === 'neutral' ? 'bg-amber-500 text-white shadow-2xs' : 'text-gray-500 hover:text-amber-700' }}">
                    Netral
                </a>
                <a href="{{ route('admin.sentiment.index', array_merge(request()->except(['sentiment', 'page']), ['sentiment' => 'negative'])) }}"
                   class="px-2.5 py-1 rounded-xl transition {{ request('sentiment') === 'negative' ? 'bg-rose-600 text-white shadow-2xs' : 'text-gray-500 hover:text-rose-700' }}">
                    Negatif
                </a>
            </div>

            <!-- Quick Search Bar -->
            <form method="GET" action="{{ route('admin.sentiment.index') }}" class="flex items-center gap-2 max-w-xs w-full">
                @foreach(request()->except(['search', 'page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <div class="relative w-full">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           id="feedbackSearchInput"
                           placeholder="Cari kata kunci, unit, atau profesi..."
                           class="w-full h-9 pl-9 pr-3 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 font-medium">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="px-3.5 h-9 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold transition shrink-0 cursor-pointer">
                    Cari
                </button>
                @if(request('search'))
                <a href="{{ route('admin.sentiment.index', request()->except(['search', 'page'])) }}"
                   class="px-2.5 h-9 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition flex items-center shrink-0 cursor-pointer"
                   title="Hapus Pencarian">
                    ✕
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto border border-gray-100 rounded-2xl">
        <table class="w-full text-left text-xs text-gray-600 border-collapse">
            <thead class="bg-gray-50/90 text-[11px] font-extrabold uppercase tracking-wider text-gray-500 border-b border-gray-100 select-none">
                <tr>
                    <th scope="col" class="py-3 px-4 w-28">Sentimen</th>
                    <th scope="col" class="py-3 px-4 min-w-[380px]">Umpan Balik & Saran per Indikator</th>
                    <th scope="col" class="py-3 px-4 whitespace-nowrap">Satuan Kerja</th>
                    <th scope="col" class="py-3 px-4 whitespace-nowrap">Kelompok Profesi</th>
                    <th scope="col" class="py-3 px-4 whitespace-nowrap">Masa Kerja</th>
                    <th scope="col" class="py-3 px-4 whitespace-nowrap">Tanggal Survei</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($feedbackPaginator as $row)
                    @php
                        $sent = $row['sentiment'] ?? 'neutral';
                        $likeText = $row['like_text'] ?? null;
                        $improveText = $row['improve_text'] ?? null;

                        // Ekstraksi data umpan balik terstruktur per unsur/indikator
                        $feedbackItems = [];
                        $feedbackData = $row['feedback_data'] ?? null;
                        if (is_string($feedbackData)) {
                            $feedbackData = json_decode($feedbackData, true);
                        }
                        if (! empty($feedbackData) && is_array($feedbackData)) {
                            foreach ($feedbackData as $aspect => $entry) {
                                $r = trim($entry['reason'] ?? '');
                                $s = trim($entry['suggestion'] ?? '');
                                if ($r !== '' || $s !== '') {
                                    $feedbackItems[] = [
                                        'aspect' => $aspect,
                                        'reason' => $r,
                                        'suggestion' => $s,
                                    ];
                                }
                            }
                        }

                        // Fallback parsing jika feedback_data belum ada di respon lama
                        if (empty($feedbackItems) && (! empty($likeText) || ! empty($improveText))) {
                            $rawAspects = [];
                            if (! empty($likeText)) {
                                preg_match_all('/\[(.*?)\]\s*(.*?)(?=\[|$)/s', $likeText, $mLike, PREG_SET_ORDER);
                                foreach ($mLike as $m) {
                                    $aName = trim($m[1]);
                                    $rawAspects[$aName]['reason'] = trim($m[2]);
                                }
                            }
                            if (! empty($improveText)) {
                                preg_match_all('/\[(.*?)\]\s*(.*?)(?=\[|$)/s', $improveText, $mImp, PREG_SET_ORDER);
                                foreach ($mImp as $m) {
                                    $aName = trim($m[1]);
                                    $rawAspects[$aName]['suggestion'] = trim($m[2]);
                                }
                            }
                            foreach ($rawAspects as $aName => $aData) {
                                $feedbackItems[] = [
                                    'aspect' => $aName,
                                    'reason' => $aData['reason'] ?? '',
                                    'suggestion' => $aData['suggestion'] ?? '',
                                ];
                            }
                        }
                    @endphp
                    <tr class="hover:bg-gray-50/75 transition">
                        <!-- Sentimen Badge -->
                        <td class="py-4 px-4 whitespace-nowrap align-top">
                            @if($sent === 'positive')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Positif</span>
                                </span>
                            @elseif($sent === 'negative')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    <span>Negatif</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span>Netral</span>
                                </span>
                            @endif
                        </td>

                        <!-- Feedback & Saran Per Indikator -->
                        <td class="py-4 px-4 align-top">
                            @if(! empty($feedbackItems))
                                <div x-data="{ activeIndex: 0, showAll: false }" class="space-y-2.5 max-w-2xl">
                                    {{-- Indicator Chips (jika lebih dari 1 butir masukan) --}}
                                    @if(count($feedbackItems) > 1)
                                        <div class="flex flex-wrap items-center gap-1.5 pb-1">
                                            @foreach($feedbackItems as $idx => $item)
                                                <button type="button"
                                                        @click="activeIndex = {{ $idx }}; showAll = false"
                                                        :class="(!showAll && activeIndex === {{ $idx }}) ? 'bg-indigo-600 text-white shadow-2xs font-extrabold ring-2 ring-indigo-200' : 'bg-gray-100 hover:bg-gray-200/80 text-gray-700 font-semibold'"
                                                        class="px-2.5 py-1 rounded-lg text-[10px] transition cursor-pointer flex items-center gap-1">
                                                    <span>{{ $item['aspect'] }}</span>
                                                </button>
                                            @endforeach

                                            <button type="button"
                                                    @click="showAll = !showAll"
                                                    :class="showAll ? 'bg-primary-700 text-white shadow-2xs' : 'bg-primary-50 text-primary-700 hover:bg-primary-100 border border-primary-200'"
                                                    class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold transition cursor-pointer shrink-0 ml-auto">
                                                <span x-show="!showAll">▼ Tampilkan Semua ({{ count($feedbackItems) }})</span>
                                                <span x-show="showAll">▲ Ringkas Tampilan</span>
                                            </button>
                                        </div>
                                    @endif

                                    {{-- Tampilan Tunggal (Aktif Terpilih) --}}
                                    <div x-show="!showAll" class="space-y-2">
                                        @foreach($feedbackItems as $idx => $item)
                                            <div x-show="activeIndex === {{ $idx }}"
                                                 class="p-3.5 rounded-2xl bg-white border border-gray-200/90 shadow-2xs space-y-2.5">
                                                <div class="flex items-center justify-between">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                        {{ $item['aspect'] }}
                                                    </span>
                                                    @if(count($feedbackItems) > 1)
                                                        <span class="text-[10px] font-semibold text-gray-400">
                                                            Indikator {{ $idx + 1 }} dari {{ count($feedbackItems) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if(! empty($item['reason']))
                                                    <div class="text-xs flex items-start gap-2.5">
                                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80 shrink-0 mt-0.5">
                                                            Alasan
                                                        </span>
                                                        <span class="text-gray-700 leading-relaxed font-normal">{{ $item['reason'] }}</span>
                                                    </div>
                                                @endif
                                                @if(! empty($item['suggestion']))
                                                    <div class="text-xs flex items-start gap-2.5">
                                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-800 border border-rose-200/80 shrink-0 mt-0.5">
                                                            Saran
                                                        </span>
                                                        <span class="text-gray-700 leading-relaxed font-normal">{{ $item['suggestion'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Tampilan Seluruh Indikator Sekaligus (Show All) --}}
                                    <div x-show="showAll" x-cloak class="space-y-2.5">
                                        @foreach($feedbackItems as $idx => $item)
                                            <div class="p-3.5 rounded-2xl bg-white border border-gray-200/90 shadow-2xs space-y-2.5">
                                                <div class="flex items-center justify-between">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                        {{ $item['aspect'] }}
                                                    </span>
                                                    <span class="text-[10px] font-semibold text-gray-400">
                                                        #{{ $idx + 1 }}
                                                    </span>
                                                </div>
                                                @if(! empty($item['reason']))
                                                    <div class="text-xs flex items-start gap-2.5">
                                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80 shrink-0 mt-0.5">
                                                            Alasan
                                                        </span>
                                                        <span class="text-gray-700 leading-relaxed font-normal">{{ $item['reason'] }}</span>
                                                    </div>
                                                @endif
                                                @if(! empty($item['suggestion']))
                                                    <div class="text-xs flex items-start gap-2.5">
                                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-800 border border-rose-200/80 shrink-0 mt-0.5">
                                                            Saran
                                                        </span>
                                                        <span class="text-gray-700 leading-relaxed font-normal">{{ $item['suggestion'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif($likeText || $improveText)
                                <div class="space-y-2 max-w-2xl">
                                    @if($likeText)
                                        <div class="bg-emerald-50/50 border border-emerald-200/80 rounded-2xl p-3 text-xs text-gray-800">
                                            <span class="font-extrabold text-[10px] uppercase tracking-wider text-emerald-800 block mb-1">Alasan Penilaian:</span>
                                            <p class="leading-relaxed whitespace-pre-line">{{ $likeText }}</p>
                                        </div>
                                    @endif
                                    @if($improveText)
                                        <div class="bg-rose-50/50 border border-rose-200/80 rounded-2xl p-3 text-xs text-gray-800">
                                            <span class="font-extrabold text-[10px] uppercase tracking-wider text-rose-800 block mb-1">Saran Perbaikan:</span>
                                            <p class="leading-relaxed whitespace-pre-line">{{ $improveText }}</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 italic text-xs">- Tidak ada catatan terbuka -</span>
                            @endif
                        </td>

                        <!-- Satuan Kerja -->
                        <td class="py-4 px-4 whitespace-nowrap align-top">
                            <div class="font-bold text-gray-900 text-xs">{{ $row['unit'] ?? '-' }}</div>
                            @if(! empty($row['directorate']))
                                <div class="text-[10px] text-gray-400 mt-0.5 max-w-[160px] truncate" title="{{ $row['directorate'] }}">
                                    {{ $row['directorate'] }}
                                </div>
                            @endif
                        </td>

                        <!-- Profesi -->
                        <td class="py-4 px-4 whitespace-nowrap align-top">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 text-[11px] font-bold border border-gray-200/60">
                                {{ $row['profession'] ?? '-' }}
                            </span>
                        </td>

                        <!-- Masa Kerja -->
                        <td class="py-4 px-4 text-gray-600 whitespace-nowrap align-top text-xs font-medium">
                            {{ $row['tenure'] ?? '-' }}
                        </td>

                        <!-- Tanggal Survei -->
                        <td class="py-4 px-4 text-gray-400 whitespace-nowrap align-top text-[11px] font-medium">
                            {{ $row['formatted_date'] ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-gray-400 italic text-xs">
                            Tidak ada data masukan responden yang sesuai dengan kriteria pencarian atau filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($feedbackPaginator->hasPages())
        <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-[11px] text-gray-500 font-medium">
                Menampilkan <strong class="text-gray-800">{{ $feedbackPaginator->firstItem() }}</strong> – <strong class="text-gray-800">{{ $feedbackPaginator->lastItem() }}</strong> dari total <strong class="text-gray-800">{{ $feedbackPaginator->total() }}</strong> masukan
            </div>
            <div>
                {{ $feedbackPaginator->links() }}
            </div>
        </div>
    @endif
</div>
