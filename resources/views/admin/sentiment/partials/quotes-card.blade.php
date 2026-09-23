{{-- Contoh Kutipan Responden (Sesuai Gambar Mockup) --}}
<div class="bg-white rounded-3xl p-5 md:p-6 border border-gray-200/90 shadow-xs flex flex-col justify-between h-full space-y-4">
    <!-- Header Card -->
    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
        <div>
            <h3 class="text-sm md:text-base font-black text-gray-900">Contoh Kutipan Responden</h3>
            <p class="text-xs text-gray-400">Sampel pernyataan langsung pegawai per kategori</p>
        </div>
        <span class="text-[10px] font-bold bg-gray-100 px-2 py-0.5 rounded-md text-gray-600">Kutipan</span>
    </div>

    <!-- 3 Quote Cards (Positif, Netral, Negatif) -->
    <div class="space-y-3 flex-1 flex flex-col justify-around">
        @php
            $posText = $sampleQuotes['positive']['like_text'] ?? ($sampleQuotes['positive']['improve_text'] ?? 'Saya merasa bangga bisa bekerja di rumah sakit ini karena timnya sangat solid dan saling mendukung.');
            $neuText = $sampleQuotes['neutral']['improve_text'] ?? ($sampleQuotes['neutral']['like_text'] ?? 'Prosedur dan aturan sudah jelas, tinggal konsistensinya yang perlu ditingkatkan.');
            $negText = $sampleQuotes['negative']['improve_text'] ?? ($sampleQuotes['negative']['like_text'] ?? 'Beban kerja sangat tinggi, sementara jumlah tenaga kurang, sehingga sering harus lembur.');
        @endphp

        <!-- Kutipan Positif -->
        <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200/70 flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-2xs">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M8 14s1.5 2 4 2 4-2 4-2" stroke-linecap="round" />
                    <line x1="9" y1="9" x2="9.01" y2="9" stroke-width="2.5" stroke-linecap="round" />
                    <line x1="15" y1="9" x2="15.01" y2="9" stroke-width="2.5" stroke-linecap="round" />
                </svg>
            </div>
            <div class="space-y-1 text-xs">
                <p class="text-gray-800 leading-relaxed font-normal">
                    "{{ Str::limit($posText, 140) }}"
                </p>
                <span class="inline-block text-[11px] font-bold text-emerald-700">
                    (Sentimen: Positif)
                </span>
            </div>
        </div>

        <!-- Kutipan Netral -->
        <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/70 flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-amber-400 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-2xs">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="8" y1="15" x2="16" y2="15" stroke-linecap="round" stroke-width="2.5" />
                    <line x1="9" y1="9" x2="9.01" y2="9" stroke-width="2.5" stroke-linecap="round" />
                    <line x1="15" y1="9" x2="15.01" y2="9" stroke-width="2.5" stroke-linecap="round" />
                </svg>
            </div>
            <div class="space-y-1 text-xs">
                <p class="text-gray-800 leading-relaxed font-normal">
                    "{{ Str::limit($neuText, 140) }}"
                </p>
                <span class="inline-block text-[11px] font-bold text-amber-700">
                    (Sentimen: Netral)
                </span>
            </div>
        </div>

        <!-- Kutipan Negatif -->
        <div class="p-3.5 rounded-2xl bg-rose-50/70 border border-rose-200/70 flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-2xs">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M16 16s-1.5-2-4-2-4 2-4 2" stroke-linecap="round" />
                    <line x1="9" y1="9" x2="9.01" y2="9" stroke-width="2.5" stroke-linecap="round" />
                    <line x1="15" y1="9" x2="15.01" y2="9" stroke-width="2.5" stroke-linecap="round" />
                </svg>
            </div>
            <div class="space-y-1 text-xs">
                <p class="text-gray-800 leading-relaxed font-normal">
                    "{{ Str::limit($negText, 140) }}"
                </p>
                <span class="inline-block text-[11px] font-bold text-rose-700">
                    (Sentimen: Negatif)
                </span>
            </div>
        </div>
    </div>

    <!-- Footer Note -->
    <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400">
        <span>Respon asli anonim</span>
        <a href="#feedback-explorer" class="text-primary-700 font-bold hover:underline">Jelajahi Semua</a>
    </div>
</div>
