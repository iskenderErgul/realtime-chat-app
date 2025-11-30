<template>
    <div class="glass-header fixed top-0 w-full shadow-lg z-50 border-b border-white/20">
        <div class="p-4">
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1 min-w-[250px]">
                    <div class="flex items-center">
                        <template v-if="user.avatar">
                            <img :src="user.avatar" alt="Avatar" class="avatar rounded-full" width="45">
                        </template>
                        <template v-else>
                            <div class="avatar avatar-initials rounded-full w-12 h-12">
                                <span class="font-semibold text-xl">{{ getInitials(user.name, user.surname) }}</span>
                            </div>
                        </template>
                        <span class="font-semibold text-xl pl-3 text-gray-800">{{ user.name }} {{ user.surname }}</span>
                    </div>
                </div>
                <div class="col-span-2 flex justify-end items-center gap-3">

                    <template v-if="showBackButton">
                        <button @click="goToHomePage" class="btn-modern bg-gradient-to-r from-gray-600 to-gray-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-md hover:shadow-lg">
                            <font-awesome-icon :icon="['fas', 'arrow-left']" class="text-white" />
                            Geri
                        </button>
                    </template>

                    <button @click="logout" class="btn-modern bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full p-3 shadow-md hover:shadow-xl" v-tooltip="'Çıkış Yap'">
                        <font-awesome-icon :icon="['fas', 'sign-out-alt']" class="text-xl"/>
                    </button>
                </div>

            </div>
        </div>


    </div>
</template>

<script setup>
import { useStore } from 'vuex';
import { useRouter, useRoute } from 'vue-router';
import {computed} from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';


    const store = useStore();
const router = useRouter();
const route = useRoute();


const user = computed(() => store.getters.user);

const logout = async () => {
    await store.dispatch('logout');
    await router.push('/login');
};

const showBackButton = computed(() => {
    const routesToShowButton = ['userProfile', 'AddFriend', 'groupProfile'];
    return routesToShowButton.includes(route.name);
});

const isHomePage = computed(() => route.path === '/chat');



const goToHomePage = async () => {
    await router.push('/chat');
};


const getInitials = (name, surname) => {
    const nameInitial = name ? name.charAt(0).toUpperCase() : '';
    const surnameInitial = surname ? surname.charAt(0).toUpperCase() : '';
    return nameInitial + surnameInitial;
};

</script>

<style scoped>
.glass-header {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}
</style>
