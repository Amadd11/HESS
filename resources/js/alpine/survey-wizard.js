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
            }
        } catch (e) {}

        // Baca preferensi auto-advance
        const savedAuto = localStorage.getItem('hess_survey_autoadvance');
        if (savedAuto !== null) {
            this.autoAdvance = savedAuto === '1';
        }

        // Daftarkan listener shortcut keyboard untuk desktop
        window.addEventListener('keydown', (e) => this.handleKeyDown(e));
    },

    persistDraft() {
        try {
            localStorage.setItem('hess_survey_draft', JSON.stringify({
                profile: this.profile,
                answers: this.answers,
                feedback: this.feedback
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

    // Daftar nama 8 unsur instrumen
    get aspectList() {
        const list = [];
        for (let i = 0; i < this.questions.length; i += 3) {
            const q = this.questions[i];
            const name = q?.category_name || (typeof q?.category === 'object' ? q.category?.name : null) || `Unsur ${list.length + 1}`;
            if (!list.includes(name)) {
                list.push(name);
            }
        }
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

    get currentAspectIndex() {
        return Math.min(Math.floor(this.currentIndex / 3), this.aspectList.length - 1);
    },

    get currentAspectTitle() {
        return this.aspectList[this.currentAspectIndex] || 'Dimensi Kuesioner';
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

    getAspectScoreCount(aIdx) {
        let count = 0;
        for (let i = 0; i < 3; i++) {
            const qIdx = aIdx * 3 + i;
            if (this.isQuestionAnswered(qIdx)) {
                count++;
            }
        }
        return count;
    },

    isAspectAnswered(aIdx) {
        return this.getAspectScoreCount(aIdx) === 3;
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

        // Total 24 soal tertutup + 8 feedback aspek = 32 unit aktivitas
        const questionProgress = this.answeredCount;
        const feedbackProgress = this.aspectList.filter(a => this.isFeedbackFilled(a)).length;
        const totalDone = questionProgress + feedbackProgress;
        return Math.min(100, Math.round(5 + (totalDone / 32) * 90));
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
        if (!this.profile.profession || !this.profile.directorate || !this.profile.unit || !this.profile.status || !this.profile.tenure || !this.profile.age || !this.profile.gender || !this.profile.education || !this.profile.income) {
            this.errorMessage = 'Mohon lengkapi seluruh kolom profil demografi terlebih dahulu.';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }

        this.errorMessage = '';
        this.step = 'questionnaire';
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
            this.currentIndex = (aIdx * 3) + 2;
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

        // Setelah 3 pertanyaan pada unsur yang sama selesai, arahkan ke umpan balik kualitatif
        if (this.currentIndex % 3 === 2) {
            this.step = 'feedback';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else if (this.currentIndex < this.questions.length - 1) {
            this.currentIndex++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            this.step = 'overall';
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

        if (this.currentAspectIndex < 7) {
            this.currentIndex = (this.currentAspectIndex + 1) * 3;
            this.step = 'questionnaire';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            this.step = 'overall';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    prevStep() {
        clearTimeout(this.autoAdvanceTimer);
        this.errorMessage = '';

        if (this.step === 'overall') {
            // Kembali ke umpan balik unsur ke-8
            this.currentIndex = 23;
            this.step = 'feedback';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else if (this.step === 'feedback') {
            // Kembali ke soal ke-3 dari unsur aktif
            this.currentIndex = (this.currentAspectIndex * 3) + 2;
            this.step = 'questionnaire';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else if (this.step === 'questionnaire') {
            if (this.currentIndex % 3 === 0 && this.currentIndex > 0) {
                // Kembali ke umpan balik unsur sebelumnya
                this.currentIndex = this.currentIndex - 1;
                this.step = 'feedback';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (this.currentIndex > 0) {
                this.currentIndex--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
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
        // Cek kelengkapan seluruh 24 soal tertutup
        const answeredCount = Object.keys(this.answers).length;
        if (answeredCount < this.questions.length) {
            this.errorMessage = `Masih ada pertanyaan yang belum dijawab (${answeredCount}/${this.questions.length} terjawab). Silakan periksa kembali melalui menu Navigator.`;
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
