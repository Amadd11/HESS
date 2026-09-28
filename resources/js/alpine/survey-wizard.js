export default (questionsData = [], submitUrl = '', csrfToken = '') => ({
    // Navigasi & Langkah: 'intro' | 'profile' | 'questionnaire' | 'feedback' | 'overall'
    step: 'intro',
    currentIndex: 0,
    questions: questionsData,
    isSubmitting: false,
    errorMessage: '',

    // Fitur UX Cerdas (Auto-Advance & Navigator Modal)
    autoAdvance: true,
    autoAdvanceTimer: null,
    questionListModalOpen: false,

    // Data Responden (Demografi RSUP Dr. Sardjito)
    profile: {
        profession: '',
        directorate: '',
        unit: '',
        status: '',
        tenure: '',
        age: '',
        gender: '',
        education: '',
        income: ''
    },

    // Jawaban Butir Kuesioner (key: question.id, value: 1-4)
    answers: {},

    // Umpan Balik Kualitatif per Unsur (key: aspectName, value: { reason: '', suggestion: '' })
    feedback: {},

    // Penilaian Keseluruhan (Opsional Tambahan)
    overall: {
        nps_score: null,
        like_text: '',
        improve_text: ''
    },

    init() {
        // Inisialisasi struktur umpan balik untuk seluruh unsur
        this.aspectList.forEach((aspect) => {
            if (!this.feedback[aspect]) {
                this.feedback[aspect] = { reason: '', suggestion: '' };
            }
        });

        // Muat draft lokal bila ada
        try {
            const savedDraft = localStorage.getItem('hess_survey_draft');
            if (savedDraft) {
                const parsed = JSON.parse(savedDraft);
                if (parsed.profile) this.profile = { ...this.profile, ...parsed.profile };
                if (parsed.answers) this.answers = { ...parsed.answers };
                if (parsed.feedback) this.feedback = { ...this.feedback, ...parsed.feedback };
                if (parsed.overall) this.overall = { ...this.overall, ...parsed.overall };
            }
        } catch (e) {}

        // Baca preferensi auto-advance
        const savedAuto = localStorage.getItem('hess_survey_autoadvance');
        if (savedAuto !== null) {
            this.autoAdvance = savedAuto === '1';
        }

        // Simpan otomatis bila ada perubahan input form
        if (typeof this.$watch === 'function') {
            this.$watch('profile', () => this.persistDraft(), { deep: true });
            this.$watch('feedback', () => this.persistDraft(), { deep: true });
            this.$watch('overall', () => this.persistDraft(), { deep: true });
        }

        // Daftarkan listener shortcut keyboard untuk desktop
        window.addEventListener('keydown', (e) => this.handleKeyDown(e));
    },

    persistDraft() {
        try {
            localStorage.setItem('hess_survey_draft', JSON.stringify({
                profile: this.profile,
                answers: this.answers,
                feedback: this.feedback,
                overall: this.overall
            }));
        } catch (e) {}
    },

    toggleAutoAdvance() {
        this.autoAdvance = !this.autoAdvance;
        try {
            localStorage.setItem('hess_survey_autoadvance', this.autoAdvance ? '1' : '0');
        } catch (e) {}
    },

    handleKeyDown(e) {
        const target = e.target;
        const isInputField = target && (['INPUT', 'TEXTAREA', 'SELECT'].includes(target.tagName) || target.isContentEditable);
        if (isInputField || this.questionListModalOpen) {
            return;
        }

        if (this.step === 'questionnaire') {
            // Tombol 1 - 4 untuk memilih nilai skala Likert 4 Poin
            if (['1', '2', '3', '4'].includes(e.key)) {
                e.preventDefault();
                this.setAnswer(Number(e.key));
            } else if (e.key === 'ArrowRight' || e.key === 'Enter') {
                if (this.isCurrentAnswered) {
                    e.preventDefault();
                    this.nextQuestion();
                }
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                this.prevStep();
            }
        } else if (this.step === 'feedback') {
            if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                this.nextFeedback();
            }
        }
    },

    // Daftar nama seluruh unsur instrumen (dinamis dari butir pertanyaan yang aktif)
    get aspectList() {
        const list = [];
        this.questions.forEach((q) => {
            const name = q?.category_name || (typeof q?.category === 'object' ? q.category?.name : null);
            if (name && !list.includes(name)) {
                list.push(name);
            }
        });
        return list.length ? list : [
            'Lingkungan Kerja',
            'Hubungan dengan Atasan',
            'Penghargaan dan Pengukuran Kerja',
            'Kesempatan Pengembangan Karir',
            'Gaji dan Kompensasi',
            'Keseimbangan Kerja dan Kehidupan / Work Life Balance',
            'Komunikasi dalam Rumah Sakit',
            'Budaya Rumah Sakit'
        ];
    },

    getQuestionsForAspect(aIdx) {
        const aspectName = this.aspectList[aIdx];
        if (!aspectName) return [];
        return this.questions
            .map((q, idx) => ({ ...q, originalIndex: idx }))
            .filter(q => {
                const name = q?.category_name || (typeof q?.category === 'object' ? q.category?.name : null);
                return name === aspectName;
            });
    },

    get currentAspectIndex() {
        if (!this.currentQuestion) return 0;
        const currentName = this.currentCategoryName;
        const idx = this.aspectList.indexOf(currentName);
        return idx !== -1 ? idx : 0;
    },

    get currentAspectTitle() {
        return this.aspectList[this.currentAspectIndex] || 'Unsur Kuesioner';
    },

    get currentFeedback() {
        const title = this.currentAspectTitle;
        if (!this.feedback[title]) {
            this.feedback[title] = { reason: '', suggestion: '' };
        }
        return this.feedback[title];
    },

    get currentQuestion() {
        const q = this.questions[this.currentIndex] || null;
        if (!q) return null;
        if (!q.labels || !Array.isArray(q.labels) || q.labels.length === 0) {
            q.labels = ['Sangat Tidak Setuju', 'Tidak Setuju', 'Setuju', 'Sangat Setuju'];
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
        return this.currentQuestion.category || 'Unsur Kuesioner';
    },

    get isCurrentAnswered() {
        if (!this.currentQuestion) return false;
        return Boolean(this.answers[this.currentQuestion.id]);
    },

    get answeredCount() {
        return this.questions.filter(q => Boolean(this.answers[q.id])).length;
    },

    isQuestionAnswered(index) {
        const q = this.questions[index];
        return q ? Boolean(this.answers[q.id]) : false;
    },

    getAspectScoreCount(aIdx) {
        const qs = this.getQuestionsForAspect(aIdx);
        return qs.filter(q => Boolean(this.answers[q.id])).length;
    },

    isAspectAnswered(aIdx) {
        const qs = this.getQuestionsForAspect(aIdx);
        return qs.length > 0 && qs.every(q => Boolean(this.answers[q.id]));
    },

    isFeedbackFilled(aspectTitle) {
        const f = this.feedback[aspectTitle];
        return Boolean(f && f.reason && f.reason.trim() !== '' && f.suggestion && f.suggestion.trim() !== '');
    },

    get progressPercentage() {
        if (this.questions.length === 0) return 0;
        if (this.step === 'intro') return 0;
        if (this.step === 'profile') return 5;
        if (this.step === 'overall') return 100;

        const totalSteps = this.questions.length + this.aspectList.length;
        const questionProgress = this.answeredCount;
        const feedbackProgress = this.aspectList.filter(a => this.isFeedbackFilled(a)).length;
        const totalDone = questionProgress + feedbackProgress;
        return Math.min(100, Math.round(5 + (totalDone / totalSteps) * 90));
    },


    get isProfileComplete() {
        return Boolean(
            this.profile.profession &&
            this.profile.directorate &&
            this.profile.unit &&
            this.profile.status &&
            this.profile.tenure &&
            this.profile.age &&
            this.profile.gender &&
            this.profile.education &&
            this.profile.income
        );
    },

    get hasDraft() {
        return this.answeredCount > 0 || this.isProfileComplete;
    },

    get firstUnansweredIndex() {
        if (!this.questions || this.questions.length === 0) return -1;
        for (let i = 0; i < this.questions.length; i++) {
            const q = this.questions[i];
            if (q && !this.answers[q.id]) {
                return i;
            }
        }
        return -1;
    },

    get resumeButtonText() {
        if (!this.isProfileComplete) {
            return 'Lengkapi Data Profil';
        }
        const nextIdx = this.firstUnansweredIndex;
        if (nextIdx !== -1) {
            return `Lanjutkan ke Soal No. ${nextIdx + 1}`;
        }
        const missingAspectIdx = this.aspectList.findIndex(aspect => !this.isFeedbackFilled(aspect));
        if (missingAspectIdx !== -1) {
            return 'Lanjutkan ke Umpan Balik';
        }
        return 'Lanjutkan ke Konfirmasi Akhir';
    },

    resumeSurvey() {
        this.errorMessage = '';
        if (!this.isProfileComplete) {
            this.goToProfile();
            return;
        }

        const nextIdx = this.firstUnansweredIndex;
        if (nextIdx !== -1) {
            this.currentIndex = nextIdx;
            this.step = 'questionnaire';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }

        const missingAspectIdx = this.aspectList.findIndex(aspect => !this.isFeedbackFilled(aspect));
        if (missingAspectIdx !== -1) {
            this.jumpToFeedback(missingAspectIdx);
            return;
        }

        this.step = 'overall';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    resetDraft() {
        if (confirm('Apakah Anda yakin ingin menghapus data pengisian sebelumnya dan mengulang dari awal?')) {
            try {
                localStorage.removeItem('hess_survey_draft');
            } catch (e) {}

            this.profile = {
                profession: '',
                directorate: '',
                unit: '',
                status: '',
                tenure: '',
                age: '',
                gender: '',
                education: '',
                income: ''
            };
            this.answers = {};
            this.feedback = {};
            this.aspectList.forEach((aspect) => {
                this.feedback[aspect] = { reason: '', suggestion: '' };
            });
            this.overall = {
                nps_score: null,
                like_text: '',
                improve_text: ''
            };
            this.currentIndex = 0;
            this.step = 'intro';
            this.errorMessage = '';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    goToProfile() {
        this.errorMessage = '';
        this.step = 'profile';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    goToIntro() {
        this.errorMessage = '';
        this.step = 'intro';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    startSurvey() {
        if (!this.isProfileComplete) {
            this.errorMessage = 'Mohon lengkapi seluruh kolom profil demografi terlebih dahulu.';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }

        this.errorMessage = '';
        this.step = 'questionnaire';
        if (this.currentIndex === 0 && this.firstUnansweredIndex > 0) {
            this.currentIndex = this.firstUnansweredIndex;
        }
        this.persistDraft();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    setAnswer(score) {
        if (!this.currentQuestion) return;
        this.answers[this.currentQuestion.id] = Number(score);
        this.persistDraft();

        if (this.autoAdvance) {
            clearTimeout(this.autoAdvanceTimer);
            this.autoAdvanceTimer = setTimeout(() => {
                this.nextQuestion();
            }, 300);
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

    jumpToFeedback(aIdx) {
        if (aIdx >= 0 && aIdx < this.aspectList.length) {
            clearTimeout(this.autoAdvanceTimer);
            const qs = this.getQuestionsForAspect(aIdx);
            if (qs.length > 0) {
                this.currentIndex = qs[qs.length - 1].originalIndex;
            }
            this.step = 'feedback';
            this.questionListModalOpen = false;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    editAspect(aIdx) {
        this.jumpToFeedback(aIdx);
    },

    nextQuestion() {
        clearTimeout(this.autoAdvanceTimer);
        if (!this.isCurrentAnswered) return;

        const currentCat = this.currentCategoryName;
        const nextQ = this.questions[this.currentIndex + 1];
        const nextCat = nextQ ? (nextQ.category_name || (typeof nextQ.category === 'object' ? nextQ.category?.name : null)) : null;

        // Jika butir saat ini adalah butir terakhir dalam dimensinya, arahkan ke umpan balik kualitatif dimensi ini
        if (!nextQ || nextCat !== currentCat) {
            this.step = 'feedback';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            this.currentIndex++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    nextFeedback() {
        const f = this.currentFeedback;
        const reason = (f?.reason || '').trim();
        const suggestion = (f?.suggestion || '').trim();

        if (!reason || !suggestion) {
            this.errorMessage = 'Mohon isi alasan penilaian dan saran perbaikan untuk unsur ini sebelum melanjutkan.';
            return;
        }

        this.errorMessage = '';
        this.persistDraft();

        const nextAspectIdx = this.currentAspectIndex + 1;
        if (nextAspectIdx < this.aspectList.length) {
            const nextQs = this.getQuestionsForAspect(nextAspectIdx);
            if (nextQs.length > 0) {
                this.currentIndex = nextQs[0].originalIndex;
                this.step = 'questionnaire';
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }
        }

        this.step = 'overall';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    prevStep() {
        clearTimeout(this.autoAdvanceTimer);
        this.errorMessage = '';

        if (this.step === 'overall') {
            // Kembali ke umpan balik dimensi terakhir
            this.jumpToFeedback(this.aspectList.length - 1);
        } else if (this.step === 'feedback') {
            // Kembali ke pertanyaan terakhir di dimensi saat ini
            this.step = 'questionnaire';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else if (this.step === 'questionnaire') {
            if (this.currentIndex > 0) {
                const currentCat = this.currentCategoryName;
                const prevQ = this.questions[this.currentIndex - 1];
                const prevCat = prevQ ? (prevQ.category_name || (typeof prevQ.category === 'object' ? prevQ.category?.name : null)) : null;

                // Jika mundur melewati batas dimensi, buka kembali umpan balik dimensi sebelumnya
                if (prevCat !== currentCat) {
                    this.currentIndex--;
                    this.step = 'feedback';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    this.currentIndex--;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } else {
                this.step = 'profile';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        } else if (this.step === 'profile') {
            this.step = 'intro';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },


    async submitSurvey() {
        // Cek kelengkapan seluruh pertanyaan aktif
        const unanswered = this.questions.filter(q => !this.answers[q.id]);
        if (unanswered.length > 0) {
            this.errorMessage = `Masih ada ${unanswered.length} pertanyaan yang belum dijawab (${this.answeredCount}/${this.questions.length} terjawab). Silakan periksa kembali melalui menu Navigator.`;
            return;
        }

        // Cek kelengkapan seluruh 8 x 2 isian terbuka
        const missingFeedback = this.aspectList.filter(aspect => !this.isFeedbackFilled(aspect));
        if (missingFeedback.length > 0) {
            this.errorMessage = `Mohon lengkapi alasan dan saran perbaikan untuk unsur: "${missingFeedback[0]}".`;
            return;
        }

        this.errorMessage = '';
        this.isSubmitting = true;

        try {
            const payload = {
                _token: csrfToken,
                profile: this.profile,
                answers: this.answers,
                feedback: this.feedback,
                overall: {
                    nps_score: this.overall.nps_score !== null && this.overall.nps_score !== '' ? Number(this.overall.nps_score) : null,
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
                try {
                    localStorage.removeItem('hess_survey_draft');
                } catch (e) {}
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
