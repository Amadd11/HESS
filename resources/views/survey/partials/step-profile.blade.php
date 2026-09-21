<!-- STEP 1: PROFIL PEGAWAI (DEMOGRAFI ANONIM) -->
<section x-show="step === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200/75 space-y-6">
        <div>
            <h2 class="text-lg md:text-xl font-bold text-gray-900">Profil Pegawai</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1">
                Tidak perlu memasukkan nama atau NIP. Data demografi digunakan murni untuk analisis kelompok kerja secara agregat.
            </p>
        </div>

        <!-- Notice Kerahasiaan -->
        <div class="bg-primary-50/70 border border-primary-100 rounded-xl p-3.5 text-xs text-primary-900/90 leading-relaxed flex items-start gap-2.5">
            <span class="text-base leading-none">🔒</span>
            <div>
                <strong>Jaminan Kerahasiaan:</strong> Jawaban individual tidak akan pernah ditampilkan kepada atasan maupun kepala unit. Hasil evaluasi disajikan dalam bentuk agregat rata-rata.
            </div>
        </div>

        <!-- Form Demografi -->
        <div class="space-y-4">
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

            <!-- Unit Kerja -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                    Unit Kerja / Instalasi <span class="text-red-500">*</span>
                </label>
                <select x-model="profile.unit"
                    class="w-full h-12 px-3.5 rounded-xl border border-gray-300 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                    <option value="">Pilih Unit Kerja</option>
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
        <div class="pt-2">
            <button type="button" @click="startSurvey()"
                class="w-full h-12 bg-primary-600 hover:bg-primary-700 active:scale-[0.99] text-white font-bold rounded-xl shadow-md shadow-primary-600/20 transition flex items-center justify-center gap-2 text-sm md:text-base cursor-pointer">
                <span>Mulai Kuesioner</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </div>
</section>
