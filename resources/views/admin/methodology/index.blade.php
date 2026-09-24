@extends('layouts.admin', ['title' => 'Panduan Metodologi & Rumus — HESS Admin'])

@section('header-title', 'Panduan Metodologi & Rumus Perhitungan')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'indicators' }">

    {{-- 1. Hero Header Banner yang Ramah --}}
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 md:p-8 shadow-xs border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-5 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-primary-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-400 text-xs font-semibold tracking-wide backdrop-blur-xs border border-white/10">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Transparansi & Metodologi Ilmiah Terbuka
            </div>
            <h1 class="text-2xl md:text-3xl font-black tracking-tight text-white">
                Cara Aplikasi Menghitung Skor & Persentase
            </h1>
            <p class="text-xs md:text-sm text-slate-300 font-normal max-w-2xl leading-relaxed">
                Penjelasan ramah, sederhana, dan mudah dipahami tentang bagaimana setiap klik jawaban pegawai diubah menjadi angka persentase berdasarkan instrumen Indikator Survei Kepuasan Pegawai RSUP Dr. Sardjito (skala 4 poin) dan indeks Net Promoter Score (NPS).
            </p>
        </div>

        <div class="relative z-10 flex flex-col items-start md:items-end gap-1 text-xs text-slate-300 bg-white/5 border border-white/10 p-4 rounded-2xl shrink-0">
            <span class="font-extrabold text-white text-sm">HESS Measurement Guide</span>
            <span class="text-emerald-400 font-semibold">Indikator Kepuasan & Skala 4 Poin</span>
            <span class="text-gray-400 text-[11px]">Bebas Jargon Rumit • 100% Gamblang</span>
        </div>
    </div>

    {{-- 2. Tab Menu Pilihan Topik --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-gray-200/80">
        <button type="button"
            @click="activeTab = 'indicators'"
            :class="activeTab === 'indicators' ? 'bg-primary-600 text-white shadow-xs' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200/80'"
            class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition flex items-center gap-2 shrink-0 cursor-pointer">
            <span class="text-sm">📋</span>
            <span>1. Indikator Survei Kepuasan Pegawai (Skala 4 Poin)</span>
        </button>

        <button type="button"
            @click="activeTab = 'nps'"
            :class="activeTab === 'nps' ? 'bg-primary-600 text-white shadow-xs' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200/80'"
            class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition flex items-center gap-2 shrink-0 cursor-pointer">
            <span class="text-sm">📣</span>
            <span>2. Net Promoter Score (NPS)</span>
        </button>
    </div>

    {{-- 3. KONTEN TAB 1: METODE INDIKATOR KEPUASAN --}}
    <div x-show="activeTab === 'indicators'" x-transition class="space-y-6">
        <!-- Pengantar Instrumen -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center shrink-0 text-2xl">
                    🌟
                </div>
                <div class="space-y-1">
                    <h2 class="text-base md:text-lg font-black text-gray-900">Instrumen Indikator Survei Kepuasan Pegawai RSUP Dr. Sardjito</h2>
                    <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                        Instrumen HESS RSUP Dr. Sardjito terdiri dari unsur-unsur <strong>indikator kepuasan pegawai</strong> dengan butir pertanyaan tertutup dan isian kualitatif terbuka (alasan penilaian dan saran perbaikan setiap unsur). Kuesioner menggunakan <strong>skala 4 poin (*forced choice*)</strong> tanpa pilihan netral untuk mendorong ketegasan penilaian responden.
                    </p>
                </div>
            </div>

            <!-- 8 Unsur Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2">
                <div class="bg-emerald-50/60 border border-emerald-200/80 rounded-2xl p-3.5 space-y-1">
                    <div class="text-xs font-extrabold text-emerald-900">1. Lingkungan Kerja</div>
                    <p class="text-[11px] text-emerald-800 leading-relaxed">Teamwork, kolaborasi lintas satker, dan sarana prasarana kerja.</p>
                </div>
                <div class="bg-indigo-50/60 border border-indigo-200/80 rounded-2xl p-3.5 space-y-1">
                    <div class="text-xs font-extrabold text-indigo-900">2. Hubungan dengan Atasan</div>
                    <p class="text-[11px] text-indigo-800 leading-relaxed">Hubungan atasan-pegawai dan pengakuan kinerja dari pimpinan/direksi.</p>
                </div>
                <div class="bg-purple-50/60 border border-purple-200/80 rounded-2xl p-3.5 space-y-1">
                    <div class="text-xs font-extrabold text-purple-900">3. Reward & Penilaian</div>
                    <p class="text-[11px] text-purple-800 leading-relaxed">Sistem reward prestasi, sanksi disiplin, dan objektivitas penilaian kinerja.</p>
                </div>
                <div class="bg-sky-50/60 border border-sky-200/80 rounded-2xl p-3.5 space-y-1">
                    <div class="text-xs font-extrabold text-sky-900">4. Pengembangan Karir</div>
                    <p class="text-[11px] text-sky-800 leading-relaxed">Pengembangan kompetensi, pendidikan formal, dan pelatihan non formal.</p>
                </div>
                <div class="bg-amber-50/60 border border-amber-200/80 rounded-2xl p-3.5 space-y-1">
                    <div class="text-xs font-extrabold text-amber-900">5. Gaji & Kompensasi</div>
                    <p class="text-[11px] text-amber-800 leading-relaxed">Transparansi indikator remunerasi, daya saing komparatif, dan ketepatan waktu.</p>
                </div>
                <div class="bg-rose-50/60 border border-rose-200/80 rounded-2xl p-3.5 space-y-1">
                    <div class="text-xs font-extrabold text-rose-900">6. Work Life Balance</div>
                    <p class="text-[11px] text-rose-800 leading-relaxed">Kesehatan fisik/mental, fleksibilitas waktu, dan kemudahan hak cuti/kepegawaian.</p>
                </div>
                <div class="bg-teal-50/60 border border-teal-200/80 rounded-2xl p-3.5 space-y-1">
                    <div class="text-xs font-extrabold text-teal-900">7. Komunikasi Internal</div>
                    <p class="text-[11px] text-teal-800 leading-relaxed">Kelancaran informasi kerja dan efektivitas saluran komunikasi (hotline/e-prens/FGD).</p>
                </div>
                <div class="bg-blue-50/60 border border-blue-200/80 rounded-2xl p-3.5 space-y-1">
                    <div class="text-xs font-extrabold text-blue-900">8. Budaya Rumah Sakit</div>
                    <p class="text-[11px] text-blue-800 leading-relaxed">Penerapan budaya BerAKHLAK, 5R, dan nilai Pendidikan Bermartabat.</p>
                </div>
            </div>
        </div>

        <!-- Rumus Perhitungan Persentase -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm md:text-base font-black text-gray-900">Rumus Mengubah Skor Jawaban Menjadi Persentase (%)</h3>
                <p class="text-xs text-gray-400">Pegawai memilih skala 1 sampai 4. Bagaimana cara sistem menghitung persentase indeksnya?</p>
            </div>

            <!-- Rumus Visual Kotak -->
            <div class="bg-slate-900 text-white p-5 rounded-2xl text-center space-y-3 shadow-inner">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Rumus Persentase Skor Skala 4 Poin:</span>
                <div class="text-base md:text-xl font-mono font-extrabold flex items-center justify-center gap-3">
                    <span>Persentase (%)</span>
                    <span>=</span>
                    <span class="inline-flex flex-col items-center">
                        <span class="border-b border-white/60 pb-1">Total Skor yang Diberikan</span>
                        <span class="pt-1">Jumlah Pertanyaan × 4</span>
                    </span>
                    <span>× 100%</span>
                </div>
                <p class="text-xs text-slate-300">
                    Atau lebih ringkasnya: <code>(Rata-Rata Skor Jawaban ÷ 4) × 100%</code>
                </p>
            </div>

            <!-- Contoh Studi Kasus Riil -->
            <div class="bg-amber-50/70 border border-amber-200 rounded-2xl p-4 space-y-2">
                <div class="font-extrabold text-amber-900 text-xs uppercase tracking-wider">Contoh Perhitungan Riil:</div>
                <p class="text-xs text-amber-950 leading-relaxed">
                    Seorang pegawai menjawab 3 pertanyaan pada unsur <strong>Lingkungan Kerja</strong> dengan skor: 4, 3, dan 4 (total skor = 11).<br>
                    Maka perhitungan di sistem adalah:
                </p>
                <div class="bg-white/80 border border-amber-300 rounded-xl p-3 font-mono text-xs text-amber-900 font-bold">
                    Persentase = (11 ÷ (3 × 4)) × 100% = (11 ÷ 12) × 100% = 91.7%
                </div>
                <p class="text-[11px] text-amber-800">
                    Nilai <strong>91.7%</strong> ini dikategorikan ke predikat <strong>Sangat Setuju / Optimal</strong> pada Dashboard Analitik!
                </p>
            </div>

            <!-- Tabel Pedoman Kategori Nilai -->
            <div class="space-y-3">
                <h4 class="text-xs font-black uppercase tracking-wider text-gray-700">Tabel Predikat Kategori Nilai Skala 4 Poin</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border border-gray-100 rounded-xl overflow-hidden">
                        <thead class="bg-gray-50 text-[10px] font-extrabold uppercase text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="p-3">Rentang Persentase</th>
                                <th class="p-3">Rata-Rata Skala</th>
                                <th class="p-3">Predikat Kepuasan</th>
                                <th class="p-3">Artinya bagi Rumah Sakit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="bg-emerald-50/30">
                                <td class="p-3 font-bold text-emerald-800">81.0% – 100%</td>
                                <td class="p-3 font-mono">3.25 – 4.00</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Sangat Setuju (Optimal)</span></td>
                                <td class="p-3 text-gray-600">Area kekuatan utama. Pegawai sangat puas dan iklim kerja sangat kondusif.</td>
                            </tr>
                            <tr class="bg-teal-50/30">
                                <td class="p-3 font-bold text-teal-800">61.0% – 80.9%</td>
                                <td class="p-3 font-mono">2.45 – 3.24</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">Setuju (Baik)</span></td>
                                <td class="p-3 text-gray-600">Kondisi kerja baik dan sehat, dengan beberapa area penyempurnaan minor.</td>
                            </tr>
                            <tr class="bg-amber-50/30">
                                <td class="p-3 font-bold text-amber-800">41.0% – 60.9%</td>
                                <td class="p-3 font-mono">1.65 – 2.44</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Tidak Setuju (Perhatian)</span></td>
                                <td class="p-3 text-gray-600">Pegawai merasakan hambatan atau kekurangan. Membutuhkan evaluasi pimpinan.</td>
                            </tr>
                            <tr class="bg-rose-50/30">
                                <td class="p-3 font-bold text-rose-800">&lt; 41.0%</td>
                                <td class="p-3 font-mono">1.00 – 1.64</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Sangat Tidak Setuju (Prioritas RTL)</span></td>
                                <td class="p-3 text-gray-600">Lampu merah! Prioritas utama Rencana Tindak Lanjut (RTL) segera.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. KONTEN TAB 2: NET PROMOTER SCORE (NPS) --}}
    <div x-show="activeTab === 'nps'" x-cloak x-transition class="space-y-6">
        <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-5">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-700 flex items-center justify-center shrink-0 text-2xl">
                    📣
                </div>
                <div class="space-y-1">
                    <h2 class="text-base md:text-lg font-black text-gray-900">Net Promoter Score (NPS)</h2>
                    <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                        NPS diawali dengan 1 pertanyaan pamungkas: <br>
                        <span class="font-bold text-gray-900">"Dari skala 0 sampai 10, seberapa besar kemungkinan Anda merekomendasikan rumah sakit ini kepada teman sebagai tempat bekerja yang baik?"</span>
                    </p>
                </div>
            </div>

            <!-- 3 Golongan Pegawai -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- 1. Promoter -->
                <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase text-emerald-900">Promoter (Nilai 9 – 10)</span>
                        <span class="text-xl">💚</span>
                    </div>
                    <p class="text-xs text-emerald-800 leading-relaxed">
                        Pegawai yang <strong>sangat bangga</strong>. Mereka loyal, bekerja dengan hati, dan dengan senang hati mengajak rekan sejawat yang kompeten untuk melamar kerja di rumah sakit ini.
                    </p>
                </div>

                <!-- 2. Passive -->
                <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase text-amber-900">Passive (Nilai 7 – 8)</span>
                        <span class="text-xl">💛</span>
                    </div>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Pegawai yang merasa <strong>cukup puas tapi pasif</strong>. Mereka tidak menjelekkan RS, namun bisa sewaktu-waktu pindah bila rumah sakit sebelah menawarkan gaji sedikit lebih tinggi.
                    </p>
                </div>

                <!-- 3. Detractor -->
                <div class="p-4 rounded-2xl bg-rose-50/70 border border-rose-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase text-rose-900">Detractor (Nilai 0 – 6)</span>
                        <span class="text-xl">💔</span>
                    </div>
                    <p class="text-xs text-rose-800 leading-relaxed">
                        Pegawai yang merasa <strong>kecewa atau tertekan</strong>. Mereka berisiko tinggi mengundurkan diri (<em>turnover</em>) dan rentan menularkan rasa demotivasi ke rekan kerjanya.
                    </p>
                </div>
            </div>

            <!-- Rumus NPS -->
            <div class="bg-slate-900 text-white p-5 rounded-2xl text-center space-y-2">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Rumus Sederhana NPS:</span>
                <div class="text-xl md:text-2xl font-mono font-black text-white">
                    Skor NPS = % Promoter − % Detractor
                </div>
                <p class="text-xs text-slate-300">
                    Nilai NPS berkisar dari <strong>-100</strong> (jika semua pegawai Detractor) hingga <strong>+100</strong> (jika semua pegawai Promoter).
                </p>
            </div>

            <!-- Contoh Hitung NPS -->
            <div class="bg-emerald-50/60 border border-emerald-200 rounded-2xl p-4 text-xs text-emerald-950 space-y-1.5">
                <div class="font-extrabold uppercase tracking-wider text-emerald-900">Contoh Hitungan:</div>
                <p>Misal dari 100 responden: Ada 60 Promoter (60%), 25 Passive (25%), dan 15 Detractor (15%).</p>
                <div class="font-mono font-bold bg-white/80 p-2.5 rounded-xl border border-emerald-300">
                    NPS = 60% − 15% = +45
                </div>
                <p class="text-[11px] text-emerald-800">
                    Skor <strong>+45</strong> artinya iklim budaya kerja rumah sakit Anda sangat sehat dan kondusif!
                </p>
            </div>
        </div>
    </div>
</div>
@endsection