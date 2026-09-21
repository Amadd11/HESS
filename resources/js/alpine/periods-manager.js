export default (config = {}) => ({
    createModalOpen: false,
    editModalOpen: false,
    shareModalOpen: false,
    copied: false,
    editPeriod: {
        id: '',
        name: '',
        target: 250,
        start_date: '',
        end_date: '',
        is_active: false
    },
    sharePeriod: {
        name: config.activePeriodName || 'Survei Kepuasan Pegawai HESS',
        url: config.publicSurveyUrl || '',
        qrUrl: config.qrUrl || ''
    },

    openEdit(p) {
        this.editPeriod = {
            id: p.id,
            name: p.name,
            target: p.target,
            start_date: p.start_date_formatted || '',
            end_date: p.end_date_formatted || '',
            is_active: Boolean(p.is_active)
        };
        this.editModalOpen = true;
    },

    openShare(name) {
        this.sharePeriod.name = name || config.activePeriodName || 'Survei Kepuasan Pegawai HESS';
        this.sharePeriod.url = config.publicSurveyUrl || '';
        this.sharePeriod.qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&margin=10&data=' + encodeURIComponent(this.sharePeriod.url);
        this.shareModalOpen = true;
    },

    copyLink() {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(this.sharePeriod.url).then(() => {
                this.copied = true;
                setTimeout(() => {
                    this.copied = false;
                }, 2500);
            });
        } else {
            const input = document.createElement('input');
            input.value = this.sharePeriod.url;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            this.copied = true;
            setTimeout(() => {
                this.copied = false;
            }, 2500);
        }
    }
});
