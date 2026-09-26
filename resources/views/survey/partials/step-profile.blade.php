<!-- STEP 1: PROFIL PEGAWAI (DEMOGRAFI ANONIM) -->
<section x-show="step === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200/75 space-y-6">
        <!-- Header -->
        <div class="border-b border-gray-100 pb-4">
            <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight">Profil Pegawai</h2>
            <p class="text-xs md:text-sm text-gray-500 mt-1 leading-relaxed">
                Tidak perlu memasukkan nama atau NIP. Data demografi digunakan murni untuk analisis kelompok kerja secara agregat demi perbaikan berkelanjutan.
            </p>
        </div>

        <!-- Notice Instrumen & Kerahasiaan -->
        <div class="bg-primary-50/80 border border-primary-100 rounded-2xl p-4 text-xs text-primary-950 leading-relaxed flex items-start gap-3.5 shadow-2xs">
            <div class="w-8 h-8 rounded-xl bg-primary-100/90 text-primary-700 flex items-center justify-center shrink-0 text-base font-bold">
                🔒
            </div>
            <div class="space-y-0.5">
                <div class="font-extrabold text-primary-950 text-xs">Jaminan Anonimitas & Kerahasiaan Responden</div>
                <p class="text-primary-900/80 text-[11px] leading-relaxed">
                    Jawaban Anda <strong>bersifat rahasia</strong> dan tidak akan pernah ditampilkan per individu ke atasan atau unit kerja. Hasil survei diolah secara statistik agregat untuk mendukung peningkatan mutu layanan dan kesejahteraan pegawai.
                </p>
            </div>
        </div>

        <!-- SEKSI 1: Penempatan & Kepegawaian di Rumah Sakit -->
        <div class="bg-gray-50/60 rounded-2xl p-5 border border-gray-200/70 space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-gray-200/60">
                <div class="w-6 h-6 rounded-lg bg-primary-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                    A
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Penempatan & Lingkup Kerja di RS</h3>
                    <p class="text-[11px] text-gray-500">Informasi penugasan dan status kepegawaian Anda di rumah sakit.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kelompok Pegawai / Profesi -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                        Kelompok Pegawai <span class="text-red-500">*</span>
                    </label>
                    <select x-model="profile.profession"
                        class="w-full h-11 px-3.5 rounded-xl border border-gray-300 bg-white text-xs md:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                        <option value="">Pilih Kelompok Pegawai...</option>
                        @foreach($professions as $prof)
                        <option value="{{ $prof }}">{{ $prof }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Direktorat -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                        Direktorat <span class="text-red-500">*</span>
                    </label>
                    <select x-model="profile.directorate"
                        @change="profile.unit = ''"
                        class="w-full h-11 px-3.5 rounded-xl border border-gray-300 bg-white text-xs md:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                        <option value="">Pilih Direktorat...</option>
                        @foreach($directorates as $dir)
                        <option value="{{ $dir }}">{{ $dir }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Instalasi / Satuan Kerja -->
                <div class="md:col-span-2"
                    x-data="surveyUnitSelector({{ Js::from($directorate_units ?? []) }})">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                        Instalasi / Satuan Kerja <span class="text-red-500">*</span>
                    </label>
                    <select x-ref="surveyUnitSelect" x-model="profile.unit"
                        class="w-full h-11 px-3.5 rounded-xl border border-gray-300 bg-white text-xs md:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                        <option value="">Pilih Instalasi / Satuan Kerja...</option>
                        @if(!empty($directorate_units))
                        @foreach($directorate_units as $dirName => $dirUnits)
                        <optgroup label="{{ $dirName }}">
                            @foreach($dirUnits as $u)
                            <option value="{{ $u }}">{{ $u }}</option>
                            @endforeach
                        </optgroup>
                        @endforeach
                        @else
                        @foreach($units as $u)
                        <option value="{{ $u }}">{{ $u }}</option>
                        @endforeach
                        @endif
                    </select>
                    <p x-show="profile.directorate" class="text-[11px] text-gray-500 mt-1">
                        Menampilkan Satuan Kerja di bawah <strong class="text-primary-700" x-text="profile.directorate"></strong>.
                    </p>
                </div>

                <!-- Status Kepegawaian -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                        Status Kepegawaian <span class="text-red-500">*</span>
                    </label>
                    <select x-model="profile.status"
                        class="w-full h-11 px-3.5 rounded-xl border border-gray-300 bg-white text-xs md:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                        <option value="">Pilih Status Kepegawaian...</option>
                        @foreach($statuses as $st)
                        <option value="{{ $st }}">{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Lama Bekerja -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                        Lama Bekerja di RS <span class="text-red-500">*</span>
                    </label>
                    <select x-model="profile.tenure"
                        class="w-full h-11 px-3.5 rounded-xl border border-gray-300 bg-white text-xs md:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                        <option value="">Pilih Lama Bekerja...</option>
                        @foreach($tenures as $tn)
                        <option value="{{ $tn }}">{{ $tn }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- SEKSI 2: Karakteristik Demografi Pegawai -->
        <div class="bg-gray-50/60 rounded-2xl p-5 border border-gray-200/70 space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-gray-200/60">
                <div class="w-6 h-6 rounded-lg bg-teal-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                    B
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Karakteristik Demografi Responden</h3>
                    <p class="text-[11px] text-gray-500">Digunakan murni untuk analisis kebutuhan program kesejahteraan karyawan secara menyeluruh.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Usia -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                        Rentang Usia <span class="text-red-500">*</span>
                    </label>
                    <select x-model="profile.age"
                        class="w-full h-11 px-3.5 rounded-xl border border-gray-300 bg-white text-xs md:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                        <option value="">Pilih Rentang Usia...</option>
                        @foreach($ages as $ag)
                        <option value="{{ $ag }}">{{ $ag }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select x-model="profile.gender"
                        class="w-full h-11 px-3.5 rounded-xl border border-gray-300 bg-white text-xs md:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                        <option value="">Pilih Jenis Kelamin...</option>
                        @foreach($genders as $gen)
                        <option value="{{ $gen }}">{{ $gen }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Latar Belakang Pendidikan -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                        Latar Belakang Pendidikan <span class="text-red-500">*</span>
                    </label>
                    <select x-model="profile.education"
                        class="w-full h-11 px-3.5 rounded-xl border border-gray-300 bg-white text-xs md:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                        <option value="">Pilih Pendidikan Terakhir...</option>
                        @foreach($educations as $edu)
                        <option value="{{ $edu }}">{{ $edu }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jumlah Pendapatan -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                        Jumlah Pendapatan Bulanan <span class="text-red-500">*</span>
                    </label>
                    <select x-model="profile.income"
                        class="w-full h-11 px-3.5 rounded-xl border border-gray-300 bg-white text-xs md:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                        <option value="">Pilih Rentang Pendapatan...</option>
                        @foreach($incomes as $inc)
                        <option value="{{ $inc }}">{{ $inc }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Tombol Mulai Survei & Navigasi -->
        <div class="pt-3 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <button type="button" @click="goToIntro()"
                class="w-full sm:w-auto h-12 px-6 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 active:bg-gray-100 text-gray-700 font-bold text-sm transition flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali ke Pengantar</span>
            </button>

            <button type="button" @click="startSurvey()"
                class="w-full sm:w-auto sm:min-w-[280px] h-12 px-8 bg-primary-600 hover:bg-primary-700 active:scale-[0.99] text-white font-bold rounded-xl shadow-md shadow-primary-600/20 transition flex items-center justify-center gap-2 text-sm md:text-base cursor-pointer">
                <span>Mulai Kuesioner</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
        <p class="text-center sm:text-right text-[11px] text-gray-400">Seluruh kolom demografi wajib dipilih sebelum melanjutkan ke butir kuesioner.</p>
    </div>
</section>

<script>
window.surveyUnitSelector = function(directorateUnits) {
    return {
        directorateUnits: directorateUnits || {},
        updateUnits() {
            const select = this.$refs.surveyUnitSelect;
            if (!select) return;
            const currentVal = this.profile ? this.profile.unit : '';
            select.innerHTML = '';

            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = 'Pilih Instalasi / Satuan Kerja...';
            select.appendChild(defaultOpt);

            if (this.profile && this.profile.directorate && this.directorateUnits[this.profile.directorate]) {
                this.directorateUnits[this.profile.directorate].forEach(u => {
                    const opt = document.createElement('option');
                    opt.value = u;
                    opt.textContent = u;
                    if (u === currentVal) opt.selected = true;
                    select.appendChild(opt);
                });
            } else {
                Object.entries(this.directorateUnits).forEach(([dir, units]) => {
                    const grp = document.createElement('optgroup');
                    grp.label = dir;
                    units.forEach(u => {
                        const opt = document.createElement('option');
                        opt.value = u;
                        opt.textContent = u;
                        if (u === currentVal) opt.selected = true;
                        grp.appendChild(opt);
                    });
                    select.appendChild(grp);
                });
            }
        },
        init() {
            this.$watch('profile.directorate', () => {
                if (this.profile) this.profile.unit = '';
                this.updateUnits();
            });
            this.$nextTick(() => this.updateUnits());
        }
    };
};
</script>