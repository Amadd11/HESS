@extends('layouts.survey')

@section('content')
@if(!isset($period) || !$period || $questions->isEmpty())
<div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-200 text-center space-y-4 max-w-lg mx-auto my-12">
    <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto text-amber-600 text-2xl font-bold">
        ⏸️
    </div>
    <h2 class="text-xl font-black text-gray-900">Belum Ada Periode Survei Aktif</h2>
    <p class="text-xs text-gray-500 leading-relaxed">
        Saat ini belum ada periode kuesioner survei kepuasan pegawai yang sedang dibuka oleh pihak rumah sakit. Silakan hubungi bagian manajemen atau administrator.
    </p>
</div>
@else
<div x-data="surveyWizard({{ Js::from($questions) }}, '{{ route('survey.submit') }}', '{{ csrf_token() }}')" class="space-y-4">

    <!-- Progress Indicator Card -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/75">
        <div class="flex items-center justify-between text-xs font-semibold text-gray-500 mb-2">
            <span class="flex items-center gap-1.5 text-primary-700 font-bold">
                <span class="inline-block w-2 h-2 rounded-full bg-primary-600 animate-pulse"></span>
                <span x-text="step === 'profile' ? 'Langkah 1: Profil' : (step === 'overall' ? 'Langkah 3: Evaluasi Akhir' : currentCategoryName)"></span>
            </span>
            <span x-text="progressText" class="text-gray-600 font-bold"></span>
        </div>
        <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-primary-600 to-primary-500 rounded-full transition-all duration-300 ease-out"
                :style="'width: ' + progressPercentage + '%'"></div>
        </div>
    </div>

    <!-- Alert Pesan Error jika ada -->
    <div x-show="errorMessage" x-cloak
        class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-xs text-red-700 flex items-start gap-2.5 animate-shake">
        <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span x-text="errorMessage" class="font-medium"></span>
    </div>

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
                        <option value="">-- Pilih Kelompok Tenaga --</option>
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
                        <option value="">-- Pilih Unit Kerja --</option>
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
                        <option value="">-- Pilih Status Kepegawaian --</option>
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
                        <option value="">-- Pilih Lama Bekerja --</option>
                        @foreach($tenures as $tn)
                        <option value="{{ $tn }}">{{ $tn }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tombol Mulai Survei -->
            <div class="pt-2">
                <button type="button" @click="startSurvey()"
                    class="w-full h-12 bg-primary-600 hover:bg-primary-700 active:scale-[0.99] text-white font-bold rounded-xl shadow-md shadow-primary-600/20 transition flex items-center justify-center gap-2 text-sm md:text-base">
                    <span>Mulai Kuesioner</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- STEP 2: KUESIONER KARTU PERTANYAAN (1 SOAL PER LAYAR) -->
    <section x-show="step === 'questionnaire'" x-cloak x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <template x-if="currentQuestion">
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200/75 space-y-6">
                <!-- Meta Info Butir Soal -->
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-primary-100 text-primary-700 tracking-wide uppercase"
                        x-text="currentQuestion.code + ' • ' + currentCategoryName"></span>
                    <span class="text-xs font-semibold text-gray-400"
                        x-text="'Soal ' + (currentIndex + 1) + ' dari ' + questions.length"></span>
                </div>

                <!-- Pernyataan Soal -->
                <div class="min-h-[70px] flex items-center">
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 leading-snug"
                        x-text="currentQuestion.text"></h3>
                </div>

                <!-- Pilihan Jawaban Skala 1 - 5 -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-2.5">
                    <template x-for="(label, idx) in currentQuestion.labels" :key="idx">
                        <label class="cursor-pointer select-none">
                            <input type="radio"
                                :name="'q_' + currentQuestion.id"
                                :value="idx + 1"
                                @change="setAnswer(idx + 1)"
                                :checked="answers[currentQuestion.id] === (idx + 1)"
                                class="sr-only">
                            <div class="flex md:flex-col items-center justify-start md:justify-center p-3 md:p-4 rounded-xl border text-left md:text-center transition-all min-h-[52px] md:min-h-[90px]"
                                :class="answers[currentQuestion.id] === (idx + 1)
                                     ? 'border-primary-600 bg-primary-50 text-primary-800 ring-2 ring-primary-600/30 shadow-sm'
                                     : 'border-gray-200 hover:border-gray-300 bg-white text-gray-700'">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center font-extrabold text-sm mr-3 md:mr-0 md:mb-1.5"
                                    :class="answers[currentQuestion.id] === (idx + 1) ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600'"
                                    x-text="idx + 1"></span>
                                <span class="text-xs md:text-[11px] font-medium leading-tight" x-text="label"></span>
                            </div>
                        </label>
                    </template>
                </div>

                <!-- Indikator Simpan Otomatis -->
                <div class="text-[11px] text-gray-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Jawaban otomatis tersimpan di perangkat ini</span>
                </div>
            </div>
        </template>
    </section>

    <!-- STEP 3: OVERALL SATISFACTION & eNPS -->
    <section x-show="step === 'overall'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200/75 space-y-6">
            <div>
                <h2 class="text-lg md:text-xl font-bold text-gray-900">Penilaian Keseluruhan</h2>
                <p class="text-xs md:text-sm text-gray-500 mt-1">
                    Bagian akhir ini mengukur penilaian kepuasan global serta kesediaan merekomendasikan rumah sakit.
                </p>
            </div>

            <!-- Kepuasan Global (1-5) -->
            <div class="space-y-2.5">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Secara keseluruhan, seberapa puas Anda dengan pekerjaan Anda di rumah sakit ini? <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-2">
                    <template x-for="(label, idx) in ['Sangat Tidak Puas', 'Tidak Puas', 'Netral', 'Puas', 'Sangat Puas']" :key="idx">
                        <label class="cursor-pointer select-none">
                            <input type="radio" name="overall_score" :value="idx + 1" x-model.number="overall.overall_score" class="sr-only">
                            <div class="flex md:flex-col items-center justify-start md:justify-center p-3 rounded-xl border transition-all text-left md:text-center min-h-[48px]"
                                :class="overall.overall_score === (idx + 1)
                                     ? 'border-primary-600 bg-primary-50 text-primary-800 ring-2 ring-primary-600/30'
                                     : 'border-gray-200 hover:border-gray-300 bg-white text-gray-700'">
                                <span class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs mr-2 md:mr-0 md:mb-1"
                                    :class="overall.overall_score === (idx + 1) ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600'"
                                    x-text="idx + 1"></span>
                                <span class="text-xs font-medium" x-text="label"></span>
                            </div>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Rekomendasi Tempat Kerja (eNPS 0 - 10) -->
            <div class="space-y-2.5 pt-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Seberapa besar kemungkinan Anda merekomendasikan rumah sakit ini sebagai tempat kerja yang baik? (Skala 0–10) <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-6 sm:grid-cols-11 gap-1.5">
                    <template x-for="n in 11" :key="n - 1">
                        <label class="cursor-pointer select-none">
                            <input type="radio" name="nps_score" :value="n - 1" x-model.number="overall.nps_score" class="sr-only">
                            <div class="h-11 rounded-xl border flex items-center justify-center font-bold text-sm transition"
                                :class="overall.nps_score === (n - 1)
                                     ? 'border-primary-600 bg-primary-600 text-white shadow-sm'
                                     : 'border-gray-200 hover:border-gray-300 bg-white text-gray-700'"
                                x-text="n - 1"></div>
                        </label>
                    </template>
                </div>
                <div class="flex justify-between text-[11px] text-gray-400 px-0.5">
                    <span>0: Sangat Tidak Mungkin</span>
                    <span>10: Sangat Mungkin</span>
                </div>
            </div>

            <!-- Pertanyaan Terbuka: Perlu Diperbaiki -->
            <div class="space-y-1.5 pt-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Apa satu hal yang paling perlu diperbaiki untuk meningkatkan kepuasan kerja pegawai?
                </label>
                <textarea x-model="overall.improve_text" rows="3"
                    placeholder="Tuliskan masukan singkat Anda (opsional)..."
                    class="w-full p-3.5 rounded-xl border border-gray-300 bg-white text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition"></textarea>
            </div>

            <!-- Pertanyaan Terbuka: Paling Disukai -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Apa hal yang paling Anda sukai dari bekerja di rumah sakit ini?
                </label>
                <textarea x-model="overall.like_text" rows="3"
                    placeholder="Tuliskan hal yang Anda sukai (opsional)..."
                    class="w-full p-3.5 rounded-xl border border-gray-300 bg-white text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition"></textarea>
            </div>
        </div>
    </section>

    <!-- FLOATING BOTTOM NAVIGATION (Mobile-friendly bar di bagian bawah) -->
    <nav x-show="step !== 'profile'" x-cloak
        class="fixed left-0 right-0 bottom-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200/80 px-4 py-3 md:py-4 shadow-lg md:static md:bg-transparent md:border-0 md:shadow-none md:p-0">
        <div class="max-w-2xl mx-auto flex items-center gap-3">
            <!-- Tombol Kembali -->
            <button type="button"
                @click="step === 'overall' ? backToQuestions() : prevQuestion()"
                class="h-12 px-5 rounded-xl border border-gray-200 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-700 font-bold text-sm transition flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="hidden sm:inline">Kembali</span>
            </button>

            <!-- Tombol Lanjut (di Step Kuesioner) -->
            <template x-if="step === 'questionnaire'">
                <button type="button"
                    @click="nextQuestion()"
                    :disabled="!isCurrentAnswered"
                    class="flex-1 h-12 bg-primary-600 hover:bg-primary-700 active:scale-[0.99] text-white font-bold rounded-xl shadow-md shadow-primary-600/20 disabled:opacity-40 disabled:cursor-not-allowed transition flex items-center justify-center gap-2 text-sm md:text-base">
                    <span>Lanjut</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </template>

            <!-- Tombol Submit Akhir (di Step Overall) -->
            <template x-if="step === 'overall'">
                <button type="button"
                    @click="submitSurvey()"
                    :disabled="isSubmitting"
                    class="flex-1 h-12 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white font-bold rounded-xl shadow-md shadow-emerald-600/20 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center gap-2 text-sm md:text-base">
                    <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="isSubmitting ? 'Mengirim Data...' : 'Kirim Survei ✓'"></span>
                </button>
            </template>
        </div>
    </nav>
</div>
@endif
@endsection