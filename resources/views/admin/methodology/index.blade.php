@extends('layouts.admin', ['title' => 'Panduan Metodologi & Rumus — HESS Admin'])

@section('header-title', 'Panduan Metodologi & Rumus Perhitungan')

@section('content')
<div class="space-y-6" x-data="{
    activeTab: 'msq',

    // State Kalkulator Simulasi Interaktif
    simQuestionsCount: 12,
    simAverageScore: 4.2,
    get simTotalScore() {
        return (this.simQuestionsCount * this.simAverageScore).toFixed(1);
    },
    get simMaxScore() {
        return this.simQuestionsCount * 5;
    },
    get simPercentage() {
        return Math.min(100, Math.max(0, ((this.simAverageScore / 5) * 100))).toFixed(1);
    },
    get simCategory() {
        const p = parseFloat(this.simPercentage);
        if (p >= 80) return { label: 'Sangat Puas (Sangat Tinggi)', color: 'text-emerald-700 bg-emerald-50 border-emerald-200' };
        if (p >= 60) return { label: 'Puas (Tinggi)', color: 'text-teal-700 bg-teal-50 border-teal-200' };
        if (p >= 40) return { label: 'Cukup / Netral (Sedang)', color: 'text-amber-700 bg-amber-50 border-amber-200' };
        if (p >= 20) return { label: 'Kurang Puas (Rendah)', color: 'text-orange-700 bg-orange-50 border-orange-200' };
        return { label: 'Sangat Tidak Puas (Kritis)', color: 'text-rose-700 bg-rose-50 border-rose-200' };
    },

    // State Kalkulator NPS
    simTotalNps: 100,
    simPromoters: 55,
    simDetractors: 15,
    get simPassives() {
        return Math.max(0, this.simTotalNps - this.simPromoters - this.simDetractors);
    },
    get simNpsScore() {
        if (this.simTotalNps <= 0) return 0;
        return Math.round(((this.simPromoters - this.simDetractors) / this.simTotalNps) * 100);
    }
}">

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
                Penjelasan ramah, sederhana, dan mudah dipahami tentang bagaimana setiap klik jawaban pegawai diubah menjadi angka persentase, predikat kepuasan kerja (MSQ-20), indeks NPS, dan analisis sentimen.
            </p>
        </div>

        <div class="relative z-10 flex flex-col items-start md:items-end gap-1 text-xs text-slate-300 bg-white/5 border border-white/10 p-4 rounded-2xl shrink-0">
            <span class="font-extrabold text-white text-sm">HESS Measurement Guide</span>
            <span class="text-emerald-400 font-semibold">20 Butir MSQ + 24 Butir RS</span>
            <span class="text-gray-400 text-[11px]">Bebas Jargon Rumit • 100% Gamblang</span>
        </div>
    </div>

    {{-- 2. Tab Menu Pilihan Topik --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-gray-200/80">
        <button type="button"
            @click="activeTab = 'msq'"
            :class="activeTab === 'msq' ? 'bg-primary-600 text-white shadow-xs' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200/80'"
            class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition flex items-center gap-2 shrink-0 cursor-pointer">
            <span class="text-sm">📋</span>
            <span>1. Kepuasan Kerja (MSQ-20)</span>
        </button>

        <button type="button"
            @click="activeTab = 'hospital'"
            :class="activeTab === 'hospital' ? 'bg-primary-600 text-white shadow-xs' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200/80'"
            class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition flex items-center gap-2 shrink-0 cursor-pointer">
            <span class="text-sm">🏥</span>
            <span>2. Delapan Faktor Rumah Sakit</span>
        </button>

        <button type="button"
            @click="activeTab = 'nps'"
            :class="activeTab === 'nps' ? 'bg-primary-600 text-white shadow-xs' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200/80'"
            class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition flex items-center gap-2 shrink-0 cursor-pointer">
            <span class="text-sm">📣</span>
            <span>3. Net Promoter Score (NPS)</span>
        </button>

        <button type="button"
            @click="activeTab = 'sentiment'"
            :class="activeTab === 'sentiment' ? 'bg-primary-600 text-white shadow-xs' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200/80'"
            class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition flex items-center gap-2 shrink-0 cursor-pointer">
            <span class="text-sm">💬</span>
            <span>4. Analisis Curhatan Teks</span>
        </button>

        <button type="button"
            @click="activeTab = 'calculator'"
            :class="activeTab === 'calculator' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200'"
            class="px-4 py-2.5 rounded-2xl text-xs font-black transition flex items-center gap-2 shrink-0 cursor-pointer">
            <span class="text-sm">🧮</span>
            <span>5. Coba Kalkulator Simulasi</span>
        </button>
    </div>

    {{-- 3. KONTEN TAB 1: METODE MSQ-20 --}}
    <div x-show="activeTab === 'msq'" x-transition class="space-y-6">
        <!-- Pengantar Santai -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center shrink-0 text-2xl">
                    🌟
                </div>
                <div class="space-y-1">
                    <h2 class="text-base md:text-lg font-black text-gray-900">Apa Itu MSQ-20?</h2>
                    <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                        <strong>MSQ (Minnesota Satisfaction Questionnaire)</strong> adalah standar kuesioner ilmiah yang dikembangkan oleh psikolog industri di University of Minnesota. Kuesioner ini dirancang untuk menjawab pertanyaan mendasar: <em>"Seberapa bahagia dan puas pegawai dengan pekerjaannya sehari-hari?"</em>
                    </p>
                </div>
            </div>

            <!-- 2 Sisi Kepuasan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div class="bg-emerald-50/60 border border-emerald-200/80 rounded-2xl p-4 space-y-2">
                    <div class="flex items-center gap-2 text-emerald-900 font-extrabold text-sm">
                        <span>🌱</span>
                        <span>Kepuasan Intrinsik (12 Butir Soal)</span>
                    </div>
                    <p class="text-xs text-emerald-800/90 leading-relaxed">
                        Kepuasan yang datang <strong>dari dalam diri pegawai sendiri</strong>. Contohnya: rasa bangga bisa menolong pasien sembuh, kebebasan mencoba ide baru, rasa tanggung jawab, dan perasaan bahwa pekerjaannya bermanfaat bagi masyarakat.
                    </p>
                    <div class="text-[11px] font-bold text-emerald-700">
                        Bobot: 12 butir × skor maksimal 5 = Maksimal 60 Poin
                    </div>
                </div>

                <div class="bg-indigo-50/60 border border-indigo-200/80 rounded-2xl p-4 space-y-2">
                    <div class="flex items-center gap-2 text-indigo-900 font-extrabold text-sm">
                        <span>🏢</span>
                        <span>Kepuasan Ekstrinsik (6 Butir Soal)</span>
                    </div>
                    <p class="text-xs text-indigo-800/90 leading-relaxed">
                        Kepuasan yang dipengaruhi oleh <strong>faktor lingkungan di luar diri pegawai</strong>. Contohnya: gaji yang adil dan tepat waktu, bimbingan yang ramah dari atasan, hubungan yang rukun dengan teman sejawat, dan peluang naik pangkat.
                    </p>
                    <div class="text-[11px] font-bold text-indigo-700">
                        Bobot: 6 butir × skor maksimal 5 = Maksimal 30 Poin
                    </div>
                </div>
            </div>
        </div>

        <!-- Rumus Perhitungan Persentase -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm md:text-base font-black text-gray-900">Rumus Mengubah Bintang Jawaban Menjadi Persentase (%)</h3>
                <p class="text-xs text-gray-400">Pegawai memilih skala 1 sampai 5. Bagaimana cara sistem mengubahnya jadi angka persentase?</p>
            </div>

            <!-- Rumus Visual Kotak -->
            <div class="bg-slate-900 text-white p-5 rounded-2xl text-center space-y-3 shadow-inner">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Rumus Persentase Skor Butir:</span>
                <div class="text-base md:text-xl font-mono font-extrabold flex items-center justify-center gap-3">
                    <span>Persentase (%)</span>
                    <span>=</span>
                    <span class="inline-flex flex-col items-center">
                        <span class="border-b border-white/60 pb-1">Total Skor yang Diberikan</span>
                        <span class="pt-1">Jumlah Pertanyaan × 5</span>
                    </span>
                    <span>× 100%</span>
                </div>
                <p class="text-xs text-slate-300">
                    Atau lebih ringkasnya: <code>(Rata-Rata Skor Jawaban ÷ 5) × 100%</code>
                </p>
            </div>

            <!-- Contoh Studi Kasus Riil -->
            <div class="bg-amber-50/70 border border-amber-200 rounded-2xl p-4 space-y-2">
                <div class="font-extrabold text-amber-900 text-xs uppercase tracking-wider">Contoh Perhitungan Riil:</div>
                <p class="text-xs text-amber-950 leading-relaxed">
                    Seorang perawat menjawab 12 pertanyaan <strong>Kepuasan Intrinsik</strong> dengan nilai rata-rata <strong>4.0</strong> (karena rata-rata memilih tombol "Puas").<br>
                    Maka perhitungan di sistem adalah:
                </p>
                <div class="bg-white/80 border border-amber-300 rounded-xl p-3 font-mono text-xs text-amber-900 font-bold">
                    Persentase = (4.0 ÷ 5) × 100% = 80.0%
                </div>
                <p class="text-[11px] text-amber-800">
                    Nilai <strong>80.0%</strong> ini yang Anda lihat muncul di kartu ringkasan Dashboard Analitik!
                </p>
            </div>

            <!-- Tabel Pedoman Kategori Nilai -->
            <div class="space-y-3">
                <h4 class="text-xs font-black uppercase tracking-wider text-gray-700">Tabel Makna Persentase Skor</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border border-gray-100 rounded-xl overflow-hidden">
                        <thead class="bg-gray-50 text-[10px] font-extrabold uppercase text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="p-3">Rentang Persentase</th>
                                <th class="p-3">Rata-Rata Skala Likert</th>
                                <th class="p-3">Predikat Kepuasan</th>
                                <th class="p-3">Artinya bagi Rumah Sakit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="bg-emerald-50/30">
                                <td class="p-3 font-bold text-emerald-800">80.0% – 100%</td>
                                <td class="p-3 font-mono">4.00 – 5.00</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Sangat Tinggi (Sangat Puas)</span></td>
                                <td class="p-3 text-gray-600">Area kekuatan utama. Pegawai sangat loyal, bersemangat, dan merasa dihargai.</td>
                            </tr>
                            <tr class="bg-teal-50/30">
                                <td class="p-3 font-bold text-teal-800">60.0% – 79.9%</td>
                                <td class="p-3 font-mono">3.00 – 3.99</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">Tinggi (Puas)</span></td>
                                <td class="p-3 text-gray-600">Kondisi kerja baik dan sehat, namun masih ada ruang kecil untuk disempurnakan.</td>
                            </tr>
                            <tr class="bg-amber-50/30">
                                <td class="p-3 font-bold text-amber-800">40.0% – 59.9%</td>
                                <td class="p-3 font-mono">2.00 – 2.99</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Sedang (Cukup / Netral)</span></td>
                                <td class="p-3 text-gray-600">Pegawai merasa biasa saja. Perlu dialog terbuka agar tidak merosot menjadi ketidakpuasan.</td>
                            </tr>
                            <tr class="bg-rose-50/30">
                                <td class="p-3 font-bold text-rose-800">&lt; 40.0%</td>
                                <td class="p-3 font-mono">1.00 – 1.99</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Rendah (Area Perbaikan / RTL)</span></td>
                                <td class="p-3 text-gray-600">Lampu kuning! Prioritas tindakan perbaikan manajemen (Rencana Tindak Lanjut).</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. KONTEN TAB 2: DELAPAN FAKTOR RUMAH SAKIT --}}
    <div x-show="activeTab === 'hospital'" x-cloak x-transition class="space-y-6">
        <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0 text-2xl">
                    🏥
                </div>
                <div class="space-y-1">
                    <h2 class="text-base md:text-lg font-black text-gray-900">Kenapa Ada Pertanyaan Khusus Rumah Sakit?</h2>
                    <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                        Bekerja di rumah sakit sangat berbeda dengan kantor biasa. Ada jadwal piket malam, risiko tertular penyakit, tuntutan keselamatan pasien, hingga perlunya koordinasi kilat antara dokter, perawat, analis lab, dan apoteker.
                        Karena itu, HESS melengkapi MSQ dengan <strong>24 butir pertanyaan kontekstual</strong> yang dipetakan ke dalam <strong>8 Pilar Lingkungan Kerja RS</strong>:
                    </p>
                </div>
            </div>

            <!-- Grid 8 Dimensi RS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3.5 pt-2">
                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-1.5">
                    <div class="flex items-center gap-1.5 font-bold text-xs text-gray-900">
                        <span class="w-6 h-6 rounded-lg bg-primary-100 text-primary-800 flex items-center justify-center text-[11px] font-black">LS</span>
                        <span>Kepemimpinan (Supervisi)</span>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed">Apakah atasan adil, mengayomi, dan cepat memberi solusi saat staf menghadapi kesulitan klinis?</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-1.5">
                    <div class="flex items-center gap-1.5 font-bold text-xs text-gray-900">
                        <span class="w-6 h-6 rounded-lg bg-primary-100 text-primary-800 flex items-center justify-center text-[11px] font-black">WS</span>
                        <span>Beban Kerja & Giliran Shift</span>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed">Apakah jumlah perawat dan dokter sebanding dengan banyaknya pasien yang harus dirawat?</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-1.5">
                    <div class="flex items-center gap-1.5 font-bold text-xs text-gray-900">
                        <span class="w-6 h-6 rounded-lg bg-primary-100 text-primary-800 flex items-center justify-center text-[11px] font-black">CR</span>
                        <span>Kompensasi & Jaspel</span>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed">Keadilan jasa pelayanan medis, insentif lembur, dan kepastian gaji dibayar tepat waktu.</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-1.5">
                    <div class="flex items-center gap-1.5 font-bold text-xs text-gray-900">
                        <span class="w-6 h-6 rounded-lg bg-primary-100 text-primary-800 flex items-center justify-center text-[11px] font-black">CD</span>
                        <span>Pengembangan Profesi</span>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed">Dukungan rumah sakit bagi staf untuk ikut seminar ilmiah, sertifikasi, dan pelatihan klinis.</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-1.5">
                    <div class="flex items-center gap-1.5 font-bold text-xs text-gray-900">
                        <span class="w-6 h-6 rounded-lg bg-primary-100 text-primary-800 flex items-center justify-center text-[11px] font-black">EF</span>
                        <span>Fasilitas & Ruang Istirahat</span>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed">Kelayakan ruangan jaga, AC, alat medis yang tidak gampang rusak, hingga keamanan parkir.</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-1.5">
                    <div class="flex items-center gap-1.5 font-bold text-xs text-gray-900">
                        <span class="w-6 h-6 rounded-lg bg-primary-100 text-primary-800 flex items-center justify-center text-[11px] font-black">TC</span>
                        <span>Kerjasama Tim Antar Unit</span>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed">Kekompakan komunikasi operan pasien antara dokter spesialis, bangsal rawat, dan farmasi.</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-1.5">
                    <div class="flex items-center gap-1.5 font-bold text-xs text-gray-900">
                        <span class="w-6 h-6 rounded-lg bg-primary-100 text-primary-800 flex items-center justify-center text-[11px] font-black">PS</span>
                        <span>Keselamatan & Budaya Adil</span>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed">Keberanian melaporkan kendala tanpa takut disalahkan secara sepihak (<em>No-blame culture</em>).</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-1.5">
                    <div class="flex items-center gap-1.5 font-bold text-xs text-gray-900">
                        <span class="w-6 h-6 rounded-lg bg-primary-100 text-primary-800 flex items-center justify-center text-[11px] font-black">WB</span>
                        <span>Keseimbangan Hidup (Work-Life)</span>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed">Kemampuan pegawai menikmati waktu libur bersama keluarga tanpa dihantui panggilan darurat terus-menerus.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 5. KONTEN TAB 3: NET PROMOTER SCORE (NPS) --}}
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

    {{-- 6. KONTEN TAB 4: ANALISIS SENTIMEN CURHATAN --}}
    <div x-show="activeTab === 'sentiment'" x-cloak x-transition class="space-y-6">
        <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 text-2xl">
                    💬
                </div>
                <div class="space-y-1">
                    <h2 class="text-base md:text-lg font-black text-gray-900">Bagaimana Sistem Membaca Curhatan Teks Pegawai?</h2>
                    <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                        Aplikasi HESS menggunakan <strong>Kamus Kata Sentimen Khusus Rumah Sakit</strong> (*Rule & Lexicon NLP*). Tanpa perlu mengirim data ke AI luar dan 100% aman untuk privasi internal.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 space-y-1.5">
                    <div class="font-black text-xs text-emerald-900">1. Kamus Kata Pujian (+1 Poin)</div>
                    <p class="text-xs text-emerald-800 leading-relaxed">
                        Kata-kata seperti: <em>ramah, kompak, solid, cepat, bersih, nyaman, transparan, tepat waktu, kolaborasi baik</em>.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-200 space-y-1.5">
                    <div class="font-black text-xs text-rose-900">2. Kamus Kata Keluhan (-1 Poin)</div>
                    <p class="text-xs text-rose-800 leading-relaxed">
                        Kata-kata seperti: <em>beban kerja, gaji kurang, lembur, stres, lelah, fasilitas rusak, antre lama, tidak adil</em>.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 space-y-1.5">
                    <div class="font-black text-xs text-amber-900">3. Pembalik Arti Kata (Negasi)</div>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Jika ada kata <em>"tidak"</em> atau <em>"kurang"</em> di depan kata baik (misal: <strong>"tidak ramah"</strong> atau <strong>"kurang nyaman"</strong>), sistem pintar membalik nilainya menjadi keluhan!
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- 7. KONTEN TAB 5: KALKULATOR SIMULASI INTERAKTIF --}}
    <div x-show="activeTab === 'calculator'" x-cloak x-transition class="space-y-6">
        <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-xs space-y-6">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-base md:text-lg font-black text-gray-900">Kalkulator Simulasi Skor Interaktif</h3>
                <p class="text-xs text-gray-400">Geser atau ubah angka di bawah ini untuk melihat langsung bagaimana rumus bekerja secara nyata!</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Simulasi 1: Persentase Skor Kategori -->
                <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase text-gray-800">Simulasi Persentase Kategori / Butir</span>
                        <span class="text-xs font-bold text-primary-700 bg-primary-50 px-2 py-0.5 rounded">Skala 1 – 5</span>
                    </div>

                    <!-- Input Jumlah Soal -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Banyaknya Pertanyaan Butir Soal:</label>
                        <select x-model.number="simQuestionsCount" class="w-full h-9 px-3 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800">
                            <option value="12">12 Soal (Contoh: Kepuasan Intrinsik)</option>
                            <option value="6">6 Soal (Contoh: Kepuasan Ekstrinsik)</option>
                            <option value="20">20 Soal (Contoh: MSQ-20 Keseluruhan)</option>
                            <option value="3">3 Soal (Contoh: 1 Kategori Rumah Sakit)</option>
                        </select>
                    </div>

                    <!-- Slider Rata-rata Skor -->
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-gray-700 mb-1">
                            <span>Rata-rata Skor Jawaban Pegawai:</span>
                            <span class="text-sm font-black text-primary-700" x-text="simAverageScore"></span>
                        </div>
                        <input type="range" min="1.0" max="5.0" step="0.1" x-model.number="simAverageScore"
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary-600">
                        <div class="flex justify-between text-[10px] text-gray-400 mt-1">
                            <span>1.0 (Sangat Tidak Puas)</span>
                            <span>3.0 (Cukup)</span>
                            <span>5.0 (Sangat Puas)</span>
                        </div>
                    </div>

                    <!-- Hasil Simulasi -->
                    <div class="p-4 rounded-xl bg-white border border-gray-200 shadow-xs space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Total Poin yang Terkumpul:</span>
                            <span class="font-mono font-bold text-gray-900" x-text="simTotalScore + ' dari ' + simMaxScore"></span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Hasil Persentase Skor (%):</span>
                            <span class="font-black text-xl text-primary-700" x-text="simPercentage + '%'"></span>
                        </div>
                        <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-xs">
                            <span class="text-gray-500">Predikat Kepuasan:</span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-extrabold border" :class="simCategory.color" x-text="simCategory.label"></span>
                        </div>
                    </div>
                </div>

                <!-- Simulasi 2: Net Promoter Score (NPS) -->
                <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase text-gray-800">Simulasi Net Promoter Score (NPS)</span>
                        <span class="text-xs font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded">Skala 0 – 10</span>
                    </div>

                    <!-- Input Total Responden -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Total Responden Pegawai:</label>
                        <input type="number" x-model.number="simTotalNps" min="10" max="1000" class="w-full h-9 px-3 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800">
                    </div>

                    <!-- Slider Promoters -->
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-emerald-800 mb-1">
                            <span>Jumlah Promoter (Nilai 9-10):</span>
                            <span class="text-sm font-black text-emerald-700" x-text="simPromoters + ' orang'"></span>
                        </div>
                        <input type="range" min="0" :max="simTotalNps" x-model.number="simPromoters"
                            class="w-full h-2 bg-emerald-200 rounded-lg appearance-none cursor-pointer accent-emerald-600">
                    </div>

                    <!-- Slider Detractors -->
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-rose-800 mb-1">
                            <span>Jumlah Detractor (Nilai 0-6):</span>
                            <span class="text-sm font-black text-rose-700" x-text="simDetractors + ' orang'"></span>
                        </div>
                        <input type="range" min="0" :max="simTotalNps - simPromoters" x-model.number="simDetractors"
                            class="w-full h-2 bg-rose-200 rounded-lg appearance-none cursor-pointer accent-rose-600">
                    </div>

                    <!-- Hasil NPS -->
                    <div class="p-4 rounded-xl bg-white border border-gray-200 shadow-xs space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Pegawai Passive (Nilai 7-8):</span>
                            <span class="font-mono font-bold text-amber-700" x-text="simPassives + ' orang'"></span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Skor Bersih NPS:</span>
                            <span class="font-black text-2xl" :class="simNpsScore >= 50 ? 'text-emerald-600' : (simNpsScore >= 0 ? 'text-teal-600' : 'text-rose-600')" x-text="(simNpsScore > 0 ? '+' : '') + simNpsScore"></span>
                        </div>
                        <p class="text-[11px] text-gray-500 pt-1 border-t border-gray-100">
                            * NPS di atas <strong>+30</strong> dianggap baik, dan di atas <strong>+50</strong> dianggap sangat luar biasa.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection