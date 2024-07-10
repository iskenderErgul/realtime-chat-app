
import Login from "../components/Auth/Login.vue";
import {createRouter, createWebHistory} from "vue-router";
import Chat from "../components/Chat/Chat.vue";

import store from "../store/index.js";



const routes = [



    {
        path: '/login',
        component:Login,
        name : 'login',

    },
    {
      path: '/chat',
      component: Chat,
      name : 'chat'
    },




];

const router = createRouter({
    history: createWebHistory(),
    routes,
});
router.beforeEach(async (to, from, next) => {
    try {
        await store.dispatch('authenticate');
        if (store.getters.authenticated || to.name === 'login') {
            next();
        } else {
            next({ name: 'login' });
        }
    } catch (error) {
        console.error('Authentication error:', error);
        next({ name: 'login' });
    }
});

export default router;
