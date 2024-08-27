

import '../css/app.css'
import { createApp } from 'vue';
import router from './router/router.js';
import store from  './store/index.js'
import Toast, { POSITION } from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import FloatingVue from 'floating-vue'
import 'floating-vue/dist/style.css';
import { VueReCaptcha } from 'vue-recaptcha-v3';


library.add(fas);


/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import App from './components/App.vue';
app.component('app', App);
app.component('font-awesome-icon', FontAwesomeIcon);
app.use(VueReCaptcha, {
    siteKey: import.meta.env.VITE_RECAPTCHA_SITE_KEY,
});
app.use(FloatingVue)
app.use(router)
app.use(store)
app.use(Toast, {
    position: POSITION.TOP_RIGHT,
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: true,
    closeButton: "button",
    icon: true,
    rtl: false
});


app.mount('#app');

const script = document.createElement('script');
script.src = 'https://www.google.com/recaptcha/api.js';
script.async = true;
script.defer = true;
document.head.appendChild(script);
