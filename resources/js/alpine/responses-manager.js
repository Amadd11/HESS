export default () => ({
    detailModalOpen: false,
    loading: false,
    selectedResponse: null,
    selectedAnswers: [],
    activeTab: 'summary',

    async openDetail(id) {
        this.loading = true;
        this.detailModalOpen = true;
        this.activeTab = 'summary';
        this.selectedResponse = null;
        this.selectedAnswers = [];

        try {
            const res = await fetch(`/admin/responses/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!res.ok) {
                throw new Error('Gagal memuat detail data respon.');
            }

            const data = await res.json();
            this.selectedResponse = data.response;
            this.selectedAnswers = data.answers || [];
        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan saat memuat data respon.');
            this.detailModalOpen = false;
        } finally {
            this.loading = false;
        }
    },

    closeDetail() {
        this.detailModalOpen = false;
        this.selectedResponse = null;
        this.selectedAnswers = [];
    },

    get hospitalAnswers() {
        return this.selectedAnswers || [];
    },

    get formattedFeedback() {
        if (!this.selectedResponse || !this.selectedResponse.feedback_data) {
            return [];
        }
        let data = this.selectedResponse.feedback_data;
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data);
            } catch (e) {
                return [];
            }
        }
        if (Array.isArray(data)) {
            return data;
        }
        if (typeof data === 'object' && data !== null) {
            return Object.entries(data).map(([aspectName, fb]) => ({
                aspect: aspectName,
                reason: (fb && typeof fb === 'object' && fb.reason) ? fb.reason : '-',
                suggestion: (fb && typeof fb === 'object' && fb.suggestion) ? fb.suggestion : '-'
            }));
        }
        return [];
    },

    get satisfactionPredicate() {
        if (!this.selectedResponse) {
            return { label: '-', class: 'text-gray-600 bg-gray-100 border-gray-200' };
        }
        const score = Number(this.selectedResponse.general_score) || 0;
        if (score >= 81) {
            return { label: 'Sangat Setuju (Optimal)', class: 'text-emerald-800 bg-emerald-100 border-emerald-300' };
        }
        if (score >= 61) {
            return { label: 'Setuju (Baik)', class: 'text-sky-800 bg-sky-100 border-sky-300' };
        }
        if (score >= 41) {
            return { label: 'Tidak Setuju (Perhatian)', class: 'text-amber-800 bg-amber-100 border-amber-300' };
        }
        return { label: 'Sangat Tidak Setuju (Prioritas RTL)', class: 'text-rose-800 bg-rose-100 border-rose-300' };
    },

    get indicatorGroups() {
        if (!this.selectedAnswers || this.selectedAnswers.length === 0) {
            return [];
        }
        const answers = this.hospitalAnswers;
        const groupsMap = new Map();

        answers.forEach(ans => {
            const cat = ans.category_name || 'Lainnya';
            if (!groupsMap.has(cat)) {
                groupsMap.set(cat, {
                    name: cat,
                    questions: [],
                    totalScore: 0
                });
            }
            const group = groupsMap.get(cat);
            group.questions.push(ans);
            group.totalScore += Number(ans.score) || 0;
        });

        const feedbackMap = {};
        this.formattedFeedback.forEach(fb => {
            feedbackMap[fb.aspect] = fb;
        });

        return Array.from(groupsMap.values()).map((g, idx) => {
            const count = g.questions.length;
            const avg = count > 0 ? (g.totalScore / count) : 0;
            const pct = count > 0 ? Math.round((g.totalScore / (count * 4)) * 100) : 0;
            const fb = feedbackMap[g.name];

            let badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
            let predicate = 'Optimal';
            if (pct < 41) {
                badgeClass = 'bg-rose-100 text-rose-800 border-rose-200';
                predicate = 'Prioritas RTL';
            } else if (pct < 61) {
                badgeClass = 'bg-amber-100 text-amber-800 border-amber-200';
                predicate = 'Perhatian';
            } else if (pct < 81) {
                badgeClass = 'bg-sky-100 text-sky-800 border-sky-200';
                predicate = 'Baik';
            }

            return {
                index: idx + 1,
                name: g.name,
                questions: g.questions,
                avg: avg.toFixed(2),
                pct: pct,
                badgeClass: badgeClass,
                predicate: predicate,
                reason: fb ? fb.reason : null,
                suggestion: fb ? fb.suggestion : null
            };
        });
    }
});
