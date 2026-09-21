import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import ApexCharts from 'apexcharts';
import surveyWizard from './alpine/survey-wizard';
import dashboardCharts from './alpine/dashboard-charts';
import dashboardFeedback from './alpine/dashboard-feedback';
import categoriesManager from './alpine/categories-manager';
import periodsManager from './alpine/periods-manager';
import questionsManager from './alpine/questions-manager';

window.ApexCharts = ApexCharts;

Alpine.plugin(persist);

// Register Alpine Data Modules
Alpine.data('surveyWizard', surveyWizard);
Alpine.data('dashboardCharts', dashboardCharts);
Alpine.data('dashboardFeedback', dashboardFeedback);
Alpine.data('categoriesManager', categoriesManager);
Alpine.data('periodsManager', periodsManager);
Alpine.data('questionsManager', questionsManager);

window.Alpine = Alpine;
Alpine.start();
