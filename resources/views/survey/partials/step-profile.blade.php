<!-- STEP 1: PROFIL PEGAWAI (DEMOGRAFI ANONIM) -->
<section x-show="step === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200/75 space-y-6">
        <div class="border-b border-gray-100 pb-4">
            <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">Profil Pegawai</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1 leading-relaxed">
                Tidak perlu memasukkan nama atau NIP. Data demografi digunakan murni untuk analisis kelompok kerja secara agregat.
            </p>
        </div>

        <!-- Notice Kerahasiaan -->
        <div class="bg-primary-50/70 border border-primary-100/80 rounded-xl p-4 text-xs text-primary-950/90 leading-relaxed flex items-start gap-3">
            <span class="text-lg leading-none shrink-0">🔒</span>
            <div>
                <strong class="font-bold text-primary-950">Jaminan Kerahasiaan Penuh:</strong> Jawaban individual tidak akan pernah ditampilkan kepada atasan maupun kepala unit. Hasil evaluasi disajikan murni dalam bentuk agregat statistik.
            </div>
        </div>

        <!-- Form Demografi (2x2 Grid di Desktop) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <!-- Kelompok Profesi -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                    Kelompok Tenaga / Profesi <span class="text-red-500">*</span>
                </label>
                <select x-model="profile.profession"
                    class="w-full h-12 px-3.5 rounded-xl border border-gray-300 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                    <option value="">Pilih Kelompok Tenaga</option>
                    @foreach($professions as $prof)
                    <option value="{{ $prof }}">{{ $prof }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Satuan Kerja -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                    Satuan Kerja <span class="text-red-500">*</span>
                </label>
                <select x-model="profile.unit"
                    class="w-full h-12 px-3.5 rounded-xl border border-gray-300 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                    <option value="">Pilih Satuan Kerja</option>
                    @foreach($units as $u)
                    <option value="{{ $u }}">{{ $u }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Kepegawaian -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                    Status Kepegawaian <span class="text-red-500">*</span>
                </label>
                <select x-model="profile.status"
                    class="w-full h-12 px-3.5 rounded-xl border border-gray-300 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                    <option value="">Pilih Status Kepegawaian</option>
                    @foreach($statuses as $st)
                    <option value="{{ $st }}">{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Lama Bekerja -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                    Lama Bekerja di Rumah Sakit Ini <span class="text-red-500">*</span>
                </label>
                <select x-model="profile.tenure"
                    class="w-full h-12 px-3.5 rounded-xl border border-gray-300 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                    <option value="">Pilih Lama Bekerja</option>
                    @foreach($tenures as $tn)
                    <option value="{{ $tn }}">{{ $tn }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tombol Mulai Survei -->
        <div class="pt-4 flex justify-center">
            <button type="button" @click="startSurvey()"
                class="w-full md:w-auto md:min-w-[320px] h-12 px-8 bg-primary-600 hover:bg-primary-700 active:scale-[0.99] text-white font-bold rounded-xl shadow-md shadow-primary-600/20 transition flex items-center justify-center gap-2 text-sm md:text-base cursor-pointer">
                <span>Mulai Kuesioner</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </div>
</section>
