import './bootstrap';

import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import 'flatpickr/dist/themes/dark.css';

// Make flatpickr globally available
window.flatpickr = flatpickr;
window.Alpine = Alpine;

Alpine.start();
