<template>
    <div class="bg-white border-b border-gray-300 fixed top-0 w-full shadow">
        <div class="p-4">
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1 min-w-[250px]">
                    <div class="flex items-center">
                        <template v-if="user.avatar">
                            <img :src="user.avatar" alt="Avatar" class="rounded-full" width="45">
                        </template>
                        <template v-else>
                            <div class="rounded-full bg-gray-300 w-12 h-12 flex items-center justify-center">
                                <span class="font-semibold text-xl text-gray-600">{{ getInitials(user.name, user.surname) }}</span>
                            </div>
                        </template>
                        <span class="font-semibold text-xl pl-1">{{ user.name }} {{ user.surname }}</span>
                    </div>
                </div>
                <div class="col-span-2 flex justify-end items-center">

                    <template v-if="showBackButton">
                        <button @click="goToHomePage" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-6 flex items-center">
                            <font-awesome-icon :icon="['fas', 'arrow-left']" class="mr-2 text-white" />
                            Geri
                        </button>
                    </template>

                    <button @click="logout" class="bg-transparent text-red-500 hover:bg-[#0095B0] hover:text-white rounded-full p-2 transition duration-300 ease-in-out">
                        <font-awesome-icon :icon="['fas', 'sign-out-alt']" class="text-3xl"/>
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

button {
    transition: background-color 0.3s;
}

button:hover {
    background-color: #4b5563;
}
</style>
