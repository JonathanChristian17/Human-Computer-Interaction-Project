import './bootstrap';
import './transaction';

import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import 'flatpickr/dist/themes/dark.css';

// Import payment form handling
import './payment-form';

// Make flatpickr globally available
window.flatpickr = flatpickr;
window.Alpine = Alpine;

Alpine.start();
