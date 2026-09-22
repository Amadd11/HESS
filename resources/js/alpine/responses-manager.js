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

    get msqAnswers() {
        return this.selectedAnswers.filter(a => a.category_type === 'msq');
    },

    get hospitalAnswers() {
        return this.selectedAnswers.filter(a => a.category_type === 'hospital');
    }
});
