<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Analisis Sentimen — {{ $selectedPeriod->name ?? 'HESS' }}</title>
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
                <a href="{{ route('admin.sentiment.index', request()->query()) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Dashboard</span>
                </a>
                <span class="text-xs text-gray-400 hidden sm:inline">&bull;</span>
                <span class="text-xs text-gray-500 font-medium hidden sm:inline">Format Siap Cetak A4 / Unduh PDF</span>
            </div>

            <div class="flex items-center gap-2.5">
                <button type="button" onclick="window.print()"
                        class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-700 hover:bg-primary-800 text-white font-bold text-xs shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak / Simpan PDF</span>
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
                            Laporan Eksekutif Analisis Sentimen & Suara Pegawai
                        </h1>
                        <p class="text-xs text-gray-500 font-medium">
                            Hospital Employee Satisfaction Survey (HESS) &bull; Instrumen MSQ-20 & Faktor Rumah Sakit
                        </p>
                    </div>
                </div>

                <div class="text-right text-xs shrink-0 hidden sm:block">
                    <div class="font-bold text-gray-800">DOKUMEN MANAJEMEN</div>
                    <div class="text-gray-500">Tanggal: {{ $generatedAt }}</div>
                    <div class="text-gray-500">Periode: <strong class="text-primary-700">{{ $selectedPeriod->name ?? 'Aktif' }}</strong></div>
                </div>
            </div>
        </header>

        <!-- 2. EXECUTIVE SUMMARY & KPI CARDS -->
        <section class="mb-6 print-break-inside-avoid">
            <h2 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-3">1. Ringkasan Eksekutif & Indikator Utama</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5">
                <!-- Sentimen Positif -->
                <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200/80">
                    <div class="text-[11px] font-bold text-emerald-800 uppercase">Positif</div>
                    <div class="text-2xl font-black text-emerald-700 mt-1">
                        {{ $sentimentProportion['positive']['percent'] }}%
                    </div>
                    <div class="text-[10px] text-emerald-700 mt-1 font-medium">
                        {{ number_format($sentimentProportion['positive']['count']) }} {{ $sentimentProportion['unit'] }} puas & apresiatif
                    </div>
                </div>

                <!-- Sentimen Netral -->
                <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/80">
                    <div class="text-[11px] font-bold text-amber-800 uppercase">Netral</div>
                    <div class="text-2xl font-black text-amber-700 mt-1">
                        {{ $sentimentProportion['neutral']['percent'] }}%
                    </div>
                    <div class="text-[10px] text-amber-700 mt-1 font-medium">
                        {{ number_format($sentimentProportion['neutral']['count']) }} {{ $sentimentProportion['unit'] }} alur & SOP
                    </div>
                </div>

                <!-- Sentimen Negatif -->
                <div class="p-3.5 rounded-2xl bg-rose-50/70 border border-rose-200/80">
                    <div class="text-[11px] font-bold text-rose-800 uppercase">Negatif</div>
                    <div class="text-2xl font-black text-rose-700 mt-1">
                        {{ $sentimentProportion['negative']['percent'] }}%
                    </div>
                    <div class="text-[10px] text-rose-700 mt-1 font-medium">
                        {{ number_format($sentimentProportion['negative']['count']) }} {{ $sentimentProportion['unit'] }} kendala & keluhan
                    </div>
                </div>

                <!-- Total Masukan Responden -->
                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200/80">
                    <div class="text-[11px] font-bold text-gray-500 uppercase">Total Feedback</div>
                    <div class="text-2xl font-black text-gray-900 mt-1">
                        {{ $totalResponses }}
                    </div>
                    <div class="text-[10px] text-gray-500 mt-1 font-medium">
                        Responden memberikan masukan teks
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. PROPORSI SENTIMEN (PROGRESS BAR & BREAKDOWN) -->
        <section class="mb-6 print-break-inside-avoid p-4 rounded-2xl border border-gray-200 bg-gray-50/40">
            <h2 class="text-xs font-black uppercase tracking-wider text-gray-500 mb-2">2. Distribusi & Proporsi Sentimen Pegawai</h2>
            <div class="space-y-2">
                <!-- Stacked Bar Visual -->
                <div class="w-full h-5 rounded-full overflow-hidden flex bg-gray-200 border border-gray-300">
                    <div style="width: {{ $sentimentProportion['positive']['percent'] }}%"
                         class="bg-emerald-500 h-full flex items-center justify-center text-[10px] font-black text-white"
                         title="Positif {{ $sentimentProportion['positive']['percent'] }}%">
                        {{ $sentimentProportion['positive']['percent'] > 8 ? $sentimentProportion['positive']['percent'].'%' : '' }}
                    </div>
                    <div style="width: {{ $sentimentProportion['neutral']['percent'] }}%"
                         class="bg-amber-400 h-full flex items-center justify-center text-[10px] font-black text-gray-900"
                         title="Netral {{ $sentimentProportion['neutral']['percent'] }}%">
                        {{ $sentimentProportion['neutral']['percent'] > 8 ? $sentimentProportion['neutral']['percent'].'%' : '' }}
                    </div>
                    <div style="width: {{ $sentimentProportion['negative']['percent'] }}%"
                         class="bg-rose-500 h-full flex items-center justify-center text-[10px] font-black text-white"
                         title="Negatif {{ $sentimentProportion['negative']['percent'] }}%">
                        {{ $sentimentProportion['negative']['percent'] > 8 ? $sentimentProportion['negative']['percent'].'%' : '' }}
                    </div>
                </div>

                <!-- Legend details -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <div class="flex items-center gap-1.5 text-emerald-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="font-bold">Positif: {{ $sentimentProportion['positive']['percent'] }}%</span>
                        <span class="text-gray-500">({{ number_format($sentimentProportion['positive']['count']) }} {{ $sentimentProportion['unit'] }})</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-amber-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="font-bold">Netral: {{ $sentimentProportion['neutral']['percent'] }}%</span>
                        <span class="text-gray-500">({{ number_format($sentimentProportion['neutral']['count']) }} {{ $sentimentProportion['unit'] }})</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-rose-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <span class="font-bold">Negatif: {{ $sentimentProportion['negative']['percent'] }}%</span>
                        <span class="text-gray-500">({{ number_format($sentimentProportion['negative']['count']) }} {{ $sentimentProportion['unit'] }})</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. TOP FAKTOR KEKUATAN & KELUHAN PEGAWAI -->
        <section class="mb-6 print-break-inside-avoid">
            <h2 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-3">3. Analisis Kata Kunci: Kekuatan vs Kendala Kerja</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kekuatan / Positif -->
                <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50/30">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-black text-emerald-900 uppercase">Kekuatan & Aspek Diapresiasi (Positif)</span>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Top 8</span>
                    </div>
                    <div class="space-y-1.5 text-xs">
                        @forelse($topPositive as $item)
                            <div class="flex items-center justify-between py-1 border-b border-emerald-100/80 last:border-none">
                                <span class="font-bold text-gray-800 font-mono">{{ $item['word'] }}</span>
                                <span class="font-semibold text-emerald-700">{{ $item['count'] }} sebutan</span>
                            </div>
                        @empty
                            <div class="text-xs text-gray-400 italic">Belum ada kata positif</div>
                        @endforelse
                    </div>
                </div>

                <!-- Masalah / Negatif -->
                <div class="p-4 rounded-2xl border border-rose-200 bg-rose-50/30">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-black text-rose-900 uppercase">Kendala & Keluhan Perbaikan (Negatif)</span>
                        <span class="text-[10px] font-bold text-rose-700 bg-rose-100 px-2 py-0.5 rounded-full">Top 8</span>
                    </div>
                    <div class="space-y-1.5 text-xs">
                        @forelse($topNegative as $item)
                            <div class="flex items-center justify-between py-1 border-b border-rose-100/80 last:border-none">
                                <span class="font-bold text-gray-800 font-mono">{{ $item['word'] }}</span>
                                <span class="font-semibold text-rose-700">{{ $item['count'] }} keluhan</span>
                            </div>
                        @empty
                            <div class="text-xs text-gray-400 italic">Belum ada keluhan tercatat</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. KUTIPAN RESIDUAL SUARA PEGAWAI -->
        <section class="mb-6 print-break-inside-avoid">
            <h2 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-3">4. Contoh Kutipan Asli Masukan Pegawai</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kutipan Positif -->
                <div class="space-y-2.5">
                    <span class="text-[11px] font-bold text-emerald-800 uppercase block">Kutipan Hal yang Paling Disukai</span>
                    @foreach($positiveQuotes as $q)
                        <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-200 text-xs">
                            <p class="italic text-gray-800">"{{ $q['like_text'] }}"</p>
                            <div class="mt-1 text-[10px] text-emerald-700 font-semibold">
                                &mdash; {{ $q['profession'] ?? 'Staf' }} &bull; {{ $q['unit'] ?? 'Unit Medis' }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Kutipan Negatif -->
                <div class="space-y-2.5">
                    <span class="text-[11px] font-bold text-rose-800 uppercase block">Kutipan Hal yang Perlu Diperbaiki</span>
                    @foreach($negativeQuotes as $q)
                        <div class="p-3 rounded-xl bg-rose-50/60 border border-rose-200 text-xs">
                            <p class="italic text-gray-800">"{{ $q['improve_text'] }}"</p>
                            <div class="mt-1 text-[10px] text-rose-700 font-semibold">
                                &mdash; {{ $q['profession'] ?? 'Staf' }} &bull; {{ $q['unit'] ?? 'Unit Medis' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- 6. KESIMPULAN STRATEGIS & REKOMENDASI -->
        <section class="mb-8 print-break-inside-avoid p-4 rounded-2xl bg-purple-50/50 border border-purple-200">
            <h2 class="text-xs font-black uppercase tracking-wider text-purple-900 mb-2">5. Rekomendasi Tindak Lanjut Manajemen (SDM & Operasional)</h2>
            <ul class="text-xs text-gray-800 space-y-1.5 list-disc list-inside">
                <li><strong>Pertahankan Budaya Kolaboratif:</strong> Tingginya sentimen positif pada hubungan tim medis dan rekan sejawat adalah modal sosial utama yang perlu dijaga melalui program apresiasi berkala.</li>
                <li><strong>Penataan Beban Kerja & Rotasi Shift:</strong> Istilah beban kerja dan kelelahan shift menjadi pendorong keluhan utama; rekomendasikan evaluasi rasio beban kerja perawat di instalasi rawat inap dan IGD.</li>
                <li><strong>Optimalisasi Sarana & Sistem RS:</strong> Mempercepat pemeliharaan sarana kerja medis dan mempermudah alur administrasi/prosedur yang berbelit agar operasional klinis staf lebih lancar.</li>
            </ul>
        </section>

        <!-- 7. LEMBAR TANDA TANGAN / PENGESAHAN -->
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
        </footer>

    </main>

</body>
</html>
