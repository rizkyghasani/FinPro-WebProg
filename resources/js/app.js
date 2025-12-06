import './bootstrap';

import Alpine from 'alpinejs';

// Import Bootstrap JavaScript dan Popper.js
import * as bootstrap from 'bootstrap'; 

// Import Chart.js (BARU)
import Chart from 'chart.js/auto';

window.Alpine = Alpine;

// Daftarkan Bootstrap dan Chart secara global
window.bootstrap = bootstrap;
window.Chart = Chart;

Alpine.start();