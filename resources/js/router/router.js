
import Login from "../components/Auth/Login.vue";
import {createRouter, createWebHistory} from "vue-router";
import Chat from "../components/Chat/Chat.vue";
import Register from "../components/Auth/Register.vue";
import store from "../store/index.js";
import App from "../components/App.vue";
import UserProfile from "../components/Chat/UserProfile.vue";
import AddFriend from "../components/Chat/AddFriend.vue";
import GroupProfile from "@/components/Chat/GroupProfile.vue";




const routes = [
    {
        path: '/chat',
        component: App,
        children: [
            {
                path: '',
                component: Chat
            },
            {
                path: 'profile/:userId',
                component: UserProfile,
                name: 'userProfile'
            },
            {
                path: 'add-friend',
                name: 'AddFriend',
                component: AddFriend
            },
            {
                path: ':userId',
                component: Chat,
                name: 'chatWithUser'
            },
            {
                path: 'groups/:selectedGroupId',
                component: GroupProfile,
                name: 'groupProfile',

            }

        ]
    },
    {
        path: '/login',
        component: Login,
        name: 'login',
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    }
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});
router.beforeEach(async (to, from, next) => {
    try {
        await store.dispatch('authenticate');
        if (store.getters.authenticated || to.name === 'login' || to.name === 'register') {
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
