<!-- STEP 0: PENGANTAR SURVEI (INTRODUCTION) -->
<section x-show="step === 'intro'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white rounded-3xl p-6 sm:p-8 md:p-10 shadow-sm border border-gray-200/80 space-y-7 relative overflow-hidden">
        
        <!-- Subtle Decorative Ambient Light -->
        <div class="absolute -top-20 -right-20 w-56 h-56 bg-primary-100/40 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-56 h-56 bg-emerald-100/30 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Header: Logo RSUP Dr. Sardjito & Judul Utama -->
        <div class="space-y-4 relative">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-gray-100">
                <div class="bg-white p-2 sm:px-3 sm:py-2 rounded-2xl border border-gray-200/80 shadow-2xs self-start inline-flex items-center">
                    <img src="{{ asset('images/Sardjito Logo.png') }}" alt="Logo RSUP Dr. Sardjito Yogyakarta" class="h-11 sm:h-13 w-auto object-contain">
                </div>

                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-primary-50 text-primary-700 border border-primary-200/80 shadow-2xs self-start sm:self-auto">
                    <span class="w-2 h-2 rounded-full bg-primary-600 animate-pulse"></span>
                    <span>Kuesioner Resmi Kepegawaian RSUP Dr. Sardjito 2026</span>
                </div>
            </div>

            <h2 class="text-xl sm:text-2xl md:text-3xl font-black text-gray-900 tracking-tight leading-snug">
                SURVEY KEPUASAN KERJA PEGAWAI RSUP DR. SARDJITO YOGYAKARTA 2026
            </h2>
        </div>

        <!-- Kartu Surat Sambutan Resmi -->
        <div class="relative bg-gradient-to-br from-gray-50/90 via-primary-50/20 to-gray-50/70 rounded-2xl p-6 md:p-8 border border-gray-200/75 shadow-2xs space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-gray-200/60">
                <div class="w-10 h-10 rounded-xl bg-primary-600 text-white flex items-center justify-center font-bold text-lg shadow-xs shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm md:text-base font-extrabold text-gray-900">Salam Sejahtera,</h3>
                    <p class="text-xs text-gray-500">Pesan Pengantar Manajemen & Tim Evaluasi Mutu</p>
                </div>
            </div>

            <div class="text-xs sm:text-sm text-gray-700 leading-relaxed md:leading-relaxed space-y-3.5">
                <p>
                    <strong>Bapak/Ibu Pegawai RSUP Dr. Sardjito Yogyakarta yang kami hormati</strong>, survei ini bertujuan untuk mengetahui tingkat kepuasan kerja pegawai sebagai bahan evaluasi dan peningkatan kualitas lingkungan kerja.
                </p>
                <p>
                    Mohon kesediaan Bapak/Ibu untuk mengisi kuesioner berikut sesuai dengan kondisi dan pengalaman yang dirasakan.
                </p>
                <p class="font-bold text-gray-900 pt-1">
                    Terima kasih atas waktu dan partisipasi Bapak/Ibu.
                </p>
            </div>
        </div>

        <!-- 3 Highlight Pilar Jaminan & Informasi -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-1">
            <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold shrink-0 border border-emerald-100">
                    🔒
                </div>
                <div>
                    <div class="text-xs font-bold text-gray-900">100% Anonim & Rahasia</div>
                    <div class="text-[11px] text-gray-500 leading-snug mt-0.5">Tanpa identitas/NIP. Hasil diolah murni secara statistik agregat.</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center text-sm font-bold shrink-0 border border-primary-100">
                    ⏱️
                </div>
                <div>
                    <div class="text-xs font-bold text-gray-900">Estimasi Waktu ±5 Menit</div>
                    <div class="text-[11px] text-gray-500 leading-snug mt-0.5">24 butir evaluasi terstruktur, cepat & nyaman di smartphone maupun desktop.</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold shrink-0 border border-amber-100">
                    📊
                </div>
                <div>
                    <div class="text-xs font-bold text-gray-900">8 Dimensi Evaluasi Kerja</div>
                    <div class="text-[11px] text-gray-500 leading-snug mt-0.5">Mencakup lingkungan kerja, kompensasi, kepemimpinan, hingga work-life balance.</div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi / Call to Action -->
        <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-[11px] text-gray-500 text-center sm:text-left">
                Langkah pertama: Pengisian data demografi kepegawaian secara anonim.
            </div>

            <button type="button" @click="goToProfile()"
                class="w-full sm:w-auto min-w-[260px] h-12 px-8 bg-primary-600 hover:bg-primary-700 active:scale-[0.99] text-white font-bold rounded-xl shadow-md shadow-primary-600/25 hover:shadow-primary-600/35 transition flex items-center justify-center gap-2.5 text-sm md:text-base cursor-pointer">
                <span>Mulai Pengisian Profil</span>
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>

    </div>
</section>
