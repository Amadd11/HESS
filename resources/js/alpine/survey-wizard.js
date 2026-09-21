export default (questionsData = [], submitUrl = '', csrfToken = '') => ({
    // Navigasi & Langkah
    step: 'profile', // 'profile' | 'questionnaire' | 'overall'
    currentIndex: 0,
    questions: questionsData,
    isSubmitting: false,
    errorMessage: '',

    // Fitur UX Cerdas (Auto-Advance & Navigator Modal)
    autoAdvance: true,
    autoAdvanceTimer: null,
    questionListModalOpen: false,

    // Data Responden
    profile: {
        profession: '',
        unit: '',
        status: '',
        tenure: ''
    },

    // Jawaban Butir Kuesioner (key: question.id, value: 1-5)
    answers: {},

    // Penilaian Keseluruhan & Kualitatif
    overall: {
        overall_score: null,
        nps_score: null,
        like_text: '',
        improve_text: ''
    },

    init() {
        // Bersihkan data draft lama jika ada
        try {
            localStorage.removeItem('hess_survey_draft');
        } catch (e) {}

        // Baca preferensi auto-advance
        const savedAuto = localStorage.getItem('hess_survey_autoadvance');
        if (savedAuto !== null) {
            this.autoAdvance = savedAuto === '1';
        }

        // Daftarkan listener shortcut keyboard untuk desktop
        window.addEventListener('keydown', (e) => this.handleKeyDown(e));
    },

    toggleAutoAdvance() {
        this.autoAdvance = !this.autoAdvance;
        try {
            localStorage.setItem('hess_survey_autoadvance', this.autoAdvance ? '1' : '0');
        } catch (e) {}
    },

    handleKeyDown(e) {
        // Abaikan shortcut jika user sedang mengetik di input / textarea atau modal sedang terbuka
        const target = e.target;
        const isInputField = target && (['INPUT', 'TEXTAREA', 'SELECT'].includes(target.tagName) || target.isContentEditable);
        if (isInputField || this.questionListModalOpen) {
            return;
        }

        if (this.step === 'questionnaire') {
            // Tombol 1 - 5 untuk memilih nilai skala Likert
            if (['1', '2', '3', '4', '5'].includes(e.key)) {
                e.preventDefault();
                this.setAnswer(Number(e.key));
            }
            // Tombol Panah Kanan / Enter untuk lanjut ke soal berikutnya
            else if (e.key === 'ArrowRight' || e.key === 'Enter') {
                if (this.isCurrentAnswered) {
                    e.preventDefault();
                    this.nextQuestion();
                }
            }
            // Tombol Panah Kiri untuk kembali ke soal sebelumnya
            else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                this.prevQuestion();
            }
        }
    },

    get currentQuestion() {
        const q = this.questions[this.currentIndex] || null;
        if (!q) return null;
        if (!q.labels || !Array.isArray(q.labels) || q.labels.length === 0) {
            q.labels = q.scale === 'agreement'
                ? ['Sangat Tidak Setuju', 'Tidak Setuju', 'Ragu-ragu / Netral', 'Setuju', 'Sangat Setuju']
                : ['Sangat Tidak Puas', 'Tidak Puas', 'Cukup Puas', 'Puas', 'Sangat Puas'];
        }
        return q;
    },

    get currentCategoryName() {
        if (!this.currentQuestion) return '';
        if (this.currentQuestion.category_name) {
            return this.currentQuestion.category_name;
        }
        if (typeof this.currentQuestion.category === 'object' && this.currentQuestion.category !== null) {
            return this.currentQuestion.category.name || this.currentQuestion.category.code || '';
        }
        return this.currentQuestion.category || 'Dimensi Kuesioner';
    },

    get isCurrentAnswered() {
        if (!this.currentQuestion) return false;
        return Boolean(this.answers[this.currentQuestion.id]);
    },

    get answeredCount() {
        return Object.keys(this.answers).length;
    },

    isQuestionAnswered(index) {
        const q = this.questions[index];
        return q ? Boolean(this.answers[q.id]) : false;
    },

    get progressPercentage() {
        if (this.questions.length === 0) return 0;
        if (this.step === 'profile') return 5;
        if (this.step === 'overall') return 95;

        // Progress pertanyaan: 5% s/d 90%
        const qProgress = Math.round(((this.currentIndex + 1) / this.questions.length) * 85);
        return 5 + qProgress;
    },

    get progressText() {
        if (this.step === 'profile') return 'Profil Pegawai';
        if (this.step === 'overall') return 'Penilaian Akhir';
        return `Soal ${this.currentIndex + 1} dari ${this.questions.length}`;
    },

    startSurvey() {
        if (!this.profile.profession || !this.profile.unit || !this.profile.status || !this.profile.tenure) {
            this.errorMessage = 'Mohon lengkapi seluruh kolom profil demografi terlebih dahulu.';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }

        this.errorMessage = '';
        this.step = 'questionnaire';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    setAnswer(score) {
        if (!this.currentQuestion) return;
        this.answers[this.currentQuestion.id] = Number(score);

        // Auto-advance dengan jeda halus ~320ms agar efek visual pilihan terpilih sempat terlihat
        if (this.autoAdvance) {
            clearTimeout(this.autoAdvanceTimer);
            this.autoAdvanceTimer = setTimeout(() => {
                this.nextQuestion();
            }, 320);
        }
    },

    jumpToQuestion(index) {
        if (index >= 0 && index < this.questions.length) {
            clearTimeout(this.autoAdvanceTimer);
            this.currentIndex = index;
            this.step = 'questionnaire';
            this.questionListModalOpen = false;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    nextQuestion() {
        clearTimeout(this.autoAdvanceTimer);
        if (!this.isCurrentAnswered) return;

        if (this.currentIndex < this.questions.length - 1) {
            this.currentIndex++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            this.step = 'overall';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    prevQuestion() {
        clearTimeout(this.autoAdvanceTimer);
        if (this.currentIndex > 0) {
            this.currentIndex--;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            this.step = 'profile';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    backToQuestions() {
        this.step = 'questionnaire';
        this.currentIndex = this.questions.length - 1;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    async submitSurvey() {
        // Validasi kelengkapan sebelum submit
        if (!this.overall.overall_score) {
            this.errorMessage = 'Mohon pilih penilaian kepuasan kerja secara keseluruhan.';
            return;
        }

        if (this.overall.nps_score === null || this.overall.nps_score === '') {
            this.errorMessage = 'Mohon pilih nilai rekomendasi rumah sakit (skala 0–10).';
            return;
        }

        // Cek apakah ada soal yang terlewat
        const answeredCount = Object.keys(this.answers).length;
        if (answeredCount < this.questions.length) {
            this.errorMessage = `Masih ada pertanyaan yang terlewat (${answeredCount}/${this.questions.length} terjawab). Silakan periksa kembali melalui menu Navigator Soal.`;
            return;
        }

        this.errorMessage = '';
        this.isSubmitting = true;

        try {
            const payload = {
                _token: csrfToken,
                profile: this.profile,
                answers: this.answers,
                overall: {
                    overall_score: Number(this.overall.overall_score),
                    nps_score: Number(this.overall.nps_score),
                    like_text: this.overall.like_text || null,
                    improve_text: this.overall.improve_text || null,
                }
            };

            const response = await fetch(submitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                window.location.href = data.redirect || '/survey/finish';
            } else {
                this.isSubmitting = false;
                this.errorMessage = data.message || 'Terjadi kesalahan saat menyimpan survei. Silakan periksa isian Anda.';
            }
        } catch (error) {
            console.error('Submit error:', error);
            this.isSubmitting = false;
            this.errorMessage = 'Koneksi terganggu. Silakan periksa jaringan dan coba klik Kirim lagi.';
        }
    }
});
