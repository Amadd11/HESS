{{-- Responses Table Card & Pagination --}}
<x-table-container class="border-gray-200/90 shadow-xs">
    <table class="w-full text-left border-collapse text-xs">
        <thead>
            <tr class="sticky top-0 z-10 bg-gray-50/95 backdrop-blur-xs border-b border-gray-200 text-gray-500 font-extrabold text-[11px] uppercase tracking-wider select-none shadow-2xs">
                <th class="py-3.5 px-4 w-28 whitespace-nowrap">ID & Waktu</th>
                <th class="py-3.5 px-4 min-w-[220px]">Periode Survei</th>
                <th class="py-3.5 px-4 min-w-[240px]">Demografi Responden</th>
                <th class="py-3.5 px-4 min-w-[180px]">Skor Indeks</th>
                <th class="py-3.5 px-4 text-center w-36 whitespace-nowrap">NPS & Kategori</th>
                <th class="py-3.5 px-4 text-right w-24 whitespace-nowrap">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($responses as $resp)
            <tr class="hover:bg-primary-50/20 transition-colors bg-white">
                <!-- ID & Tanggal -->
                <td class="py-3.5 px-4 align-middle">
                    <span class="font-mono text-xs font-bold px-2 py-0.5 rounded-lg bg-gray-100 text-gray-700 border border-gray-200/70 block w-fit">
                        #{{ $resp->id }}
                    </span>
                    <span class="text-[11px] text-gray-400 block mt-1">
                        {{ $resp->completed_at?->format('d/m/Y H:i') }}
                    </span>
                </td>

                <!-- Periode Survei -->
                <td class="py-3.5 px-4 align-middle">
                    <div class="space-y-1 max-w-[320px]">
                        <span class="font-semibold text-gray-900 block text-xs leading-snug" title="{{ $resp->period?->name }}">
                            {{ $resp->period?->name ?? 'Periode Tidak Diketahui' }}
                        </span>
                        @if($resp->period?->is_active)
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/70 w-fit">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Periode Aktif
                        </span>
                        @endif
                    </div>
                </td>

                <!-- Demografi Responden -->
                <td class="py-3.5 px-4 align-middle">
                    <div class="space-y-1">
                        @if($resp->directorate)
                        <span class="inline-block text-[10px] font-bold text-primary-700 bg-primary-50 px-2 py-0.5 rounded-md border border-primary-200/60 max-w-[220px] truncate" title="{{ $resp->directorate }}">
                            {{ $resp->directorate }}
                        </span>
                        @endif
                        <div class="text-xs flex items-center gap-1.5 flex-wrap">
                            <span class="font-bold text-gray-900">{{ $resp->unit }}</span>
                            <span class="text-gray-300">•</span>
                            <span class="font-medium text-gray-600">{{ $resp->profession }}</span>
                        </div>
                        <div class="text-[11px] text-gray-500 flex items-center gap-1.5 flex-wrap">
                            <span>{{ $resp->status }}</span>
                            <span class="text-gray-300">&mdash;</span>
                            <span>{{ $resp->tenure }}</span>
                            @if($resp->age || $resp->gender)
                            <span class="text-gray-300">•</span>
                            <span class="text-gray-600">{{ implode(', ', array_filter([$resp->gender, $resp->age])) }}</span>
                            @endif
                        </div>
                        @if($resp->education || $resp->income)
                        <div class="text-[10px] text-gray-400 flex items-center gap-1.5 flex-wrap">
                            @if($resp->education)
                            <span>{{ $resp->education }}</span>
                            @endif
                            @if($resp->education && $resp->income)
                            <span class="text-gray-300">•</span>
                            @endif
                            @if($resp->income)
                            <span class="text-gray-500 font-medium">{{ $resp->income }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </td>

                <!-- Skor Indeks Kepuasan Pegawai -->
                <td class="py-3.5 px-4 align-middle">
                    <div class="space-y-1">
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-sm font-black font-mono text-primary-800">{{ number_format($resp->general_score, 1) }}%</span>
                            <span class="text-[10px] text-gray-400 font-medium">({{ number_format(($resp->general_score / 100) * 4, 2) }}/4.0)</span>
                        </div>
                        <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold {{ $resp->general_score >= 81 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : ($resp->general_score >= 61 ? 'bg-primary-50 text-primary-700 border border-primary-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60') }}">
                            {{ $resp->general_score >= 81 ? 'Sangat Setuju' : ($resp->general_score >= 61 ? 'Setuju' : ($resp->general_score >= 41 ? 'Tidak Setuju' : 'Sangat Tidak Setuju')) }}
                        </span>
                    </div>
                </td>

                <!-- NPS & Kategori -->
                <td class="py-3.5 px-4 text-center align-middle">
                    <div class="space-y-1 inline-flex flex-col items-center">
                        <span class="font-black text-sm text-gray-900 font-mono block">
                            {{ $resp->nps_score }} <span class="text-[10px] text-gray-400 font-normal font-sans">/ 10</span>
                        </span>
                        @if($resp->nps_category === 'promoter')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Promoter
                        </span>
                        @elseif($resp->nps_category === 'passive')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                            Passive
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-red-100 text-red-800 border border-red-200">
                            Detractor
                        </span>
                        @endif
                    </div>
                </td>

                <!-- Aksi -->
                <td class="py-3.5 px-4 text-right align-middle">
                    <div class="flex items-center justify-end gap-1">
                        <!-- Detail Button (triggers Alpine modal) -->
                        <button type="button" @click="openDetail({{ $resp->id }})"
                            class="p-2 text-gray-400 hover:text-primary-700 hover:bg-primary-50 rounded-xl transition border border-transparent hover:border-primary-100 cursor-pointer"
                            title="Lihat Detail Jawaban">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>

                        @hasrole('super-admin')
                        <!-- Delete Button (Khusus Super Admin) -->
                        <form action="{{ route('admin.responses.destroy', $resp->id) }}" method="POST" class="inline"
                            data-title="Hapus Respon #{{ $resp->id }}?"
                            data-confirm="Apakah Anda yakin ingin menghapus respon #{{ $resp->id }} ini? Tindakan ini bersifat permanen.">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition border border-transparent hover:border-red-100 cursor-pointer"
                                title="Hapus Respon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        @endhasrole
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-12 px-4 text-center">
                    <div class="max-w-sm mx-auto space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-gray-800">Tidak ada respon survei yang ditemukan</h4>
                            <p class="text-xs text-gray-500">Coba sesuaikan kata kunci pencarian atau bersihkan filter yang aktif.</p>
                        </div>
                        @if($hasActiveFilters)
                        <a href="{{ route('admin.responses.index') }}"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Reset Semua Filter</span>
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Table Pagination Footer -->
    <div class="px-5 py-3.5 border-t border-gray-200/80 bg-gray-50/60 flex flex-col md:flex-row md:items-center md:justify-between gap-3 select-none">
        <!-- Sisi Kiri: Ringkasan Jumlah Data & Opsi Per Page -->
        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
            <div class="flex items-center gap-1.5">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-primary-50 text-primary-700">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </span>
                <span>Menampilkan <strong class="text-gray-900 font-bold">{{ number_format($responses->firstItem() ?? 0) }}</strong> &ndash; <strong class="text-gray-900 font-bold">{{ number_format($responses->lastItem() ?? 0) }}</strong> dari <strong class="text-gray-900 font-bold">{{ number_format($responses->total()) }}</strong> respon</span>
            </div>

            <span class="text-gray-300 hidden sm:inline">&bull;</span>

            <!-- Quick Per Page Selector -->
            <div class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                <span class="text-[11px] font-medium text-gray-400">Tampilkan:</span>
                <div class="inline-flex rounded-lg bg-white border border-gray-200/90 p-0.5 shadow-2xs">
                    @foreach([25, 50, 100] as $opt)
                    <a href="{{ request()->fullUrlWithQuery(['per_page' => $opt, 'page' => 1]) }}"
                        class="px-2 py-0.5 rounded-md text-[11px] font-bold transition-all {{ ($perPage ?? 50) == $opt ? 'bg-primary-50 text-primary-700 font-extrabold shadow-2xs border border-primary-200/80' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                        {{ $opt }}
                    </a>
                    @endforeach
                </div>
                <span class="text-[11px] font-medium text-gray-400">baris</span>
            </div>
        </div>

        <!-- Sisi Kanan: Navigasi Halaman -->
        <div class="flex items-center justify-end shrink-0">
            {{ $responses->onEachSide(1)->links() }}
        </div>
    </div>
</x-table-container>
