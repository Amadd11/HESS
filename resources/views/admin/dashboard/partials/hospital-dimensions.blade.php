<div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs space-y-4" id="hospital-dimensions">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-xs font-bold text-gray-900">Dimensi Lingkungan & Budaya Kerja Rumah Sakit</h3>
                <x-badge color="blue" size="xs">
                    {{ $hospitalCategoryScores->count() }} Dimensi Operasional RS
                </x-badge>
            </div>
            <p class="text-[11px] text-gray-400 mt-1">Analisis skor rata-rata kepuasan pegawai per dimensi kerja operasional rumah sakit.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-700 hover:text-primary-800 transition">
            <span>Kelola Master Kategori</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse($hospitalCategoryScores as $cat)
            <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-white hover:border-primary-200 hover:shadow-md transition-all duration-200 space-y-3 flex flex-col justify-between group">
                <div class="space-y-2">
                    <div class="flex items-start justify-between gap-2">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 text-primary-700 font-black text-xs group-hover:bg-primary-600 group-hover:text-white transition">
                            {{ $cat->code }}
                        </span>
                        <x-badge :color="($cat->percentage_score ?? 0) >= 75 ? 'emerald' : (($cat->percentage_score ?? 0) >= 65 ? 'primary' : 'amber')">
                            {{ ($cat->percentage_score ?? 0) >= 75 ? 'Optimal' : (($cat->percentage_score ?? 0) >= 65 ? 'Baik' : 'Perhatian') }}
                        </x-badge>
                    </div>
                    <h4 class="font-bold text-xs text-gray-900 group-hover:text-primary-700 transition line-clamp-2 min-h-[32px]">
                        {{ $cat->name }}
                    </h4>
                </div>

                <div class="space-y-2 pt-2 border-t border-gray-100">
                    <div class="flex items-baseline justify-between">
                        <span class="text-2xl font-black text-gray-900">{{ $cat->percentage_score ?? 0 }}%</span>
                        <span class="text-[11px] font-bold text-gray-400">{{ $cat->avg_raw_score ?? 0 }} / 5.0</span>
                    </div>
                    <div class="h-2 w-full bg-gray-200/80 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 {{ ($cat->percentage_score ?? 0) >= 75 ? 'bg-emerald-500' : (($cat->percentage_score ?? 0) >= 65 ? 'bg-primary-600' : 'bg-amber-500') }}"
                             style="width: {{ min($cat->percentage_score ?? 0, 100) }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-[10px] text-gray-400 pt-1">
                        <span>{{ $cat->questions_count }} Butir Soal</span>
                        <a href="{{ route('admin.questions.index', ['category_id' => $cat->id]) }}"
                           class="text-primary-600 hover:text-primary-800 font-bold flex items-center gap-0.5">
                            <span>Detail Soal</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-8 text-center text-gray-400 text-xs">
                Belum ada data kategori rumah sakit yang terdaftar.
            </div>
        @endforelse
    </div>
</div>
