<!-- MODAL SHARE LINK & QR CODE -->
<x-modal show="shareModalOpen" title="Bagikan Tautan & QR Code Survei" maxWidth="max-w-md">
    <div class="space-y-5 text-xs">
        <!-- Period Badge Info -->
        <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-200">
            <span class="text-[10px] font-bold uppercase text-gray-400 tracking-wider">Periode Survei Terpilih</span>
            <div class="font-bold text-gray-900 text-sm mt-0.5" x-text="sharePeriod.name"></div>
            <div class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Siap disebarkan melalui WhatsApp, Email, atau Poster QR</span>
            </div>
        </div>

        <!-- QR Code Visual Display Card -->
        <div class="flex flex-col items-center justify-center p-6 bg-linear-to-b from-primary-50/50 to-white rounded-2xl border border-primary-100 shadow-xs text-center">
            <div class="p-3 bg-white rounded-2xl shadow-sm border border-gray-200 mb-3">
                <img :src="sharePeriod.qrUrl" alt="QR Code Survei HESS" class="w-48 h-48 object-contain rounded-lg">
            </div>
            <p class="font-bold text-gray-800 text-xs">Pindai dengan Kamera HP untuk Mulai Mengisi</p>
            <p class="text-[11px] text-gray-400 mt-0.5">Pegawai langsung diarahkan ke form survei tanpa perlu login</p>
        </div>

        <!-- Copy URL Input Box -->
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5">Tautan Langsung Formulir Survei</label>
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input type="text" readonly :value="sharePeriod.url"
                           class="w-full pl-3 pr-3 py-2 text-xs bg-gray-100 border border-gray-200 rounded-xl font-mono text-gray-700 select-all focus:outline-none focus:ring-1 focus:ring-primary-500">
                </div>
                <button type="button" @click="copyLink()"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 cursor-pointer"
                        :class="copied ? 'bg-emerald-600 text-white' : 'bg-primary-700 hover:bg-primary-800 text-white shadow-xs'">
                    <template x-if="!copied">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span>Salin</span>
                        </span>
                    </template>
                    <template x-if="copied">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Tersalin!</span>
                        </span>
                    </template>
                </button>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between gap-2 pt-4 border-t border-gray-100">
            <a :href="sharePeriod.url" target="_blank"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-600 hover:text-primary-700 py-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Buka Survei</span>
            </a>

            <div class="flex items-center gap-2">
                <x-button type="button" @click="shareModalOpen = false" variant="secondary">
                    Tutup
                </x-button>
                <a :href="sharePeriod.qrUrl" download="qr-survei-hess.png" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-white bg-primary-700 hover:bg-primary-800 rounded-xl transition shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh QR</span>
                </a>
            </div>
        </div>
    </div>
</x-modal>
