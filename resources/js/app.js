

import '../css/app.css'
import { createApp } from 'vue';
import router from './router/router.js';
import store from  './store/index.js'




/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import App from './components/App.vue';
app.component('app', App);
app.use(router)
app.use(store)


app.mount('#app');
