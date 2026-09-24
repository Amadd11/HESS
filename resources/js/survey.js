import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import surveyWizard from './alpine/survey-wizard';

Alpine.plugin(persist);

// Register survey wizard module for public respondents
Alpine.data('surveyWizard', surveyWizard);

window.Alpine = Alpine;
Alpine.start();
