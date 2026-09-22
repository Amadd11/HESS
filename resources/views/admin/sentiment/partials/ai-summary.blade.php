{{-- AI Insight Summary Card --}}
<div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-slate-950 rounded-3xl p-6 md:p-8 text-white shadow-xs border border-indigo-800/80 space-y-6 relative overflow-hidden">
    <div class="absolute -right-16 -top-16 w-64 h-64 bg-primary-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Header Insight -->
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-5">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-primary-500 to-indigo-400 text-white flex items-center justify-center shadow-xs shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-primary-500/20 text-primary-300 text-[10px] font-extrabold uppercase tracking-wider mb-0.5">
                    Executive Intelligence
                </div>
                <h3 class="text-base md:text-lg font-black text-white">AI & Executive Insight Summary</h3>
            </div>
        </div>
        <div class="text-xs text-slate-300 font-serif italic self-start sm:self-auto">
            "Karyawan Sehat, Rumah Sakit Hebat" — HESS {{ date('Y') }}
        </div>
    </div>

    <!-- 4 Sub-Sections Grid -->
    <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <!-- 1. Topik Positif -->
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-xs space-y-2.5">
            <div class="flex items-center gap-2 text-emerald-400 font-extrabold text-xs uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Topik Apresiasi Positif</span>
            </div>
            <div class="flex flex-wrap gap-1.5">
                @foreach($insights['positive_topics'] as $topic)
                    <span class="px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-semibold">
                        {{ $topic }}
                    </span>
                @endforeach
            </div>
            <p class="text-[11px] text-slate-300 leading-relaxed pt-1">
                Aspek pelayanan dan kerjasama tim merupakan keunggulan kultural yang konsisten diapresiasi oleh staf.
            </p>
        </div>

        <!-- 2. Topik Negatif / Evaluasi -->
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-xs space-y-2.5">
            <div class="flex items-center gap-2 text-rose-400 font-extrabold text-xs uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                <span>Isu Kritis & Perbaikan</span>
            </div>
            <div class="flex flex-wrap gap-1.5">
                @foreach($insights['negative_topics'] as $topic)
                    <span class="px-2.5 py-1 rounded-lg bg-rose-500/20 text-rose-300 border border-rose-500/30 text-xs font-semibold">
                        {{ $topic }}
                    </span>
                @endforeach
            </div>
            <p class="text-[11px] text-slate-300 leading-relaxed pt-1">
                Fokus keluhan terpusat pada sarana penunjang operasional, distribusi beban giliran tugas, dan kepastian fasilitas.
            </p>
        </div>

        <!-- 3. Rekomendasi Aksi Manajemen -->
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-xs space-y-2.5 md:col-span-2 lg:col-span-1">
            <div class="flex items-center gap-2 text-amber-300 font-extrabold text-xs uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-amber-300"></span>
                <span>Rekomendasi Tindak Lanjut</span>
            </div>
            <ul class="space-y-1.5 text-xs text-slate-200">
                @foreach($insights['recommendations'] as $rec)
                    <li class="flex items-start gap-2">
                        <span class="text-amber-400 font-bold shrink-0">▸</span>
                        <span class="leading-relaxed">{{ $rec }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Kesimpulan Naratif -->
    <div class="relative z-10 bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-xs flex items-start gap-3.5">
        <div class="w-9 h-9 rounded-xl bg-amber-400/20 text-amber-300 border border-amber-400/30 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
        </div>
        <div class="space-y-1">
            <span class="text-[11px] font-extrabold uppercase tracking-wider text-amber-300">Kesimpulan Strategis</span>
            <p class="text-xs md:text-sm text-slate-200 leading-relaxed">
                {{ $insights['conclusion'] }}
            </p>
        </div>
    </div>
</div>
