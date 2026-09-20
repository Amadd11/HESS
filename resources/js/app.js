import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import surveyWizard from './survey-wizard';

Alpine.plugin(persist);

Alpine.data('surveyWizard', surveyWizard);

window.Alpine = Alpine;
Alpine.start();
