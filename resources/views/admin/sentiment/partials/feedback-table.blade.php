{{-- Feedback Explorer Table --}}
<div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
        <div>
            <h3 class="text-sm md:text-base font-black text-gray-900">Feedback Explorer</h3>
            <p class="text-xs text-gray-400">Eksplorasi seluruh masukan kualitatif teks dengan klasifikasi sentimen individual.</p>
        </div>

        <!-- Quick Search Bar -->
        <form method="GET" action="{{ route('admin.sentiment.index') }}" class="flex items-center gap-2 max-w-sm w-full">
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
            <button type="submit" class="px-3.5 h-9 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold transition shrink-0">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('admin.sentiment.index', request()->except(['search', 'page'])) }}"
               class="px-2.5 h-9 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition flex items-center shrink-0"
               title="Hapus Pencarian">
                ✕
            </a>
            @endif
        </form>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-gray-600">
            <thead class="bg-gray-50 text-[11px] font-extrabold uppercase tracking-wider text-gray-500 border-y border-gray-100">
                <tr>
                    <th scope="col" class="py-3 px-4">Sentimen</th>
                    <th scope="col" class="py-3 px-4 min-w-[320px]">Umpan Balik / Aspirasi Responden</th>
                    <th scope="col" class="py-3 px-4">Unit Kerja</th>
                    <th scope="col" class="py-3 px-4">Profesi</th>
                    <th scope="col" class="py-3 px-4">Masa Kerja</th>
                    <th scope="col" class="py-3 px-4">Tanggal Survei</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($feedbackPaginator as $row)
                    @php
                        $sent = $row['sentiment'] ?? 'neutral';
                        $score = $row['sentiment_score'] ?? 0;
                        $likeText = $row['like_text'] ?? null;
                        $improveText = $row['improve_text'] ?? null;
                    @endphp
                    <tr class="hover:bg-gray-50/75 transition">
                        <!-- Sentimen Badge -->
                        <td class="py-3.5 px-4 whitespace-nowrap align-top">
                            @if($sent === 'positive')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Positif</span>
                                    <span class="text-[9px] text-emerald-600 font-mono">({{ $score > 0 ? '+'.$score : $score }})</span>
                                </span>
                            @elseif($sent === 'negative')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    <span>Negatif</span>
                                    <span class="text-[9px] text-rose-600 font-mono">({{ $score }})</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span>Netral</span>
                                    <span class="text-[9px] text-amber-600 font-mono">(0.0)</span>
                                </span>
                            @endif
                        </td>

                        <!-- Feedback Teks -->
                        <td class="py-3.5 px-4 space-y-1.5 align-top">
                            @if($likeText)
                                <div class="bg-[#f2fbf5]/70 border border-emerald-100 rounded-xl p-2.5 text-xs text-gray-800">
                                    <span class="font-extrabold text-[10px] uppercase tracking-wider text-emerald-800 block mb-0.5">Disukai:</span>
                                    {{ $likeText }}
                                </div>
                            @endif
                            @if($improveText)
                                <div class="bg-[#fef2f2]/70 border border-rose-100 rounded-xl p-2.5 text-xs text-gray-800">
                                    <span class="font-extrabold text-[10px] uppercase tracking-wider text-rose-800 block mb-0.5">Saran Perbaikan:</span>
                                    {{ $improveText }}
                                </div>
                            @endif
                            @if(! $likeText && ! $improveText)
                                <span class="text-gray-400 italic text-xs">- Tidak ada catatan terbuka -</span>
                            @endif
                        </td>

                        <!-- Unit Kerja -->
                        <td class="py-3.5 px-4 font-semibold text-gray-700 whitespace-nowrap align-top">
                            {{ $row['unit'] ?? '-' }}
                        </td>

                        <!-- Profesi -->
                        <td class="py-3.5 px-4 text-gray-600 whitespace-nowrap align-top">
                            {{ $row['profession'] ?? '-' }}
                        </td>

                        <!-- Masa Kerja -->
                        <td class="py-3.5 px-4 text-gray-500 whitespace-nowrap align-top">
                            {{ $row['tenure'] ?? '-' }}
                        </td>

                        <!-- Tanggal Survei -->
                        <td class="py-3.5 px-4 text-gray-400 whitespace-nowrap align-top text-[11px]">
                            {{ $row['formatted_date'] ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400 italic text-xs">
                            Tidak ada data masukan responden yang sesuai dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($feedbackPaginator->hasPages())
        <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
            <div class="text-[11px] text-gray-500">
                Menampilkan {{ $feedbackPaginator->firstItem() }} – {{ $feedbackPaginator->lastItem() }} dari total {{ $feedbackPaginator->total() }} masukan
            </div>
            <div>
                {{ $feedbackPaginator->links() }}
            </div>
        </div>
    @endif
</div>
