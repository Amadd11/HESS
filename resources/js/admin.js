import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import ApexCharts from 'apexcharts';
import dashboardCharts from './alpine/dashboard-charts';
import dashboardFeedback from './alpine/dashboard-feedback';
import categoriesManager from './alpine/categories-manager';
import periodsManager from './alpine/periods-manager';
import questionsManager from './alpine/questions-manager';
import responsesManager from './alpine/responses-manager';
import demographicsManager from './alpine/demographics-manager';
import sentimentDashboard from './alpine/sentiment-dashboard';

window.ApexCharts = ApexCharts;

Alpine.plugin(persist);

// Register Alpine Data Modules for Admin Dashboard
Alpine.data('dashboardCharts', dashboardCharts);
Alpine.data('dashboardFeedback', dashboardFeedback);
Alpine.data('sentimentDashboard', sentimentDashboard);
Alpine.data('categoriesManager', categoriesManager);
Alpine.data('periodsManager', periodsManager);
Alpine.data('questionsManager', questionsManager);
Alpine.data('responsesManager', responsesManager);
Alpine.data('demographicsManager', demographicsManager);

window.Alpine = Alpine;
Alpine.start();

// Universal Modern Delete Confirmation Modal Interceptor
if (typeof document !== 'undefined') {
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        const isDelete = form.querySelector('input[name="_method"][value="DELETE"]') !== null;
        if (!isDelete || form.dataset.confirmed === 'true') {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        let message = 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
        let title = 'Konfirmasi Hapus Data';

        // Extract custom message & title from data attributes or onsubmit
        if (form.dataset.confirm) {
            message = form.dataset.confirm;
        } else {
            const onsubmitStr = form.getAttribute('onsubmit') || '';
            const match = onsubmitStr.match(/confirm\(\s*['"](.*?)['"]\s*\)/);
            if (match && match[1]) {
                message = match[1].replace(/\\'/g, "'").replace(/\\"/g, '"');
            }
        }

        if (form.dataset.title) {
            title = form.dataset.title;
        }

        form.removeAttribute('onsubmit');

        window.dispatchEvent(new CustomEvent('open-delete-modal', {
            detail: {
                form: form,
                title: title,
                message: message
            }
        }));
    }, true);
}
