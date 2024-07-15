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
                    <button @click="goToAddFriend" class="bg-green-500 text-white px-4 py-2 rounded-md mr-2">Add Friend</button>
                    <button @click="goToProfile(user.id)" class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2">Profile</button>
                    <button @click="logout" class="bg-red-500 text-white px-4 py-2 rounded-md">Logout</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import { computed } from 'vue';

const store = useStore();
const router = useRouter();

const user = computed(() => store.getters.user);

const logout = async () => {
    await store.dispatch('logout');
    await router.push('/login');
};

const goToProfile = async (userId) => {
    await router.push({ name: 'userProfile', params: { userId } });
};

const goToAddFriend = async () => {
    await router.push({ name: 'AddFriend' });
};

const getInitials = (name, surname) => {
    const nameInitial = name ? name.charAt(0).toUpperCase() : '';
    const surnameInitial = surname ? surname.charAt(0).toUpperCase() : '';
    return nameInitial + surnameInitial;
};
</script>

<style scoped>
/* Add custom styles if needed */
</style>
