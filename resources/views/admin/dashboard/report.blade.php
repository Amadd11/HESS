<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Analitik Kepuasan Pegawai — {{ $selectedPeriod->name ?? 'HESS' }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body {
                background: #ffffff !important;
                color: #0f172a !important;
                font-size: 11pt;
            }

            .no-print {
                display: none !important;
            }

            .print-break-inside-avoid {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .print-shadow-none {
                box-shadow: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 1.2cm 1.2cm 1.5cm 1.2cm;
            }
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 font-sans antialiased min-h-screen">

    <!-- Top Action Bar (Screen Only) -->
    <div class="no-print bg-white border-b border-gray-200 sticky top-0 z-30 px-6 py-3.5 shadow-xs">
        <div class="max-w-4xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard', request()->query()) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </a>
                <span class="text-xs text-gray-400 hidden sm:inline">&bull;</span>
                <span class="text-xs text-gray-500 font-medium hidden sm:inline">Format Siap Cetak A4 / Unduh PDF</span>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.dashboard.export', array_merge(request()->query(), ['from_dashboard' => 1, 'period_id' => $selectedPeriod?->id])) }}"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Unduh Excel</span>
                </a>

                <button type="button" onclick="window.print()"
                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-700 hover:bg-primary-800 text-white font-bold text-xs shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak / Unduh PDF</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Printable Document Container -->
    <main class="max-w-4xl mx-auto my-6 p-8 md:p-10 bg-white rounded-3xl border border-gray-200 shadow-sm print-shadow-none print:m-0 print:p-0 print:border-none">

        <!-- 1. KOP SURAT RESMI RUMAH SAKIT -->
        <header class="border-b-2 border-gray-900 pb-5 mb-6">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white border border-gray-200 p-1 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/logo-icon.png') }}" alt="Logo RS" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-black text-gray-900 tracking-tight">HESS HOSPITAL</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-primary-100 text-primary-800">MRSTC INDONESIA</span>
                        </div>
                        <h1 class="text-lg md:text-xl font-black text-gray-900 mt-0.5 tracking-tight uppercase">
                            Laporan Eksekutif Survei Kepuasan Pegawai (IKP & NPS)
                        </h1>
                        <p class="text-xs text-gray-500 font-medium">
                            Hospital Employee Satisfaction Survey (HESS) &bull; Instrumen 8 Unsur Dimensi RS Dr. Sardjito
                        </p>
                    </div>
                </div>

                <div class="text-right text-xs shrink-0 hidden sm:block">
                    <div class="font-bold text-gray-800">DOKUMEN MANAJEMEN</div>
                    <div class="text-gray-500">Tanggal: {{ $generatedAt }}</div>
                    <div class="text-gray-500">Periode: <strong class="text-primary-700">{{ $selectedPeriod->name ?? 'Aktif' }}</strong></div>
                    @if($hasFilters)
                    <div class="text-[10px] font-bold text-amber-700 mt-0.5">Segmentasi Terfilter</div>
                    @endif
                </div>
            </div>
        </header>

        <!-- 2. EXECUTIVE SUMMARY & KPI CARDS -->
        <section class="mb-6 print-break-inside-avoid">
            <h2 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-3">1. Ringkasan Eksekutif & Indikator Utama</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5">
                <!-- Indeks Kepuasan Pegawai -->
                <div class="p-3.5 rounded-2xl bg-primary-50/70 border border-primary-200/80">
                    <div class="text-[11px] font-bold text-primary-800 uppercase">Indeks Kepuasan (IKP)</div>
                    <div class="text-2xl font-black text-primary-700 mt-1">
                        {{ number_format($avgGeneral, 1) }}%
                    </div>
                    <div class="text-[10px] text-primary-800 mt-1 font-semibold flex items-center gap-1">
                        <span>Skala: {{ number_format(($avgGeneral / 100) * 4, 2) }}/4.0</span>
                        <span>&bull;</span>
                        <span>{{ $avgGeneral >= 81 ? 'Sangat Setuju' : ($avgGeneral >= 61 ? 'Setuju' : ($avgGeneral >= 41 ? 'Tidak Setuju' : 'Sangat Tidak Setuju')) }}</span>
                    </div>
                </div>

                <!-- Net Promoter Score -->
                <div class="p-3.5 rounded-2xl {{ $npsScore >= 50 ? 'bg-emerald-50/70 border-emerald-200/80' : ($npsScore >= 0 ? 'bg-amber-50/70 border-amber-200/80' : 'bg-rose-50/70 border-rose-200/80') }} border">
                    <div class="text-[11px] font-bold {{ $npsScore >= 50 ? 'text-emerald-800' : ($npsScore >= 0 ? 'text-amber-800' : 'text-rose-800') }} uppercase">Net Promoter Score</div>
                    <div class="text-2xl font-black {{ $npsScore >= 50 ? 'text-emerald-700' : ($npsScore >= 0 ? 'text-amber-700' : 'text-rose-700') }} mt-1">
                        {{ $npsScore > 0 ? '+'.$npsScore : $npsScore }}
                    </div>
                    <div class="text-[10px] {{ $npsScore >= 50 ? 'text-emerald-700' : ($npsScore >= 0 ? 'text-amber-700' : 'text-rose-700') }} mt-1 font-medium">
                        {{ $npsScore >= 50 ? 'Loyalitas Sangat Baik' : ($npsScore >= 0 ? 'Kategori Netral / Waspada' : 'Perhatian Khusus') }}
                    </div>
                </div>

                <!-- Partisipasi Responden -->
                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200/80">
                    <div class="text-[11px] font-bold text-gray-500 uppercase">Partisipasi Responden</div>
                    <div class="text-2xl font-black text-gray-900 mt-1">
                        {{ number_format($totalResponses) }}
                    </div>
                    <div class="text-[10px] text-gray-500 mt-1 font-medium">
                        {{ $target > 0 ? 'Target: '.number_format($target).' ('.$responseRate.'%)' : 'Pengisian kuesioner terverifikasi' }}
                    </div>
                </div>

                <!-- Promoters (Loyalitas) -->
                @php
                $promoterPct = $totalResponses > 0 ? (int) round(($promoters / $totalResponses) * 100) : 0;
                $passivePct = $totalResponses > 0 ? (int) round(($passives / $totalResponses) * 100) : 0;
                $detractorPct = $totalResponses > 0 ? max(0, 100 - $promoterPct - $passivePct) : 0;
                @endphp
                <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200/80">
                    <div class="text-[11px] font-bold text-emerald-800 uppercase">Promoters (Loyal)</div>
                    <div class="text-2xl font-black text-emerald-700 mt-1">
                        {{ $promoterPct }}%
                    </div>
                    <div class="text-[10px] text-emerald-700 mt-1 font-medium">
                        {{ number_format($promoters) }} responden siap merekomendasikan
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. PROPORSI NET PROMOTER SCORE (PROGRESS BAR & BREAKDOWN) -->
        <section class="mb-6 print-break-inside-avoid p-4 rounded-2xl border border-gray-200 bg-gray-50/40">
            <h2 class="text-xs font-black uppercase tracking-wider text-gray-500 mb-2">2. Distribusi & Segmentasi Loyalitas Pegawai (NPS)</h2>
            <div class="space-y-2">
                <!-- Stacked Bar Visual -->
                <div class="w-full h-5 rounded-full overflow-hidden flex bg-gray-200 border border-gray-300">
                    <div style="width: {{ $promoterPct }}%"
                        class="bg-emerald-500 h-full flex items-center justify-center text-[10px] font-black text-white"
                        title="Promoters {{ $promoterPct }}%">
                        {{ $promoterPct > 8 ? $promoterPct.'%' : '' }}
                    </div>
                    <div style="width: {{ $passivePct }}%"
                        class="bg-amber-400 h-full flex items-center justify-center text-[10px] font-black text-gray-900"
                        title="Passives {{ $passivePct }}%">
                        {{ $passivePct > 8 ? $passivePct.'%' : '' }}
                    </div>
                    <div style="width: {{ $detractorPct }}%"
                        class="bg-rose-500 h-full flex items-center justify-center text-[10px] font-black text-white"
                        title="Detractors {{ $detractorPct }}%">
                        {{ $detractorPct > 8 ? $detractorPct.'%' : '' }}
                    </div>
                </div>

                <!-- Legend details -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <div class="flex items-center gap-1.5 text-emerald-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="font-bold">Promoters (9-10): {{ $promoterPct }}%</span>
                        <span class="text-gray-500">({{ number_format($promoters) }} pegawai)</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-amber-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="font-bold">Passives (7-8): {{ $passivePct }}%</span>
                        <span class="text-gray-500">({{ number_format($passives) }} pegawai)</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-rose-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <span class="font-bold">Detractors (0-6): {{ $detractorPct }}%</span>
                        <span class="text-gray-500">({{ number_format($detractors) }} pegawai)</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. DETAIL 8 UNSUR DIMENSI RUMAH SAKIT -->
        <section class="mb-6 print-break-inside-avoid">
            <h2 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-3">3. Evaluasi Capaian Berdasarkan 8 Dimensi Kepuasan Kerja RS</h2>
            <div class="overflow-hidden rounded-2xl border border-gray-200">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 font-bold uppercase text-[10px] tracking-wider">
                            <th class="py-2.5 px-3 w-16">Kode</th>
                            <th class="py-2.5 px-3">Nama Dimensi Kondisi Kerja RS</th>
                            <th class="py-2.5 px-3 text-center w-24">Jumlah Soal</th>
                            <th class="py-2.5 px-3 text-center w-28">Rerata (Skala 4)</th>
                            <th class="py-2.5 px-3 text-center w-28">Skor Indeks (%)</th>
                            <th class="py-2.5 px-3 text-center w-28">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categoryScores as $cat)
                        @php
                        $score = (float) ($cat->percentage_score ?? 0);
                        $raw = (float) ($cat->avg_raw_score ?? 0);
                        $predicate = $score >= 81 ? 'Optimal' : ($score >= 61 ? 'Baik' : ($score >= 41 ? 'Perhatian' : 'Prioritas RTL'));
                        $badgeClass = $score >= 81 ? 'bg-emerald-100 text-emerald-800' : ($score >= 61 ? 'bg-primary-100 text-primary-800' : ($score >= 41 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800'));
                        @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-2.5 px-3 font-mono font-bold text-gray-700">{{ $cat->code }}</td>
                            <td class="py-2.5 px-3 font-semibold text-gray-900">{{ $cat->name }}</td>
                            <td class="py-2.5 px-3 text-center text-gray-600">{{ $cat->questions_count }} butir</td>
                            <td class="py-2.5 px-3 text-center font-mono font-bold text-gray-800">{{ number_format($raw, 2) }} / 4.0</td>
                            <td class="py-2.5 px-3 text-center font-mono font-bold text-primary-700">{{ number_format($score, 1) }}%</td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $badgeClass }}">
                                    {{ $predicate }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-400 italic">Belum ada data dimensi survei tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- 5. TOP 5 KEKUATAN VS TOP 5 AREA PRIORITAS RTL -->
        <section class="mb-6 print-break-inside-avoid">
            <h2 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-3">4. Analisis Butir Indikator: Kekuatan Utama vs Prioritas RTL</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kekuatan Utama (Top 5) -->
                <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50/30">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-black text-emerald-900 uppercase">5 Butir Kekuatan Tertinggi (Pertahankan)</span>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Optimal</span>
                    </div>
                    <div class="space-y-2 text-xs">
                        @forelse($topStrengths as $item)
                        <div class="p-2 rounded-xl bg-white/80 border border-emerald-100 shadow-2xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">{{ $item->code }}</span>
                                <span class="font-mono font-extrabold text-emerald-800 text-xs">{{ number_format($item->avg_score, 2) }}/4.0 ({{ number_format($item->percentage_score, 1) }}%)</span>
                            </div>
                            <p class="text-gray-800 font-medium line-clamp-2 text-[11px] leading-snug">{{ $item->text }}</p>
                            <span class="text-[10px] text-gray-400 block font-semibold">{{ $item->category_name }}</span>
                        </div>
                        @empty
                        <div class="text-xs text-gray-400 italic">Belum ada data kekuatan kuesioner.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Prioritas Perbaikan (Top 5) -->
                <div class="p-4 rounded-2xl border border-rose-200 bg-rose-50/30">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-black text-rose-900 uppercase">5 Butir Skor Terendah (Rencana Tindak Lanjut)</span>
                        <span class="text-[10px] font-bold text-rose-700 bg-rose-100 px-2 py-0.5 rounded-full">Perlu Intervensi</span>
                    </div>
                    <div class="space-y-2 text-xs">
                        @forelse($topImprovements as $item)
                        <div class="p-2 rounded-xl bg-white/80 border border-rose-100 shadow-2xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">{{ $item->code }}</span>
                                <span class="font-mono font-extrabold text-rose-800 text-xs">{{ number_format($item->avg_score, 2) }}/4.0 ({{ number_format($item->percentage_score, 1) }}%)</span>
                            </div>
                            <p class="text-gray-800 font-medium line-clamp-2 text-[11px] leading-snug">{{ $item->text }}</p>
                            <span class="text-[10px] text-gray-400 block font-semibold">{{ $item->category_name }}</span>
                        </div>
                        @empty
                        <div class="text-xs text-gray-400 italic">Belum ada data butir prioritas perbaikan.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. RINGKASAN SEGMENTASI DIREKTORAT -->
        @if($directorateScores->isNotEmpty())
        <section class="mb-6 print-break-inside-avoid">
            <h2 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-3">5. Capaian Indeks Kepuasan Berdasarkan Direktorat RS</h2>
            <div class="overflow-hidden rounded-2xl border border-gray-200">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 font-bold uppercase text-[10px] tracking-wider">
                            <th class="py-2.5 px-3">Direktorat / Divisi</th>
                            <th class="py-2.5 px-3 text-center w-28">Jumlah Responden</th>
                            <th class="py-2.5 px-3 text-center w-32">Rata-rata Skor (%)</th>
                            <th class="py-2.5 px-3 text-center w-28">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($directorateScores as $dir)
                        @php
                        $dScore = (float) $dir->avg_score;
                        $dPred = $dScore >= 81 ? 'Optimal' : ($dScore >= 61 ? 'Baik' : ($dScore >= 41 ? 'Perhatian' : 'Prioritas RTL'));
                        $dBadge = $dScore >= 81 ? 'bg-emerald-100 text-emerald-800' : ($dScore >= 61 ? 'bg-primary-100 text-primary-800' : 'bg-amber-100 text-amber-800');
                        @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-2.5 px-3 font-semibold text-gray-900">{{ $dir->directorate }}</td>
                            <td class="py-2.5 px-3 text-center text-gray-600">{{ number_format($dir->total) }} orang</td>
                            <td class="py-2.5 px-3 text-center font-mono font-bold text-primary-700">{{ number_format($dScore, 1) }}%</td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $dBadge }}">
                                    {{ $dPred }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        @endif

        <!-- 7. REKOMENDASI TINDAK LANJUT MANAJEMEN
        <section class="mb-8 print-break-inside-avoid p-4 rounded-2xl bg-purple-50/50 border border-purple-200">
            <h2 class="text-xs font-black uppercase tracking-wider text-purple-900 mb-2">6. Rekomendasi Tindak Lanjut Manajemen (SDM & Operasional RS)</h2>
            <ul class="text-xs text-gray-800 space-y-1.5 list-disc list-inside">
                <li><strong>Penguatan Aspek Unggulan:</strong> Mempertahankan dan mengapresiasi faktor-faktor kerja berkategori "Optimal" sebagai fondasi budaya organisasi dan daya retensi talenta rumah sakit.</li>
                <li><strong>Intervensi Terfokus pada Area Prioritas RTL:</strong> Menjadwalkan peninjauan khusus bersama Kepala Bagian terkait untuk 5 butir dengan skor terendah guna merumuskan program perbaikan terukur dalam 3–6 bulan ke depan.</li>
                <li><strong>Monitoring & Evaluasi Berkala:</strong> Melakukan evaluasi tindak lanjut survei pada siklus periode berikutnya untuk memantau tren perbaikan indeks kepuasan kerja pegawai secara berkelanjutan.</li>
            </ul>
        </section> -->

        <!-- 8. LEMBAR TANDA TANGAN / PENGESAHAN
        <footer class="pt-6 border-t border-gray-200 print-break-inside-avoid">
            <div class="grid grid-cols-2 gap-8 text-center text-xs">
                <div>
                    <div class="text-gray-500 mb-16">Dipersiapkan Oleh,</div>
                    <div class="font-extrabold text-gray-900 underline">Kepala Bagian SDM & HRD</div>
                    <div class="text-gray-500 text-[11px]">PT. MRSTC Indonesia</div>
                </div>
                <div>
                    <div class="text-gray-500 mb-16">Mengetahui & Menyetujui,</div>
                    <div class="font-extrabold text-gray-900 underline">Direktur Utama Rumah Sakit</div>
                    <div class="text-gray-500 text-[11px]">Hospital Employee Satisfaction Survey</div>
                </div>
            </div>
        </footer> -->

    </main>

</body>

</html>