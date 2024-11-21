import './bootstrap';
import '../css/app.css';
import '../css/Assets/mdi/css/materialdesignicons.min.css'
import '../css/Assets/ti-icons/css/themify-icons.css'
import '../css/Assets/font-awesome/css/font-awesome.min.css'
import '../css/Assets/select2/select2.min.css'
import '../css/Assets/select2-bootstrap-theme/select2-bootstrap.min.css'


import { createApp } from 'vue';
import app from './Pages/App.vue';
import router from './Router/index.js';

createApp(app).use(router).mount("#app");
