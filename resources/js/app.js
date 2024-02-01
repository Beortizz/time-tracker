/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

import SweetAlert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// import PortalVue from 'portal-vue';
import app from './app.vue';
import router from './router/index.js';


createApp(app).use(SweetAlert2).use(router).mount('#app');