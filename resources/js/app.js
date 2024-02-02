
import './bootstrap';
import { createApp } from 'vue';

import SweetAlert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import app from './app.vue';
import router from './router/index.js';


createApp(app).use(SweetAlert2).use(router).mount('#app');